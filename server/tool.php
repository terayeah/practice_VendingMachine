<?php

function createSalt($length = 8){
  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

 ?>
