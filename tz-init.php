<?php
/**
 * Created by Hellcoderz.
 * Date: 03/13/15
 * Plugin Name: Metaboxes
 * Plugin URI: https://github.com/ashutosh2k12/tzelan-metabox
 * Description: Create Metabox for woocommerce
 * Version: 1.0
 * Author: Ashutosh Chaudhary
 * Author URI: http://codeasashu.tk
 * License: GPLv2
 */
define( 'WPRESS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'WPRESS_TPL_DIR', WPRESS_PLUGIN_DIR.'/templates' );
define( 'TZELAN_TBL', 'metabox' );

include 'tz_functions.php';
include 'tz_request.php';
include 'class_tz_table.php';
include 'tz_shortcode.php';

register_activation_hook(__FILE__,'tzelan_tbl_install');

function tzelan_tbl_install()
{
    //install sort_search_result options to the database
    global $wpdb;

    $sortsearchtitle_db_version = '1.0';
    $table = $wpdb->prefix. TZELAN_TBL;
    $sql = "CREATE TABLE IF NOT EXISTS $table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `metaboxid` mediumint(9) NOT NULL,
      `title` mediumtext NOT NULL,
      `content` longtext NOT NULL,
      UNIQUE KEY id (id)
      );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
} 

tz_meta_form_handler(); //Capture Metabox update

//Add Global Ajax URL
add_action('wp_head','tzelan_metabox_ajaxurl');
function tzelan_metabox_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php
}

function my_add_menu_items(){
  $hook = add_menu_page( 'Metabox Tzelan', 'Metabox Tzelan', 'activate_plugins', 'tzelan_metabox', 'my_render_list_page' );
  add_action( "load-$hook", 'add_options' );
}

function add_options() {
  global $myListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Books',
         'default' => 10,
         'option' => 'books_per_page'
         );
  add_screen_option( $option, $args );
  $myListTable = new Tzelan_table();
}

add_action( 'admin_menu', 'my_add_menu_items' );
add_action( 'add_meta_boxes', 'tzelan_metabox_add_meta_box' );

if ( woocommerce_active() ) {
add_action( 'woocommerce_after_single_product_summary', 'tzelan_metabox_after_product', 5 );

function tzelan_metabox_after_product(){
  echo do_shortcode("[metabox]");
}
}

function tzelan_metabox_add_meta_box(){
 add_meta_box( 'product', __( 'Tzelan Metabox', 'woocommerce' ), 'add_meta_box_product', 'product', 'side', 'low' ); 
}

function add_meta_box_product( $post ){ 
  wp_register_style('tzelan-metabox-productmeta', plugins_url( '/css/productmeta.css', __FILE__ ));
  wp_enqueue_style( 'tzelan-metabox-productmeta' );
  wp_enqueue_script( 'jquery' );
  wp_register_script('tzelan-metabox-productjs', plugins_url( '/js/tzelan_product_meta.js', __FILE__ ));
  wp_enqueue_script( 'tzelan-metabox-productjs' );
?>
  <div id="product_images_container">
    <select id="product_metabox_tzelan">
      <option value="">Choose metabox</option>
    <?php
      $metaboxes = get_meta_boxes();
      $maps = get_option('tzelan_metabox_productmap',array());
      $metaboxselected = ( isset( $maps[$post->ID] ) )? $maps[$post->ID] : "";
      foreach ($metaboxes as $metabox) {
        $selected = ( $metabox['metaboxid'] === $metaboxselected ) ? "selected='selected'":'';
        echo "<option value='".$metabox['metaboxid']."' ".$selected.">Metabox #".$metabox['metaboxid'] ."</option>";
      }
    ?>
    </select>
    <input type="hidden" name="productid" value="<?= $post->ID ?>">
    <input type="button" class="button tzelanmetaboxadd" value="Save">
    <span class="tzelan_check_success"></span>
    <div class="tzelan_spinner"></div>
  </div>
<?php }

function my_render_list_page(){
  tzelan_register_scripts();
  if( isset($_REQUEST['action'] ) && 'add' == $_REQUEST['action'] )
    include WPRESS_TPL_DIR.'/add_metabox.php';
  else if( isset($_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] && isset( $_REQUEST['metabox'] ) )
    include WPRESS_TPL_DIR.'/edit_metabox.php';
  else
    include WPRESS_TPL_DIR.'/table.php';
}

function tzelan_register_scripts() {
  wp_enqueue_style( 'farbtastic' );
  wp_register_style('tzelan-metabox-css', plugins_url( '/css/style.css', __FILE__ ));
  wp_enqueue_style( 'tzelan-metabox-css' );
}  

