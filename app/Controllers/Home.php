<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;
class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
    
    public function home()
    {
        print_r('up');
    }
}
