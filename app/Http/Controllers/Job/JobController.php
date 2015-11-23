<?php

namespace Laraveles\Http\Controllers\Job;

use Laraveles\Job;
use Illuminate\Http\Request;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\CreateJobRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('job.index');
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
            $this->authorize(Job::class);

            return view('job.create');
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('job.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Job::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize(Job::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('update', Job::class);
    }
}
