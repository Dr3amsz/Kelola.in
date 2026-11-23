<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roris extends Model
{
    protected $table = 'roris';

    protected $fillable = [
        'tanggal',
        'user_id',
        'total_modal',
        'total_pendapatan',
        'nilai_roris',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'          => 'date',
            'total_modal'      => 'decimal:2',
            'total_pendapatan' => 'decimal:2',
            'nilai_roris'      => 'decimal:2',
        ];
    }

    // ─── Konstanta Status ──────────────────────────────────────────────────────

    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_SELESAI  = 'selesai';

    public function labelStatus(): string
    {
        return match ($this->status) {
            self::STATUS_SELESAI  => 'Selesai',
            default               => 'Diajukan',
        };
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_SELESAI  => 'bg-emerald-100 text-emerald-800',
            default               => 'bg-amber-100 text-amber-800',
        };
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(RorisDetail::class, 'roris_id');
    }
}
