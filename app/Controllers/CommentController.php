<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PointLogsModel;
use CodeIgniter\HTTP\ResponseInterface;

class CommentController extends BaseController
{
    /**
     * ✅ Ambil daftar komentar untuk berita tertentu
     */
    public function list($newsId)
    {
        try {
            $commentModel = new CommentModel();
            $comments = $commentModel
                ->select('comments.*, COALESCE(users.name, "Anonim") AS username')
                ->join('users', 'users.id = comments.user_id', 'left')
                ->where('comments.news_id', (int)$newsId)
                ->orderBy('comments.created_at', 'DESC')
                ->findAll();

            return view('partials/comment_list', ['comments' => $comments]);
        } catch (\Throwable $e) {
            log_message('error', '[ERROR] Comment List: ' . $e->getMessage());
            return '<p class="text-red-500">Gagal memuat komentar.</p>';
        }
    }

    /**
     * ✅ Tambah komentar baru via AJAX (JSON Response)
     */
    public function add($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->jsonError('Anda harus login untuk berkomentar.', '/login', ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $content = trim($this->request->getPost('content'));
        if (empty($content)) {
            return $this->jsonError('Komentar tidak boleh kosong');
        }

        try {
            $userId   = (int)session()->get('user_id');
            $userName = session()->get('user_name');
            $commentModel = new CommentModel();

            // ✅ Opsional: Cegah spam komentar di berita yang sama
            if ($commentModel->where('user_id', $userId)->where('news_id', $newsId)->first()) {
                return $this->jsonError('Anda sudah berkomentar di berita ini.');
            }

            // ✅ Simpan komentar
            $commentId = $commentModel->insert([
                'news_id'    => $newsId,
                'user_id'    => $userId,
                'content'    => $content,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // ✅ Tambah poin user
            $newPoints = $this->addPoints($userId, $newsId, 'comment', 5);

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Komentar berhasil ditambahkan!',
                'html'       => view('partials/comment_item', [
                    'commentId' => $commentId,
                    'user'      => esc($userName),
                    'content'   => esc($content),
                    'time'      => 'Baru saja',
                    'userId'    => $userId
                ]),
                'newPoints'  => $newPoints,
                'csrfToken'  => csrf_hash()
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[ERROR] Comment Add: ' . $e->getMessage());
            return $this->jsonError('Terjadi kesalahan, coba lagi nanti');
        }
    }

    /**
     * ✅ Hapus komentar (Hanya pemilik atau Admin)
     */
    public function delete($commentId)
    {
        if (!session()->get('logged_in')) {
            return $this->jsonError('Anda harus login untuk menghapus komentar', '/login', ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            $commentModel = new CommentModel();
            $comment = $commentModel->find($commentId);

            if (!$comment) {
                return $this->jsonError('Komentar tidak ditemukan');
            }

            $userId = session()->get('user_id');
            $role   = session()->get('role') ?? 'user';

            // ✅ Pastikan hanya admin atau pemilik komentar yang boleh hapus
            if ($comment['user_id'] != $userId && $role !== 'admin') {
                return $this->jsonError('Anda tidak memiliki izin menghapus komentar ini');
            }

            // ✅ Hapus komentar
            $commentModel->delete($commentId);

            // ✅ Jika user (bukan admin), kurangi poin hanya jika ini komentar terakhir di berita tsb
            if ($role !== 'admin') {
                $hasOtherComments = $commentModel
                    ->where('news_id', $comment['news_id'])
                    ->where('user_id', $userId)
                    ->countAllResults();

                if ($hasOtherComments == 0) {
                    $this->deductPoints($userId, 5);
                }
            }

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Komentar berhasil dihapus',
                'commentId'  => $commentId,
                'newPoints'  => session()->get('user_points'),
                'csrfToken'  => csrf_hash()
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[ERROR] Delete Comment: ' . $e->getMessage());
            return $this->jsonError('Terjadi kesalahan, coba lagi nanti');
        }
    }

    /**
     * ✅ Tambahkan poin user (hindari duplikasi)
     */
    private function addPoints(int $userId, int $newsId, string $action, int $points): int
    {
        $pointLogModel = new PointLogsModel();
        $userModel     = new UserModel();

        // ✅ Jika sudah ada log poin untuk aksi ini, jangan tambah lagi
        if ($pointLogModel->where('user_id', $userId)->where('news_id', $newsId)->where('action_type', $action)->first()) {
            return $userModel->getPoints($userId);
        }

        // ✅ Simpan log poin
        $pointLogModel->insert([
            'user_id'        => $userId,
            'news_id'        => $newsId,
            'action_type'    => $action,
            'points_awarded' => $points,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // ✅ Update total poin user
        $total = $userModel->getPoints($userId) + $points;
        $userModel->update($userId, ['total_points' => $total]);
        session()->set('user_points', $total);

        return $total;
    }

    /**
     * ✅ Kurangi poin user
     */
    private function deductPoints(int $userId, int $points): void
    {
        $userModel = new UserModel();
        $currentPoints = $userModel->getPoints($userId);
        $newPoints = max(0, $currentPoints - $points);
        $userModel->updatePoints($userId, $newPoints);
        session()->set('user_points', $newPoints);
    }

    /**
     * ✅ Helper untuk JSON Error Response
     */
    private function jsonError(string $msg, string $redirect = null, int $statusCode = ResponseInterface::HTTP_BAD_REQUEST)
    {
        $data = ['error' => $msg];
        if ($redirect) {
            $data['redirect'] = $redirect;
        }

        return $this->response->setStatusCode($statusCode)->setJSON($data);
    }
}
