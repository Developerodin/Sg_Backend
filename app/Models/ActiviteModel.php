<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ActiviteModel extends Model
{
    protected $table = 'sg_activity_log';
    // protected $allowedFields = [
    //     'name',
    //     'email',
    //     'retainer_fee'
    // ];
    protected $db;
    protected $updatedField = 'updated_at';
    public function findPostById($id)
    {
        $post = $this
            ->asArray()
            ->where(['post_id' => $id])
            ->first();

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
    
    public function updatepub($id ,$data): bool
    {


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

}