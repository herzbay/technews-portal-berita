<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\AdvertisementModel;

class Home extends BaseController
{
    public function index()
    {
        helper('text'); // <-- Tambahkan ini

        $newsModel = new \App\Models\NewsModel();
        $adModel   = new \App\Models\AdvertisementModel();

        $data['news'] = $newsModel->orderBy('created_at', 'DESC')->findAll(6);
        $data['ads']  = $adModel
            ->where('is_active', true)
            ->where('start_date <=', date('Y-m-d'))
            ->where('end_date >=', date('Y-m-d'))
            ->findAll();

        return view('home', $data);
    }

}
