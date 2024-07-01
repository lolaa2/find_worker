<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $table='services_requests';
    protected $guarded=[];

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
