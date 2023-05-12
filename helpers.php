<?php
if(!function_exists('print_x')) {
  function print_x($var, $name = false) {
    echo "<pre>";
      if($name) {
        echo "<b>$name</b>";
      }
      print_r($var);
    echo "</pre>";
  }
}

// Helpers
function vz_verify_metabox($field, $post_type, $post_id) {
  // Check if our nonce is set.
  if ( ! isset( $_POST[$field.'_nonce'] ) ) {
    return false;
  }
  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $_POST[$field.'_nonce'], $field.'_nonce' ) ) {
    return false;
  }
  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return false;
  }
  // Check the user's permissions.
  if ( isset( $_POST['post_type'] ) && $post_type == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return false;
    }
  }
  else {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return false;
    }
  }
  return true;
}

function viroz_fm_add_metabox($name, $post_type) {
	$name_lower = strtolower($name);
	$slug = str_replace(' ', '-', $name_lower);
	$slug_underscore = str_replace(' ', '_', $name_lower);
	add_meta_box(
		$slug,
		$name,
		$slug_underscore.'_meta_box_callback',
		$post_type
	);
}