<?php
require_once("UserController.php");

$uriParts = explode('/',$_SERVER['REQUEST_URI']);
@array_shift($uriParts);
// index.phpでいうlessons/a_vending_machine/server/index.php
$controller = $uriParts[2];
// index.phpでいうserver
$action = $uriParts[3];
// index.phpでいうindex.php
function response_json($value) {
  $response = json_encode($value, JSON_UNESCAPED_UNICODE);
  header('Content-Type: application/json');
  echo $response;
}

switch ($controller) {
		case 'user':
			switch ($action) {
				case 'signup':
          $result = UserController::signup($_POST['newUsername'], $_POST['newPassword']);
          if($result != null){
						response_json($result);
					}
					break;
				case 'login':
					break;
				case 'addcount':
					break;
			}
			break;
	}
