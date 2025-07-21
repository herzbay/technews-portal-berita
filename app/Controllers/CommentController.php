<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PointLogsModel;
use CodeIgniter\HTTP\ResponseInterface;

class CommentController extends BaseController
{
    /**
     * ✅ Load komentar untuk 1 berita.
     */
    public function list($newsId)
    {
        try {
            $commentModel = new CommentModel();

            $comments = $commentModel
                ->select('comments.*, COALESCE(users.name, "Anonim") AS username')
                ->join('users', 'users.id = comments.user_id', 'left')
                ->where('comments.news_id', $newsId)
                ->orderBy('comments.created_at', 'DESC')
                ->findAll();

            return view('partials/comment_list', ['comments' => $comments]);
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Comment List: ' . $e->getMessage());
            return '<p class="text-red-500">Gagal memuat komentar.</p>';
        }
    }

    /**
     * ✅ Tambah komentar via HTMX.
     */
    public function add($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'error'    => 'Anda harus login untuk berkomentar.',
                'redirect' => '/login'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            $content = trim($this->request->getPost('content'));
            if (empty($content)) {
                return $this->failResponse('Komentar tidak boleh kosong');
            }

            $userId   = (int) session()->get('user_id');
            $userName = session()->get('user_name');

            $commentModel = new CommentModel();

            // ✅ Cek jika sudah pernah komentar di berita ini
            $existing = $commentModel
                ->where('user_id', $userId)
                ->where('news_id', $newsId)
                ->first();

            if ($existing) {
                return $this->failResponse('Anda sudah berkomentar di berita ini.');
            }

            // ✅ Simpan komentar
            $commentModel->insert([
                'news_id'    => $newsId,
                'user_id'    => $userId,
                'content'    => $content,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // ✅ Tambahkan poin & log
            $newPoints = $this->addPoints($userId, $newsId, 'comment', 5);

            // ✅ Kirim partial + update poin + toast
            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Komentar berhasil ditambahkan!',
                'html'       => view('partials/comment_item', [
                    'user'    => esc($userName),
                    'content' => esc($content),
                    'time'    => 'Baru saja'
                ]),
                'newPoints'  => $newPoints,
                'csrfToken'  => csrf_hash(),
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Comment Add: ' . $e->getMessage());
            return $this->failResponse('Terjadi kesalahan, coba lagi nanti');
        }
    }

    /**
     * ✅ Tambah poin jika belum ada log sebelumnya.
     */
    private function addPoints(int $userId, int $newsId, string $action, int $points): int
    {
        $pointLogModel = new PointLogsModel();
        $userModel     = new UserModel();

        $exists = $pointLogModel
            ->where('user_id', $userId)
            ->where('news_id', $newsId)
            ->where('action_type', $action)
            ->first();

        if ($exists) {
            return $userModel->getPoints($userId);
        }

        $pointLogModel->insert([
            'user_id'        => $userId,
            'news_id'        => $newsId,
            'action_type'    => $action,
            'points_awarded' => $points,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        $current = $userModel->getPoints($userId);
        $total   = $current + $points;

        $userModel->update($userId, ['total_points' => $total]);
        session()->set('user_points', $total);

        return $total;
    }

    /**
     * ✅ Helper error response
     */
    private function failResponse(string $msg)
    {
        return $this->response->setJSON([
            'error' => $msg
        ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }
}
