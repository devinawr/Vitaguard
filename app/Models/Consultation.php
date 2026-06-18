<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'started_at',
        'ended_at',
        'status',
        'summary',
        'diagnosis',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConsultationMessage::class);
    }

    /**
     * MANY-TO-MANY:
     * consultation_messages menjadi tabel penghubung antara consultations
     * dan users, sekaligus menyimpan atribut tambahan pesan.
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'consultation_messages',
            'consultation_id',
            'sender_id'
        )
            ->using(ConsultationMessage::class)
            ->as('messageDetail')
            ->withPivot(['id', 'message', 'read_at'])
            ->withTimestamps()
            ->orderByPivot('created_at');
    }
}
