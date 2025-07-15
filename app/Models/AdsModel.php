<?php

namespace App\Models;
use CodeIgniter\Model;

class AdsModel extends Model
{
    protected $table = 'advertisements';
    protected $allowedFields = ['title', 'image_url', 'target_url', 'start_date', 'end_date', 'is_active'];
}

