<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name'     => 'required|min_length[3]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
            }

            $userModel = new UserModel();
            $inserted = $userModel->insert([
                'name'       => $this->request->getPost('name'),
                'email'      => $this->request->getPost('email'),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if ($inserted) {
                return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
            }

            return redirect()->back()->with('error', 'Gagal menyimpan data ke database.');
        }

        return view('auth/register');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();

            if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                session()->set([
                    'user_id'      => $user['id'],
                    'user_name'    => $user['name'],
                    'role'         => $user['role'],
                    'is_logged_in' => true
                ]);
                return redirect()->to('/')->with('success', 'Login berhasil!');
            }

            return redirect()->back()->with('error', 'Email atau password salah.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('info', 'Kamu telah logout.');
    }
}
