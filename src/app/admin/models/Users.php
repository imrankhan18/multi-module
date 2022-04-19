<?php
namespace Model;

use Phalcon\Mvc\Model;

class Users extends Model
{
   
    public function insert($user)
    {
        $doc = $this->di->get("mongo");
        $doc = $doc->multi->user;
        $doc->insertOne($user);
            
    }
}