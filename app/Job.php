<?php

namespace Laraveles;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * Protected fields.
     *
     * @var array
     */
    protected $guard = [];

    /**
     * A Job position belongs to a unique Recruiter.
     *
     * @return BelongsTo
     */
    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }
}
