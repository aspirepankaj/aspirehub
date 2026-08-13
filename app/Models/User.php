<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Send the password reset notification using our premium template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the admin profile associated with the user.
     */
    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Modules\Core\Authentication\Models\Admin::class, 'user_id');
    }

    /**
     * Get the client profile associated with the user.
     */
    public function client(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Modules\CRM\Clients\Models\Client::class, 'user_id');
    }

    /**
     * Get initials of the user's name.
     */
    public function getInitials(): string
    {
        $name = $this->name ?? '';
        $words = preg_split("/\s+/", trim($name));
        $initials = "";
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_substr($w, 0, 1);
            }
        }
        return mb_strtoupper(mb_substr($initials, 0, 2));
    }

    /**
     * Get the staff profile associated with the user.
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Modules\CRM\Staff\Models\Staff::class, 'user_id');
    }
}

