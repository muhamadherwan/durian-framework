<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{
    public function login(Request $request, Response $response)
    {
        // init LoginForm model
        $loginForm = new LoginForm();

        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());

            if($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return true;
            }
        }

        $this->setLayout('auth');
        return $this->render("login", [
            'model' => $loginForm
        ]);
    }

    public function register(Request $request)
    {
        // init user model
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

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');

    }
}