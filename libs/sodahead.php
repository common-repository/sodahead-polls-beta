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

class SodaLibs{
  function getToken(){
    global $wpdb;
    global $current_user;
    get_currentuserinfo();
    $user_id = $current_user->ID;
    $table_name = $wpdb->prefix . "sodahead_user";
    $token = $wpdb->get_var(
      "select token from $table_name where user_id = $user_id");
    return $token;
  }

  function isValidToken($token){
    # a token should be alpha + digit of 32 char
    # this method also avoid SQL-injection
    if (strlen($token) == 32 && preg_match("/\w{32}/", $token)){
      return True;
    }
    return False;
  }

  function insertToken($token, $user_id){
    if (self::isValidToken($token)){
      global $wpdb;
      $table_name = $wpdb->prefix . "sodahead_user";
      $wpdb->query(
          "insert into $table_name (user_id, token, expires) values ($user_id, '$token', date_add(now(), interval 1 year))");
      }
    }

  function updateToken($token, $user_id){
    if (self::isValidToken($token)){
      global $wpdb;
      $table_name = $wpdb->prefix . "sodahead_user";
      $wpdb->query("update $table_name set token = '$token' where user_id = $user_id");
    }
  }
}
?>
