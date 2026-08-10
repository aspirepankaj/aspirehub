<?php

namespace App\Modules\Core\Authentication\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'adspv_admins';

    protected $fillable = ['user_id', 'role_id', 'is_active', 'profile_image', 'phone'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getInitials(): string
    {
        $name = $this->user->name ?? '';
        $words = preg_split("/\s+/", trim($name));
        $initials = "";
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        return mb_strtoupper(mb_substr($initials, 0, 2));
    }
}
