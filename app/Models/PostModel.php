<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class PostModel extends Model
{
    protected $table = 'sg_posts';
    // protected $allowedFields = [
    //     'name',
    //     'email',
    //     'retainer_fee'
    // ];
    protected $db;
    protected $updatedField = 'updated_at';
    public function findPostById($id)
    {
        $query = $this->db->table('sg_posts')
                ->select('sg_posts.*, sg_users.user_name, sg_users.dp_url') // Add other user details you want to fetch
                ->join('sg_users', 'sg_users.user_id = sg_posts.created_by')
                ->where('sg_posts.post_id', $id)
                ->get();

    $post = $query->getResultArray();

        if (!$post) 
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function findUserId($id)
    {
        $query = $this->db->table('sg_posts')
                ->select('sg_posts.*, sg_users.user_name, sg_users.dp_url') // Add other user details you want to fetch
                ->join('sg_users', 'sg_users.user_id = sg_posts.created_by')
                ->where('sg_posts.created_by', $id)
                ->get();

    $post = $query->getResultArray();
        // echo "<pre>"; print_r($post);
        // echo "</pre>";
        // die();

        // $post = $this
        //     ->asArray()
        //     ->where(['created_by' => $id])
        //     ->findAll();

        if (!$post) 
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function get_post()
    {
        $query = $this->db->table('sg_posts')
                ->select('sg_posts.*, sg_users.user_name, sg_users.dp_url') // Add other user details you want to fetch
                ->join('sg_users', 'sg_users.user_id = sg_posts.created_by')
                ->get();

    $post = $query->getResultArray();
        // echo "<pre>"; print_r($post);
        // echo "</pre>";
        // die();

        // $post = $this
        //     ->asArray()
        //     ->where(['created_by' => $id])
        //     ->findAll();

        if (!$post) 
            throw new Exception('Post does not exist for specified id');

        return $post;
    }

    public function deletedata($id)
    {
        $post = $this
            ->asArray()
            ->where(['post_id' => $id])
            ->delete();

        if (!$post) 
            throw new Exception('Post does not exist for specified id');

        return $post;
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        if ($this->tempAllowCallbacks) {
            // Call the before event and check for a return
            $eventData = $this->trigger('beforeFind', [
                'method'    => 'findAll',
                'limit'     => $limit,
                'offset'    => $offset,
                'singleton' => false,
            ]);

            if (! empty($eventData['returnData'])) {
                return $eventData['data'];
            }
        }

        $eventData = [
            'data'      => $this->doFindAll($limit, $offset),
            'limit'     => $limit,
            'offset'    => $offset,
            'method'    => 'findAll',
            'singleton' => false,
        ];

        if ($this->tempAllowCallbacks) {
            $eventData = $this->trigger('afterFind', $eventData);
        }

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;
        $this->tempAllowCallbacks = $this->allowCallbacks;

        return $eventData['data'];
    }

    public function save($data): bool
    {
        if (empty($data)) {
            echo "1";
            return true;
               }
    $post_title = $data['post_title'];
    $post_description = $data['post_description'];
    $cover_image_url = $data['cover_image_url'];
    $created_by = $data['created_by'];
    $user_name = $data['user_name'];
    $dp_url = $data['dp_url'];
    $keywords = $data['keywords'];
    $published = $data['published'];
    $date = new DateTime();
$date = date_default_timezone_set('Asia/Kolkata');

$date1 = date("m/d/Y h:i A");
    $liked = 0;

        $sql = "INSERT INTO `sg_posts` (`post_id`, `post_title`, `post_description`, `cover_image_url`, `liked`, `created_by`, `user_name`, `dp_url`, `timestamp`, `keywords`, `published`)
         VALUES (NULL, '$post_title', '$post_description', '$cover_image_url', '$liked', '$created_by', '$user_name', '$dp_url', '$date1', '$keywords', '$published')";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        // $this->sendFcmNotification();

    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
     

    public function activity($data): bool
    {
        if (empty($data)) {
            echo "1";
            return true;
        }

$user_id = $data['created_by'];
$username = $data['user_name'];
$dp_url = $data['dp_url'];
$activity_name = "Posted";
$activity_description = $data['post_description'];
$post_id = $data['post_id'];
$date = new DateTime();
$date = date_default_timezone_set('Asia/Kolkata');

$date1 = date("m/d/Y h:i A");


$sql1 = "INSERT INTO `sg_activity_log` (`activity_log_id`, `user_id`,`username`, `dp_url`, `activity_name`, `activity_description`,  `post_id`, `timestamp`) VALUES (NULL, '$user_id', '$username', '$dp_url', '$activity_name', '$activity_description','$post_id','$date1')";

     $post1 = $this->db->query($sql1);


    if (!$post1) 
        throw new Exception('Post does not save specified post');

    return $post1;

       
    }



    public function update1($id ,$data): bool
    {

// echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }
$post_title = $data['post_title'];
$post_description = $data['post_description'];
$cover_image_url = $data['cover_image_url'];
$created_by = $data['created_by'];
$user_name = $data['user_name'];
$dp_url = $data['dp_url'];
$keywords = $data['keywords'];
$published = $data['published'];
$liked = 0;

        $sql = "UPDATE `sg_posts` SET  post_title= '$post_title', post_description ='$post_description', 
        user_name ='$user_name',
        dp_url ='$dp_url',
        cover_image_url ='$cover_image_url',created_by = '$created_by', keywords = '$keywords'  WHERE post_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
    public function updatelike($id ,$data): bool
    {

// echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

       $liked = $data['liked'];

        $sql = "UPDATE `sg_posts` SET  liked = '$liked' WHERE post_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        // $this->sendFcmNotification();
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
    public function updatepub($id ,$data): bool
    {

// echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

    $published = $data['published'];

        $sql = "UPDATE `sg_posts` SET  published = '$published'  WHERE post_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }

    public function get_drafts($data)
    {
        $published = $data['published'];
        $id = $data['id'];
        
            $post = $this
                ->asArray()
                ->where(['published' => $published, 'created_by' => $id])
                ->findAll();
    
            if (!$post) 
                throw new Exception('Post does not exist for specified id');
    
            return $post;
       
    }

    // Method to send the FCM notification to all users
private function sendFcmNotification()
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $serverKey = 'AAAAIsFx_XY:APA91bGG-IK95zxMfNlW3ZjhS_JQBQofgqDJf0pF1HSWhz7LXB5dKkVivBxIhAnPIwEwppFPP3U7thWznXGtarzAQLmMSaVhcjOQslx-ncGn40h8Z0z7PLrMq6hv3OJUdd_17nRYquRQ';
    
    $notificationData = [
        'to' => '/topics/all_users', // Specify the topic name here
        'notification' => [
            'title' => 'akshay pareek',
            'body' => 'Rich Notification testing (body)',
            'mutable_content' => true,
            'sound' => 'Tri-tone',
        ],
        'data' => [
            'url' => 'https://cdn.imgbin.com/16/24/19/imgbin-thor-odin-anthony-hopkins-loki-film-thor-FTKPzJEYv06kq46wwAWw9CjRN.jpg',
        ],
    ];

    // Convert the data to JSON format
    $jsonData = json_encode($notificationData);

    // Set up the HTTP headers for the request
    $headers = [
        'Content-Type: application/json',
        'Authorization: key=' . $serverKey,
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        // Handle the error as needed
    }

    // Close cURL session
    curl_close($ch);

    // Handle the response data as needed
    // For example, you can echo the response:
    echo $response;
}

}