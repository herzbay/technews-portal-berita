<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $session = session();
        $model = new UserModel();

        $user = $model->where('email', $this->request->getPost('email'))->first();
        if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
            $session->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'role' => $user['role'],
                'is_logged_in' => true
            ]);
            return redirect()->to('/');
        }

        return redirect()->back()->with('error', 'Email atau password salah');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        $model = new UserModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];
        $model->insert($data);
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
