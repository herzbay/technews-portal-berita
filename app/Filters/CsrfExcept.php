<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CsrfExcept implements FilterInterface
{
    /**
     * Jalankan sebelum request (skip CSRF untuk route tertentu)
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Daftar URL yang di-exclude dari CSRF
        $excludedRoutes = [
            'share/*',   // contoh: /share/1
            'api/*'      // contoh: /api/endpoint
        ];

        $currentPath = uri_string(); // contoh: "share/1"

        foreach ($excludedRoutes as $pattern) {
            if (fnmatch($pattern, $currentPath)) {
                // ✅ Nonaktifkan CSRF di request ini
                service('security')->disableCSRFProtection();
                break;
            }
        }
    }

    /**
     * Jalankan setelah request (tidak digunakan di sini)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi apapun
    }
}
