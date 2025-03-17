<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $with = ['category', 'country', 'accounts'];
    protected $casts = [
        'ports' => 'array'
    ];

    protected $hidden = ['token'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function prices()
    {
        return $this->hasMany(ServerPrice::class);
    }

    public function price()
    {
        return $this->hasOne(ServerPrice::class)->where('role_id', auth()->user()->roles->first()->id);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class)->where('tipe', 3);
    }

}
