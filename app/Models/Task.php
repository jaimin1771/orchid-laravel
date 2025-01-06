<?php

namespace App\Models;

use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'progress',
        'due_date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'name'       => Like::class,
        'description'      => Like::class,
        'status'      => Like::class,
        'progress'      => Like::class,
        'updated_at' => WhereDateStartEnd::class,
        'due_date' => WhereDateStartEnd::class,
        'created_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'description',
        'status',
        'progress',
        'due_date',
        'updated_at',
        'created_at',
    ];
}
