<?php
namespace Lib\Controller;

class Error extends \Lib\Controller {
    public function indexAction($options)
    {
        header("HTTP/1.0 404 Not Found");
        $this->render("../views/errors/index.phtml", array('message' => "Page not found!" ));
    }
}
?>