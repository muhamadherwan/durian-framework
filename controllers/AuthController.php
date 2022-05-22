<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;

class AuthController extends Controller
{
    public function login()
    {
        return $this->render("login");
    }

    public function register(Request $request)
    {
        $user = new User();

        if ($request->isPost()) {

            // past the data to models for validation && save to db
            $user->loadData($request->getBody());


            // if success
            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
            }

            // else return the form view with the error messages!
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // if request method is get display the content.
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }
}