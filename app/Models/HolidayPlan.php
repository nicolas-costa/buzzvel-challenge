<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HolidayPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'participants'
    ];

    protected $casts = [
        'participants' => 'array',
        'date' => 'date:Y-m-d'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
