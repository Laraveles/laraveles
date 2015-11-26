<?php

namespace Laraveles\Http\Controllers;

use Gate;
use Laraveles\Job;
use Laraveles\Events\JobWasCreated;
use Laraveles\Events\JobWasApproved;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\CreateJobRequest;
use Laraveles\Repositories\JobRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JobController extends Controller
{
    /**
     * JobController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // If the user does not have moderation privileges, we will just show
        // the approved jobs. Otherwise, we will display every job found at
        // database and tag it with a "pending for approval" when listing.
        $jobs = Gate::denies('moderate', Job::class)
            ? Job::approved()
            : Job::ordered();

        return view('job.index', ['jobs' => $jobs->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $this->authorize('create', Job::class);

            // This authorization will prevent creating jobs if the recruiter
            // profile is not fully completed. If so, the user will be able
            // to access to the create display and perform a job creation.
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

        $job = Job::register(
            auth()->user()->recruiter, $request->except('_token')
        );
        $job->save();

        // If the data has been correctly validated and the user has creation
        // access we will then create the new job instance associating the
        // recruiter. If no exception thrown we assume everythng was ok.
        // Then broadcast a new JobWasCreatedEvent to notifiy users.
        event(new JobWasCreated($job));

        flash()->info("El puesto se ha creado correctamente y está pendiente de revisión. Recibirá un e-mail cuando se confirme su aprobación.");

        return redirect()->route('job.index');
    }

    /**
     * Aprove a job for listing.
     *
     * @param $id
     * @return string
     */
    public function approve($id)
    {
        $this->authorize('moderate', Job::class);

        $job = Job::approve($id);
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
        $job = Job::with('recruiter');

        // We will block any request from any user that is not a moderator as
        // we will not show this job until it has been approved. This will
        // prevent any direct access from the URL until it is validated.
        if (Gate::denies('moderate', Job::class)) {
            $job = $job->approved();
        }

        $job = $job->findOrFail($id);

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
     * @param CreateJobRequest $request
     * @param  int             $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateJobRequest $request, $id)
    {
        $job = Job::findOrFail($id);
        $this->authorize($job);

        $job->fill($request->except('_token'));
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
        $job = Job::findOrFail($id);

        $this->authorize('update', $job);

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
