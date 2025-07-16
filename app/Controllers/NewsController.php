<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\LikesModel;

class NewsController extends BaseController
{
    public function detail($slug)
    {
        $newsModel = new NewsModel();
        $likeModel = new LikesModel();

        // Ambil berita berdasarkan slug
        $news = $newsModel->where('slug', $slug)->first();
        if (!$news) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Berita tidak ditemukan');
        }

        // Hitung like untuk berita ini
        $likeCount = $likeModel->where('news_id', $news['id'])->countAllResults();

        return view('news/detail', [
            'news' => $news,
            'likeCount' => $likeCount
        ]);
    }
}
