<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'google_user_id',
        'email',
        'name',
        'picture',
        'is_active',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the user that owns the Google connection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the token is expired
     */
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    /**
     * Check if the connection is valid
     */
    public function isValid(): bool
    {
        return $this->is_active && !$this->isTokenExpired();
    }
} 