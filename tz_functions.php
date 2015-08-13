<?php

function get_wc_products() {
	$products = array();
	$args = array( 'post_type' => 'product', 'posts_per_page' => 10);
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
			$products[] = array("id"=>get_the_ID(),"title"=>get_the_title());
			endwhile;
	}
	wp_reset_postdata();
	return $products;
}

function delete_metabox( $metaboxid ) {
	global $wpdb;

	$wpdb->delete(
		"{$wpdb->prefix}".TZELAN_TBL,
		[ 'metaboxid' => $metaboxid ],
		[ '%d' ]
	);
}

function get_meta_boxes( $id=NULL ) {
	global $wpdb;
	$sql = "SELECT * ";
	if( $id === NULL) $sql .= ", COUNT(*) AS counter";
	$sql .= " FROM {$wpdb->prefix}".TZELAN_TBL;
	if( $id !== NULL) $sql .= " WHERE metaboxid=". $id;
	if( $id === NULL) $sql .= ' GROUP BY metaboxid';
	$result = $wpdb->get_results( $sql, 'ARRAY_A' );
	return $result;
}

function print_error( $msg, $die=false ){
	echo '<div id="message" class="error below-h2"><p>'.$msg.'</p></div>';
	if( $die ) die();
}
function metabox_col( $count = 0 ) {
	switch ($count) {
		case 1:
			echo "tz_mb_column-s1";
			break;
		case 2:
			echo "tz_mb_column-s2";
			break;
		case 3:
			echo "tz_mb_column-s3";
			break;
		default:
			echo "tz_mb_column-s1";
			break;
	}
}

function metabox_col_sec( $i ){
	switch ($i) {
		case 1:
			echo "tz_mb_column-left-10";
			break;
		case 2:
			echo "tz_mb_column-left-20";
			break;
		default:
			break;
	}
}

function woocommerce_active(){
	return class_exists( 'WooCommerce' );
}