<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->orderBy('created_at', 'DESC')->findAll();

        return view('admin/users/index', $data);
    }

    public function create()
    {
        return view('admin/users/create');
    }

    public function store()
    {
        $userModel = new UserModel();

        // ✅ Validasi sederhana
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[user,admin]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! Pastikan semua data benar.');
        }

        $data = [
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $userModel->insert($data);
            session()->setFlashdata('success', '✅ User berhasil ditambahkan!');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }

        return redirect()->to('/admin/users');
    }

    public function edit($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User tidak ditemukan");
        }

        return view('admin/users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User tidak ditemukan");
        }

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'role'  => 'required|in_list[user,admin]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal!');
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role')
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        try {
            $model->update($id, $data);
            session()->setFlashdata('success', '✅ User berhasil diperbarui!');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }

        return redirect()->to('/admin/users');
    }

    public function delete($id)
    {
        $model = new UserModel();

        try {
            $model->delete($id);
            session()->setFlashdata('success', '✅ User berhasil dihapus!');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menghapus user: ' . $e->getMessage());
        }

        return redirect()->to('/admin/users');
    }
}
