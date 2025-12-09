<?php

/**
 * Helper untuk membuat URL yang sesuai dengan routing
 */

function base_url()
{
    return '/Lab11Web/';
}

function url($path = '')
{
    return base_url() . $path;
}

function current_path()
{
    return isset($_GET['path']) ? $_GET['path'] : 'home/index';
}
