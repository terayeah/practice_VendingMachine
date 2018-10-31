<?php
Class SessionUtil{

  public static $SESSION_KEY_VM_ARRAY = "vmArray";
  public static $SESSION_KEY_USER_ID = "userId";
  public static $SESSION_KEY_SELECTED_VM_NAME = "selectedVmName";
  public static $SESSION_KEY_SELECTED_VM_TYPE = "selectedVmType";
  public static $SESSION_KEY_SELECTED_VM = "selectedVm";
  public static $SESSION_KEY_CHOICE = "choice";

  public static $SESSION_KEY_DB_SELECTED_USER = "selected_user_records";
  public static $SESSION_KEY_DB_VM = "vm_records";
  public static $SESSION_KEY_DB_DRINK = "drink_records";
  public static $SESSION_KEY_DB_VM_DRINK = "vm_drink_records";
  public static $SESSION_KEY_DB_USER_DRINK = "user_drink_records";

  public static function Set($key, $value){
    session_start();
    $_SESSION[$key] = $value;
  }

  public static function Get($key, $default = null){
    session_start();

    if(!isset($_SESSION[$key])){
      $_SESSION[$key] = $default;
    }
    return $_SESSION[$key];
  }


}
