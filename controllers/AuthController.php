<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{
    public function login()
    {
        return $this->render("login");
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel();

        if ($request->isPost()) {

            // past the data to models for validation && save to db
            $registerModel->loadData($request->getBody());


            // if success
            if ($registerModel->validate() && $registerModel->register()) {
                return 'Success';
            }

            // else return the form view with the error messages!
            return $this->render('register', [
                'model' => $registerModel
            ]);
        }

        // if request method is get display the content.
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}