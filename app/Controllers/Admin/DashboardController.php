<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\UserModel;
use App\Models\CommentModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $newsModel = new NewsModel();
        $userModel = new UserModel();
        $commentModel = new CommentModel();

        $data = [
            'totalNews' => $newsModel->countAll(),
            'totalUsers' => $userModel->countAll(),
            'totalComments' => $commentModel->countAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
