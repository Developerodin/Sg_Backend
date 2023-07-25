<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table = 'sg_users';
    protected $db;
    // protected $allowedFields = [
    //     'user_name',
    //     'pin',
    // ];
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
    
    public function update($id ,$data)
    {
        if (empty($data)) {
            echo "1";
            return true;
        }

    $user_name = $data['pin'];
   
        $sql = "UPDATE `sg_users` SET  
        user_name = '$user_name',
        WHERE user_id = $id";
        
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('user not updated');

    return $post;

       
    }
    public function update1($id ,$data)
    {
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
        throw new Exception('user not updated');

    return $post;

       
    }
    public function save($data): bool
    {

    $user_name = $data['user_name'];
    $pin = $data['pin'];
    $dp_url = "";
    $about_me = "";
    $status = "";
    $cover_img = "";
   
    $sql = "INSERT INTO `sg_users` (`user_id`, `user_name`,
    `dp_url`,
    `about_me`,
    `status`,
    `cover_img`,
    `pin`) VALUES (NULL, '$user_name', 
    '$dp_url',
    '$about_me',
    '$status',
    '$cover_img',
    '$pin')";
    echo $sql;
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }

    public function subscribeToTopic($fcmToken, $topic)
    {
     
        $url = 'https://iid.googleapis.com/iid/v1/' . $fcmToken . '/rel/topics/' . $topic;
        $serverKey = 'AAAAZVYW4AM:APA91bEcs2tl2ZpCNeikEAVNZUUXse2VxWoeQIKlOd_w8O0kvcMlLRlb-gcn9IlMF52ZNQpwd0T7xzl_c1xkkRcz4NgRq1rJ6_1dr53EskPXXQCkTpt-iTNNOLaDncdFgG3KTNdywXCE';
    
        // Set up the HTTP headers for the request
        $headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey,
        ];
    
        // Initialize cURL session
        $ch = curl_init($url);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        // Execute cURL session
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// if ($httpStatusCode === 200) {
//     $responseData = json_decode($response, true);
//     echo 'Subscription successful. Response: ' . print_r($responseData, true);
// } else {
//     echo 'Subscription failed. HTTP Status Code: ' . $httpStatusCode . ', Response: ' . $response;
// }
//         echo "<pre>";print_r($response);
//         echo "</pre>";
//         die();
        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            // Handle the error as needed
        }
    
        // Close cURL session
        curl_close($ch);
    
        // Handle the response data as needed
        // For example, you can return the response or echo it:
        return $response;
    }
}
