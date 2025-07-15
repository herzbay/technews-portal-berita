<?php

namespace App\Controllers;

use App\Models\NewsModel;

class NewsController extends BaseController
{
    public function detail($slug)
    {
        $newsModel = new NewsModel();
        $news = $newsModel->where('slug', $slug)->first();

        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Berita tidak ditemukan");
        }

        return view('news/detail', ['news' => $news]);
    }
}
