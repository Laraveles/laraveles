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
    protected $guarded = [];

    /**
     * A Job position belongs to a unique Recruiter.
     *
     * @return BelongsTo
     */
    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }

    /**
     * Approves the job for listing
     *
     * @return $this
     */
    public function approve()
    {
        $this->setAttribute('listing', true);

        return $this;
    }
}
