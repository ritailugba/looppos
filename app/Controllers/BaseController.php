<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;

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
    protected $helpers = ['url', 'form', 'html'];

    /**
     * Current user data
     */
    protected $user = null;
    
    /**
     * Application settings
     */
    protected $setting = null;
    
    /**
     * Current register
     */
    protected $register = null;
    
    /**
     * View data
     */
    protected $viewData = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
        
        // Load current user
        $userId = $this->session->get('user_id');
        if ($userId) {
            $userModel = new UserModel();
            $this->user = $userModel->find($userId);
        }
        
        // Load current register
        $this->register = $this->session->get('register');
        
        // Set language
        $lang = $this->session->get('lang') ?? 'en';
        $this->request->setLocale($lang);
    }
    
    /**
     * Check if user is logged in
     */
    protected function requireAuth()
    {
        if (!$this->user) {
            return redirect()->to('/auth/login');
        }
        return true;
    }
    
    /**
     * Render view with layout
     */
    protected function render($view, $data = [])
    {
        $data = array_merge($this->viewData, $data);
        $data['user'] = $this->user;
        $data['setting'] = $this->setting;
        $data['register'] = $this->register;
        
        return view($view, $data);
    }
    
    /**
     * Return JSON response
     */
    protected function jsonResponse($data, $status = 200)
    {
        return $this->response
                    ->setStatusCode($status)
                    ->setJSON($data);
    }
    
    /**
     * Set flash message
     */
    protected function setMessage($type, $message)
    {
        $this->session->setFlashdata('message_type', $type);
        $this->session->setFlashdata('message', $message);
    }
    
    /**
     * Get flash message
     */
    protected function getMessage()
    {
        return [
            'type' => $this->session->getFlashdata('message_type'),
            'message' => $this->session->getFlashdata('message')
        ];
    }
}
