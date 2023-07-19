<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table = 'sg_users';
   
    protected $allowedFields = [
        'user_name',
        'pin',
    ];
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data): array
    {
    
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }                         
    public function findUserByUserName(string $user_name)
    {
        $user = $this
            ->asArray()
            ->where(['user_name' => $user_name])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified user name');

        return $user;
    }
    public function findUserById(string $id)
    {
        $user = $this
            ->asArray()
            ->where(['user_id' => $id])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified user name');

        return $user;
    }
    
    public function update1($id ,$data)
    {

// echo $id;
//   echo "<pre>"; print_r($data);
//           echo "</pre>";
//           die;

        if (empty($data)) {
            echo "1";
            return true;
        }

    $user_name = $data['user_name'];
    $dp_url = $data['dp_url'];
    $about_me = $data['about_me'];
    $status = $data['status'];
    $keywords = $data['keywords'];
    $cover_img = $data['cover_img'];
    // $cover_img = json_encode($cover_img1);
   
    // echo "<pre>"; print_r($data);
    // echo "</pre>";
    // die;

      

        $sql = "UPDATE `sg_users` SET  
        user_name = '$user_name',
        dp_url = '$dp_url',
        about_me = '$about_me',
        status = '$status',
        keywords = '$keywords',
        cover_img = '$cover_img'
        
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
}
