<?php

namespace app\core;

class View
{
    public string $string = '';

    // return the layout + content + params data
    public function renderView($view, $params = [])
    {
        // get the view content based on the requested string
        $viewContent = $this->renderOnlyView($view, $params);


        // get the layoutContent
        $layoutContent = $this->layoutContent();


        // return the content and the layout content
        return str_replace('{{content}}',$viewContent, $layoutContent);
    }

    // return the layout
    protected function layoutContent()
    {
        $layout = Application::$app->layout;

        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }

//        $layout = Application::$app->controller->layout ?? 'main';

        ob_start();
        include_once Application::$ROOT_DIR."/views/layout/$layout.php";
        return ob_get_clean();
    }

    // return the content
    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value){
            // set the key name same as the value
            $$key = $value;
        }

        ob_start();
        // get the view file based on the requested string
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }


}