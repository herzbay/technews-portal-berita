<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table            = 'comments';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['news_id', 'user_id', 'content', 'created_at'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    /**
     * Ambil komentar untuk berita tertentu.
     *
     * @param int $newsId
     * @param int $limit
     * @return array
     */
    public function getByNews(int $newsId, int $limit = 50): array
    {
        return $this->select('comments.*, COALESCE(users.name, "Anonim") AS user_name')
            ->join('users', 'users.id = comments.user_id', 'left')
            ->where('comments.news_id', $newsId)
            ->orderBy('comments.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Cek apakah user sudah berkomentar di berita ini (untuk anti-spam).
     *
     * @param int $userId
     * @param int $newsId
     * @return bool
     */
    public function hasCommented(int $userId, int $newsId): bool
    {
        return (bool) $this->where('user_id', $userId)
            ->where('news_id', $newsId)
            ->countAllResults();
    }

    /**
     * Tambahkan komentar baru.
     *
     * @param int $userId
     * @param int $newsId
     * @param string $content
     * @return bool
     */
    public function addComment(int $userId, int $newsId, string $content): bool
    {
        return $this->insert([
            'news_id'    => $newsId,
            'user_id'    => $userId,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
