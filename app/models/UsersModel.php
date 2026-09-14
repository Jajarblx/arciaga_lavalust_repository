<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = ['firstname', 'lastname', 'email', 'username', 'password'];

    public function find_by_username($username)
    {
        return $this->find_by('username', $username);
    }

    public function email_exists($email, $ignore_id = null)
    {
        return $this->belongs_to_another_user($this->find_by('email', $email), $ignore_id);
    }

    public function username_exists($username, $ignore_id = null)
    {
        return $this->belongs_to_another_user($this->find_by('username', $username), $ignore_id);
    }

    private function belongs_to_another_user($record, $ignore_id = null)
    {
        if (!$record) {
            return false;
        }

        if ($ignore_id !== null && (int) $record['id'] === (int) $ignore_id) {
            return false;
        }

        return true;
    }
}
