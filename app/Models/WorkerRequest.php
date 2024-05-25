<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerRequest extends Model
{
    protected $table='worker_requests';
    protected $guarded=[];

    public function users():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
