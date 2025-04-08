<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if (!$username || !$password) {
            $this->send_error('Username and password are required');
        }

        $user = $this->UserModel->get_by_username($username);

        if (!$user || !password_verify($password, $user->password)) {
            $this->send_error('Invalid username or password', 401);
        }

        $token_data = [
            'id' => $user->id,
            'username' => $user->username,
            'timestamp' => now()
        ];

        $token = AUTHORIZATION::generateToken($token_data);

        $this->send_success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function refresh() {
        $decoded = $this->verify_token();
        
        $token_data = [
            'id' => $decoded->id,
            'username' => $decoded->username,
            'timestamp' => now()
        ];

        $token = AUTHORIZATION::generateToken($token_data);

        $this->send_success([
            'token' => $token
        ]);
    }
} 
