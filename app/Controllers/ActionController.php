<?php

namespace App\Controllers;

use App\Models\LikesModel;
use App\Models\ShareModel;
use App\Models\PointLogsModel;
use App\Models\UserModel;
use App\Models\CommentsModel;

class ActionController extends BaseController
{
    /**
     * Tambah poin dan update session.
     */
    protected function addPoints($userId, $newsId, $action, $points)
    {
        $pointModel = new PointLogsModel();
        $userModel  = new UserModel();

        // Cek apakah user sudah dapat poin untuk aksi ini (hindari spam)
        $existing = $pointModel->where('user_id', $userId)
            ->where('news_id', $newsId)
            ->where('action_type', $action)
            ->first();

        if ($existing) {
            return $userModel->find($userId)['total_points'];
        }

        // Simpan log
        $pointModel->insert([
            'user_id'        => $userId,
            'news_id'        => $newsId,
            'action_type'    => $action,
            'points_awarded' => $points,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // Update poin user
        $user = $userModel->find($userId);
        $newTotal = $user['total_points'] + $points;
        $userModel->update($userId, ['total_points' => $newTotal]);
        session()->set('user_points', $newTotal);

        return $newTotal;
    }

    /**
     * Like / Unlike berita.
     */
    public function like($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON([
                'error'    => 'Silakan login untuk memberikan like',
                'redirect' => '/login'
            ]);
        }

        $userId = session()->get('user_id');
        $likeModel = new LikesModel();

        $existing = $likeModel->where('user_id', $userId)->where('news_id', $newsId)->first();

        if ($existing) {
            $likeModel->delete($existing['id']);
            $count = $likeModel->where('news_id', $newsId)->countAllResults();
            return $this->response->setJSON([
                'status'    => 'unliked',
                'count'     => $count,
                'newPoints' => session()->get('user_points')
            ]);
        } else {
            $likeModel->insert([
                'user_id'    => $userId,
                'news_id'    => $newsId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $newPoints = $this->addPoints($userId, $newsId, 'like', 3);
            $count = $likeModel->where('news_id', $newsId)->countAllResults();

            return $this->response->setJSON([
                'status'    => 'liked',
                'count'     => $count,
                'newPoints' => $newPoints
            ]);
        }
    }

    /**
     * Share berita.
     */
    public function share($newsId)
    {
        $userId = session()->get('user_id');
        $platform = $this->request->getPost('platform') ?? 'unknown';

        $shareModel = new ShareModel();
        $shareModel->insert([
            'user_id'    => $userId ?? 0,
            'news_id'    => $newsId,
            'platform'   => $platform,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $newPoints = null;
        if ($userId) {
            $newPoints = $this->addPoints($userId, $newsId, 'share', 5);
        }

        return $this->response->setJSON([
            'status'    => 'shared',
            'platform'  => $platform,
            'newPoints' => $newPoints
        ]);
    }

    /**
     * Tambah komentar.
     */
    public function comment($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON([
                'error'    => 'Silakan login untuk berkomentar',
                'redirect' => '/login'
            ]);
        }

        $userId = session()->get('user_id');
        $content = trim($this->request->getPost('content'));

        if (empty($content)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Komentar tidak boleh kosong']);
        }

        $commentModel = new CommentsModel();

        // Cek apakah user sudah pernah komen berita ini
        $existing = $commentModel->where('user_id', $userId)->where('news_id', $newsId)->first();
        if ($existing) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Anda sudah berkomentar pada berita ini']);
        }

        $commentModel->insert([
            'user_id'    => $userId,
            'news_id'    => $newsId,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $newPoints = $this->addPoints($userId, $newsId, 'comment', 5);

        $commentHTML = '<div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow mb-2">
                            <p class="font-semibold text-blue-600">'.esc(session()->get('user_name')).'</p>
                            <p>'.esc($content).'</p>
                            <small class="text-gray-500">Baru saja</small>
                        </div>';

        return $this->response->setJSON([
            'status'    => 'comment_added',
            'html'      => $commentHTML,
            'newPoints' => $newPoints
        ]);
    }
}
