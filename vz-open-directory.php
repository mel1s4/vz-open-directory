<?php
/**
* Plugin Name: Viroz Open Directory
* Plugin URI: https://www.melisaviroz.com/
* Description: Creates a post type for directory contacts that will be displayed publicly in the website.
* Version: 0.1
* Author: mel1s4
* Author URI: https://www.melisaviroz.com/
**/

include 'helpers.php';
include __DIR__ . '/blocks/contact-field/contact-field.php';
// include __DIR__ . '/blocks/contact-details/contact-detail.php';

add_action( 'init', 'create_vz_contact_post_type' );
function create_vz_contact_post_type() {
  register_post_type( 'vz-open-contact',
    array(
      'labels' => array(
        'name' => __( 'Directory' ),
        'singular_name' => __( 'Contact' )
      ),
      'description' => __('Open Directory Contact List'),
      'public' => true,
      'has_archive' => true,
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-id',
      'supports' => array( 'title', 'thumbnail', 'custom-fields'),
      'taxonomies' => ['post_tag', 'category'],
    )
  );

  $attr = [
    'show_in_rest' => true,
    'single'       => true,
    'type'         => 'string',
  ];
  $post_type = 'vz-open-contact';
  $fields = "vz_title,vz_name,vz_last_name,vz_description,vz_address,vz_phone,vz_social_media,vz_email,vz_external_links";
  foreach(explode(',', $fields) as $field) {
    register_post_meta( $post_type, $field, $attr );
  }
}


// METABOX REGISTRATION
function vz_contact_metabox_registration() {
	viroz_fm_add_metabox('Contact Details', 'vz-open-contact');
}
add_action( 'add_meta_boxes', 'vz_contact_metabox_registration' );

// DEFAULT - PAGE TEMPLATES
// PAGE FIELDS BEGIN
function contact_details_meta_box_callback( $post ) {
	wp_nonce_field( 'contact_details_nonce', 'contact_details_nonce' );
	?>
    <style>
    .vz-contact-details {
      width: 100%;
      display: flex;
      flex-wrap: wrap;
    }
    .vz-contact-field {
      align-items: top;
      margin: 0.5em;
      min-width: 180px;
      max-width: 100%;
      flex-grow: 1;
    }
    .grid-s {
      width: 30%;
    }
    .grid-m {
      width: 60%;
    }
    .vz-contact-field label {
      display: block;
      font-weight: bold;
      margin-bottom: 0.5em;
    }
    .vz-contact-field input,
    .vz-contact-field textarea {
      display: block;
      width: 100%;
    }
    </style>
    <section class="vz-contact-details">
      <!-- Title -->
      <div class='vz-contact-field grid-s'>
        <label for="contact-title">Title</label>
        <input type="text" id="contact-title" name="vz_title" value='<?php echo get_post_meta($post->ID, 'vz_title', true); ?>'>
      </div>
      <!-- Name -->
      <div class='vz-contact-field grid-s'>
        <label for="name">Name</label>
        <input type="text" id="name" name="vz_name" value='<?php echo get_post_meta($post->ID, 'vz_name', true); ?>'>
      </div>
      <!-- Last Name -->
      <div class='vz-contact-field grid-s'>
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="vz_last_name" value='<?php echo get_post_meta($post->ID, 'vz_last_name', true); ?>'>
      </div>
      <!-- Description -->
      <div class='vz-contact-field grid-m'>
        <label for="description">Description</label>
        <textarea id="description" name="vz_description"><?php echo get_post_meta($post->ID, 'vz_description', true); ?></textarea>
      </div>
      <!-- address -->
      <div class='vz-contact-field grid-m'>
        <label for="address">Address</label>
        <input type="text" id="address" name="vz_address" value='<?php echo get_post_meta($post->ID, 'vz_address', true); ?>'>
      </div>
      <!-- phone -->
      <div class='vz-contact-field grid-s'>
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="vz_phone" value='<?php echo get_post_meta($post->ID, 'vz_phone', true); ?>'>
      </div>
      <!-- Social Media -->
      <div class='vz-contact-field grid-m'>
        <label for="social-media">Social Media</label>
        <input type="text" id="social-media" name="vz_social_media" value='<?php echo get_post_meta($post->ID, 'vz_social_media', true); ?>'>
      </div>
      <!-- eMail -->
      <div class='vz-contact-field grid-s'>
        <label for="email">Email</label>
        <input type="email" id="email" name="vz_email" value='<?php echo get_post_meta($post->ID, 'vz_email', true); ?>'>
      </div>
      <!-- External Links -->
      <div class='vz-contact-field grid-m'>
        <label for="external-links">External Links</label>
        <input type="text" id="external-links" name="vz_external_links" value='<?php echo get_post_meta($post->ID, 'vz_external_links', true); ?>'>
      </div>
    </section>
  <?php
}

function contact_details_string_meta_box_data( $post_id ) {
	if(!vz_verify_metabox('contact_details', 'vz-open-contact', $post_id)) return;
  $fields = "vz_title,vz_name,vz_last_name,vz_description,vz_address,vz_phone,vz_social_media,vz_email,vz_external_links";
  foreach(explode(',', $fields) as $slug) {
    update_post_meta($post_id, $slug, $_POST[$slug]);
  }
}
add_action( 'save_post', 'contact_details_string_meta_box_data' );
