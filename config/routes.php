<?php
return array(
    'default' => '/topic/list',
    'errors' => '/error/index',
    'routes' => array(
        '/topic(/:action(/:id))' => array(
            'controller' => '\Suggestotron\Controller\Topics',
            'action' => 'list'
			),
		'/vote(/:action(/:id))' => array(
	        'controller' => '\Suggestotron\Controller\Votes'
			),
		'/:controller(/:action(/:id))' => array(
            'controller' => '\Suggestotron\Controller\:controller',
            'action' => 'index'
			)
		)
);
?>