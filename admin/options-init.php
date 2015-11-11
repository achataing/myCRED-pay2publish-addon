<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "myCRED_P2P_options";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name' => 'myCRED_P2P_options',
        'use_cdn' => TRUE,
        'display_name' => 'Pay 2 publish settings',
        'display_version' => '1.0.1',
        'page_slug' => 'myCRED_pay2post',
        'page_title' => 'myCRED Pay2Publish',
        'update_notice' => false,
        'intro_text' => __('Welcome to Pay2Publish addon for myCRED. At the top-right corner you will find more information regarding the use of this plugin. With this plugin you can set a price for publishing Pages, Post or even Custom Post Types using myCRED credits. You may find a new submenu under each myCRED type called "Pay 2 Publish. In there you can set a price for the different Post types registered in your site. Note: Choosing "ANY" in the drop-down field will override all rules; Choosing the same Post Type in the same myCRED Type will result in unexpected results, make sure you only stablish 1 rule per Post Type (if you have different myCRED Types, you may stablish one rule for that same Post Type in the different myCRED type, this will result in adding an extra condition when charging a user... Click help tab for more details. </br><p>In this panel, you may decide which message is to be displayed to the user in case he/she is not allowed to publish any more of the chosen Post Type (not enough funds)', 'pay2publish'),
        'footer_text' => __('You can find under each myCRED menu, the respective form for setting up a price', 'pay2publish'),
        'footer_credit'     => __('pay2publish by <a href="//alejandrochataing.com"/>Alejandro Chataing</a>', 'pay2publish'),                   // Disable the footer credit of Redux. Please leave if you can help it.
        'menu_type' => 'submenu',
        'menu_title' => 'Pay2Publish Settings',
        'allow_sub_menu' => TRUE,
        'page_parent' => 'options-general.php',
        'page_parent_post_type' => 'myCRED',
        'default_mark' => '*',
        'hints' => array(
            'icon' => 'el el-exclamation-sign',
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
                'style' => 'bootstrap',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'global_variable' => 'myCRED_P2P_options',
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => false,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
        'dev_mode' => false,
        'hide_reset' => TRUE,
        'open_expanded' => TRUE,
        'hide_expand' => TRUE,
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => '//alejandrochataing.com  ',
        'title' => __('My website ', 'pay2publish'),
        'icon'  => 'el el-website'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/alejandro.chataing  ',
        'title' => __('Personal Facebook Profile', 'pay2publish'),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/AlejandroChataingcom-English-737390806406310/',
        'title' => __('Community Page in English', 'pay2publish'),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/alejandrochataing.comES',
        'title' => __('Community Page in Spanish', 'pay2publish'),
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/achataing',
        'title' => __('Follow us on Twitter', 'pay2publish'),
        'icon'  => 'el el-twitter'
    );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'How to setup a price for publishing a post', 'admin_folder' ),
            'content' => __( '<p>Since myCRED allows you to create several types of credits, you must check under the "Mycred" menu for the "Pay 2 Publish" submenu if you have nothing but the default myCRED type. But, if you have more than 1 myCRED Type, then check under each mycred_type menu, and you will there find the "Pay 2 Publish" submenu under each myCRED_Type menu. Click there and then press "Add new price". Pick a post_type (Pages are included) and then select a price and save.</p><p>Note: If you select "Any" from the drop-down field, this rule will override any other rules previously set. Also, if you create 2 rules of the same post type, only the first one will apply', 'admin_folder' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'What happens if I set several rules on the same Post Type?', 'admin_folder' ),
            'content' => __( '<p>If you create 2 or more rules for the same POST type inside the same myCRED Type, then only the first rule of that POST Type for that myCRED POINT Type will apply...<p>But, if you have a rule for the same POST Type in different myCRED Types, then the user will be charged in every single Point Types that have a rule on that particular Post Type</p>', 'admin_folder' )
        ),
        array(
            'id'      => 'redux-help-tab-3',
            'title'   => __( 'How to apply the stablished rules for front-end publishing', 'admin_folder' ),
            'content' => __( '<p>This plugin comes bundled with a shortcode [p2p_price] use it to apply the same rules you have set for front-end publishing as follows: [p2p_price message="Not enough funds" type="post_type"][Your-form-shortcode][/p2p_price]</p><p>Please note that you can override the global message if you decide to use the message attribute. IMPORTANT! since there is no way to know from the front-end which Post Type is being published, it is a must to provide it here manually unless or it will default to post_type="post"</p>', 'admin_folder' )
        ),
        array(
            'id'      => 'redux-help-tab-4',
            'title'   => __( 'What happens if my front-end form sets the post Status to "Pending" instead of publish?', 'admin_folder' ),
            'content' => __( '<p>If that is the case, then the user will not be charged for that post until its status is set to "Publish". IMPORTANT: This plugin is programmed to charge a user when the status goes from "Pending" to "Published" only.... This means, if the status is manually set back to "Pending" and then back to "Published" the user will be charged again!</p>', 'admin_folder' )
        ),
        array(
            'id'      => 'redux-help-tab-5',
            'title'   => __( 'Are there any filters I can use in for my own theme plugin?', 'admin_folder' ),
            'content' => __( '<p>Yes, there are!</p><p>"p2p_before_globalmessage_filter": You can use this filter display a custom message above the globalmessage</p><p>"p2p_globalmessage_filter": You can use this filter display a custom message instead of the globalmessage saved in this panel</p><p>"p2p_after_globalmessage_filter": You can use this filter display a custom message below the globalmessage</p><p>"p2p_globalmessage_class": You can use this filter to add a class to the globalmessage</p>', 'admin_folder' )
        ),
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This plugin was inspired by a client of mine, and it does meet with all of his requirements... However, if you find any bugs or if you are looking forward to adapting this plugin to your site and you need custom modifications, please do not hesitate in contacting me at: </p><p><a href="mailto:chataing.alejandro@gmail.com?Subject=Need%20help%20with%20pay2publish%20plugin" target="_top">chataing.alejandro@gmail.com</a></p><p>Support:</p> <p>-Please <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JRE6UZXBJK5VL">donate</a> to encourage updates, thank you. </p>', 'admin_folder' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     * pay2publish
     */

    Redux::setSection( $opt_name, array(
        'title'  => __( 'Pay 2 Publish', 'pay2publish' ),
        'id'     => 'p2p_pay2publish',
        'desc'   => __( 'In here you can define a message to be displayed to the user whenever he/she has no funds to create a new publication on the given Post Type. (Check the help tab on the upper right corner to get information on how to put a price for each post type)', 'pay2publish' ),
        'icon'   => 'el el-home',
        'fields' => array(
            array(
                'id'       => 'p2p_globalmessage_editor',
                'type'     => 'editor',
                'title'    => __( 'Global message', 'pay2publish' ),
                'subtitle' => __( 'Use any of the features of WordPress editor inside your message!', 'pay2publish' ),
                'default'  => __('You do not have enought credits to create a new publication of this Post Type to buy credits follow this link: <a href="#">Buy</a>', 'pay2publish'),
            ),
            array( 
                'id'       => 'opt-raw',
                'type'     => 'raw',
                'title'    => __('Support', 'pay2publish'),
                'subtitle' => __('If you like this plugin you can support it by doing the following:.', 'pay2publish'),
                'desc'     => __('Rate, follow or donate... will not ask for more', 'pay2publish'),
                'content'  => file_get_contents(P2PADDON_ROOT_DIR . '/admin/myfile.txt'),
            ),
        )
    ) );

    /*
     * <--- END SECTIONS
     */
