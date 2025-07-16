<?php

namespace App\Controllers;

use App\Models\CommentModel;
use CodeIgniter\Controller;

class CommentController extends BaseController
{
    public function list($newsId)
    {
        $commentModel = new CommentModel();
        $comments = $commentModel
            ->select('comments.*, users.username')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->where('comments.news_id', $newsId)
            ->orderBy('comments.created_at', 'DESC')
            ->findAll();

        return view('partials/comment_list', ['comments' => $comments]);
    }

    public function add($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON([
                'error'    => 'Silakan login untuk berkomentar',
                'redirect' => '/login'
            ]);
        }

        $content = trim($this->request->getPost('content'));
        if (!$content) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Komentar tidak boleh kosong']);
        }

        $commentModel = new CommentModel();
        $commentModel->insert([
            'news_id'    => $newsId,
            'user_id'    => session()->get('user_id'),
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $data = [
            'user'    => session()->get('user_name'),
            'content' => $content,
            'time'    => date('d M Y H:i')
        ];

        return view('partials/comment_item', $data);
    }
}
