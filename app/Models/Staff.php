<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'password',
    ];

    protected $hidden = ['password'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'handled_by');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class, 'responded_by');
    }
}
