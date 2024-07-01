<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'city_id',
        'serviceable_type',
        'serviceable_id',
        
        
    ];

    public function serviceable()
    {
        return $this->morphTo('serviceable');
    }


    // public function servicRequests()
    // {
    //     return $this->hasMany(ServiceRequest::class);
    // }
    
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }

    public function requests(){
        return $this->hasMany(ServiceRequest::class,'serviceable_id');
    }
}
