<?php

if (! function_exists('markdown')) {
    /**
     * Transforms markdown to HTML.
     *
     * @param $content
     * @return mixed|string
     */
    function markdown($content)
    {
        $parser = app()->make(ParsedownExtra::class);

        return $parser->text($content);
    }
}