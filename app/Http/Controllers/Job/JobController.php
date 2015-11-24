<?php

namespace Laraveles\Http\Controllers\Job;

use Laraveles\Job;
use Illuminate\Http\Request;
use Laraveles\Events\JobWasCreated;
use Laraveles\Events\JobWasApproved;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\CreateJobRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JobController extends Controller
{
    /**
     * JobController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::orderBy('listing', 'ASC')
                   ->orderBy('id', 'DESC')
                   ->get();

        return view('job.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateJobRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $this->authorize('create', Job::class);
            
            return view('job.create')
                ->withTypes($this->getJobTypes());
        } catch (HttpException $e) {
            flash()->info('Antes de crear una nueva oferta de empleo completa tu perfil.');

            return redirect()->route('account.recruiter');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateJobRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateJobRequest $request)
    {
        $this->authorize('create', Job::class);

        $job = new Job(
            $request->only('title', 'description', 'apply', 'type', 'city', 'country', 'remote')
        );
        $job->recruiter()->associate(auth()->user()->recruiter);
        $job->save();

        event(new JobWasCreated($job));
    }

    /**
     * @param $id
     * @return string
     */
    public function approve($id)
    {
        $this->authorize('moderate', Job::class);

        $job = Job::findOrFail($id)
                  ->approve();

        $job->save();

        // TODO: capture this event and send an email to its owner and subscriptors
        event(new JobWasApproved($job));

        flash()->info(
            "El empleo \"{$job->title}\" ha sido aprobado correctamente, se notificará al propietario e interesados"
        );

        return redirect()->route('job.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with('recruiter')
                  ->findOrFail($id);

        return view('job.show', [
            'job'       => $job,
            'recruiter' => $job->recruiter
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $this->authorize('update', $job);

        return view('job.create', ['job' => $job, 'types' => $this->getJobTypes()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateJobRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        $this->authorize($job);

        $job->fill($request->only('title', 'description', 'apply', 'type', 'city', 'country', 'remote'));
        $job->save();

        flash()->info("El empleo \"{$job->title}\" se modificó con éxito.");

        return redirect()->route('job.show', $job->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('moderate', Job::class);

        $job = Job::findOrFail($id);
        $job->delete();

        flash()->info("Se ha eliminado el empleo \"{$job->title}\"");

        return redirect()->route('job.index');
    }

    /**
     * Listing job types.
     *
     * @return array
     */
    protected function getJobTypes()
    {
        return [
            'full-time' => 'Jornada completa',
            'part-time' => 'Media jornada',
            'temporal'  => 'Temporal',
            'freelance' => 'Freelance'
        ];
    }
}
