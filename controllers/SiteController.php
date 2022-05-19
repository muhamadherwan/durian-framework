<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function home()
    {
        // pass data to the view
        $params = [
            'name' => "Obiwan Kenobi"
        ];

        return $this->render('home', $params);
    }

//    public function contact()
//    {
//        return $this->render('contact');
//    }

    public function contact(Request $request)
    {
        if ($request->isPost()){
            return 'handle form data';
        }

        return $this->render('contact');
    }


    public function handleContact(Request $request)
    {
        $body = $request->getBody();
    }

}