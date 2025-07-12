<?php

namespace App\Http\Controllers;

use App\Services\GoogleTasksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        $user = Auth::user();
        $googleService = new GoogleTasksService($user);
        
        return redirect($googleService->getAuthUrl());
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect()->route('platform.tasks')
                ->with('error', 'Authorization failed. Please try again.');
        }

        $user = Auth::user();
        $googleService = new GoogleTasksService($user);
        
        $success = $googleService->handleCallback($code);
        
        if ($success) {
            return redirect()->route('platform.tasks')
                ->with('success', 'Google account connected successfully!');
        } else {
            return redirect()->route('platform.tasks')
                ->with('error', 'Failed to connect Google account. Please try again.');
        }
    }
} 