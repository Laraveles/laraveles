<?php

namespace Laraveles;

use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    /**
     * Protected fields.
     *
     * @var array
     */
    protected $guard = [];

    /**
     * A recruiter may have many jobs.
     *
     * @return HasMany
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * A recruiter belongs to a User.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
