<?php

namespace App\Controllers;

use App\Models\UserModel;
use Google_Client;
use Google_Service_Oauth2;

class Auth extends BaseController
{
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();

            $userModel->save([
                'name'        => $this->request->getPost('name'),
                'email'       => $this->request->getPost('email'),
                'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'        => 'user',
                'created_at'  => date('Y-m-d H:i:s')
            ]);

            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
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

            return redirect()->back()->with('error', 'Login gagal. Email atau password salah.');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('info', 'Kamu telah logout.');
    }

    public function googleLogin()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope("email");
        $client->addScope("profile");

        return redirect()->to($client->createAuthUrl());
    }

    public function googleCallback()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        if ($this->request->getGet('code')) {
            $client->authenticate($this->request->getGet('code'));
            $token = $client->getAccessToken();
            $client->setAccessToken($token);

            $google_service = new Google_Service_Oauth2($client);
            $google_user = $google_service->userinfo->get();

            $userModel = new UserModel();
            $user = $userModel->where('email', $google_user->email)->first();

            if (!$user) {
                $userModel->insert([
                    'name'        => $google_user->name,
                    'email'       => $google_user->email,
                    'password'    => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                    'role'        => 'user',
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
                $user = $userModel->where('email', $google_user->email)->first();
            }

            session()->set([
                'user_id'      => $user['id'],
                'user_name'    => $user['name'],
                'role'         => $user['role'],
                'is_logged_in' => true
            ]);

            return redirect()->to('/')->with('success', 'Login dengan Google berhasil!');
        }

        return redirect()->to('/login')->with('error', 'Autentikasi Google gagal.');
    }
}
