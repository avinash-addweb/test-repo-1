<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
      'password',
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
      'password'          => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * Get the achievements associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get the badges associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function badges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserBadge::class);
    }


    public function latestBadge(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserBadge::class)->orderBy('created_at', 'DESC');
    }

}

