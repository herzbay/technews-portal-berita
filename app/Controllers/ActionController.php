<?php

namespace App\Controllers;

use App\Models\LikesModel;
use App\Models\ShareModel;
use App\Models\PointLogsModel;
use App\Models\UserModel;
use App\Models\CommentModel;
use App\Models\NewsModel;

class ActionController extends BaseController
{
    /**
     * ✅ Tambah poin jika belum ada log sebelumnya.
     */
    protected function addPoints(int $userId, int $newsId, string $action, int $points): int
    {
        $pointModel = new PointLogsModel();
        $userModel  = new UserModel();

        // Cek apakah sudah ada log untuk aksi ini
        $exists = $pointModel->where('user_id', $userId)
                             ->where('news_id', $newsId)
                             ->where('action_type', $action)
                             ->first();

        if ($exists) {
            return (int) $userModel->getPoints($userId);
        }

        // Simpan log
        $pointModel->insert([
            'user_id'        => $userId,
            'news_id'        => $newsId,
            'action_type'    => $action,
            'points_awarded' => $points,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // Update total poin user
        $currentPoints = (int) $userModel->getPoints($userId);
        $newTotal = max(0, $currentPoints + $points);
        $userModel->updatePoints($userId, $newTotal);

        session()->set('user_points', $newTotal);

        return $newTotal;
    }

    /**
     * ✅ Hapus log poin saat unlike.
     */
    private function removePointsLog(int $userId, int $newsId, string $action, int $points): void
    {
        $pointModel = new PointLogsModel();
        $userModel  = new UserModel();

        $pointModel->where('user_id', $userId)
                   ->where('news_id', $newsId)
                   ->where('action_type', $action)
                   ->delete();

        $currentPoints = (int) $userModel->getPoints($userId);
        $newPoints = max(0, $currentPoints - $points);
        $userModel->updatePoints($userId, $newPoints);

        session()->set('user_points', $newPoints);
    }

    /**
     * ✅ Like / Unlike berita.
     */
    public function like($newsId)
    {
        if (!$this->isLoggedIn()) {
            return $this->respondLoginError('Silakan login untuk memberikan like');
        }

        $userId    = (int) session()->get('user_id');
        $likeModel = new LikesModel();
        $newsModel = new NewsModel();

        // Pastikan berita ada
        if (!$newsModel->find($newsId)) {
            return $this->failResponse('Berita tidak ditemukan');
        }

        $existing = $likeModel->getLikeRecord($userId, $newsId);
        $status   = '';
        $message  = '';
        $newPoints = session()->get('user_points') ?? 0;

        if ($existing) {
            // ✅ Unlike
            $likeModel->removeLike($userId, $newsId);
            $this->removePointsLog($userId, $newsId, 'like', 3);

            $status  = 'unliked';
            $message = 'Like dibatalkan';
            $newPoints = (new UserModel())->getPoints($userId);
        } else {
            // ✅ Tambah Like
            $likeModel->addLike($userId, $newsId);
            $newPoints = $this->addPoints($userId, $newsId, 'like', 3);

            $status  = 'liked';
            $message = 'Berhasil Like!';
        }

        $count = $likeModel->countLikes($newsId);

        return $this->successResponse($message, [
            'status'    => $status,
            'count'     => $count,
            'icon'      => ($status === 'liked') ? '❤️' : '🤍',
            'newPoints' => $newPoints
        ]);
    }

    /**
     * ✅ Share berita.
     */
    public function share($newsId)
    {
        if (!$this->isLoggedIn()) {
            return $this->respondLoginError('Silakan login untuk membagikan berita');
        }

        $userId   = (int) session()->get('user_id');
        $platform = $this->request->getPost('platform') ?? 'unknown';

        $shareModel = new ShareModel();
        $shareModel->insert([
            'user_id'    => $userId,
            'news_id'    => $newsId,
            'platform'   => $platform,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $newPoints = $this->addPoints($userId, $newsId, 'share', 5);

        return $this->successResponse('Berita berhasil dibagikan!', [
            'newPoints' => $newPoints
        ]);
    }

    /**
     * ✅ Tambah komentar.
     */
    public function comment($newsId)
    {
        if (!$this->isLoggedIn()) {
            return $this->respondLoginError('Silakan login untuk berkomentar');
        }

        $userId = (int) session()->get('user_id');
        $content = esc(trim($this->request->getPost('content')));

        if (empty($content)) {
            return $this->failResponse('Komentar tidak boleh kosong');
        }

        $commentModel = new CommentModel();

        // Anti-spam: hanya 1 komentar per user per berita
        if ($commentModel->where('user_id', $userId)
                         ->where('news_id', $newsId)
                         ->first()) {
            return $this->failResponse('Anda sudah berkomentar di berita ini');
        }

        $commentModel->insert([
            'user_id'    => $userId,
            'news_id'    => $newsId,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $newPoints = $this->addPoints($userId, $newsId, 'comment', 5);

        $commentHTML = view('partials/comment_item', [
            'user'    => esc(session()->get('user_name')),
            'content' => $content,
            'time'    => 'Baru saja'
        ]);

        return $this->successResponse('Komentar berhasil ditambahkan!', [
            'html'      => $commentHTML,
            'newPoints' => $newPoints
        ]);
    }

    /**
     * ✅ Helper: cek login.
     */
    private function isLoggedIn(): bool
    {
        return session()->get('logged_in') === true;
    }

    /**
     * ✅ JSON Response Helpers.
     */
    private function respondLoginError(string $message)
    {
        return $this->response->setStatusCode(401)->setJSON([
            'error'    => $message,
            'redirect' => '/login'
        ]);
    }

    private function failResponse(string $message)
    {
        return $this->response->setStatusCode(400)->setJSON([
            'error' => $message
        ]);
    }

    private function successResponse(string $message, array $data = [])
    {
        return $this->response->setStatusCode(200)->setJSON(array_merge([
            'status'  => 'success',
            'message' => $message,
            'newPoints' => $newPoints ?? session()->get('user_points')
        ], $data));
    }
}
