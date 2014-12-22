<?php
namespace Suggestotron\Controller;

class Error extends \Suggestotron\Controller {
    public function indexAction($options)
    {
        header("HTTP/1.0 404 Not Found");
        $this->render("../views/errors/index.phtml", array('message' => "Page not found!" ));
    }
}
?>