<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Session implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       // $request = $this->request;
        $key = getenv("KEY_JWT");
        $token = $request->getCookie('SESSION_LOGIN');
        try {
            $payload = JWT::decode($token, new Key($key, 'HS256'));  
        } catch (\Throwable $th) {
            //Token invalid
            return redirect()->to('auth');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu implementasi apa-apa di sini
    }
}
