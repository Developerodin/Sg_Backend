<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\UserModel;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;
use Exception;

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
     * @var array
     */
    protected $helpers = ['form'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function getResponse(array $responseBody,
                            int $code = ResponseInterface::HTTP_OK)
            {
                return $this
                    ->response
                    ->setStatusCode($code)
                    ->setJSON($responseBody);
            }

    public function getRequestInput(IncomingRequest $request){
            $input = $request->getPost();
            if (empty($input)) {
                //convert request body to associative array
                $input = json_decode($request->getBody(), true);
            }
            return $input;
        }

    public function validateRequest($input, array $rules, $errors){
        //   echo "<pre>"; print_r($rules); echo "</pre>";
            $this->validator = Services::Validation()->setRules($rules);
            //  echo $rules;
            // If you replace the $rules array with the name of the group
              
            if (is_array($input)) {
                try {
                    $model = new UserModel();
                    
                    $user = $model->findUserByUserName($input['user_name']);
                    if(!$user){
                        echo "User not found";
                        return false;
                    }else{
                        return password_verify($input['pin'], $user['pin']);
                    }
                    // echo "<pre>"; print_r($user['pin']); echo "</pre>";
                    // $pass = password_verify($input['pin'], $user['pin']);
                    //  echo "<pre>"; print_r($pass ); echo "</pre>";   
                } catch (Exception $e) {
                    return false;
                }
               
        
               
            }
           
        }
        





}
