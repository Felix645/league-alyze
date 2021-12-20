<?php

if( !function_exists('formatNumber') ) {
    function formatNumber($number, int $precision) : string
    {
        return number_format(round($number, $precision), $precision);
    }
}