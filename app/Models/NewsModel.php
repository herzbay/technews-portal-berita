<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table            = 'news';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['title', 'slug', 'content', 'image_url', 'category', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;
    protected $returnType       = 'array';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Ambil berita terbaru.
     *
     * @param int $limit
     * @return array
     */
    public function getLatest(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')->limit($limit)->findAll();
    }

    /**
     * Cari berita berdasarkan kategori.
     *
     * @param string $category
     * @param int $limit
     * @return array
     */
    public function getByCategory(string $category, int $limit = 10): array
    {
        return $this->where('category', $category)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Cari berita berdasarkan slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}
