<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'notes',
        'completed_status',
        'due_date',
        'user_id',
    ];

    /**
     * Get todo user.
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
