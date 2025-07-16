<?php

namespace App\Controllers;

use App\Models\UserModel;

class LeaderboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $leaders = $userModel->orderBy('total_points', 'DESC')
                             ->limit(10)
                             ->findAll();

        return view('leaderboard/index', ['leaders' => $leaders]);
    }
}
