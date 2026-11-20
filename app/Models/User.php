<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi: User memiliki satu Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Helper: Periksa apakah user memiliki role tertentu berdasarkan slug.
     * Contoh: $user->hasRole('admin') atau $user->hasRole(['admin', 'pemilik_toko'])
     *
     * @param string|array<string> $roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (! $this->role) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($this->role->slug, $roles, true);
        }

        return $this->role->slug === $roles;
    }

    /**
     * Helper: Periksa apakah user memiliki permission tertentu (melalui relasi Role).
     * Contoh: $user->hasPermission('kelola-barang')
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (! $this->role) {
            return false;
        }

        return $this->role->permissions->contains('slug', $permissionSlug);
    }

    public function dashboardRouteName(): ?string
    {
        return match ($this->role?->slug) {
            'admin' => 'admin.dashboard',
            'pemilik_toko' => 'pemilik.dashboard',
            'karyawan' => 'karyawan.dashboard',
            default => null,
        };
    }

    /** Cari user berdasarkan username atau email. */
    public static function findByIdentifier(string $identifier): ?self
    {
        $column = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return static::where($column, $identifier)->first();
    }
}
