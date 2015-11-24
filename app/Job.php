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

    public static function boot()
    {
        parent::boot();

        static::saving(function (Job $job) {
            $job->remote = ! is_null($job->remote);
        });
    }

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

    /**
     * Approved scope.
     *
     * @param $query
     */
    public function scopeApproved($query)
    {
        return $query->where('listing', true)
                     ->orderBy('id', 'DESC');
    }

    /**
     * Ordered scope.
     *
     * @param $query
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('listing', 'ASC')
                     ->orderBy('id', 'DESC');
    }

    /**
     * Get the formatted location.
     *
     * TODO: This should be moved to a presenter
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        $result = implode(', ', array_filter([
            $this->getAttribute('city'),
            $this->getAttribute('country')
        ]));

        if ($this->getAttribute('remote')) {
            $result .= (! empty($result) ? ' / ' : '') . 'Remoto';
        }

        return $result;
    }

    /**
     * Check if the job was recently created.
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->created_at->diffInDays() <= 7;
    }

    /**
     * Check if the job has been approved.
     *
     * @return mixed
     */
    public function isApproved()
    {
        return $this->getAttribute('listing');
    }
}
