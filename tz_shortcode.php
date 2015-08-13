<?php

function metabox_shortcode( $args ) {
	if( empty( $args ) ) {
		if( get_the_ID() ){
			$maps = get_option('tzelan_metabox_productmap',array());
			if( isset( $maps[get_the_ID()] ) ){
				$metaboxes = get_meta_boxes( $maps[get_the_ID()] );
				wp_register_style('metabox-css', plugins_url( '/css/metabox.css', __FILE__ ));
			  	wp_enqueue_style( 'metabox-css' );
				include WPRESS_TPL_DIR.'/metabox.php';
			}
		}
		return;
	}
	if( !isset( $args['id'] ) ) return;

	$metaboxes = get_meta_boxes( $args['id'] );
	wp_register_style('metabox-css', plugins_url( '/css/metabox.css', __FILE__ ));
  	wp_enqueue_style( 'metabox-css' );
	include WPRESS_TPL_DIR.'/metabox.php';
}

add_shortcode('metabox', 'metabox_shortcode');