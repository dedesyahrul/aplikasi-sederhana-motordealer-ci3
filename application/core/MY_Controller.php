<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    protected $response = array();
    protected $status_code = 200;

    public function __construct() {
        parent::__construct();
        $this->config->load('api');
        $this->_check_auth();
    }

    private function _check_auth() {
        // Skip auth check if not required
        if (!$this->config->item('require_auth')) {
            return;
        }

        $api_key = $this->input->get_request_header($this->config->item('api_key_header'));
        
        if (!$api_key) {
            $this->send_error('API key is required', 401);
            return;
        }

        if ($api_key !== $this->config->item('api_key')) {
            $this->send_error('Invalid API key', 401);
            return;
        }
    }

    protected function send_response($data = null, $message = 'Success') {
        $this->response['status'] = true;
        $this->response['message'] = $message;
        if ($data !== null) {
            $this->response['data'] = $data;
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($this->status_code)
            ->set_output(json_encode($this->response));
    }

    protected function send_error($message = 'Error', $status_code = 400, $data = null) {
        $this->status_code = $status_code;
        $this->response['status'] = false;
        $this->response['message'] = $message;
        if ($data !== null) {
            $this->response['data'] = $data;
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($this->status_code)
            ->set_output(json_encode($this->response));
        exit;
    }
} 
