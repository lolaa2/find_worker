<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    protected $table = 'companies';
    protected $guarded=[];

       /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function city(){
        return $this->belongsTo(City::class);
    }


    public function category(){
        return $this->belongsTo(Category::class);
    }
 
    public function workerRequest()
    {
        return $this->hasMany(WorkerRequest::class);
    }

    public function users(){
        return $this->belongsToMany(related:'Users',table:'company_user');
    }
    public function task(){
        return $this->belongsTo(ServiceRequest::class);
    }
    
    public function services()
    {
        return $this->morphMany(Service::class , 'serviceable');
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
}
