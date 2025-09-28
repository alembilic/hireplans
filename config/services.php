<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],

    'linkedin' => [
        'api_key' => env('LINKEDIN_API_KEY'),
        'api_secret' => env('LINKEDIN_API_SECRET'),
        'base_url' => env('LINKEDIN_BASE_URL', 'https://api.linkedin.com/v2'),
        'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
    ],

    'rapidapi' => [
        'key' => env('RAPIDAPI_KEY'),
        'linkedin_host' => env('RAPIDAPI_LINKEDIN_HOST', 'linkedin-profile-scraper.p.rapidapi.com'),
    ],

];