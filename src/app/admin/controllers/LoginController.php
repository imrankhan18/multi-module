<?php

namespace Multi\Admin\Controllers;

use Model\Users;

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        // echo "hy";
    }
    public function registerAction()
    {
        $user = $this->request->getPost();
        if ($user['uname'] != null && $user['psw'] != null) {
            $data = array();
            $data = array_merge($data, ['username' => $user['uname']]);
            $data = array_merge($data, ['password' => $user['psw']]);
            $product = new Users();
            $product->insert($data);
            $this->response->redirect('/admin/login');
        } else {
            echo "Invalid Response";
        }
    }
    public function loginAction()
    {
        $login = $this->request->getPost();
        $userdb = $this->mongo->multi->user->find();
        // echo "<pre>";
        foreach ($userdb as $key => $value) {
            $val = $value;
        }
        if (count($login)) {
            if ($login['uname'] == $val->username && $login['psw'] == $val->password) {
                $this->response->redirect('/admin/products/productslist');
            }  
    } else {
        echo "enter login details";
    }
}
}
