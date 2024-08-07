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
    protected $table='users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city_id',
        'work_id'
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

    public function services()
    {
        return $this->morphMany(Service::class,'serviceable');
    }
    public function previous()
    {
        return $this->hasMany(Previous::class);
    }

    public function workerRequest()
    {
        return $this->hasMany(WorkerRequest::class);
    }

    public function companies(){
        return $this->belongsToMany(related:'Company',table:'company_user');
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function work(){
        return $this->belongsTo(Work::class);
    }

}
