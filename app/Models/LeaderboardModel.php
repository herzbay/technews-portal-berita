<?php

namespace App\Models;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    protected $table            = 'users'; // Leaderboard diambil dari tabel users
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'total_points'];
    protected $returnType       = 'array';

    /**
     * Ambil 10 besar leaderboard.
     *
     * @param int $limit
     * @return array
     */
    public function getTopUsers(int $limit = 10): array
    {
        return $this->select('id, name, total_points')
                    ->orderBy('total_points', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Ambil peringkat user tertentu.
     *
     * @param int $userId
     * @return int|null
     */
    public function getUserRank(int $userId): ?int
    {
        $users = $this->select('id')
                      ->orderBy('total_points', 'DESC')
                      ->findAll();

        foreach ($users as $index => $user) {
            if ($user['id'] == $userId) {
                return $index + 1; // peringkat dimulai dari 1
            }
        }

        return null;
    }
}
