<?php
/**
 * Plugin Name: Add FAQ on any page
 * Description: A plugin to create collapsible panels and shortcodes to call them, pretty usefull for FAQ pages.
 * Version: 1.0
 * Author: ifourtechnolab
 * Author URI: http://www.ifourtechnolab.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: add-faq-on-any-page
 */
if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Add FAQ on any page main class
 */
class Add_faq_on_any_page {

    /**
     * init plug-in.
     */
    public function __construct() {
        add_shortcode('add_faq_onp', array($this, 'shortcode_add_faq'));
        add_action('init', array($this, 'add_faq_cpt'), 0);
        add_action('wp_enqueue_scripts', array($this, 'add_faq_js'));
        //add_action('admin_notices', array($this, 'general_admin_notice'));
    }

    /**
     * Plugin javascript callback function
     * @global type $wp_scripts
     */
    public function add_faq_js() {
        wp_register_style('jquery-ui-smoothness-faq',plugin_dir_url(__FILE__) . 'assets/css/faq-jquery-ui.css');
        wp_register_script('add-faq-js-hadler', plugin_dir_url(__FILE__) . 'assets/js/add-any-faq.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion'), 1, true);
    }

    /**
     * Register Custom Post Type for FAQ.
     */
    public function add_faq_cpt() {

        $labels = array(
            'name' => _x('FAQ Items', 'Post Type General Name', 'add-faq-on-any-page'),
            'singular_name' => _x('FAQ Item', 'Post Type Singular Name', 'add-faq-on-any-page'),
            'menu_name' => __('FAQ', 'add-faq-on-any-page'),
            'name_admin_bar' => __('FAQ', 'add-faq-on-any-page'),
            'parent_item_colon' => __('Parent Item:', 'add-faq-on-any-page'),
            'all_items' => __('All Items', 'add-faq-on-any-page'),
            'add_new_item' => __('Add New Item', 'add-faq-on-any-page'),
            'add_new' => __('Add New', 'add-faq-on-any-page'),
            'new_item' => __('New Item', 'add-faq-on-any-page'),
            'edit_item' => __('Edit Item', 'add-faq-on-any-page'),
            'update_item' => __('Update Item', 'add-faq-on-any-page'),
            'view_item' => __('View Item', 'add-faq-on-any-page'),
            'search_items' => __('Search Item', 'add-faq-on-any-page'),
            'not_found' => __('Not found', 'add-faq-on-any-page'),
            'not_found_in_trash' => __('Not found in Trash', 'add-faq-on-any-page'),
        );
        $args = array(
            'label' => __('FAQ Item', 'add-faq-on-any-page'),
            'description' => __('Questions and answers', 'add-faq-on-any-page'),
            'labels' => $labels,
            'supports' => array('title', 'editor',  'revisions'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 25,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
        register_post_type('add_faq_onp', $args);
    }

    
    
    /**
     * Plug-in Shortcode init.
     * @param type $atts
     * @return string
     */
    public function shortcode_add_faq($atts) {

        $args = shortcode_atts(array(
            'cat' => null,
            'category_name' => null,
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => -1,
            'post_type' => 'add_faq_onp'
                ), $atts);
           
        /*global $wp_post_types;
        $obj = $wp_post_types['add_faq_onp'];
        echo "<pre>";
        //print_r($obj);
        echo $obj->description;exit;*/

        $content = "";

        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) :

            $content .= "<div id='add_faq_onp' style='margin-bottom : 16px '>";

            while ($the_query->have_posts()) :
                $the_query->the_post();
                $content .= '<h3>' . get_the_title() . '</h3>';
                $content .= '<div>' . get_the_content() . '</div>';
            endwhile;
            $content .= '</div>';

            wp_enqueue_style('jquery-ui-smoothness-faq');
            wp_enqueue_script('add-faq-js-hadler');
        else :
            $content .= "No FAQ items found";
        endif;

        wp_reset_postdata();

        return $content;
    }
    
    /*public function general_admin_notice(){
    	global $pagenow;
    	print_r($pagenow);
    	if ( $pagenow == 'edit.php?post_type=add_faq_onp' ) {
    		echo '<div class="notice notice-warning is-dismissible">
             <p>This notice appears on the settings page.</p>
         </div>';
    	}
    }*/
}
add_action('admin_footer','print_mynote');
function print_mynote(){
	global $typenow,$pagenow;
	if  (in_array( $pagenow, array( 'edit.php'))  && "add_faq_onp" == $typenow ) {
		?>
        <SCRIPT TYPE="text/javascript">
            jQuery(document).ready(function(){
                var myDiv = jQuery('<div>');
                myDiv.css("border","1px dashed #000000");
                myDiv.css("padding","5px");
                myDiv.css("background","#820000");
                myDiv.css("width","100%");
                myDiv.css("color","#FFEB3B");
                myDiv.css("font-size","17px");
                myDiv.css("font-weight","500");
                myDiv.html("You can use this shortcode to any page [add_faq_onp]");
                jQuery(".wrap h1:first-child").before(myDiv);
            });
        </SCRIPT>
    <?php
    }
}
$addfaq = new Add_faq_on_any_page();
