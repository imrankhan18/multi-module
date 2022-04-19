<?php
namespace Model;

use Phalcon\Mvc\Model;

class Products extends Model
{
   
    public function insert($data)
    {
        $doc = $this->di->get("mongo");
        $doc = $doc->multi->products;
        $doc->insertOne($data);
            
    }
}