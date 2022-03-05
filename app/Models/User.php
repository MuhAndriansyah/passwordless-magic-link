<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Mail\MagicLoginLink;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function loginTokens(): HasMany
    {
        return $this->hasMany(LoginToken::class);
    }

    public function scopeCheckEmail($query, $email)
    {
        $query->where('email', $email);
    }

    private function _processSendLink($plainText, $expires)
    {
        \App\Jobs\ProcessLoginLink::dispatch($plainText, $expires, $this->email);
    }

    public function sendLinkLogin()
    {
        $plainText = Str::random(32);

        $generateToken = $this->loginTokens()->create([
            'token' => hash('sha256', $plainText),
            'expires_at' => now()->addMinutes(10)
        ]);

        $this->_processSendLink($plainText, $generateToken->expires_at);
    }


}
