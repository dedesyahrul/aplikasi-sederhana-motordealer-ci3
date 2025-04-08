<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| REST API Configuration
|--------------------------------------------------------------------------
*/

// JWT Configuration
$config['jwt_key'] = 'your-secret-key-here';
$config['token_timeout'] = 60; // Minutes

// API Rate Limiting
$config['rate_limit_enabled'] = TRUE;
$config['rate_limit_requests'] = 60; // Number of requests
$config['rate_limit_time'] = 60; // Time period in seconds

// CORS Settings
$config['allowed_origins'] = ['*'];
$config['allowed_methods'] = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
$config['allowed_headers'] = ['Origin', 'Content-Type', 'Accept', 'Authorization'];
$config['exposed_headers'] = [''];
$config['max_age'] = 3600;
$config['allow_credentials'] = TRUE;

// Response Format
$config['response_format'] = 'json'; // json or xml 
