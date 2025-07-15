<?php

namespace App\Controllers;

use App\Models\NewsModel;

class Home extends BaseController
{
    public function index()
    {
        $newsModel = new NewsModel();
        $news = $newsModel->orderBy('created_at', 'DESC')->findAll(6); // Ambil 6 berita terbaru

        return view('home', ['news' => $news]);
    }
}
