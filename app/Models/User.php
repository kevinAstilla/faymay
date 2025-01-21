<?php

namespace App\Models;

use App\Models\Preference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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
        ];
    }

    public function preferences(): HasMany
    {
        return $this->hasMany(Preference::class);
    }

    public function updatePreferences($preferences)
    {
        $preference =  new Preference();
    
        foreach($preferences as $key => $values) {
            if (!isset($preference->types[$key])) {
                throw new \InvalidArgumentException("Invalid preference key: $preference");
            }
            foreach ($values as $value) {
                $temp = $preference->types[$key]->normalize($value);
                Preference::updateOrCreate(
                    [
                        'user_id' => $this->id,
                        'key' => $key,
                        'value' => $temp
                    ]
                );
            }
        }
    }
}
