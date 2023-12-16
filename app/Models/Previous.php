<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Previous extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }
    protected $table ="previous_works";
}
