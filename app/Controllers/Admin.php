<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends Controller
{
    public function index()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/');
        }

        return view('admin/dashboard');
    }
}
