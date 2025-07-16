<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $userModel->insert($data);

        session()->setFlashdata('success', 'Registrasi berhasil, silakan login.');
        return redirect()->to('/login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'role' => $user['role'],
            'user_points' => $user['total_points'], 
            'logged_in' => true
        ]);

            session()->setFlashdata('success', 'Login berhasil! Selamat datang, '.$user['name']);
            return redirect()->to('/');
        }

        session()->setFlashdata('error', 'Email atau password salah.');
        return redirect()->back();
    }

    public function logout()
    {
        session()->destroy();
        session()->setFlashdata('success', 'Anda berhasil logout.');
        return redirect()->to('/');
    }
}
