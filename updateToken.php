<?php
/**
 *  copyright (c) 2010  sodahead.com
 *
 *  this program is free software: you can redistribute it and/or modify
 *  it under the terms of the gnu general public license as published by
 *  the free software foundation, either version 3 of the license, or
 *  (at your option) any later version.
 *
 *  this program is distributed in the hope that it will be useful,
 *  but without any warranty; without even the implied warranty of
 *  merchantability or fitness for a particular purpose.  see the
 *  gnu general public license for more details.
 *
 *  you should have received a copy of the gnu general public license
 *  along with this program.  if not, see <http://www.gnu.org/licenses/>.
 */
  ### Include wp-config.php
  # this script is called remotely and update the token for the current user
  $wp_root = '../../..';
  if (file_exists($wp_root.'/wp-load.php')) {
    require_once($wp_root.'/wp-load.php');
  } else {
    require_once($wp_root.'/wp-config.php');
  }

  require_once('libs/sodahead.php');
  # ensure user is logged in
  $current_user = wp_get_current_user();
  if (0 == $current_user->ID)
    return;

  $token = $_POST["token"];
  $actual_token = SodaLibs::getToken();

  # update token
  if (empty($actual_token)){
    SodaLibs::insertToken($token, $current_user->ID);
  }

  # create a new token
  elseif ($token != $actual_token){
    SodaLibs::updateToken($token, $current_user->ID);
  }
?>

