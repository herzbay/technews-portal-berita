<?php

namespace App\Controllers;

use App\Models\UserModel;

class LeaderboardController extends BaseController
{
    /**
     * ✅ Menampilkan 10 user dengan poin tertinggi.
     */
    public function index()
    {
        try {
            $userModel = new UserModel();

            $leaders = $userModel
                ->select('id, name, total_points')
                ->orderBy('total_points', 'DESC')
                ->limit(10)
                ->findAll();

            return view('leaderboard/index', ['leaders' => $leaders]);
        } catch (\Exception $e) {
            // Bisa redirect ke error page atau tampilkan pesan fallback
            return view('leaderboard/index', ['leaders' => [], 'error' => 'Gagal memuat leaderboard']);
        }
    }
}
