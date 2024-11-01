<?php
    $token = SodaLibs::getToken();
    global $sodahead_url;
?>
<p>
  <a class="thickbox"
     href="<?php echo $poll_url;?>?width=650&customize=no&widget_select_id=<?php echo $this->get_field_id('poll_id'); ?>"
     title="Create a new poll" >
    Create a new poll
  </a>
</p>
<p>
  <textarea style="display:none"
            name="<?php echo $this->get_field_name('embed_code'); ?>"
            id="<?php echo $this->get_field_id('embed_code'); ?>"><?php echo $instance['embed_code']; ?></textarea>

  <label for="<?php echo $this->get_field_id('poll_id'); ?>">
    Select a poll from your archive:
  </label>
  <select class="widefat"
          name="<?php echo $this->get_field_name('poll_id'); ?>"
          onchange="on_list_change('<?php echo $this->get_field_id('customize_link'); ?>',this.value,'<?php echo $this->get_field_id('embed_code'); ?>','<?php echo $this->get_field_id('embed_code'); ?>-span')"
          id="<?php echo $this->get_field_id('poll_id'); ?>" >
  </select>
</p>
<p>
  <div>
    <a class="thickbox"
       title="Customize your poll"
       id="<?php echo $this->get_field_id('customize_link'); ?>"
       href="<?php echo $customizer_url; ?>?poll_id=<?php echo $instance['poll_id'] . '&embed_id=' . $this->get_field_id('embed_code'); ?>&width=700&height=600">
      Customize your poll
    </a>
    <span id="<?php echo $this->get_field_id('embed_code'); ?>-span"
          class="alignright"
          style="color: red; font-weight:bold;">
    </span>
  </div>
</p>
<script>
  var customizer_url = "<?php echo plugins_url('sodahead-polls/customizer.php'); ?>";
  function on_list_change(url_id, poll_id, embed_code_id, span_id){
    document.getElementById(url_id).href= customizer_url + "?poll_id=" + poll_id + "&embed_id=<?php echo $this->get_field_id('embed_code'); ?>&width=700&height=600";
    document.getElementById(embed_code_id).value = '';
    document.getElementById(span_id).innerHTML = "Don't forget to save";
    return false;
  }

  jQuery.ajax({ url:'http://www.<?php echo $sodahead_url; ?>/questions/my_poll_list/',
                dataType: 'jsonp',
                data: {token:'<?php echo $token ?>', affiliate_id: 45},
                success: function(data){
                  <?php if ( empty($instance['poll_id']) ) {
                    echo "var options = '<option>Choose a question...</option>';\n";
                  }else{
                    echo "var options = '';\n";
                  }
                  ?>
                  for(position in data){
                    selected = '' + data[position].poll_id == '<?php echo $instance['poll_id'] ?>' ? "SELECTED":"";
                    options += '<option value="' + data[position].poll_id + '" '+ selected +' >' + data[position].poll_title + '</option>';
                  }
                  jQuery('#<?php echo $this->get_field_id('poll_id'); ?>').html(options);
                }
              });
</script>
