<?php

namespace Laraveles\Services;

use Intervention\Image\ImageManager;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class Image
{
    /**
     * The image manager instance.
     *
     * @var ImageManager
     */
    protected $image;

    /**
     * Image to play with.
     *
     * @var string
     */
    protected $from;

    /**
     * The file manager instance.
     *
     * @var Filesystem
     */
    protected $file;

    /**
     * Result image width.
     *
     * @var int
     */
    protected $width = 300;

    /**
     * Result image height.
     *
     * @var int
     */
    protected $height = 300;

    /**
     * Aspect ratio contraint.
     *
     * @var bool
     */
    protected $aspectRatio = true;

    /**
     * Avatar constructor.
     *
     * @param ImageManager $image
     * @param Filesystem   $file
     */
    public function __construct(ImageManager $image, Filesystem $file)
    {
        $this->image = $image;
        $this->file = $file;
    }

    /**
     * Make a new instance.
     *
     * @param $from
     * @return Image
     */
    public function make($from)
    {
        return (new static($this->image, $this->file))
            ->from($from);
    }

    /**
     * @param string $to
     * @param string $prefix
     * @return string
     */
    public function store($to, $prefix = 'img_')
    {
        $this->createPath($to);

        $extension = (new File($this->from))->guessExtension();
        $file = uniqid($prefix) . '.' . $extension;
        $image = $this->image->make($this->from);

        // Once we have identified the file extension and generated an unique
        // file name, we will just proceed to resize the image based on the
        // preconfigured values. After it will be saved to $to$file path.
        $image->resize($this->width, $this->height, function ($c) {
            if ($this->aspectRatio) {
                $c->aspectRatio();
            }
        })->save($to . '/' . $file);

        return $file;
    }

    /**
     * Delete the from file.
     */
    public function clear()
    {
        if ($this->file->exists($this->from)) {
            $this->file->delete($this->from);
        }
    }

    /**
     * Create the folder if does not exist.
     *
     * @param $to
     */
    protected function createPath($to)
    {
        if (! $this->file->exists($to)) {
            $this->file->makeDirectory($to, null, true);
        }
    }

    /**
     * Set the image width.
     *
     * @param int $width
     * @return Avatar
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the image height.
     *
     * @param int $height
     * @return Avatar
     */
    public function height($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set the aspect ratio.
     *
     * @param boolean $aspectRatio
     * @return Avatar
     */
    public function aspectRatio($aspectRatio)
    {
        $this->aspectRatio = $aspectRatio;

        return $this;
    }

    /**
     * Set the file to play with.
     *
     * @param string $from
     * @return Image
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the file to play with.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
}
