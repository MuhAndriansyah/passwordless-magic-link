<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'expires_at', 'consumed_at',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid()
    {
        return !$this->isExpired() && !$this->isConsumed();
    }

    public function isExpired()
    {
        return $this->expires_at->isBefore(now());
    }

    public function isConsumed()
    {
        return $this->consumed_at !== null;
    }

    public function consume()
    {
        $this->consumed_at = now();
        $this->save();
    }
}
