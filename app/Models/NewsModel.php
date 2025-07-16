<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'content', 'image_url', 'category', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
}
