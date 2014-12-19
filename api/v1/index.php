<?php
require_once '../src/Library/Config.php';
\Library\Config::setDirectory('../config');

$config = \Library\Config::get('autoload');
require_once $config['class_path'] . '/Library/Autoloader.php';

// Slim couldn't cover by autoloader
require '../src/Library/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 * 
 */
function authenticate(\Slim\Route $route) {
	// Getting request headers
	$headers = apache_request_headers();
	$response = array();
	$app = \Slim\Slim::getInstance();

	// Verifying Authorization Header
	if (isset($headers['Authorization']) && isset($headers['Authorization-id'])) {
		$auth = new \Library\Auth();

		// get the api key
		$api_key = $headers['Authorization'];
		$app_id = $headers['Authorization-id'];
		// validating api key
		if (!$auth -> isValidApiKey($api_key)) {
			// api key is not present in users table
			$response["error"] = true;
			$response["message"] = "Access Denied. Invalid Api key";
			echoRespnse(401, $response);
			$app -> stop();
		} else {
			if ($app_id == $auth -> getAppId($api_key)) {
				// to do
			} else {
				$response["error"] = true;
				$response["message"] = "Access Denied. Unknown Application id";
				echoRespnse(401, $response);
				$app -> stop();
			}
		}
	} else {
		// api key is missing in header
		$response["error"] = true;
		$response["message"] = "Api key is misssing";
		echoRespnse(400, $response);
		$app -> stop();
	}
}

/**
 * Listing all authors
 * method GET
 * url /authors
 */
$app -> get('/authors', 'authenticate', function() {
	$response = array();
	$data = new \Library\Model\Authors();
	$authors = $data -> getAll();

	$response["error"] = false;
	$response["authors"] = $authors;

	echoRespnse(200, $response);
});

/**
 * Listing single asset
 * method GET
 * url /authors/:id
 * Will return 404 if the asset doesn't exist
 */
$app -> get('/authors/:id', 'authenticate', function($id) {
	$response = array();
	$data = new \Library\Model\Authors();
	$author = $data -> getAuthor($id);

	if ($author != NULL) {
		$response["error"] = false;
		$response["author"] = $author;
		echoRespnse(200, $response);
	} else {
		$response["error"] = true;
		$response["message"] = "The requested resource doesn't exists";
		echoRespnse(404, $response);
	}
});

/**
 * Updating existing asset
 * method PUT
 * params 	name,
 *			address
 * url - /authors/:id
 */

$app -> put('/authors/:id', 'authenticate', function($id) use ($app) {
	verifyRequiredParams(array('name', 'address'));

	$response = array();
	$data = new \Library\Model\Authors();

	$name = $app -> request -> put('name');
	$address = $app -> request -> put('address');
	
	$put_data = array('id' => $id, 'name' => $name, 'address' => $address);

	$result = $data -> update($put_data);

	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task updated successfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to update. Please try again!";
	}
	echoRespnse(200, $response);
});

/**
 * Deleting asset.
 * method DELETE
 * url /authors/:id
 */
$app -> delete('/authors/:id', 'authenticate', function($id) use ($app) {
	$data = new \Library\Model\Authors();
	$response = array();
	$result = $data -> delete(id);
	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task deleted succesfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to delete. Please try again!";
	}
	echoRespnse(200, $response);
});

/**
 * Listing all books
 * method GET
 * url /books
 */
$app -> get('/books', 'authenticate', function() {
	$response = array();
	$data = new \Library\Model\Books();
	$books = $data -> getAll();

	$response["error"] = false;
	$response["books"] = $books;

	echoRespnse(200, $response);
});

/**
 * Listing single asset
 * method GET
 * url /books/:id
 * Will return 404 if the asset doesn't exist
 */
$app -> get('/books/:id', 'authenticate', function($id) {
	$response = array();
	$data = new \Library\Model\Books();
	$book = $data -> getBook($id);

	if ($book != NULL) {
		$response["error"] = false;
		$response["book"] = $book;
		echoRespnse(200, $response);
	} else {
		$response["error"] = true;
		$response["message"] = "The requested resource doesn't exists";
		echoRespnse(404, $response);
	}
});


/**
 * Creating new asset in db
 * method POST
 * params - title,
 * description,
 * author_id,
 * publisher_id,
 * year,
 * isbn
 * url - /books
 */
$app -> post('/books', 'authenticate', function() use ($app) {
	verifyRequiredParams(array('title', 'description', 'author_id', 'publisher_id', 'year', 'isbn'));

	$response = array();
	$data = new \Library\Model\Books();

	$result = $data -> add($_POST);

	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task created successfully";
		echoRespnse(201, $response);
	} else {
		$response["error"] = true;
		$response["message"] = "Failed to create task. Please try again";
		echoRespnse(200, $response);
	}
});

/**
 * Updating existing asset
 * method PUT
 * params 	title,
 description,
 author_id,
 publisher_id,
 year,
 isbn
 * url - /books/:id
 */

$app -> put('/books/:id', 'authenticate', function($id) use ($app) {
	verifyRequiredParams(array('title', 'description', 'author_id', 'publisher_id', 'year', 'isbn'));

	$response = array();
	$data = new \Library\Model\Books();

	$title = $app -> request -> put('title');
	$description = $app -> request -> put('description');
	$author_id = $app -> request -> put('author_id');
	$publisher_id = $app -> request -> put('publisher_id');
	$year = $app -> request -> put('year');
	$isbn = $app -> request -> put('isbn');

	$put_data = array('id' => $id, 'title' => $title, 'description' => $description, 'author_id' => $author_id, 'publisher_id' => $publisher_id, 'year' => $year, 'isbn' => $isbn);

	$result = $data -> update($put_data);

	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task updated successfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to update. Please try again!";
	}
	echoRespnse(200, $response);
});

/**
 * Deleting asset.
 * method DELETE
 * url /books/:id
 */
$app -> delete('/books/:id', 'authenticate', function($id) use ($app) {
	$data = new \Library\Model\Books();
	$response = array();
	$result = $data -> delete(id);
	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task deleted succesfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to delete. Please try again!";
	}
	echoRespnse(200, $response);
});

/**
 * Listing all publishers
 * method GET
 * url /publishers
 */
$app -> get('/publishers', 'authenticate', function() {
	$response = array();
	$data = new \Library\Model\Publishers();
	$publishers = $data -> getAll();

	$response["error"] = false;
	$response["authors"] = $publishers;

	echoRespnse(200, $response);
});

/**
 * Listing single asset
 * method GET
 * url /publishers/:id
 * Will return 404 if the asset doesn't exist
 */
$app -> get('/publishers/:id', 'authenticate', function($id) {
	$response = array();
	$data = new \Library\Model\Publishers();
	$publisher = $data -> getPublisher($id);

	if ($author != NULL) {
		$response["error"] = false;
		$response["publisher"] = $publisher;
		echoRespnse(200, $response);
	} else {
		$response["error"] = true;
		$response["message"] = "The requested resource doesn't exists";
		echoRespnse(404, $response);
	}
});

/**
 * Updating existing asset
 * method PUT
 * params 	name,
 *			address
 * url - /publishers/:id
 */

$app -> put('/publishers/:id', 'authenticate', function($id) use ($app) {
	verifyRequiredParams(array('name', 'address'));

	$response = array();
	$data = new \Library\Model\Publishers();

	$name = $app -> request -> put('name');
	$address = $app -> request -> put('address');
	
	$put_data = array('id' => $id, 'name' => $name, 'address' => $address);

	$result = $data -> update($put_data);

	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task updated successfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to update. Please try again!";
	}
	echoRespnse(200, $response);
});

/**
 * Deleting asset.
 * method DELETE
 * url /publishers/:id
 */
$app -> delete('/publishers/:id', 'authenticate', function($id) use ($app) {
	$data = new \Library\Model\Publishers();
	$response = array();
	$result = $data -> delete(id);
	if ($result) {
		$response["error"] = false;
		$response["message"] = "Task deleted succesfully";
	} else {
		$response["error"] = true;
		$response["message"] = "Task failed to delete. Please try again!";
	}
	echoRespnse(200, $response);
});


/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
	$error = false;
	$error_fields = "";
	$request_params = array();
	$request_params = $_REQUEST;
	// Handling PUT request params
	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		$app = \Slim\Slim::getInstance();
		parse_str($app -> request() -> getBody(), $request_params);
	}
	foreach ($required_fields as $field) {
		if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
			$error = true;
			$error_fields .= $field . ', ';
		}
	}

	if ($error) {
		// Required field(s) are missing or empty
		// echo error json and stop the app
		$response = array();
		$app = \Slim\Slim::getInstance();
		$response["error"] = true;
		$response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
		echoRespnse(400, $response);
		$app -> stop();
	}
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
	$app = \Slim\Slim::getInstance();
	// Http response code
	$app -> status($status_code);
	// setting response content type to json
	$app -> contentType('application/json');
	echo json_encode($response);
}

$app -> run();
?>