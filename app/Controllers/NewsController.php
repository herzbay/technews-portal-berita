<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\LikesModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class NewsController extends BaseController
{
    /**
     * ✅ Tampilkan detail berita berdasarkan slug.
     */
    public function detail(string $slug)
    {
        $newsModel = new NewsModel();
        $likeModel = new LikesModel();

        // ✅ Ambil berita berdasarkan slug
        $news = $newsModel->where('slug', $slug)->first();

        if (!$news) {
            throw PageNotFoundException::forPageNotFound('Berita tidak ditemukan');
        }

        // ✅ Hitung total like
        $likeCount = $likeModel->countLikes((int) $news['id']);

        // ✅ Cek apakah user sudah like (jika login)
        $isLiked = false;
        if (session()->get('logged_in')) {
            $userId = (int) session()->get('user_id');
            $isLiked = (bool) $likeModel->isLiked($userId, (int) $news['id']);
        }

        // ✅ Kirim data ke view
        return view('news/detail', [
            'title'      => esc($news['title']) . ' | NEWSTECHLY',
            'news'       => $news,
            'likeCount'  => $likeCount,
            'isLiked'    => $isLiked
        ]);
    }
}
