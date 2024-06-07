<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    use HasFactory;
    protected $table = 'company_employee';
    protected $guarded=[];
    protected $fillable=['state','company_id', 'worker_id','worker_name','company_name'];


    // public function workeras()
    // {
    //     return $this->morphTo();
    // }
}
