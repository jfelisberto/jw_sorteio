<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'match_id',
        'attacker',
        'midfield_left',
        'midfield_right',
        'wing_left',
        'wing_right',
        'defender',
        'goalkeeper',
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
