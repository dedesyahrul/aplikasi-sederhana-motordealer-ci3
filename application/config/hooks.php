<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// CORS Hook
$hook['pre_system'] = array(
    'class'    => 'CorsHook',
    'function' => 'handle',
    'filename' => 'CorsHook.php',
    'filepath' => 'hooks'
);
