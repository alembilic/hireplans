<?php

namespace App\Services;

use App\Models\GoogleConnection;
use App\Models\User;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    private Google_Client $client;
    private Google_Service_Calendar $service;
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
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setScopes([
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

        $this->service = new Google_Service_Calendar($this->client);
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
     * Check if user is connected to Google
     */
    public function isConnected(): bool
    {
        return $this->connection && $this->connection->isValid();
    }

    /**
     * Get calendar events
     */
    public function getEvents(string $calendarId = 'primary', array $options = []): array
    {
        if (!$this->isConnected()) {
            return [];
        }

        $defaultOptions = [
            'timeMin' => now()->toISOString(),
            'maxResults' => 100,
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $options = array_merge($defaultOptions, $options);
        
        try {
            $events = $this->service->events->listEvents($calendarId, $options);
            return $events->getItems() ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Google Calendar events: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a new calendar event
     */
    public function createEvent(array $eventData, string $calendarId = 'primary'): ?object
    {
        if (!$this->isConnected()) {
            return null;
        }

        try {
            $event = new \Google_Service_Calendar_Event($eventData);
            $createdEvent = $this->service->events->insert($calendarId, $event);
            
            return $createdEvent;
        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a calendar event
     */
    public function updateEvent(string $eventId, array $eventData, string $calendarId = 'primary'): ?object
    {
        if (!$this->isConnected()) {
            return null;
        }

        try {
            $event = new \Google_Service_Calendar_Event($eventData);
            $updatedEvent = $this->service->events->update($calendarId, $eventId, $event);
            
            return $updatedEvent;
        } catch (\Exception $e) {
            Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a calendar event
     */
    public function deleteEvent(string $eventId, string $calendarId = 'primary'): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        try {
            $this->service->events->delete($calendarId, $eventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete Google Calendar event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get calendar event by ID
     */
    public function getEvent(string $eventId, string $calendarId = 'primary'): ?object
    {
        if (!$this->isConnected()) {
            return null;
        }

        try {
            return $this->service->events->get($calendarId, $eventId);
        } catch (\Exception $e) {
            Log::error('Failed to get Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get available calendars
     */
    public function getCalendars(): array
    {
        if (!$this->isConnected()) {
            return [];
        }

        try {
            $calendarList = $this->service->calendarList->listCalendarList();
            return $calendarList->getItems() ?? [];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Google calendars: ' . $e->getMessage());
            return [];
        }
    }
} 