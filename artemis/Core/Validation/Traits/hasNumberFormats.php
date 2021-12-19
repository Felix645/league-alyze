<?php


namespace Artemis\Core\Validation\Traits;


trait hasNumberFormats
{
    /**
     * Converts any given number string
     *
     * @param string $number
     *
     * @return string
     */
    protected function toSystemNumber($number)
    {
        $number = str_replace(',', '.', $number);
        return preg_replace('/[.](?=.*[.])/', '', $number);
    }
}