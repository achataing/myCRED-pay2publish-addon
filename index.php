<?php
/**
 * Plugin Name: myCRED Pay to Publish Addon
 * Description: This plugin is an addon for myCRED plugin. It allows you to charge for Publishing Pages, Post, Custom Post Types both in backend and frontend (you must wrap your form using the shortcode [p2p_price]). Requires myCRED version 1.4 and above
 * Version: 1.0.0
 * Author: Alejandro Chataing
 * Author URI: http://alejandrochataing.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
/*  Copyright 2015 Alejandro Chataing  (email : administrator@alejandrochataing.com | chataing.alejandro@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Addon: Pay to Publish
 * Addon URI: http://alejandrochataing.com
 * Version: 1.0.1
 * Description: Non-official addon Pay to publish Post/Pages using myCRED
 * Author: Alejandro Chataing
 * Author URI: http://alejandrochataing.com
 */
define( 'myCRED_P2P',                   __FILE__ );
define( 'myCRED_P2P_VERSION',           myCRED_VERSION . '.1' );
define( 'P2PADDON_PLUGIN_VERSION',      '1.0.0' );
define( 'P2PADDON_myCRED_ROOT_DIR',     ABSPATH . '/wp-content/plugins/mycred/' );

define( 'P2PADDON_ROOT_DIR',            plugin_dir_path( myCRED_P2P ) );
define( 'P2PADDON_ADMIN_DIR',           P2PADDON_ROOT_DIR . 'admin/' );
define( 'P2PADDON_REQUIRE_DIR',         P2PADDON_ROOT_DIR . 'required-plugins/' );

define( 'P2PADDON_FRAMEWORK_ROOT_DIR',  ABSPATH . '/wp-content/plugins/redux-framework/ReduxCore/' );
require_once( P2PADDON_ADMIN_DIR . 'admin-init.php' );
if ( file_exists(P2PADDON_myCRED_ROOT_DIR . 'mycred.php'))
    require_once P2PADDON_myCRED_ROOT_DIR . 'mycred.php';
/**
* Check if myCRED and Redux Frame work plugins are installed and activated
*/
require_once P2PADDON_REQUIRE_DIR . 'class-tgm-plugin-activation.php'; //loads required plugins
$_SERVER['REMOTE_ADDR'] = "local"; //Disables Debug mode for redux lccalhost

add_action('tgmpa_register', 'p2p_required_plugins');
function p2p_required_plugins(){
    $plugins = array(
        array(
            'name'      => 'myCRED',
            'slug'      => 'mycred',
            'required'  => true
        ),
    );
    $config = array(
        'id'            => 'tgmpa_myCRED_PTP',      // Unique ID for hashing notices for multiple instances of TGMPA.                
        'menu'          => 'p2pOptions-install-plugins',   // Menu slug.
        'parent_slug'   => 'plugins.php',           // Parent menu slug.
        'capability'    => 'manage network plugins',// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'is_automatic'  => true,                    // Automatically activate plugins after installation or not.
        'has_notices'   => true,                    // Show admin notices or not.
        'dismissable'   => false,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'   => __('Thanks for using Pay 2 Publish addon for myCRED. The following plugins are required for it to function properly','mycred_p2p'),     // If 'dismissable' is false, this message will be output at top of nag.
    );
    tgmpa( $plugins, $config );
}

//add_action( 'activated_plugin', 'p2p_activation_redirect' );            
function p2p_activation_redirect( $plugin ) {
    if ( is_plugin_active( 'mycred/mycred.php' ) && is_plugin_active( 'redux-framework/redux-framework.php' ) ) {
        exit( wp_redirect( admin_url( 'options-general.php?page=myCRED_pay2post' ) ) );

    }
}

/**
 * myCRED_P2P_Main class|
 * @since 1.6.6
 * @version 1.0.1
 * @author Alejandro Chataing
 */
add_action('mycred_pre_init', 'load_P2P_module',199);
function load_P2P_module(){
    // Make sure the module class is available
    if ( ! class_exists( 'myCRED_Module' ) ) return;
    
    // Our custom class
    if ( !class_exists( 'myCRED_P2P_Main' ) ) {
        class myCRED_P2P_Main extends myCRED_Module {
            protected $_MU;
            
            public $current_message, $current_pt, $current_option, $mycred = array (), $mycredTypes = array();
            
                
            public function __construct( $point_type = 'mycred_default' ) {
                global $mycred_types;
                $this->current_pt = $point_type;
                if ($point_type == 'mycred_default'){
                    $this->current_option = 'myCRED_' . $point_type . '_pay2publish';
                }else{
                    $this->current_option = $point_type . '_pay2publish';
                }
                foreach ($mycred_types as $key => $value) {
                    $this->mycred[ $key ] = mycred( $key );
                }
                $this->mycredTypes[$this->counter] = $this->current_pt;
                $this->counter++;
                
                if (function_exists('is_multisite') && is_multisite())
                    $this->_MU = true;
                else
                    $this->_MU = false;
                
                parent::__construct( $point_type . '_pay2publish', array(
                    'module_name' => $point_type . '_pay2publish',
                    'labels'      => array(
                        'menu'        => __( $point_type . '_pay2publish', 'mycred' ),
                        'page_title'  => __( 'Pay 2 publish ', 'mycred' )
                    ),
                    'register'    => false,
                    'screen_id'   => $point_type . '_pay2publish',
                    'cap'         => 'plugin',
                    'menu_pos'    => 15
                 ), $point_type );
                //myCRED Module methods
                //hook actions and filters
                $this->p2p_hooks();
                //add shortcodes
                $this->p2p_addShortCodes();
            }

            /*
             * tests to be displayed in the footer
             */
            public function p2p_tests($user_id = null,$type = null,$use_m = true){
                
                
            }            
            
            public function admin_page(){
                ?>
                <div class="wrap">
                    <div id="icon-options-general" class="icon32"></div> <h2><?php _e('Post Creation Limits'); ?> 
                    <a title="add a new price rule" class="add-new-h2"><?php _e('Add New Price'); ?></a></h2><br/><br/>
                    <form method="post" action="options.php">
                    <style>#TB_ajaxContent{height: 420px !important;}</style>
                    <?php 
                    $screen = get_current_screen();

                    if ($screen->parent_base == 'myCRED'){
                        settings_fields('myCRED_mycred_default');
                        $options = get_option('myCRED_mycred_default_pay2publish');
                        $this->current_pt = 'mycred_default';
                    }  
                    else{
                        settings_fields($screen->parent_base);
                        $options = get_option($this->current_option);
                        $this->current_pt = str_replace('_pay2publish','',$this->current_option);
                    }
                    $price = 'Price in ' . $this->mycred[$this->current_pt]->plural();
                    ?>
                        <div id="list_limits">
                            <table id="limit_rules" class="widefat">
                                <thead>
                                    <tr>
                                        <th><?php _e('Post Type'); ?></th>
                                        <th><?php _e($price); ?></th>
                                        <th><?php _e('myCRED Point Type'); ?></th>
                                        <th><?php _e('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th><?php _e('Post Type'); ?></th>
                                        <th><?php _e($price); ?></th>
                                        <th><?php _e('myCRED Point Type'); ?></th>
                                        <th><?php _e('Actions'); ?></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php

                                $c = 0;
                                
                                if (isset($options[$this->current_option]['rules'])){
                                    foreach($options[$this->current_option]['rules'] as $k => $v){
                                        echo '<tr>';                                       
                                        echo '<td>' . $v['post_type'] . '<input type="hidden" name="' . $this->current_option . '[' . $this->current_option . '][rules][' . $c . '][post_type]" value="' . $v['post_type'] . '"></td>';
                                        echo '<td>' . $v['price'] . '<input type="hidden" name="' . $this->current_option. '[' . $this->current_option . '][rules][' . $c . '][price]" value="' . $v['price'] . '"</td>';
                                        echo '<td>' . $v['mycred_type'] . '<input type="hidden" name="'. $this->current_option .'[' . $this->current_option . '][rules][' . $c . '][mycred_type]" value="' . $v['mycred_type'] . '"></td>';
                                        echo '<td><span class="edit_rule button-primary">Edit</span> <span class="remove_rule button-primary">Remove</span></td>';
                                        echo '</tr>';
                                        $c++;
                                    }
                                }                                 
                                                                    
                                ?>
                                </tbody>
                            </table>			
                        </div>

                        <?php 
                            //TODO: move to an external file 
                            
                        ?>
                        <script type="text/javascript">
                        var counter = <?php echo $c; ?>;
                        var curr_row;

                        function res_form(){
                            jQuery('#pt').val('');
                            jQuery('#prc').val('');
                            jQuery('#rule_count').val('');
                            jQuery('.save_edit').removeClass('save_edit').addClass('new_rule');
                            jQuery('.new_rule').val('Add');
                        }

                        function add_new(){
                            counter++;
                            var tr = jQuery('<tr>');
                            v1 = jQuery('#pt').val();
                            v2 = jQuery('#prc').val();
                            v3 = jQuery('#mycred_pt').val();
                            tr.append('<td>'+ v1 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][post_type]" value="'+v1+'"></td>'); //<th>Post Type</th>
                            tr.append('<td>'+ v2 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][price]" value="'+v2+'"></td>'); //<th>Price</th>
                            tr.append('<td>'+ v3 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][mycred_type]" value="'+v3+'"></td>'); //<th>myCRED Pt</th>
                            tr.append('<td><span class="edit_rule button-primary">Edit</span> <span class="remove_rule button-primary">Remove</span></td>'); //<th>Actions</th>
                            jQuery('#limit_rules').find('tbody').append(tr);
                            res_form();
                            tb_remove();
                        }

                        //load edit
                        function pre_edit_form(){
                            res_form();
                            v = new Array();
                            jQuery('.new_rule').val('Save Edit');
                            jQuery('.new_rule').removeClass('new_rule').addClass('save_edit');
                            jQuery(curr_row).children().each(function(index, value) { 
                                    if ( index <= 3){
                                            if (jQuery(value).find('input').length)
                                                    v[index] = jQuery(value).find('input').val();
                                    }
                            });
                            jQuery('#pt').val(v[0]);
                            jQuery('#prc').val(v[1]);
                            jQuery('#mycred_pt').val(v[2]);
                            tb_show('Edit Limit Rule','TB_inline?height=420&width=400&inlineId=d_e_f');

                        }

                        //save edit function
                        function r_save_edit(){
                            var tr = jQuery('<tr>');
                            v1 = jQuery('#pt').val();
                            v2 = jQuery('#prc').val();
                            v3 = jQuery('#mycred_pt').val();
                            tr.append('<td>'+ v1 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][post_type]" value="'+v1+'"></td>'); //<th>Post Type</th>
                            tr.append('<td>'+ v2 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][price]" value="'+v2+'"></td>'); //<th>Limit</th>
                            tr.append('<td>'+ v3 +' <input type="hidden" name="<?php echo $this->current_option; ?>[<?php echo $this->current_option; ?>][rules][' + counter + '][mycred_type]" value="'+v3+'"></td>'); //<th>Mycred Type Pt</th>
                            tr.append('<td><span class="edit_rule button-primary">Edit</span> <span class="remove_rule button-primary">Remove</span></td>'); //<th>Actions</th>
                            jQuery(curr_row).remove();
                            jQuery('#limit_rules').find('tbody').append(tr);
                            res_form();
                            tb_remove();
                        }

                        //htmlentities
                        function html_entities(str){
                            encoded = jQuery('<div />').text(str).html();
                            return encoded;
                        }
                        function isNumeric(n) {
                            return !isNaN(parseFloat(n)) && isFinite(n);
                        }
                        //add new rule
                        jQuery(document).on('click','.new_rule',function(e){
                            e.preventDefault();
                            add_new();
                            return false;
                        });
                        //remove rule
                        jQuery(document).on('click','.remove_rule',function(e){
                            e.preventDefault();
                            jQuery(this).parent().parent().remove();
                            return false;
                        });
                        //row edit
                        jQuery(document).on('click','.edit_rule',function(e){
                            e.preventDefault();
                            curr_row = jQuery(this).parent().parent()
                            pre_edit_form();
                        });
                        //save edit
                        jQuery(document).on('click','.save_edit',function(e){
                            e.preventDefault();
                            r_save_edit();
                            return false;
                        });

                        //add new button-primary
                        jQuery(document).on('click','.add-new-h2',function(){
                            res_form();
                            tb_show('add a new price rule','TB_inline?height=420&width=400&inlineId=d_e_f');
                        });
                        </script>
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                        </p>			
                    </form>

                    <div id="d_e_f" style="display: none">
                        <br/>
                        <form action="limit" id="limit_form" method="post" name="limiter">
                            <ul>
                                <li>
                                    <label for="ptype"><?php _e('Post Type'); ?></label>
                                    <select name="ptype" id="pt">
                                        <option value="any">Any</option>
                                        <?php
                                            $post_types=get_post_types('','names'); 
                                            foreach ($post_types as $post_type ) {
                                                ?>
                                                <option value="<?php echo $post_type;?>"><?php echo $post_type;?></option>
                                                <?php
                                            }
                                        ?>
                                    </select><br/>
                                    <span class="dsc"><small><?php _e('Select a Post Type'); ?></small></span>
                                </li>
                                <li>
                                    <label for="price"><?php _e($price); ?></label>
                                    <input name="price" type="text" id="prc" size="4"/><br/>
                                    <span class="dsc"><small><?php _e('Enter Price'); ?></small></span>
                                </li>
                                <input name="mycred_pt" type="hidden" id="mycred_pt" value="<?php echo $this->current_pt; ?>" size="10"/><br/>
                                <input type="hidden" id="rule_count" value=""/>
                            </ul>
                            <input type='submit' value='<?php _e('add'); ?>' class='button-primary new_rule' />
                        </form>
                    </div>
                </div>
                <?php
            }

            //register options api
            public function p2p_wpsnfl_init(){
                global $mycred_types;
                foreach ($mycred_types as $key => $value){
                    if ($key == 'mycred_default'){
                        register_setting( 'myCRED_mycred_default', 'myCRED_mycred_default_pay2publish', array( $this, 'validation_callback' ));
                    }                        
                    else{
                        register_setting( 'myCRED_' . $key, $key . '_pay2publish', array( $this, 'validation_callback' ));
                    }
                         
                    $this->p2p_getOptions();
                }                
            }
            
            /**
            * Recursive function that parses the general template tags to the settings prior to sending them to the data base
            *
            * @param array $arr Array to process
            * @param bool $done returns the changes once it finds the template tags
            */
            function validation_callback($arr, $mycred_type = 'mycred_default', $done = false ){
                global $mycred_types;
                //check if $arr is an array
                if (is_array($arr)) {
                    foreach ($arr as $key => $value) {
                        if (is_array($key)) { //check if the key is an array
                                $arr[$key] = $this->validation_callback($key); //since it is, we need to go further down the nest
                        } else {//its not an array so this is the last item nested item
                            foreach ($mycred_types as $k => $v){
                                if ( $value === $k ){
                                    $arr['message'] = $this->validation_callback($arr['message'], $value,true);
                                }
                            }
                            $arr[$key] = $this->validation_callback($value, true);
                        }                        
                    }
                } elseif ($done){ //applies the changes
                    /*
                     * $this->mycred[$this->current_pt]->template_tags_general( $arr );
                     * should work when it reads %plural% or %singular% for all point types
                     * however, this function seems to be working only for the mycred_default
                     * point type... Even if the type of the object is different:
                     * 
                     */
                    return $this->mycred[ $mycred_type ]->template_tags_general( $arr );
                }
                return $arr;                
            }
            
            /**
             * p2p_hasCREDS this is the money function which checks the user balance vs the post_type's price
             * @since 2.5
             * @param  int  $user_id 
             * @param  strin  $type    post type
             * @param  boolean $use_m   use shortcode message flag
             * @return true to limit false to allow
             */            
            public function p2p_hasCREDS( $user_id = null, $ptype = null, $message = '' ){   
                
                global $mycred_types, $myCRED_P2P_options;
                $can_publish = false;
                $options = array();
                $balance = array();
                //$rule = array();

                foreach ( $mycred_types as $key => $value ) {
                    if ( $key === 'mycred_default' ){
                        $local_option = 'myCRED_' . $key . '_pay2publish';
                    } else {
                        $local_option = $key . '_pay2publish';
                    }
                    $options[] = get_option( $local_option );
                    $balance[] = $this->mycred[ $key ]->get_users_balance( $user_id );
                }

                if ( empty ( $options ) ){
                    return true;
                }
                //print_r($options);
                if ($user_id == null){
                        global $current_user;
                        get_currentuserinfo();
                        $user_id = $current_user->ID;
                        if ($user_id <= 0)
                            return false;
                }

                if ($ptype == null){
                        global $typenow;
                        $ptype = isset($typenow)? $typenow: 'post';
                }

                if ($this->_MU) { 
                        if ( current_user_can('manage_network') )
                                return true;
                }elseif( current_user_can('manage_options') ){
                        return true;
                }
                $options = array_filter($options);
                $options = array_values($options);
                $rule = $this->p2p_find_post_type( $ptype, $options );
                $rule = array_filter($rule);
                $rule = array_values($rule);
                if ( ! empty ( $rule ) ){
                    for ( $i = 0; $i < count($rule); $i++ ){
                        if ( isset( $rule[ $i ] ) ){
                            if ( $balance[ $i ] >= $rule[ $i ][0] ){
                                $has_creds[ $i ] = 1;
                            } else {
                                $has_creds[ $i ] = 0;
                            }
                        } else {
                            return true;
                        }
                    }

                    for ( $i = 0; $i < count($has_creds); $i++ ){
                        if ( ! $has_creds[ $i ] ){
                            if ( $message != '' ){
                                $this->current_message = $message;
                            }
                            $can_publish = false;
                            break;
                        } else {
                            $can_publish = true;
                        }
                    }
                    
                    if ( $message != '' ){
                        $this->current_message = $message;
                    }
                    return $can_publish;                    
                }
                return $can_publish;
            }

            //plugin settings and defaults
            public function p2p_getOptions() {	
                $getOptions = get_option($this->current_option);
                if (empty($getOptions)) {
                    if ($this->_MU)
                    $getOptions = get_site_option('mycred_default_pay2publish');
                }

                if (is_main_site())
                    update_site_option($this->current_option, $getOptions);
                
                return $getOptions;
            }
            
            /**
             * p2p_addShortCodes registers plugin shortcodes
             * @since 3.0
            */
            public function p2p_addShortCodes(){
                add_shortcode('p2p_price', array( $this, 'p2p_price_shortcode_handler' ) );
            }

            /**
             * p2p_limit_xml_rpc limit xml-rpc user
             * @since 2.5
             * @param  boolean $maybe  
             * @param  array $postarr
             * @return true to limit false to allow
             */
            public function p2p_limit_xml_rpc($maybe,$postarr = array()){
                //exit early if not xmlrpc request
                if (!defined('XMLRPC_REQUEST') ||  XMLRPC_REQUEST != true)
                    return $maybe;

                if (isset($postarr['post_post_type']) &&  isset($postarr['post_author']) && ! $this->p2p_hasCREDS($postarr['post_author'],$postarr['post_type']))
                    return apply_filters('p2p_xml_rpc_limit',true);

                return $maybe;
            }

            /**
             * p2p_price_shortcode_handler 
             * @since 2.4
             * @param  array $atts 
             * @param  string $content 
             * @return string
             */
            public function p2p_price_shortcode_handler( $atts, $content = NULL ){
                
                $a = shortcode_atts( 
                    array(
                        'message'   => '',
                        'type'      => 'post',
                    ), $atts, 'p2p_price' );
                
                //echo "hi there: " . $atts[ 'message' ];
                global $myCRED_P2P_options, $current_user;
                
                if ( ! is_user_logged_in() )
                    return apply_filters( 'p2p_shortcode_not_logged_in' , $a[ 'm' ] );

                get_currentuserinfo();
                if ( $this->_MU ) { 
                    if ( current_user_can( 'manage_network' ) )
                            return apply_filters( 'p2p_shortcode_network_admin' , do_shortcode( $content ) );
                }elseif( current_user_can( 'manage_options' ) ){
                    return apply_filters( 'p2p_shortcode_admin' , do_shortcode( $content ) );
                }

                if ( ! $this->p2p_hasCREDS( $current_user->ID, $a[ 'type' ], $a[ 'message' ] ) ){
                    if ( $a[ 'message' ] != '' ){
                        return apply_filters( 'p2p_shortcode_limited', $this->current_message );
                    } else{
                        return apply_filters( 'p2p_shortcode_limited', $myCRED_P2P_options[ 'p2p_globalmessage_editor' ] );
                    }
                }

                //all good return the content
                return apply_filters( 'p2p_shortcode_ok', do_shortcode( $content ) );
            }

            public function p2p_remove_add_new(){
                global $pagenow, $current_user, $typenow;
                if ( is_admin() && $pagenow == 'edit.php' ){
                    get_currentuserinfo();
                    if ( ! $this->p2p_hasCREDS( $current_user->ID, $typenow ) ){
                        $this->p2p_not_allowed_remove_links();
                    }
                }
            }

            public function p2p_not_allowed_remove_links(){
                add_action('admin_footer',array($this,'p2p_hide_links'));
            }

            //remove links
            public function p2p_hide_links(){
                global $typenow;
                if ('post' == $typenow)
                    $href='post-new.php';
                else
                    $href='post-new.php?post_type='.$typenow;
                ?>
                <script>
                    jQuery(document).ready(function() {
                        jQuery('.add-new-h2').remove();
                        jQuery('[href$="<?php echo $href;?>"]').remove();
                    });
                </script>
                <?php
            }

            //limit post type count per user 
            public function p2p_limit_post_count(){
                global $pagenow ,$current_user,$typenow;			
                if ( is_admin() && in_array( $pagenow, array( 'post-new.php', 'press-this.php' ) ) ){
                    //$options = $this->p2p_getOptions($this->current_option);
                    //if (!isset($options[$this->current_option]['rules']))
                    //        return;
                    get_currentuserinfo();
                    if ( $this->_MU ) { 
                        if ( current_user_can( 'manage_network' ) )
                            return;
                    } elseif ( current_user_can( 'manage_options' ) ){
                        return;
                    }
                    if ( ! $this->p2p_hasCREDS( $current_user->ID, $typenow ) ){
                        $this->p2p_not_allowed( $this->current_message );
                        exit;
                    }
                    do_action( 'post_creation_limits_custom_checks', $typenow, $current_user->ID );
                }
            }

            // display error massage
            public function p2p_not_allowed($m=null){
                global $myCRED_P2P_options;

                do_action('p2p_before_globalmessage_filter');
                if ($m == null){
                    $m = $myCRED_P2P_options['p2p_globalmessage_editor'];
                }
                ?>
                <style>
                    html {background: #f9f9f9;}
                    #error-page {margin-top: 50px;}
                    #error-page p {font-size: 14px;line-height: 1.5;margin: 25px 0 20px;}
                    #error-page code {font-family: Consolas, Monaco, monospace;}
                    body {background: #fff;color: #333;font-family: sans-serif;margin: 2em auto;padding: 1em 2em;-webkit-border-radius: 3px;border-radius: 3px;border: 1px solid #dfdfdf;max-width: 700px;height: auto;}
                </style>
                <div id="error-page">
                    <div id="message" class="<?php echo apply_filters('p2p_globalmessage_class','error'); ?>" style="padding: 10px;"><?php echo apply_filters('p2p_globalmessage_filter',$m); ?></div>
                </div>
                <?php
                do_action('p2p_after_globalmessage_filter');
            }

            /************************
            * helpers
            ************************/
            public function p2p_find_post_type( $elem, $array ){
                $rule = array();
                foreach ($array as &$value){
                    $rule[] = $this->p2p_in_multiarray( $elem, $value );
                }
                return $rule;
            }
            
            public function p2p_in_multiarray( $elem, $array ){
                foreach( $array as $value ){
                    if ( $value == $elem ){
                        return array( $array[ 'price' ], $array[ 'mycred_type' ] );
                    } else {
                        if( is_array( $value ) ){
                            $hi = $this->p2p_in_multiarray( $elem, $value );
                            if ( $hi[0] ){
                                return $hi;
                            }
                        }
                    }
                }
            }
            
            public function in_multiarray($elem, $array){
                $top = sizeof($array) - 1;
                $bottom = 0;
                while($bottom <= $top)
                {
                    
                    if($array[$bottom] == $elem){
                        return true;
                    } else {
                        if(is_array($array[$bottom])){
                            echo $bottom;
                            if( $this->in_multiarray($elem, $array[$bottom]) ){
                                echo "hello world";
                                return true;
                            }
                        }          
                    }
                    $bottom++;
                }       
                return false;
            }
            
            public function p2p_get_sub_array($Arr){
                $new_arr = array();
                foreach((array)$Arr as $k => $v){

                    $new_arr[$k] = $v;
                }
                if (count($new_arr) > 0 )
                    return $new_arr;
                return false;
            }

            function p2p_admin_options_panel(){
                if ( ! class_exists( 'Redux' ) ) {
                    return;
                }             
            }
            
            public function p2p_panel_messages(){
                global $myCRED_P2P_options;

                return apply_filters('p2p_globalmessage_filter',$myCRED_P2P_options['p2p_globalmessage_editor']);
            }
            
            /**
             * Hooks a central location for all action and filter p2p_hooks
             * @since 3.0
             * @return void
             */
            public function p2p_hooks(){
                add_action( 'redux/init', array( $this, 'p2p_admin_options_panel' ));
                add_action('admin_head',array( $this, 'p2p_limit_post_count'));
                add_action('admin_head',array( $this, 'p2p_remove_add_new'));
                add_action('admin_init', array( $this, 'p2p_wpsnfl_init'));
                add_filter('wp_insert_post_empty_content',array( $this,'p2p_limit_xml_rpc'));
                add_filter( 'p2p_custom_message', array( $this, 'p2p_panel_messages' ));
            }
        }

        // Initilize for each point type and add to global
        global $mycred_modules, $mycred_types;

        foreach ( $mycred_types as $type => $label ) {
                $mycred_modules[ $type ][$type . '_p2pmodule'] = new myCRED_P2P_Main( $type );
                $mycred_modules[ $type ][$type . '_p2pmodule']->load();
        }
    }
}

add_action( 'publish_post', 'p2p_myCRED_charge_publish');
function p2p_myCRED_charge_publish(){
    global $post_type, $myCRED_P2P_options, $mycred_types, $post;
    $myCRED_P2P_Main = new myCRED_P2P_Main();
    $counter = 0;
    foreach ( $mycred_types as $key => $value ) {
        if ( $key === 'mycred_default' ){
            $local_option = 'myCRED_' . $key . '_pay2publish';
        } else {
            $local_option = $key . '_pay2publish';
        }
        $options[] = get_option( $local_option );
        $counter++;
    }
    $options = array_filter($options);
    $options = array_values($options);
    $current_user = wp_get_current_user();
    $rule = $myCRED_P2P_Main->p2p_find_post_type($post_type, $options);
    $rule = array_filter($rule);
    $rule = array_values($rule);
    if ( ! $rule ){
    } else {
            for ( $i = 0; $i < count( $rule ); $i++ ){
                if ( $rule[ $i ][ 0 ] > 0){
                    if ( !mycred_exclude_user( $current_user->ID ) ) {
                        $amount = $rule[ $i ][ 0 ];
                        $myCRED_P2P_Main->mycred[$rule[ $i ][ 1 ]]->round_value( $amount, 'up', $precision );
                        mycred_subtract( 'Publish payment', $current_user->ID, $rule[ $i ][ 0 ], 'Deducted for posting a new: ' . $post_type, date( 'W' ), '', $rule[ $i ][ 1 ] );
                    }
                }
            }
    }
}

add_action(  'pending_to_publish', 'p2p_myCRED_charge_pending_publish', 10, 1 );
function p2p_myCRED_charge_pending_publish( $post ){
    global $post_type, $myCRED_P2P_options, $mycred_types;
    $myCRED_P2P_Main = new myCRED_P2P_Main();
    $counter = 0;
    foreach ( $mycred_types as $key => $value ) {
        if ( $key === 'mycred_default' ){
            $local_option = 'myCRED_' . $key . '_pay2publish';
        } else {
            $local_option = $key . '_pay2publish';
        }
        $options[] = get_option( $local_option );
        $counter++;
    }
    $options = array_filter($options);
    $options = array_values($options);
    $post_info = get_post( $post->ID );
    $author = $post_info->post_author;
    $current_user = $author;
    $rule = $myCRED_P2P_Main->p2p_find_post_type($post_info->post_type, $options);
    $rule = array_filter($rule);
    $rule = array_values($rule);
    if ( ! $rule ){
    } else {
        for ( $i = 0; $i < count( $rule ); $i++ ){
            if ( $rule[ $i ][ 0 ] > 0){
                if ( ! mycred_exclude_user( $current_user ) ) {
                    $amount = $rule[ $i ][ 0 ];
                    $myCRED_P2P_Main->mycred[$rule[ $i ][ 1 ]]->round_value( $amount, 'up', $precision );
                    mycred_subtract( 'Publish payment', $current_user, $rule[ $i ][ 0 ], 'Deducted for posting a new: ' . $post_info->post_type, date( 'W' ), '', $rule[ $i ][ 1 ] );

                }
            }
        } 
    }    
}