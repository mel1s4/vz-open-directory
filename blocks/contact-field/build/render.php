<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
global $post;
$field = $attributes['field'];
$value = get_post_meta($post->ID, $field);
if(@$value[0]) :
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo $value[0]; ?>
</p>
<?php
endif;