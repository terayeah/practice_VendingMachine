<?php
require_once("UserController.php");
require_once("VendingMachineController.php");
require_once("DrinkController.php");
require_once("VendingMachine.php");
require_once("User.php");
require_once("Drink.php");
session_start();

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
          $result = UserController::login($_POST['username']);
          if($result != null){
						response_json($result);
					}
					break;
				case 'putCash':
          UserController::putCash($_POST['userEncrypt'], $_POST["howMuchCash"]);
					break;
        case 'backChange':
          UserController::backChange($_POST['userEncrypt']);
					break;
        case 'charge':
          UserController::charge($_POST['userEncrypt'], $_POST["howMuchSuica"]);
          break;
        case 'selectedDrink':
          UserController::selectedDrink($_POST['userEncrypt'], $_POST["selectedDrink"]);
          break;
        case 'buySuica':
          UserController::buySuica($_POST['userEncrypt']);
          break;
			}
		break;
}

switch ($controller) {
		case 'vendingmachine':
			switch ($action) {
				case 'drowvmtop':
          $result = VendingMachineController::drowvmtop($_POST['userEncrypt']);
          if($result != null){
						response_json($result);
					}
					break;
				case 'drowvmview':
          $result = VendingMachineController::drowvmview($_POST['userEncrypt'], $_POST['selectedVmId']);
          if($result != null){
						response_json($result);
					}
					break;
				case 'setDrink':
          $result = VendingMachineController::setDrink($_POST['selectedVmId']);
          if($result != null){
						response_json($result);
					}
					break;
        case 'addVm':
          VendingMachineController::addVm($_POST['vmType'], $_POST['vmName']);
					break;
        case 'addDrink':
          VendingMachineController::addDrink($_POST['userEncrypt'], $_POST['addedExistingDrink'], $_POST['addDrinkCount']);
					break;
        case 'changeDrink':
          VendingMachineController::changeDrink($_POST['userEncrypt'], $_POST['changedDrink'], $_POST['changeDrinkStock']);
					break;
        case 'deleteDrink':
          VendingMachineController::deleteDrink($_POST['userEncrypt'], $_POST['deletedDrink']);
					break;
			}
		break;
}

switch ($controller) {
		case 'drink':
			switch ($action) {
				case 'makeProduct':
          DrinkController::makeProduct($_POST['drinkName'], $_POST['drinkPrice']);
					break;
        case 'changeProduct':
          DrinkController::changeProduct($_POST['changedProduct'], $_POST['changeProductName'], $_POST['changeProductPrice']);
					break;
			}
		break;
}
