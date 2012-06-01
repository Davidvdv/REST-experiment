<?php
	// Save request method in $requestMethod.
	$requestMethod = $_SERVER['REQUEST_METHOD'];

	// Check if id and type exist in the URI.
	if(isset($_GET['id']) && isset($_GET['type'])) {
		
		// Check for the request method
		switch($requestMethod) {
			case 'HEAD':
				// Sends headers back to client.
			break;
			case 'OPTIONS':
				// Sends the HTTP-request methods back which are allowed.
				header('Allow: GET,HEAD,OPTIONS');
			break;
			default:
				// Check for data type in the URI.
				switch($_GET['type']) {
					case 'html':
						header('Content-Type: text/html');
						echo '<body><h1>'.$_GET['id'].'</h1></body>';
					break;
					case 'json':
						header('Content-Type: text/json');
						echo '{ "review": { "id": "'.$_GET['id'].'"} }';
					break;
					case 'xml':
						header('Content-Type: text/xml');
						echo '<review><id>'.$_GET['id'].'</id></review>';
					break;
				}
			break;
		}
	}
?>