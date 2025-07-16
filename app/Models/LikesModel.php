<?php

namespace App\Models;

use CodeIgniter\Model;

class LikesModel extends Model
{
    protected $table = 'likes';
    protected $allowedFields = ['user_id', 'news_id', 'created_at'];
}
