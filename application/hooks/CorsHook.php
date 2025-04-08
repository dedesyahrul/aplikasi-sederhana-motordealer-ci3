<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CorsHook {
    public function handle() {
        $CI =& get_instance();
        $CI->load->config('rest');
        
        // Get CORS settings from config
        $allowed_origins = $CI->config->item('allowed_origins');
        $allowed_methods = $CI->config->item('allowed_methods');
        $allowed_headers = $CI->config->item('allowed_headers');
        $exposed_headers = $CI->config->item('exposed_headers');
        $max_age = $CI->config->item('max_age');
        $allow_credentials = $CI->config->item('allow_credentials');

        // Get the request origin
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        // Check if the origin is allowed
        if (in_array('*', $allowed_origins) || in_array($origin, $allowed_origins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }

        // Set other CORS headers
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowed_methods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $allowed_headers));
        
        if (!empty($exposed_headers)) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $exposed_headers));
        }
        
        if ($max_age) {
            header('Access-Control-Max-Age: ' . $max_age);
        }
        
        if ($allow_credentials) {
            header('Access-Control-Allow-Credentials: true');
        }

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }
} 
