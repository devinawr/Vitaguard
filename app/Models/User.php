<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ConsultationMessage::class, 'sender_id');
    }

    /**
     * MANY-TO-MANY:
     * Satu user dapat terlibat dalam banyak konsultasi dan satu konsultasi
     * dapat memiliki banyak user/pengirim melalui consultation_messages.
     */
    public function consultations(): BelongsToMany
    {
        return $this->belongsToMany(
            Consultation::class,
            'consultation_messages',
            'sender_id',
            'consultation_id'
        )
            ->using(ConsultationMessage::class)
            ->as('messageDetail')
            ->withPivot(['id', 'message', 'read_at'])
            ->withTimestamps()
            ->orderByPivot('created_at');
    }
}
