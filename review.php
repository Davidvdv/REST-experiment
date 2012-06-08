<?php
	// Save request method in $requestMethod.
	$requestMethod = $_SERVER['REQUEST_METHOD'];

	// Check for the request method
	switch($requestMethod) {
		case 'HEAD':
			// Sends headers back to client.
		break;
		case 'OPTIONS':
			// Sends the HTTP-request methods back which are allowed.
			header('Allow: GET, HEAD, OPTIONS, PUT');
		break;
		case 'PUT':
			// Get the data of the PUT-request
			$putSocket = fopen('php://input', 'r');
			$putData = '';
			
			// Save all the data of the PUT-request in a variable
			while($data = fread($putSocket, 1024)) {
				$putData .= $data;
			}		
			fclose($putSocket);
			
			// Get the URL to for in the file name.
			$fileName = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
			
			/* Check if the file already exists
			 * if true: set boolean isNewFile true
			 * if false: set boolean isNewFile false
			 */
			if(file_exists('reviews/'.$fileName)) $isNewFile = true;
			else $isNewFile = false;
			
			// Write data in a the XML-file and send header responses
			if(file_put_contents('reviews/'.$fileName.'.xml', $putData)) {
				if($isNewFile) {
					header('http/1.0 201 Created'); // Succeed
				} else {
					header('http/1.0 204 ');
				}
			} else {
				header('http/1.0 500 Internal Server Error'); // Failed
			}
			
			// Clean the XML-data
			$fileContents = str_replace(array("\n", "\r", "\t"), '', $putData);
			$fileContents = trim(str_replace('"', "'", $fileContents));
			
			// Load the XML
			$loadedXMLstring = simplexml_load_string($fileContents);
			
			// Convert XML to JSON
			$jsonData = json_encode($loadedXMLstring);
			
			// Write data in a the JSON-file and send header responses
			if(file_put_contents('reviews/'.$fileName.'.json', $jsonData)) {
				if($isNewFile) {
					header('http/1.0 201 Created'); // Succeed
				} else {
					header('http/1.0 204 ');
				}
			} else {
				header('http/1.0 500 Internal Server Error'); // Failed
			}
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
?>