<?php

namespace Bocapro\QuickbooksConnector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $realm_id
 * @property string $access_token
 * @property string $refresh_token
 * @property Carbon|null $access_token_expires_at
 * @property Carbon|null $refresh_token_expires_at
 * @property string $environment
 */
class QuickbooksToken extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'access_token' => 'encrypted',
        'refresh_token' => 'encrypted',
        'access_token_expires_at' => 'datetime',
        'refresh_token_expires_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('quickbooks-connector.table_name', 'quickbooks_tokens');
    }

    public function accessTokenHasExpired(): bool
    {
        return $this->access_token_expires_at !== null
            && $this->access_token_expires_at->isPast();
    }
}
