<?php

namespace Laraveles\Http\Controllers\Serve;

use Illuminate\Http\Request;
use Laraveles\Http\Controllers\Controller;

class MarkdownController extends Controller
{
    /**
     * MarkdownController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Will transform the markdown into html.
     *
     * @param Request $request
     * @return mixed|string|void
     */
    public function transform(Request $request)
    {
        if (! $request->has('content')) {
            return;
        }

        return markdown($request->get('content'));
    }
}