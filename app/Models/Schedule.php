<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Schedule extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'is_active',
        'title',
        'start_time',
        'end_time',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
