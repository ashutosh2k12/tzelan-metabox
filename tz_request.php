<?php
add_action( 'wp_ajax_tzelan_ajax_product', 'tzelan_ajax_handler' );
function tzelan_ajax_handler() {
	global $wpdb;
	$metaboxid = $_POST['metaboxid'];
	$productid = $_POST['productid'];
	$maps = get_option('tzelan_metabox_productmap',array());
	$maps[$productid] = $metaboxid;
	$updated = update_option('tzelan_metabox_productmap',$maps);
    echo json_encode(array($updated));
	wp_die();
}

function tz_meta_form_handler(){
	if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
		if( isset($_REQUEST['metabox_form_submit']) ){
			handle_meta_box_add();
		}
		if( isset($_REQUEST['metabox_form_update']) ){
			handle_meta_box_update();
		}
    }
}

function handle_meta_box_add() {
	global $wpdb;
	$table_name = $wpdb->prefix . TZELAN_TBL;
	$titles = $_REQUEST['metabox_title'];
	$text = $_REQUEST['metabox_text'];
	if(empty($titles)) return;
	$unique = random_id();

	foreach ($titles as $key => $title) {
		if(!empty($title)){
			$wpdb->insert( $table_name, array( 'metaboxid' => $unique, 'title' => $title, 'content' => $text[$key] ) );
		}
	}
}

function handle_meta_box_update() {
	global $wpdb;
	$table_name = $wpdb->prefix . TZELAN_TBL;
	$titles = $_REQUEST['metabox_title'];
	$text = $_REQUEST['metabox_text'];
	$metaboxid = $_REQUEST['metabox_id'];

	delete_metabox( $metaboxid );
	foreach ($titles as $key => $title) {
		if(!empty($title)){
			$wpdb->insert( $table_name, array( 'metaboxid' => $metaboxid, 'title' => $title, 'content' => $text[$key] ) );
		}
	}
}

function random_id( $len=5 ) {
	$result = '';

    for($i = 0; $i < $len; $i++) {
        $result .= mt_rand(0, 9);
    }

    return intval($result);
}