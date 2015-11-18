<?php

namespace Laraveles\Http\Controllers;

use Illuminate\Http\Request;

use Laraveles\Http\Requests;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Services\Docs;

class DocsController extends Controller
{
    /**
     * The Docs service.
     *
     * @var Docs
     */
    protected $docs;

    /**
     * DocsController constructor.
     *
     * @param Docs $docs
     */
    public function __construct(Docs $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Redirect to the current version.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('docs.show', $this->docs->lastVersion());
    }

    /**
     * Display the specified section.
     *
     * @param        $version
     * @param string $section
     * @return \Illuminate\Http\Response
     */
    public function show($version, $section = 'installation')
    {
        $this->docs->version($version);

        if (! $this->docs->exists($section)) {
            return redirect()->route('docs.show', [$version]);
        }

        return view('docs.docs', [
            'index'        => $this->docs->index(),
            'content'      => $this->docs->section($section),
            'fileVersions' => $this->docs->versionsOf($section)
        ]);
    }

}
