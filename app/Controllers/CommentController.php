<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\UserModel;
use App\Models\PointLogsModel;
use CodeIgniter\HTTP\ResponseInterface;

class CommentController extends BaseController
{
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

    public function add($newsId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'error' => 'Anda harus login untuk berkomentar.',
                    'redirect' => '/login'
                ]);
        }

        $content = trim($this->request->getPost('content'));
        if (empty($content)) {
            return $this->failResponse('Komentar tidak boleh kosong');
        }

        try {
            $userId = (int) session()->get('user_id');
            $userName = session()->get('user_name');
            $commentModel = new CommentModel();

            if ($commentModel->where('user_id', $userId)->where('news_id', $newsId)->first()) {
                return $this->failResponse('Anda sudah berkomentar di berita ini.');
            }

            $commentModel->insert([
                'news_id' => $newsId,
                'user_id' => $userId,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $newPoints = $this->addPoints($userId, $newsId, 'comment', 5);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Komentar berhasil ditambahkan!',
                'html' => view('partials/comment_item', [
                    'user' => esc($userName),
                    'content' => esc($content),
                    'time' => 'Baru saja'
                ]),
                'newPoints' => $newPoints,
                'csrfToken' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Comment Add: ' . $e->getMessage());
            return $this->failResponse('Terjadi kesalahan, coba lagi nanti');
        }
    }

    public function delete($commentId)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setStatusCode(401)->setJSON([
                'error' => 'Anda harus login untuk menghapus komentar',
                'redirect' => '/login'
            ]);
        }

        try {
            $commentModel = new CommentModel();
            $comment = $commentModel->find($commentId);

            if (!$comment) {
                return $this->failResponse('Komentar tidak ditemukan');
            }

            $userId = session()->get('user_id');
            $role = session()->get('role') ?? 'user';

            // ✅ Hanya pemilik atau admin
            if ($comment['user_id'] != $userId && $role !== 'admin') {
                return $this->failResponse('Anda tidak memiliki izin menghapus komentar ini');
            }

            // ✅ Hapus komentar
            $commentModel->delete($commentId);

            // ✅ Hanya jika user pemilik (bukan admin)
            if ($role !== 'admin') {
                // Cek apakah user masih punya komentar lain di berita yang sama
                $hasOtherComments = $commentModel
                    ->where('news_id', $comment['news_id'])
                    ->where('user_id', $userId)
                    ->countAllResults();

                if ($hasOtherComments == 0) {
                    // ✅ Tidak ada komentar lain → kurangi poin
                    $pointsToDeduct = 5; // Sesuai aturan poin komentar
                    $userModel = new UserModel();
                    $currentPoints = $userModel->getPoints($userId);
                    $newPoints = max(0, $currentPoints - $pointsToDeduct);
                    $userModel->updatePoints($userId, $newPoints);
                    session()->set('user_points', $newPoints);
                }
            }

            return $this->response->setJSON([
                'status'     => 'success',
                'message'    => 'Komentar berhasil dihapus',
                'commentId'  => $commentId,
                'newPoints'  => session()->get('user_points') // Untuk update real-time
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Delete Comment: ' . $e->getMessage());
            return $this->failResponse('Terjadi kesalahan, coba lagi nanti');
        }
    }


    private function addPoints(int $userId, int $newsId, string $action, int $points): int
    {
        $pointLogModel = new PointLogsModel();
        $userModel = new UserModel();

        if ($pointLogModel->where('user_id', $userId)->where('news_id', $newsId)->where('action_type', $action)->first()) {
            return $userModel->getPoints($userId);
        }

        $pointLogModel->insert([
            'user_id' => $userId,
            'news_id' => $newsId,
            'action_type' => $action,
            'points_awarded' => $points,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $total = $userModel->getPoints($userId) + $points;
        $userModel->update($userId, ['total_points' => $total]);
        session()->set('user_points', $total);

        return $total;
    }

    private function failResponse(string $msg)
    {
        return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
            ->setJSON(['error' => $msg]);
    }
}
