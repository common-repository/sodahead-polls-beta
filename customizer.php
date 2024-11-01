<?
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
  require_once('config.php');
?>
<script>
  function SHFinishPollEmbed(embed, arg){
    var args = arg.split("|");
    var embedId = args[0];
    var pollId = args[1];
    embed = embed.replace(new RegExp('</?(object|param).*?>', 'g'), '');
    window.parent.document.getElementById('<?php echo $_GET['embed_id']; ?>').value = embed;
    window.parent.document.getElementById('<?php echo $_GET['embed_id']; ?>-span').innerHTML = "Don't forget to save";
    tb_remove();
  }
 function injectSWF(url, id, parentId, width, height){
   var swf = new SWFObject(url, id, width, height, '9,0,0,0');
   swf.addParam('allowScriptAccess', 'always');
   swf.addParam('wmode', 'transparent');
   swf.write(parentId);
 }
</script>
<script type="text/javascript" src="<?php echo plugins_url('js/swfobject.js', __FILE__) ?>"></script>
<div id="widgetCustomizer">
    <script type="text/javascript">
      injectSWF('http://widgets.<?php echo $sodahead_url; ?>/images/flash/customizer.swf?poll_id=<?php echo $_GET['poll_id']; ?>&size=medium&pollserver=partners.<?php echo $sodahead_url; ?>&server=partners.<?php echo $sodahead_url; ?>&callback=SHFinishPollEmbed&nogigya=true', 'customizer', 'widgetCustomizer', '100%', '500');
    </script>
</div>
<div style="text-align:right">
  <input id="embedPoll" type="submit" onclick="document.getElementById('customizer').getEmbedCode('|<?php echo $_GET['poll_id']; ?>')" value="Done" class="button-primary" />
</div>
