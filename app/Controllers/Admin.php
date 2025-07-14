<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function __construct()
    {
        // Proteksi: hanya admin yang boleh akses 
        if (!session('is_logged_in') || session('role') !== 'admin') {
            redirect()->to('/')->send(); 
            exit;
        }
    }

    public function index()
    {
        return view('admin/dashboard');
    }
}
