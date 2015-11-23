<?php

namespace Laraveles\Http\Controllers\Account;

use Laraveles\Commands\Account\UpdateRecruiter;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\UpdateRecruiterRequest;

class RecruiterController extends Controller
{
    /**
     * RecruiterController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.recruiter');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpdateRecruiterRequest $request
     * @return string
     */
    public function store(UpdateRecruiterRequest $request)
    {
        $command = $request->only('company', 'website', 'avatar');
        $command['actor'] = auth()->user();

        $this->dispatchFromArray(UpdateRecruiter::class, $command);

        return redirect()->route('account.recruiter');
    }
}