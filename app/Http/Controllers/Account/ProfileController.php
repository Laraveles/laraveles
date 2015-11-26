<?php

namespace Laraveles\Http\Controllers\Account;

use Laraveles\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('account.profile');
    }

    public function store()
    {

    }
}