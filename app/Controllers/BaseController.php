<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

abstract class BaseController extends Controller
{

    use ResponseTrait;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->db = db_connect();
        $this->session = session();
        $this->key = getenv("KEY_JWT");
        $this->request = $request;

    }

    public function cek_session(){
        
        $key = getenv("KEY_JWT");
        $token = get_cookie('SESSION_LOGIN');
        try {
            $payload = JWT::decode($token, new Key($key, 'HS256'));

            $this->session_id = $payload->id;
            $this->session_nama = $payload->nama;
            $this->session_email = $payload->email;
            $this->session_level = $payload->level;
            
        } catch (\Throwable $th) {
            //return redirect()->to('auth');
        }

    }

    public function cek_session_before(){
        
        $key = getenv("KEY_JWT");
        $token = get_cookie('SESSION_LOGIN');
        try {
            $payload = JWT::decode($token, new Key($key, 'HS256'));

            
            return redirect()->to('profile');
        } catch (\Throwable $th) {
            return redirect()->to('auth');
        }

    }
}
