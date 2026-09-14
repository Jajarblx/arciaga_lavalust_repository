<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
    }

    public function login()
    {
        if ($this->session->has_userdata('auth_user_id')) {
            $this->response->redirect(site_url('products'));
        }

        $this->call->view('login', [
            'username' => '',
            'error' => '',
            'message' => $this->session->flashdata('auth_flash'),
        ]);
    }

    public function authenticate()
    {
        if ($this->session->has_userdata('auth_user_id')) {
            $this->response->redirect_after_post(site_url('products'));
        }

        $username = trim((string) $this->request->post('username', ''));
        $password = (string) $this->request->post('password', '');

        if ($username === '' || $password === '') {
            $this->render_login($username, 'Username and password are required.');
            return;
        }

        $user = $this->UsersModel->find_by_username($username);
        $hash = $user['password'] ?? '';

        if (!$user || $hash === '' || !password_verify($password, $hash)) {
            $this->render_login($username, 'Invalid username or password.');
            return;
        }

        $this->session->after_successful_login();
        $this->session->set_userdata([
            'auth_user_id' => (int) $user['id'],
            'auth_username' => (string) $user['username'],
        ]);
        $this->session->set_flashdata(
            'products_flash',
            ['type' => 'success', 'message' => 'Welcome back, ' . $user['username'] . '.']
        );

        $this->response->redirect_after_post(site_url('products'));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->response->redirect(site_url('login'));
    }

    private function render_login($username, $error)
    {
        $this->call->view('login', [
            'username' => $username,
            'error' => $error,
            'message' => '',
        ]);
    }
}
