<?php

namespace App\Models;

use CodeIgniter\Model;

class PointLogsModel extends Model
{
    protected $table = 'point_logs';
    protected $allowedFields = ['user_id', 'news_id', 'action_type', 'points_awarded', 'created_at'];
}
