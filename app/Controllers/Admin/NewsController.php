<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NewsModel;

class NewsController extends BaseController
{
    public function index()
    {
        $model = new NewsModel();
        $data['news'] = $model->orderBy('created_at', 'DESC')->findAll();

        return view('admin/news/index', $data);
    }

    public function create()
    {
        return view('admin/news/create');
    }

    public function store()
    {
        $model = new NewsModel();

        $imagePath = null;
        if ($img = $this->request->getFile('image')) {
            if ($img->isValid() && !$img->hasMoved()) {
                $imageName = $img->getRandomName();
                $img->move(FCPATH . 'uploads', $imageName);
                $imagePath = '/uploads/' . $imageName;
            }
        }

        $model->insert([
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'content'     => $this->request->getPost('content'),
            'image_url'   => $imagePath,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        session()->setFlashdata('success', 'Berita berhasil ditambahkan!');
        return redirect()->to('/admin/news');
    }

    public function edit($id)
    {
        $model = new NewsModel();
        $news = $model->find($id);

        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Berita tidak ditemukan");
        }

        return view('admin/news/edit', ['news' => $news]);
    }

    public function update($id)
    {
        $model = new NewsModel();
        $news = $model->find($id);

        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Berita tidak ditemukan");
        }

        $imagePath = $news['image_url'];
        if ($img = $this->request->getFile('image')) {
            if ($img->isValid() && !$img->hasMoved()) {
                $imageName = $img->getRandomName();
                $img->move(FCPATH . 'uploads', $imageName);
                $imagePath = '/uploads/' . $imageName;
            }
        }

        $model->update($id, [
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'content'     => $this->request->getPost('content'),
            'image_url'   => $imagePath
        ]);

        session()->setFlashdata('success', 'Berita berhasil diperbarui!');
        return redirect()->to('/admin/news');
    }

    public function delete($id)
    {
        $model = new NewsModel();
        $model->delete($id);

        session()->setFlashdata('success', 'Berita berhasil dihapus!');
        return redirect()->to('/admin/news');
    }
}
