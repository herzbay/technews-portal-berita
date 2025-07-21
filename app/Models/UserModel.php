<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'email', 'password', 'role', 'total_points', 'created_at'
    ];

    /**
     * Ambil total poin user.
     */
    public function getPoints(int $userId): int
    {
        $user = $this->find($userId);
        return isset($user['total_points']) ? (int) $user['total_points'] : 0;
    }

    /**
     * Update total poin user.
     */
    public function updatePoints(int $userId, int $points): bool
    {
        return $this->update($userId, ['total_points' => max(0, $points)]);
    }

    /**
     * Ambil user berdasarkan email (untuk login).
     */
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}
