<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| API Configuration
|--------------------------------------------------------------------------
|
| This file contains the configuration for the API
|
*/

// API Key that will be used to authenticate requests
$config['api_key'] = '1234567890';  // Ganti dengan API key yang lebih aman

// Whether to enable API authentication
$config['require_auth'] = TRUE;

// API Key header name
$config['api_key_header'] = 'X-API-KEY';

// Response format
$config['response_format'] = 'json'; 
