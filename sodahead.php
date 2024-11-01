<?php
/*
plugin name: sodahead polls
plugin uri: http://sodahead.com/group/412/
description: allows you to create and embed a sodahead poll into your blog.
version: 2.0
author: sodahead.com
author uri: http://www.sodahead.com/
*/
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
require_once('libs/sodahead.php');
require_once('config.php');

function sh_media_button($title, $icon, $poll_url) {
  # this is a simple modification of the media button to allow pictures from plugin instead of admin
  return "<a href='" .  plugins_url($poll_url) . "' id='add_$type' class='thickbox' title='$title'><img src='" . esc_url( plugins_url( $icon ) ) . "' alt='$title' /></a>";
  }

function sh_media_buttons(){
  $plugin_url = plugins_url('');
  $out = sh_media_button(__('Add Poll'), 'sodahead-polls/images/sodahead.gif', 'sodahead-polls/poll.php?width=700');
  printf($out);
  }

class Sodahead_Polls extends WP_Widget {
  function Sodahead_Polls(){
    $widget_ops = array('classname' => 'sodahead_polls', 'description' => __('Easy to create integrated polls from SodaHead.com.'));
    $this->WP_Widget('sodahead-polls-widget', __('Sodahead Polls'), $widget_ops);
  }

  function widget($args, $instance){
    extract( $args );
    echo $before_widget;
    echo $instance['embed_code'];
    echo $after_widget;
  }

  function form($instance){
    $customizer_url = plugins_url('customizer.php', __FILE__);
    $poll_url = plugins_url('poll.php', __FILE__);
    include ('includes/widget_form.php');
  }

  function getEmbedCode($poll_id){
    global $sodahead_url;
    return '<div style="display: table; margin: 5px auto; font-family: arial,helvetica,sans-serif; text-align: center; width: 250px;" class="widgetContainer"><embed width="250" height="375" allowscriptaccess="always" style="display: block;" wmode="transparent" flashvars="theme_id=5764&amp;height=375&amp;width=250&amp;poll_id='. $poll_id .'&amp;pollserver=partners.' . $sodahead_url .'&server=www.' . $sodahead_url .'" src="http://widgets.' . $sodahead_url .'/images/flash/poll.swf" type="application/x-shockwave-flash"><div style="background: url(&quot;http://widgets.'. $sodahead_url .'/images/flash/footerGradient.gif&quot;) repeat-x scroll center bottom rgb(255, 255, 255); border: 1px solid rgb(230, 230, 230); font-size: 0pt; height: 13px; line-height: 13px; padding: 0pt 3px; text-align: right;" class="widgetFooter"><a style="color: rgb(72, 71, 71); font-size: 10px; text-decoration: none; float: left;" href="http://www.'. $sodahead_url .'/questions/">Questions</a><a style="color: rgb(72, 71, 71); font-size: 10px; text-decoration: none;" href="http://www.'. $sodahead_url. '/all/-/question-'. $poll_id .'">View Results</a></div></div>';
  }


  function update($new_instance, $old_instance){
    $instance = $old_instance;
    if ($new_instance['embed_code'] == "" && $new_instance['poll_id'] != $old_instance['poll_id']){
      // case we changed poll id with no cusomization
      $instance['poll_id'] = $new_instance['poll_id'];
      $instance['embed_code'] = $this->getEmbedCode($instance['poll_id']);
    }elseif ($new_instance['embed_code'] != "" && $new_instance['poll_id'] == $old_instance['poll_id']){
      // case we optimized the code but keept the same poll_id
      $instance['embed_code'] = $new_instance['embed_code'];
    }elseif ($new_instance['embed_code'] != "" && $new_instance['poll_id'] != $old_instance['poll_id']){
      // case we changed poll and optimized it
      $instance['embed_code'] = $new_instance['embed_code'];
      $instance['poll_id'] = $new_instance['poll_id'];
    }
    return $instance;
  }

  function widgets_init(){
    register_widget('Sodahead_Polls');
  }

  function install(){
    /*
     * Perform installation steps.
     */
    global $wpdb;
    global $sodahead_db_version;

    $table_name = $wpdb->prefix . "sodahead_user";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
      $sql = "CREATE TABLE " . $table_name . " (
          user_id INTEGER NOT NULL,
          token VARCHAR(128) NOT NULL,
          expires DATETIME NOT NULL,
          PRIMARY KEY  (user_id)
        );";

      require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      dbDelta($sql);

      add_option("sodahead_db_version", $sodahead_db_version);
    }
  }
}

wp_enqueue_script("jquery");
wp_enqueue_script("yui");
wp_enqueue_script('thickbox');
wp_enqueue_script('swfobject');
wp_enqueue_style('thickbox');
add_action( 'media_buttons', 'sh_media_buttons', 15 );
add_action('widgets_init', array('SodaHead_Polls', 'widgets_init'));
register_activation_hook(__FILE__, array('Sodahead_Polls', 'install'));
?>
