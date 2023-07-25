<?php

namespace App\Controllers;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\ActiviteModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;
class Users extends BaseController
{
    public function index()
    {
        $model1 = new UserModel();
        $input = $this->getRequestInput($this->request);
        $fcmToken = $input['fbtoken'];
       $topic ="all_users";
     $ret = $model1->subscribeToTopic($fcmToken, $topic);
    //  echo "<pre>";print_r($ret);
    //  echo "</pre>";
    //  die();
        $model = new PostModel();
        $posts = $model->get_post();
        $keywords =$model->getMostUsedKeywords($posts);

        return $this->getResponse(
            [
                'message' => 'Post retrieved successfully',
                'post' => $model->get_post(),
                'keywords' => $keywords
            ]
        );
    }
    
    public function get_drafts($id)
    {
        try {
            $data['published'] = 0;
            $data['id'] = $id;

            $model = new PostModel();
            $post = $model->get_drafts($data);
            

            return $this->getResponse(
                [
                    'message' => 'Post Drafts get successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function get_activites()
    {
        try {
            
            $model = new ActiviteModel();
            
            return $this->getResponse(
                [
                    'message' => 'Activites get successfully',
                    'Activites' => $model->findAll()
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function store()
    {
        // $rules = [
        //     'image' => 'required',
        //     'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[client.email]',
        //     'retainer_fee' => 'required|max_length[255]'
        // ];

        $input = $this->getRequestInput($this->request);



        // $errors = [
        //     'post' => [
        //         'validateUser' => 'Invalid credentials provided'
        //     ]
        // ];
        // if (!$this->validateRequest($input, $rules , $errors)) {
        //     return $this
        //         ->getResponse(
        //             $this->validator->getErrors(),
        //             ResponseInterface::HTTP_BAD_REQUEST
        //         );
        // }

        $post_title = $input['post_title'];

        $model = new PostModel();
        $model->save($input);
        

        $post = $model->where('post_title', $post_title)->first();
       
       $input['post_id'] = $post['post_id'];
      

         $model->activity($input);
        return $this->getResponse(
            [
                'message' => 'Post added successfully',
                'post' => $post
                
            ]
        );
    }
    public function show($id)
    {
        
        try {

            $model = new PostModel();
            $post = $model->findPostById($id);

            return $this->getResponse(
                [
                    'message' => 'Post retrieved successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find client for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function post_userid($id)
    {
        
        try {

            $model = new PostModel();
            $post = $model->findUserId($id);
// echo "<pre>"; print_r($post);
// echo "</pre>";
// die();
            return $this->getResponse(
                [
                    'message' => 'Post retrieved successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find client for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function update($id)
    {
        try {

            $model = new PostModel();
            $model->findPostById($id);

          $input = $this->getRequestInput($this->request);
        //   echo "<pre>"; print_r($input);
        //   echo "</pre>";
          

            $model->update1($id ,$input);
        //         echo "<pre>"; print_r($input);
        //   echo "</pre>";
            $post = $model->findPostById($id);

            return $this->getResponse(
                [
                    'message' => 'Client updated successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function post_like($id)
    {
        try {

            $model = new PostModel();
            $predata = $model->findPostById1($id);

          $input = $this->getRequestInput($this->request);
        //   echo "<pre>"; print_r($predata['liked']);
        //   echo "</pre>";
        //   die;
        $data['like'] = $input['like'];
        $data['username'] = $input['username'];
          if ($input['like'] == '1') {
            $data['liked'] = $predata['liked']+1;
          }
        else if ($input['like'] == '0') {
            $data['liked'] = $predata['liked']-1;
          }
            $model->updatelike($id ,$data);
      
            $post = $model->findPostById($id);
          
          $data['post_description'] = $input['post_description'];
            $data['user_name'] = $input['username'];
            $data['user_id'] = $input['user_id'];
            $data['dp_url'] = $input['dp_url'];
          
           $model->activitylike($data,$id);
            return $this->getResponse(
                [
                    'message' => 'post like updated successfully',
                    'post' => $post
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function post_published($id)
    {
        try {

            $model = new PostModel();
            $model->findPostById($id);

          $input = $this->getRequestInput($this->request);
   

            $model->updatepub($id ,$input);
        //         echo "<pre>"; print_r($input);
        //   echo "</pre>";
            $post = $model->findPostById($id);

            return $this->getResponse(
                [
                    'message' => 'Post Published successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function user_update($id)
    {
        try {

            $model = new UserModel();
            

          $input = $this->getRequestInput($this->request);
   
        //   echo "<pre>"; print_r($input);
        //   echo "</pre>";
        //   die;
            $model->update1($id ,$input);
              
            $post = $model->findUserById($id);

            return $this->getResponse(
                [
                    'message' => 'user updaetd successfully',
                    'client' => $post
                ]
            );

        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function destroy($id)
    {
        try {

            $model = new PostModel();
            $post = $model->findPostById($id);

            // echo "<pre>"; print_r($post);
            // echo "</pre>";
           
            $model->deletedata($id);
            // $model->delete($id);

            // echo "<pre>"; print_r($post);
            // echo "</pre>";
            return $this
                ->getResponse(
                    [
                        'message' => 'Post deleted successfully',
                    ]
                );

        } catch (Exception $exception) {                    
            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function reset_pass($id)
    {

        // echo "yes";
       
        // $rules = [
        //     'user_name' => 'required',
        //     'pin' => 'required|min_length[4]'
        // ];
       $input = $this->getRequestInput($this->request);
       
        $data =[
            'pin' => password_hash($input['pin'], PASSWORD_DEFAULT),
        ];
      
       $userModel = new UserModel(); 
           $userModel->update11($id,$data);
           return $this->getResponse(
            [
                'message' => 'Pin updated successfully',
                
            ]
        );
    }
}
