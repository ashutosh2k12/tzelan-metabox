<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Tzelan_table extends WP_List_Table {

    var $example_data = array();

    function __construct(){
    	global $status, $page;

        parent::__construct( array(
            'singular'  => __( 'metabox', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'metaboxes', 'mylisttable' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?

    	) );

    	add_action( 'admin_head', array( &$this, 'admin_header' ) );            
    }

  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'my_list_test' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '</style>';
  }

  function no_items() {
    _e( 'No Metabox found.' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'metaboxid':
        case 'shortcode':
        case 'count':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

function get_sortable_columns() {
  $sortable_columns = array(
    'metaboxid'  => array('metaboxid',false),
    'shortcode' => array('shortcode',false),
    'count'   => array('count',false)
  );
  return $sortable_columns;
}

function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'metaboxid' => __( 'Metabox ID', 'mylisttable' ),
            'shortcode'    => __( 'Shortcode', 'mylisttable' ),
            'count'      => __( 'Count', 'mylisttable' )
        );
         return $columns;
    }

function usort_reorder( $a, $b ) {
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  $result = strcmp( $a[$orderby], $b[$orderby] );
  return ( $order === 'asc' ) ? $result : -$result;
}

function column_metaboxid($item){
  $delete_nonce = wp_create_nonce( 'tzelan_metabox' );
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&metabox=%s">Edit</a>',$_REQUEST['page'],'edit',$item['metaboxid']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&metabox=%s&_wpnonce=%s">Delete</a>',$_REQUEST['page'],'delete',$item['metaboxid'],$delete_nonce),
        );

  return sprintf('%1$s %2$s', $item['metaboxid'], $this->row_actions($actions) );
}

function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}

function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="metaboxid[]" value="%s" />', $item['metaboxid']
        );    
    }

function process_bulk_action() {

    //Detect when a bulk action is being triggered...
    if ( 'delete' === $this->current_action() ) {

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );

      if ( ! wp_verify_nonce( $nonce, 'tzelan_metabox' ) ) {
        die( 'Go get a life script kiddies' );
      }
      else {
        delete_metabox( absint( $_GET['metabox'] ) );

        wp_redirect( esc_url( add_query_arg() ) );
        exit;
      }

    }

    // If the delete bulk action is triggered
    if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
         || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
    ) {

      $delete_ids = esc_sql( $_POST['bulk-delete'] );

      // loop over the array of record IDs and delete them
      foreach ( $delete_ids as $id ) {
        delete_metabox( $id );

      }

      wp_redirect( esc_url( add_query_arg() ) );
      exit;
    }
}

function prepare_items() {
  $metabox_db_data = get_meta_boxes();
  foreach ($metabox_db_data as $metabox_data) {
    array_push($this->example_data, 
      array(
        'id' => $metabox_data['id'],
        'metaboxid' => $metabox_data['metaboxid'],
        'count' => $metabox_data['counter'],
        'shortcode' => '[metabox id='. $metabox_data['metaboxid'] .']'
      )
    );
  }

  $this->process_bulk_action();

  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  usort( $this->example_data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 5;
  $current_page = $this->get_pagenum();
  $total_items = count( $this->example_data );

  // only ncessary because we have sample data
  $this->found_data = array_slice( $this->example_data,( ( $current_page-1 )* $per_page ), $per_page );

  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $this->found_data;
}

} //class
?>