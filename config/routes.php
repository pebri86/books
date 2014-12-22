<?php
return array(
    'default' => '/page/home',
    'errors' => '/error/index',
    'routes' => array(
		'/:controller(/:action(/:id))' => array(
            'controller' => '\Lib\Controller\:controller',
            'action' => 'index'
			)
		)
);
?>