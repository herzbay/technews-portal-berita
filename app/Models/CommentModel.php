<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['news_id', 'user_id', 'content', 'created_at'];
    protected $useTimestamps = false;

    public function getByNews($newsId, $limit = 50)
    {
        return $this->select('comments.*, users.username as user_name')
                    ->join('users', 'users.id = comments.user_id', 'left') // pakai LEFT JOIN
                    ->where('comments.news_id', $newsId)
                    ->orderBy('comments.id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
