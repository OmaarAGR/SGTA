<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'start_datetime',
        'end_datetime',
        'service_performed',
        'labor_cost',
        'notes',
        'technician_id',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }
}
