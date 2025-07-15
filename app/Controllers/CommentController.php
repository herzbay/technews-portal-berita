<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentController extends BaseController
{
    public function add()
    {
        $commentModel = new CommentModel();
        $data = [
            'user_id' => 1, // sementara hardcode (nanti pakai session)
            'news_id' => $this->request->getPost('news_id'),
            'content' => $this->request->getPost('content'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $commentModel->insert($data);

        return view('comments/item', ['comment' => $data]); // partial view
    }

    public function list($newsId)
    {
        $commentModel = new CommentModel();
        $comments = $commentModel->where('news_id', $newsId)->orderBy('created_at', 'DESC')->findAll();
        return view('comments/list', ['comments' => $comments]);
    }
}
