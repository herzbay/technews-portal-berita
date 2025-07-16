<?php
namespace App\Models;

use CodeIgniter\Model;

class ShareModel extends Model
{
    protected $table = 'shares';
    protected $allowedFields = ['user_id', 'news_id', 'platform', 'created_at'];
}
