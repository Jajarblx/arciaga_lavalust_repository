<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
    }

    public function index()
    {
        $this->call->view('users', [
            'users' => $this->UsersModel->all(),
            'flash' => $this->session->flashdata('users_flash'),
        ]);
    }

    public function create()
    {
        $this->render_form('create', $this->empty_user());
    }

    public function store()
    {
        $user = $this->user_input();
        $errors = $this->validate_user($user);

        if (!isset($errors['email']) && $this->UsersModel->email_exists($user['email'])) {
            $errors['email'] = 'That email address is already registered.';
        }

        if (!isset($errors['username']) && $this->UsersModel->username_exists($user['username'])) {
            $errors['username'] = 'That username is already in use.';
        }

        if ($errors) {
            $this->render_form('create', $user, $errors);
            return;
        }

        $id = $this->UsersModel->insert($user);

        if ($id === false) {
            $this->render_form('create', $user, [
                'general' => 'The user could not be created. Please try again.',
            ]);
            return;
        }

        $this->flash('success', 'User created successfully.');
        $this->response->redirect_after_post(site_url('users'));
    }

    public function edit($id)
    {
        $id = (int) $id;
        $user = $this->UsersModel->find($id);

        if (!$user) {
            $this->flash('error', 'The requested user was not found.');
            $this->response->redirect(site_url('users'));
        }

        $this->render_form('edit', $this->normalize_user($user), [], $id);
    }

    public function update($id)
    {
        $id = (int) $id;

        if (!$this->UsersModel->find($id)) {
            $this->flash('error', 'The requested user was not found.');
            $this->response->redirect_after_post(site_url('users'));
        }

        $user = $this->user_input();
        $errors = $this->validate_user($user);

        if (!isset($errors['email']) && $this->UsersModel->email_exists($user['email'], $id)) {
            $errors['email'] = 'That email address is already registered.';
        }

        if (!isset($errors['username']) && $this->UsersModel->username_exists($user['username'], $id)) {
            $errors['username'] = 'That username is already in use.';
        }

        if ($errors) {
            $this->render_form('edit', $user, $errors, $id);
            return;
        }

        if ($this->UsersModel->update($id, $user) === false) {
            $this->render_form('edit', $user, [
                'general' => 'The user could not be updated. Please try again.',
            ], $id);
            return;
        }

        $this->flash('success', 'User updated successfully.');
        $this->response->redirect_after_post(site_url('users'));
    }

    public function delete($id)
    {
        $id = (int) $id;

        if (!$this->UsersModel->find($id)) {
            $this->flash('error', 'The requested user was not found.');
            $this->response->redirect_after_post(site_url('users'));
        }

        if ($this->UsersModel->delete($id) === false) {
            $this->flash('error', 'The user could not be deleted.');
        } else {
            $this->flash('success', 'User deleted successfully.');
        }

        $this->response->redirect_after_post(site_url('users'));
    }

    private function render_form($mode, array $user, array $errors = [], $id = null)
    {
        $this->call->view('user_form', [
            'mode' => $mode,
            'user' => $user,
            'errors' => $errors,
            'id' => $id,
        ]);
    }

    private function user_input()
    {
        return [
            'firstname' => trim((string) $this->request->post('firstname', '')),
            'lastname' => trim((string) $this->request->post('lastname', '')),
            'email' => strtolower(trim((string) $this->request->post('email', ''))),
            'username' => trim((string) $this->request->post('username', '')),
        ];
    }

    private function validate_user(array $user)
    {
        $errors = [];

        foreach (['firstname' => 'First name', 'lastname' => 'Last name'] as $field => $label) {
            if ($user[$field] === '') {
                $errors[$field] = $label . ' is required.';
            } elseif (strlen($user[$field]) > 100) {
                $errors[$field] = $label . ' must not exceed 100 characters.';
            } elseif (!preg_match("/^[\\p{L} .'-]+$/u", $user[$field])) {
                $errors[$field] = $label . ' contains invalid characters.';
            }
        }

        if ($user['email'] === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (strlen($user['email']) > 150) {
            $errors['email'] = 'Email address must not exceed 150 characters.';
        } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($user['username'] === '') {
            $errors['username'] = 'Username is required.';
        } elseif (!preg_match('/^[A-Za-z0-9._-]{3,100}$/', $user['username'])) {
            $errors['username'] = 'Use 3-100 letters, numbers, dots, underscores, or hyphens.';
        }

        return $errors;
    }

    private function normalize_user(array $user)
    {
        return [
            'firstname' => (string) ($user['firstname'] ?? ''),
            'lastname' => (string) ($user['lastname'] ?? ''),
            'email' => (string) ($user['email'] ?? ''),
            'username' => (string) ($user['username'] ?? ''),
        ];
    }

    private function empty_user()
    {
        return [
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'username' => '',
        ];
    }

    private function flash($type, $message)
    {
        $this->session->set_flashdata('users_flash', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
