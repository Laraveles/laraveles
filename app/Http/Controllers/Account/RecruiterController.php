<?php

namespace Laraveles\Http\Controllers\Account;

use Laraveles\Commands\Account\UpdateRecruiter;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\UpdateRecruiterRequest;

class RecruiterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recruiter = auth()->user()->recruiter;

        return view('account.recruiter', compact('recruiter'));
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

        if ($request->file('avatar')) {
            $command['avatar'] = $request->file('avatar')->getRealPath();
        }

        $this->dispatchFromArray(UpdateRecruiter::class, $command);

        return redirect()->route('account.recruiter.index');
    }
}
