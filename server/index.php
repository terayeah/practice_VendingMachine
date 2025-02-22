<?php
require_once("Controller/UserController.php");
require_once("Controller/VendingMachineController.php");
require_once("Controller/DrinkController.php");
require_once("Models/UserMapper.php");
require_once("Models/UserDrinkMapper.php");
require_once("Models/VendingMachineMapper.php");
require_once("Models/VendingMachineDrinkMapper.php");
require_once("Models/DrinkMapper.php");
require_once("Models/helper.php");
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
          $result = UserController::login($_POST['username'], $_POST['password']);
          if($result != null){
						response_json($result);
					}
					break;
				case 'putCash':
          $result = UserController::putCash($_POST['userEncrypt'], $_POST["howMuchCash"]);
          if($result != null){
						response_json($result);
					}
          break;
        case 'backChange':
          UserController::backChange($_POST['userEncrypt']);
					break;
        case 'charge':
          UserController::charge($_POST['userEncrypt'], $_POST["howMuchSuica"]);
          break;
        case 'selectedDrink':
          $result = UserController::selectedDrink($_POST['userEncrypt'], $_POST["selectedDrink"]);
          if($result != null){
						response_json($result);
					}
          break;
        case 'buySuica':
          $result = UserController::buySuica($_POST['userEncrypt']);
          if($result != null){
						response_json($result);
					}
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
          $result = VendingMachineController::addVm($_POST['vmType'], $_POST['vmName']);
          if($result != null){
						response_json($result);
					}
					break;
        case 'addDrink':
          $result = VendingMachineController::addDrink($_POST['userEncrypt'], $_POST['addedExistingDrink'], $_POST['addDrinkCount']);
          if($result != null){
						response_json($result);
					}
					break;
        case 'changeDrink':
          $result = VendingMachineController::changeDrink($_POST['userEncrypt'], $_POST['changedDrink'], $_POST['changeDrinkStock']);
          if($result != null){
						response_json($result);
					}
					break;
        case 'deleteDrink':
          $result = VendingMachineController::deleteDrink($_POST['userEncrypt'], $_POST['deletedDrink']);
          if($result != null){
						response_json($result);
					}
					break;
			}
		break;
}

switch ($controller) {
		case 'drink':
			switch ($action) {
				case 'makeProduct':
          $result = DrinkController::makeProduct($_POST['drinkName'], $_POST['drinkPrice']);
          if($result != null){
						response_json($result);
					}
					break;
        case 'changeProduct':
          $result = DrinkController::changeProduct($_POST['changedProduct'], $_POST['changeProductName'], $_POST['changeProductPrice']);
          if($result != null){
						response_json($result);
					}
          break;
			}
		break;
}
