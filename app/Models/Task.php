<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Task extends Model
{
    protected $table='task';
    protected $guarded=[];


    public function service() {
        return $this->belongsTo(Service::class);
    }
    public function workerRequest() {
        return $this->belongsTo(WorkerRequest::class);
    }
    
public function worker() {
    return $this->belongsTo(User::class,'worker_id'); 
}
public function company():BelongsTo
{
    return $this->belongsTo(Company::class);
}
}
