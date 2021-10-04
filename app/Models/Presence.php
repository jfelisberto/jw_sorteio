<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'play_id',
        'match_id',
        'team_id',
        'confirmed_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'restored_by',
        'restored_at'
    ];

    /**
     * Optional, report deleted_at column as a date Mutator
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
