<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthMiddleware
{
    public function handle(Closure $next)
    {
        $lava = lava_instance();

        if (!$lava->session->has_userdata('auth_user_id')) {
            $lava->session->set_flashdata(
                'auth_flash',
                'Please log in to access product management.'
            );
            $lava->response->redirect(site_url('login'));
        }

        return $next();
    }
}
