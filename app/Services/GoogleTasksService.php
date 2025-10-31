<?php

namespace App\Services;

use App\Models\GoogleConnection;
use App\Models\User;
use Google_Client;
use Google_Service_Tasks;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleTasksService
{
    private Google_Client $client;
    private Google_Service_Tasks $service;
    private ?GoogleConnection $connection;

    public function __construct(User $user)
    {
        $this->connection = $user->googleConnection;
        $this->initializeClient();
    }

    /**
     * Initialize Google Client
     */
    private function initializeClient(): void
    {
        $this->client = new Google_Client();
        
        // Get configuration values
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');
        
        // Validate required configuration
        if (empty($clientId)) {
            throw new \Exception('Google Client ID is not configured. Please set GOOGLE_CLIENT_ID in your environment.');
        }
        
        if (empty($clientSecret)) {
            throw new \Exception('Google Client Secret is not configured. Please set GOOGLE_CLIENT_SECRET in your environment.');
        }
        
        if (empty($redirectUri)) {
            throw new \Exception('Google Redirect URI is not configured. Please set GOOGLE_REDIRECT_URL in your environment.');
        }
        
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setScopes([
            'https://www.googleapis.com/auth/tasks',
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ]);

        if ($this->connection && $this->connection->isValid()) {
            $this->client->setAccessToken($this->connection->access_token);
            
            if ($this->connection->isTokenExpired()) {
                $this->refreshToken();
            }
        }

        $this->service = new Google_Service_Tasks($this->client);
    }

    /**
     * Refresh the access token
     */
    private function refreshToken(): void
    {
        try {
            if ($this->client->isAccessTokenExpired() && $this->connection->refresh_token) {
                $this->client->refreshToken($this->connection->refresh_token);
                $token = $this->client->getAccessToken();
                
                $this->connection->update([
                    'access_token' => $token['access_token'],
                    'token_expires_at' => now()->addSeconds($token['expires_in']),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to refresh Google token: ' . $e->getMessage());
            $this->connection->update(['is_active' => false]);
        }
    }

    /**
     * Get authorization URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code): bool
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                Log::error('Google OAuth error: ' . $token['error']);
                return false;
            }

            // Get user info
            $oauth2 = new \Google_Service_Oauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            // Create or update connection
            GoogleConnection::updateOrCreate(
                ['user_id' => $this->connection?->user_id ?? auth()->id()],
                [
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? $this->connection?->refresh_token,
                    'token_expires_at' => now()->addSeconds($token['expires_in']),
                    'google_user_id' => $userInfo->id,
                    'email' => $userInfo->email,
                    'name' => $userInfo->name,
                    'picture' => $userInfo->picture,
                    'is_active' => true,
                ]
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user is connected to Google
     */
    public function isConnected(): bool
    {
        return $this->connection && $this->connection->isValid();
    }

    /**
     * Get all task lists
     */
    public function getTaskLists(): array
    {
        if (!$this->isConnected()) {
            return [];
        }

        $cacheKey = "google_tasks_lists_{$this->connection->user_id}";
        
        return Cache::remember($cacheKey, 300, function () {
            try {
                $taskLists = $this->service->tasklists->listTasklists();
                return $taskLists->getItems() ?? [];
            } catch (\Exception $e) {
                Log::error('Failed to fetch Google task lists: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get tasks from a specific list
     */
    public function getTasks(string $listId = '@default', bool $showCompleted = false): array
    {
        if (!$this->isConnected()) {
            return [];
        }

        $showCompletedStr = $showCompleted ? '1' : '0';
        $cacheKey = "google_tasks_{$this->connection->user_id}_{$listId}_{$showCompletedStr}";
        
        return Cache::remember($cacheKey, 60, function () use ($listId, $showCompleted) {
            try {
                $tasks = $this->service->tasks->listTasks($listId, [
                    'showCompleted' => $showCompleted,
                    'maxResults' => 100,
                ]);

                return $tasks->getItems() ?? [];
            } catch (\Exception $e) {
                Log::error('Failed to fetch Google tasks: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Create a new task
     */
    public function createTask(string $title, string $listId = '@default', array $options = []): ?object
    {
        if (!$this->isConnected()) {
            return null;
        }

        try {
            $task = new \Google_Service_Tasks_Task();
            $task->setTitle($title);
            
            if (isset($options['notes'])) {
                $task->setNotes($options['notes']);
            }
            
            if (isset($options['due'])) {
                $task->setDue($options['due']);
            }
            
            if (isset($options['status'])) {
                $task->setStatus($options['status']);
            }

            $createdTask = $this->service->tasks->insert($listId, $task);
            
            // Clear all task caches to ensure the list updates
            $this->clearAllTaskCaches();
            
            return $createdTask;
        } catch (\Exception $e) {
            Log::error('Failed to create Google task: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a task
     */
    public function updateTask(string $taskId, string $listId, array $updates): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        try {
            $task = $this->service->tasks->get($listId, $taskId);
            
            foreach ($updates as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($task, $setter)) {
                    $task->$setter($value);
                }
            }
            
            $this->service->tasks->update($listId, $taskId, $task);
            
            // Clear all task caches to ensure the list updates
            $this->clearAllTaskCaches();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update Google task: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a task
     */
    public function deleteTask(string $taskId, string $listId): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        try {
            $this->service->tasks->delete($listId, $taskId);
            
            // Clear all task caches to ensure the list updates
            $this->clearAllTaskCaches();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete Google task: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark task as completed
     */
    public function completeTask(string $taskId, string $listId): bool
    {
        return $this->updateTask($taskId, $listId, [
            'status' => 'completed',
            'completed' => now()->toRfc3339String(),
        ]);
    }

    /**
     * Mark task as incomplete
     */
    public function uncompleteTask(string $taskId, string $listId): bool
    {
        return $this->updateTask($taskId, $listId, [
            'status' => 'needsAction',
            'completed' => null,
        ]);
    }

    /**
     * Clear tasks cache
     */
    private function clearTasksCache(string $listId): void
    {
        // Clear cache for both showCompleted=true and showCompleted=false
        $cacheKey1 = "google_tasks_{$this->connection->user_id}_{$listId}_1";
        $cacheKey2 = "google_tasks_{$this->connection->user_id}_{$listId}_0";
        
        Cache::forget($cacheKey1);
        Cache::forget($cacheKey2);
    }

    /**
     * Clear all task caches for the current user
     */
    private function clearAllTaskCaches(): void
    {
        // Clear task lists cache
        $listsCacheKey = "google_tasks_lists_{$this->connection->user_id}";
        Cache::forget($listsCacheKey);
        
        // Clear all task caches for this user
        // Note: This is a simple approach - in production you might want to use cache tags
        $this->clearTasksCache('@default');
        
        // Clear caches for any other task lists the user might have
        $taskLists = $this->getTaskLists();
        foreach ($taskLists as $list) {
            $this->clearTasksCache($list->id);
        }
    }

    /**
     * Disconnect Google account
     */
    public function disconnect(): bool
    {
        if ($this->connection) {
            $this->connection->update(['is_active' => false]);
            return true;
        }
        return false;
    }
} 