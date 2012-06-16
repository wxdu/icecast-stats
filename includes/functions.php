<?php

/**
 * Utility functions 
 */

function strTime($s) {
    $d = intval($s / 86400);
    $s -= $d * 86400;

    $h = intval($s / 3600);
    $s -= $h * 3600;

    $m = intval($s / 60);
    $s -= $m * 60;

    if ($d)
        $str = $d . 'd ';
    if ($h)
        $str .= $h . 'h ';
    if ($m)
        $str .= $m . 'm ';
    if ($s)
        $str .= $s . 's';

    return $str;
}
