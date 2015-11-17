<?php

namespace Laraveles\Services;

use ParsedownExtra as Markdown;
use Illuminate\Filesystem\Filesystem;

class Docs
{
    /**
     * Version to work for.
     *
     * @var string
     */
    protected $version;

    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var Markdown
     */
    private $markdown;

    /**
     * Docs constructor.
     *
     * @param Filesystem $file
     * @param Markdown   $markdown
     */
    public function __construct(Filesystem $file, Markdown $markdown)
    {
        $this->file = $file;
        $this->markdown = $markdown;
    }

    /**
     * Get the index of sessions.
     *
     * @return string
     */
    public function index()
    {
        return $this->section('documentation');
    }

    /**
     * Provides a single section parsed to html.
     *
     * @param $section
     * @return string
     */
    public function section($section)
    {
        return $this->markdown->parse(
            $this->file->get($this->sectionPath($section))
        );
    }

    /**
     * Replace the version tag for any link found.
     *
     * @param $content
     * @return mixed
     */
    public function versionLinks($content)
    {
        return str_replace('{{version}}', $this->version, $content);
    }

    /**
     * Gives the full path for a section.
     *
     * @param $section
     * @return string
     */
    protected function sectionPath($section)
    {
        return base_path("resources/docs/{$this->version}/{$section}.md");
    }

    /**
     * Setting the version to work with.
     *
     * @param string $version
     * @return Docs
     */
    public function version($version)
    {
        $this->version = $version;

        return $this;
    }
}