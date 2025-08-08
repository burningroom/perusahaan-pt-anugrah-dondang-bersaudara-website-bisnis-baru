<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasAvatar, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids, SoftDeletes;

    protected array $guard_name = ['web', 'api', 'sactum'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'fcm_token',
        'remember_token',
        'custom_fields',
        'avatar_url'
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
        'password' => 'hashed',
    ];


    public static function loginValidator()
    {
        return [
            'username_email' => ['required'],
            'password'       => ['required'],
            'fcm_token'      => ['nullable']
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class, 'user_id', 'id');
    }

    public function requestArrivals(): HasMany
    {
        return $this->hasMany(RequestArrival::class, 'user_id', 'id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'user_id', 'id');
    }

    public function pkks(): HasMany
    {
        return $this->hasMany(Pkk::class, 'pkk_id', 'id');
    }

    public function rpkros(): HasMany
    {
        return $this->hasMany(Rpkro::class, 'user_id', 'id');
    }

    public function setSpkPandus(): HasMany
    {
        return $this->hasMany(SetSpkPandu::class, 'user_id', 'id');
    }

    public function rkbms(): HasMany
    {
        return $this->hasMany(Rkbm::class, 'user_id', 'id');
    }

    public function stevedoringRequests(): HasMany
    {
        return $this->hasMany(StevedoringRequest::class, 'user_id', 'id');
    }

    public function vesselMasters(): HasMany
    {
        return $this->hasMany(VesselMaster::class, 'user_id', 'id');
    }

    public function spkPandus(): HasMany
    {
        return $this->hasMany(SpkPandu::class, 'user_id', 'id');
    }

    public function agentSpkPandus(): HasMany
    {
        return $this->hasMany(SpkPandu::class, 'agent_id', 'id');
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class, 'agent_id', 'id');
    }
}
