<?php
    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
    $opt_name = "myCRED_P2P_options";

    $args = array(
        'opt_name'             => $opt_name,
        'disable_tracking' => true,
        'display_name'         => 'myCRED Pay2Publish',
        'display_version'      => '1.0',
        'menu_type'            => 'menu',
        'allow_sub_menu'       => false,
        'menu_title'           => __( 'myCRED Pay to post', 'pay2publish' ),
        'page_title'           => __( 'myCRED Pay to post', 'pay2publish' ),
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        'google_update_weekly' => false,
        'async_typography'     => true,
        'admin_bar'            => true,
        'admin_bar_icon'       => 'dashicons-portfolio',
        'admin_bar_priority'   => 9,
        'global_variable'      => 'myCRED_P2P_options',
        'dev_mode'             => false,
        'update_notice'        => false,
        'customizer'           => false,
        'open_expanded'        => false,
        'disable_save_warn'    => false,
        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        'page_parent'          => 'options-general.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        'menu_icon'            => '',
        'last_tab'             => '',
        'page_icon'            => 'icon-themes',
        'page_slug'            => 'myCRED_pay2post',
        'save_defaults'        => true,
        'default_show'         => false,
        'default_mark'         => '',
        'show_import_export'   => false,
        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => false,
        'output_tag'           => false,
        'footer_credit'     => 'pay2publish',
        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/alejandro.chataing  ',
        'title' => 'Personal Facebook Profile',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/AlejandroChataingcom-English-737390806406310/',
        'title' => 'Community Page in English',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/alejandrochataing.comES',
        'title' => 'Community Page in Spanish',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/achataing',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );
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
            'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );
    /*
     * <--- END HELP TABS
     */
    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
    
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Global Message', 'pay2publish' ),
        'desc'             => __( 'Set the message to display when the user does not have enough funds', 'pay2publish' ),
        'id'               => 'global_message',
        'subsection'       => true,
        'customizer_width' => '700px',
        'fields'           => array(
            array(
                'id'       => 'global_message_editor',
                'type'     => 'editor',
                'title'    => __( 'Message when no funds', 'pay2publish' ),
                'subtitle' => __( '', 'pay2publish' ),
                'desc'     => __( 'Set the message to display when the user does not have enough funds', 'pay2publish' ),
                'default'  => __( 'You do not have enought credits to create a new publication of that Post Type', 'pay2publish' ),
                'validate_callback' => array( 'myCRED_P2P_Main', 'validation_callback' ),
            ),
            array( 
                'id'       => 'opt-raw',
                'type'     => 'raw',
                'title'    => __('Support', 'pay2publish'),
                'subtitle' => __('If you like this plugin you can support it by doing the following:.', 'pay2publish'),
                'desc'     => __('Rate, follow or donate... will not ask for more', 'pay2publish'),
                'content'  => file_get_contents(P2PADDON_ROOT_DIR . 'myfile.txt'),
            ),
        )
    ) );
    Redux::init($opt_name);
    /*
     * <--- END SECTIONS
     */