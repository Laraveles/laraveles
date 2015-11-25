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
    protected $fillable = ['title', 'description', 'apply', 'type', 'city', 'country', 'remote'];

    /**
     * Booting the model.
     */
    public static function boot()
    {
        parent::boot();

        // When getting an unchecked checkbox from the view form, it normally
        // obtain a null value. We will check for it in order to transform
        // this into a not null value as `false` to pass the constraint.
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
    public static function approve($id)
    {
        $job = $this->findOrFail($id);
        $job->setAttribute('listing', true);

        return $this;
    }

    /**
     * Will register a new job.
     *
     * @param Recruiter $recruiter
     * @param           $data
     * @return static
     */
    public static function register(Recruiter $recruiter, $data)
    {
        $job = new static($data);
        $job->recruiter()->associate($recruiter);

        return $job;
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
