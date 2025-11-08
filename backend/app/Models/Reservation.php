<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'reservation_date',
        'reservation_time',
        'number_of_people',
        'special_requests',
        'status'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'time',
        'number_of_people' => 'integer'
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->toDateString());
    }
}
