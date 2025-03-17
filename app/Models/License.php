<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;
    protected $connection = 'mysql2', $guarded = ['id'];
    public $timestamps = true;
    
    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class);
    }
}
