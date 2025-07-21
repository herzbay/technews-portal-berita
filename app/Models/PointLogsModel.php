<?php

namespace App\Models;

use CodeIgniter\Model;

class PointLogsModel extends Model
{
    protected $table            = 'point_logs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'news_id', 'action_type', 'points_awarded', 'created_at'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    /**
     * Tambahkan log poin.
     *
     * @param int    $userId
     * @param int    $newsId
     * @param string $actionType
     * @param int    $points
     * @return bool
     */
    public function logPoint(int $userId, int $newsId, string $actionType, int $points): bool
    {
        return $this->insert([
            'user_id'        => $userId,
            'news_id'        => $newsId,
            'action_type'    => $actionType,
            'points_awarded' => $points,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Periksa apakah user sudah dapat poin untuk aksi tertentu (anti-spam).
     *
     * @param int    $userId
     * @param int    $newsId
     * @param string $actionType
     * @return bool
     */
    public function hasLoggedAction(int $userId, int $newsId, string $actionType): bool
    {
        return (bool) $this->where('user_id', $userId)
            ->where('news_id', $newsId)
            ->where('action_type', $actionType)
            ->countAllResults();
    }

    /**
     * Ambil semua log poin untuk user tertentu.
     *
     * @param int $userId
     * @return array
     */
    public function getUserLogs(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
