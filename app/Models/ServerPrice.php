<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerPrice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'float'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
