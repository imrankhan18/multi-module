<?php

namespace Multi\Admin\Controllers;

use Phalcon\Mvc\Controller;

use Model\Products;

class ProductsController extends Controller
{
    public function indexAction()
    {
    }
    public function addAction()
    {

        $productdata = $this->request->getPost();
        // echo "<pre>";
        // print_r($productdata);
        // die;

        if (count($productdata) > 0) {
            $data = array();
            $data = array_merge($data, ["name" => $productdata['name']]);
            $data = array_merge($data, ["category" => $productdata['cname']]);
            $data = array_merge($data, ["price" => $productdata['price']]);
            $data = array_merge($data, ["stock" => $productdata['stock']]);

            $metadat = array();
            for ($i = 0; $i < count($productdata['fields']); $i++) {
                $label = [$productdata['fields'][$i] => $productdata['value'][$i]];
                $metadat = array_merge($metadat, $label);
            }
            $variations = array();
            for ($i = 0; $i < count($productdata['field1']); $i++) {
                if (array_key_exists($productdata['field1'][$i], $variations)) {
                    $var = [$productdata['field2'][$i] => $productdata['field3'][$i]];
                    $variations[$productdata['field1'][$i]] = array_merge($variations[$productdata['field1'][$i]], $var);
                } else {
                    $var = [$productdata['field1'][$i] => [$productdata['field2'][$i] => $productdata['field3'][$i]]];
                    $variations = array_merge($variations, $var);
                }
            }
            $data = array_merge($data, ['metadata' => $metadat]);
            $data = array_merge($data, ['variations' => $variations]);
            // echo "<pre>";
            // print_r($data);
            // die;
            $products = new Products();
            $products->insert($data);

            $this->response->redirect('/admin/products/productslist');
        }
    }
    public function productslistAction()
    {
        $search = $this->request->getPost();
        $list = $this->mongo->multi->products->find();
        $display = '';
        if ($search['search'] != "") {
            foreach ($list as $key => $value) {
                if ($search['search'] == $value->name) {
                    $display .= '<div class="card">
            <h3><b>ProductName: </b>' . $value->name . '</h3>
            <p>Category: ' . $value->category . '</p>
            <p>Price: ' . $value->price . '</p>
            <p>Stock: ' . $value->stock . '</p>
            <p></p>
            <p>
            <button type="button" class="btn btn-info btn-lg modalBtn"  data-toggle="modal" data-id=' . $value['_id'] . ' data-target="#myModal">View More</button>
            <a class="btn btn-primary" href="/products/edit?id=' . $value['_id'] . '" >Edit</a>
            <a class="btn btn-danger" href="/products/delete?id=' . $value['_id'] . '" >Delete</a>
            </p>
        </div>';
                }
            }
        } else {

            foreach ($list as $key => $value) {

                $display .= '<div class="card">
            <h3><b>ProductName: </b>' . $value->name . '</h3>
            <p>Category: ' . $value->category . '</p>
            <p>Price: ' . $value->price . '</p>
            <p>Stock: ' . $value->stock . '</p>
            <p></p>
            <p>
            <button type="button" class="btn btn-info btn-lg modalBtn"  data-toggle="modal" data-id=' . $value['_id'] . ' data-target="#myModal">View More</button>
            <a class="btn btn-primary" href="/admin/products/edit?id=' . $value['_id'] . '" >Edit</a>
            <a class="btn btn-danger" href="/admin/products/delete?id=' . $value['_id'] . '" >Delete</a>
            </p>
        </div>';
            }
        }

        $this->view->display = $display;
        // $this->view->data=$list;
    }
    public function quickAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $result = $this->mongo->multi->products->findOne(
                [
                    "_id" => new \MongoDB\BSON\ObjectId($id)
                ]
            );

            echo json_encode($result);
            die;
        }
    }
    public function deleteAction()
    {
        $id = $this->request->get('id');
        // echo $id;
        // die;
        $res = $this->mongo->multi->products->deleteOne(
            [
                '_id' => new \mongoDB\BSON\ObjectId("$id")
            ]
        );
        $this->response->redirect('/admin/products/productslist');
    }
    public function editAction()
    {

        $id = $this->request->get('id');

        $edit = $this->mongo->multi->products->findOne(
            [
                '_id' => new \mongoDB\BSON\ObjectId("$id")
            ]
        );
        $this->view->edit = $edit;
        $data = json_decode(json_encode($edit), true);
        if (count($data['variations']) > 0) {
            $this->view->var = $data['variations'];
        }
        $this->view->data = $data['metadata'];
    }
    public function updateAction()
    {
        $id = $this->request->getPost('id');
        $update1 = $this->request->get();

        // echo "<pre>";
        // print_r($update1);
        // die;
        // $update2 = array();
        // $update2 = array_merge($update2, ['name' => $update1['name']]);
        // $update2 = array_merge($update2, ['category' => $update1['cname']]);
        // $update2 = array_merge($update2, ['price' => $update1['price']]);
        // $update2 = array_merge($update2, ['stock' => $update1['stock']]);
        $variations = array();
        for ($i = 0; $i < count($update1['field1']); $i++) {
            if (array_key_exists($update1['field1'][$i], $variations)) {
                $var = [$update1['field2'][$i] => $update1['field3'][$i]];
                $variations[$update1['field1'][$i]] = array_merge($variations[$update1['field1'][$i]], $var);
            } else {
                $var = [$update1['field1'][$i] => [$update1['field2'][$i] => $update1['field3'][$i]]];
                $variations = array_merge($variations, $var);
            }
        }
        // echo "<pre>";
        // print_r($variations);
        // die;
        $metadat = array();
        for ($i = 0; $i < count($update1['fields']); $i++) {
            $label = [$update1['fields'][$i] => $update1['value'][$i]];
            $metadat = array_merge($metadat, $label);
        }


        $this->mongo->multi->products->updateOne(
            ['_id' => new \mongoDB\BSON\ObjectId("$id")],
            [
                '$set' => [
                    'name' => $update1['name'],
                    'category' => $update1['cname'],
                    'price' => $update1['price'],
                    'stock' => $update1['stock'],
                    'metadata' => $metadat,
                    'variations' => $variations,
                ]
            ]
        );
        $this->response->redirect('/admin/products/productslist');
    }
}
