<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;

class Auth extends BaseController
{
    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    public function register()
    {


        $rules = [
            'user_name' => 'required',
            'pin' => 'required|min_length[4]'
        ];
      
       $input = $this->getRequestInput($this->request);
      
        $data =[
            'user_name' => $input['user_name'],
            'pin' => password_hash($input['pin'], PASSWORD_DEFAULT),
        ];
// echo json_encode($data);

       $userModel = new UserModel(); 

          
           $userModel->save($data);
           return $this->getJWTForNewUser(
               $data['user_name'],
               ResponseInterface::HTTP_CREATED
           );
      
    }
public function validuser($data){
 


}
    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
      
        $rules = [
            'user_name' => 'required|min_length[2]|max_length[50]',
            'pin' => 'required|min_length[4]|max_length[4]|validateUser[user_name, pin]'
        ];

        $errors = [
            'pin' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];
        
        $input = $this->getRequestInput($this->request);
       
        if($this->validateRequest($input, $rules, $errors)){
            // return $this->getResponse($input);
            return $this->getJWTForUser($input['user_name']);
        }else{
            echo "Invalid login credentials provided";
        }
        
       
       
    }

    private function getJWTForUser(
        string $user_Name,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        
        try {
            $model = new UserModel();
            $user = $model->findUserByUserName($user_Name);
            // echo json_encode($user);
            unset($user['pin']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($user_Name)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
    private function getJWTForNewUser(
        string $user_Name,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        
        try {
            $model = new UserModel();
            $user = $model->findUserByUserName($user_Name);
            // echo json_encode($user);
            unset($user['pin']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User Created successfully',
                        
                        'access_token' => getSignedJWTForUser($user_Name)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
