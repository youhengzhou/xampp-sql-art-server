<?php

function redirect($url)
{
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    die();
}

function searchForId($key, $value, $array)
{
    foreach ($array as $i => $val) {
        if ($val[$key] === $value) {
            return $i;
        }
    }
    return null;
}
