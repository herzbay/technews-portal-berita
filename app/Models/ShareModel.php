<?php

namespace App\Models;

use CodeIgniter\Model;

class ShareModel extends Model
{
    protected $table            = 'shares';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'news_id', 'platform', 'created_at'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    /**
     * Simpan log share berita.
     *
     * @param int $userId
     * @param int $newsId
     * @param string $platform
     * @return bool
     */
    public function logShare(int $userId, int $newsId, string $platform = 'unknown'): bool
    {
        return $this->insert([
            'user_id'    => $userId,
            'news_id'    => $newsId,
            'platform'   => $platform,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Hitung jumlah share berita tertentu.
     *
     * @param int $newsId
     * @return int
     */
    public function countShares(int $newsId): int
    {
        return $this->where('news_id', $newsId)->countAllResults();
    }
}
