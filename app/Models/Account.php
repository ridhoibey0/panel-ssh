<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class)->withTrashed();
    }

    public function getTipeAttribute($value)
    {
        switch ($value) {
            case 1:
                return 'Monthly';
            case 2:
                return 'Daily';
            case 3:
                return 'Trial';
            default:
                return 'Unknown';
        }
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'Inactive';
            case 1:
                return 'Active';
            case 2:
                return 'Stopped';
            case 3:
                return 'Suspend';
            default:
                return 'Unknown';
        }
    }
    
    public function scopeCurrentMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }
    
    public function scopePreviousMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ]);
    }

}
