<?php

namespace App\Models;

use CodeIgniter\Model;

class LikesModel extends Model
{
    protected $table         = 'likes';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'news_id', 'created_at'];
    protected $useTimestamps = false; // karena kita set manual
    protected $returnType    = 'array';

    /**
     * ✅ Hitung jumlah like pada berita tertentu.
     */
    public function countLikes(int $newsId): int
    {
        return (int) $this->where('news_id', $newsId)->countAllResults();
    }

    /**
     * ✅ Periksa apakah user sudah like berita (return array jika ada).
     */
    public function getLikeRecord(int $userId, int $newsId): ?array
    {
        return $this->where('user_id', $userId)
                    ->where('news_id', $newsId)
                    ->first() ?: null;
    }

    /**
     * ✅ Cek apakah user sudah like (boolean).
     */
    public function isLiked(int $userId, int $newsId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('news_id', $newsId)
                    ->countAllResults() > 0;
    }

    /**
     * ✅ Tambahkan like.
     */
    public function addLike(int $userId, int $newsId): bool
    {
        return (bool) $this->insert([
            'user_id'    => $userId,
            'news_id'    => $newsId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * ✅ Hapus like user pada berita.
     */
    public function removeLike(int $userId, int $newsId): bool
    {
        return (bool) $this->where('user_id', $userId)
                           ->where('news_id', $newsId)
                           ->delete();
    }

    /**
     * ✅ Ambil semua like milik user.
     */
    public function getUserLikes(int $userId): array
    {
        return $this->where('user_id', $userId)->findAll();
    }
}
