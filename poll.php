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
  $wp_root = '../../..';
  if (file_exists($wp_root.'/wp-load.php')) {
    require_once($wp_root.'/wp-load.php');
  } else {
    require_once($wp_root.'/wp-config.php');
  }
?>
<div id="sh_placeholder"></div>
<script type="text/javascript" src="<?php echo plugins_url('js/swfobject.js',__FILE__); ?>"></script>
<script type="text/javascript">
<?php
require_once('config.php');
require_once('libs/sodahead.php');
$token = Sodalibs::getToken();
echo "var customize = '" . $_GET['customize'] . "';";
echo "var widget_select_id = '" . $_GET['widget_select_id'] . "';";
?>

function injectSWF(url, id, parentId, width, height){
 var swf = new SWFObject(url, id, width, height, '9,0,0,0');
 swf.addParam('allowScriptAccess', 'always');
 swf.addParam('wmode', 'transparent');
 swf.write(parentId);
}

var update_token_url= "<?php echo plugins_url('/sodahead-polls/updateToken.php'); ?>";
var proxy="<?php echo plugins_url('/sodahead-polls/proxy/proxy.php'); ?>";
function updateToken(token, old_token){
  jQuery.ajax({
    url:update_token_url,
    data: {token:token, old_token:old_token},
    dataType: 'json',
    type: 'POST'});
  }

jQuery.ajax({
  url:'http://<?php echo $sodahead_url; ?>/remote/question/crossdomain_ask/',
  data:{proxy:proxy,update_token_url:update_token_url,affiliate_id: <?php echo $affiliate_id; ?><?php if ($token){ print ", token:'$token'"; }  ?>},
  dataType: 'jsonp',
  success:function(data){
    updateToken(data.token);
    jQuery("#sh_placeholder").html(data['content']);
    }
  });
</script>
