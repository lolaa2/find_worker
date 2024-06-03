<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerRequest extends Model
{
    protected $table='worker_requests';
    protected $guarded=[];

    // public function worker():BelongsTo
    // {
    //     return $this->belongsTo(User::class,'worker_id');
    // }
    
    public function worker()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
   
}
