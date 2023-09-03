<?php
/*
 *  wpf_admin_function.pgp
 *  This file contains the code to initialize  the commenting on the backend.
 *  Below are the mentioned functions present in the file.
 */

/*
 * This function contains the code to initialize the sidebar and commenting feature on the backend. 
 *
 * @input NULL
 * @return NULL
 */
if ( ! function_exists( 'wpf_comment_button_admin' ) ) {
    function wpf_comment_button_admin() {
        global $wpdb;
        $disable_for_admin  = 0;
        $wpf_current_screen = '';
        if ( is_admin() ) {
            $wpf_current_screen = get_current_screen();
            $wpf_current_screen = $wpf_current_screen->id;
        }
        // STEP 1: Fetching current user information
        $currnet_user_information = wpf_get_current_user_information();
        $current_role             = $currnet_user_information['role'];
        $current_user_name        = $currnet_user_information['display_name'];
        $current_user_id          = $currnet_user_information['user_id'];
        $wpf_website_builder      = get_site_data_by_key( 'wpf_website_developer' );

        if ( $current_user_name == 'Guest' ) {
            $wpf_website_client = get_site_data_by_key( 'wpf_website_client' );
            $wpf_current_role   = 'guest';
            if ( $wpf_website_client ) {
                $wpf_website_client_info = get_userdata( $wpf_website_client );
                if ( $wpf_website_client_info ) {
                    if ( $wpf_website_client_info->display_name == '' ) {
                        $current_user_name = $wpf_website_client_info->user_nicename;
                    } else {
                        $current_user_name = $wpf_website_client_info->display_name;
                    }
                }
            }
        } else {
            $wpf_current_role = wpf_user_type();
        }
	    $current_user_name = addslashes( $current_user_name );

        if ( $wpf_current_role == 'advisor' ) {
            $wpf_tab_permission_user              = get_site_data_by_key( 'wpf_tab_permission_user_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_user_webmaster' ) : 'no';
            $wpf_tab_permission_priority          = get_site_data_by_key( 'wpf_tab_permission_priority_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_user_webmaster' ) : 'no';
            $wpf_tab_permission_status            = get_site_data_by_key( 'wpf_tab_permission_status_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_status_webmaster' ) : 'no';
            $wpf_tab_permission_screenshot        = get_site_data_by_key( 'wpf_tab_permission_screenshot_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_screenshot_webmaster' ) : 'no';
            $wpf_tab_permission_information       = get_site_data_by_key( 'wpf_tab_permission_information_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_information_webmaster' ) : 'no';
            $wpf_tab_permission_delete_task       = get_site_data_by_key( 'wpf_tab_permission_delete_task_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_delete_task_webmaster' ) : 'no';
            $wpf_tab_permission_auto_screenshot   = get_site_data_by_key( 'wpf_tab_auto_screenshot_task_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_auto_screenshot_task_webmaster' ) : 'no';
            $wpf_tab_permission_display_stickers  = get_site_data_by_key( 'wpf_tab_permission_display_stickers_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_stickers_webmaster' ) : 'no';
            $wpf_tab_permission_display_task_id   = get_site_data_by_key( 'wpf_tab_permission_display_task_id_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_task_id_webmaster' ) : 'no';
            $wpf_tab_permission_keyboard_shortcut = get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_webmaster' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_webmaster' ) : 'no';
        } else if ( $wpf_current_role == 'king' ) {
            $wpf_tab_permission_user              = get_site_data_by_key( 'wpf_tab_permission_user_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_user_client' ) : 'no';
            $wpf_tab_permission_priority          = get_site_data_by_key( 'wpf_tab_permission_priority_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_priority_client' ) : 'no';
            $wpf_tab_permission_status            = get_site_data_by_key( 'wpf_tab_permission_status_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_status_client' ) : 'no';
            $wpf_tab_permission_screenshot        = get_site_data_by_key( 'wpf_tab_permission_screenshot_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_screenshot_client' ) : 'no';
            $wpf_tab_permission_information       = get_site_data_by_key( 'wpf_tab_permission_information_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_information_client' ) : 'no';
            $wpf_tab_permission_delete_task       = get_site_data_by_key( 'wpf_tab_permission_delete_task_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_delete_task_client' ) : 'no';
            $wpf_tab_permission_auto_screenshot   = get_site_data_by_key( 'wpf_tab_auto_screenshot_task_client' ) != '' ? get_site_data_by_key( 'wpf_tab_auto_screenshot_task_client' ) : 'no';
            $wpf_tab_permission_display_stickers  = get_site_data_by_key( 'wpf_tab_permission_display_stickers_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_stickers_client' ) : 'no';
            $wpf_tab_permission_display_task_id   = get_site_data_by_key( 'wpf_tab_permission_display_task_id_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_task_id_client' ) : 'no';
            $wpf_tab_permission_keyboard_shortcut = get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_client' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_client' ) : 'no';
        } else if ( $wpf_current_role == 'council' ) {
            $wpf_tab_permission_user              = get_site_data_by_key( 'wpf_tab_permission_user_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_user_others' ) : 'no';
            $wpf_tab_permission_priority          = get_site_data_by_key( 'wpf_tab_permission_priority_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_priority_others' ) : 'no';
            $wpf_tab_permission_status            = get_site_data_by_key( 'wpf_tab_permission_status_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_status_others' ) : 'no';
            $wpf_tab_permission_screenshot        = get_site_data_by_key( 'wpf_tab_permission_screenshot_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_screenshot_others' ) : 'no';
            $wpf_tab_permission_information       = get_site_data_by_key( 'wpf_tab_permission_information_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_information_others' ) : 'no';
            $wpf_tab_permission_delete_task       = get_site_data_by_key( 'wpf_tab_permission_delete_task_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_delete_task_others' ) : 'no';
            $wpf_tab_permission_auto_screenshot   = get_site_data_by_key( 'wpf_tab_auto_screenshot_task_others' ) != '' ? get_site_data_by_key( 'wpf_tab_auto_screenshot_task_others' ) : 'no';
            $wpf_tab_permission_display_stickers  = get_site_data_by_key( 'wpf_tab_permission_display_stickers_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_stickers_others' ) : 'no';
            $wpf_tab_permission_display_task_id   = get_site_data_by_key( 'wpf_tab_permission_display_task_id_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_display_task_id_others' ) : 'no';
            $wpf_tab_permission_keyboard_shortcut = get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_others' ) != '' ? get_site_data_by_key( 'wpf_tab_permission_keyboard_shortcut_others' ) : 'no';
        } else {
            $wpf_tab_permission_user              = 'no';
            $wpf_tab_permission_priority          = 'no';
            $wpf_tab_permission_status            = 'no';
            $wpf_tab_permission_screenshot        = 'yes';
            $wpf_tab_permission_information       = 'yes';
            $wpf_tab_permission_delete_task       = 'no';
            $wpf_tab_permission_auto_screenshot   = 'no';
            $wpf_tab_permission_display_stickers  = 'no';
            $wpf_tab_permission_display_task_id   = 'no';
            $wpf_tab_permission_keyboard_shortcut = 'no'; /* => v2.1.0 */
	    }

        // STEP 3: Fetching current page url for task meta information
        $protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";

        $current_page_id = '';
        if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' && isset( $_GET['vcv-action'] ) && $_GET['vcv-action'] == 'frontend' && isset( $_GET['vcv-source-id'] ) ) {
            $current_page_id = $_GET['vcv-source-id'];
        }
        $current_page_url   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $current_page_title = '';
        $current_option     = get_current_screen();
        if ( ! empty( $current_option ) ) {
            $current_page_title = $current_option->id;
        }

        // STEP 4: Fetching plugin options
        $wpf_disable_for_admin = get_site_data_by_key( 'wpf_disable_for_admin' );
        if ( $wpf_disable_for_admin == 'yes' && $current_role == 'administrator' ) {
            $disable_for_admin = 1;
        } else {
            $disable_for_admin = 0;
        }

        $wpf_show_front_stikers = get_site_data_by_key( 'wpf_show_front_stikers' );
        $wpfb_users             = do_shortcode( '[wpf_user_list_front]' );
        $ajax_url               = admin_url( 'admin-ajax.php' );
        $plugin_url             = WPF_PLUGIN_URL;
        $sound_file             = WPF_PLUGIN_URL . 'images/wpf-screenshot-sound.mp3';
        $wpf_tag_enter_img      = WPF_PLUGIN_URL . 'images/enter.png';
        $wpf_reconnect_icon     = WPF_PLUGIN_URL . 'images/wpf_reconnect.png';
        $bubble_and_db_id       = get_last_task_id(true);
        $comment_count          = $bubble_and_db_id['Dbid'];
        $bubble_comment_count   = $bubble_and_db_id['Bubbleid'];

        // STEP 5: Fetching options for task meta information
        $wpf_powered_class            = '_blank';
        $wpf_powered_by               = get_site_data_by_key( 'wpfeedback_powered_by' );
        $selected_roles               = get_site_data_by_key( 'wpf_selcted_role' );
        $current_user                 = wp_get_current_user();
        $wpf_user_name                = $current_user->display_name;
        $wpf_user_email               = $current_user->user_email;
        $wpf_allow_backend_commenting = get_site_data_by_key( 'wpf_allow_backend_commenting' );
        $selected_roles               = explode( ',', $selected_roles );
        $wpf_powerbylink              = WPF_MAIN_SITE_URL . '/reviews/?website=' . get_bloginfo( 'name' ) . '&email=' . $wpf_user_email . '&nameu=' . $wpf_user_name;
        $wpf_powerbylogo              = get_wpf_logo();
        if ( $wpf_powered_by == 'yes' ) {
            $wpf_powered_class = '_self';
            $wpf_powered_link  = get_site_data_by_key( 'wpf_powered_link' );
            if ( $wpf_powered_link != '' ) {
                $wpf_powerbylink   = $wpf_powered_link;
                $wpf_powered_class = '_blank';
            } else {
                $wpf_powerbylink = "javascript:void(0)";
            }
        }

        // STEP 6: Checking if page builder is active on current page (if so then remove the feature of adding new task)
        $wpf_check_page_builder_active = wpf_check_page_builder_active();
        /*if ( $wpf_check_page_builder_active == 0 ) {
            if ( is_customize_preview() ) {
                $wpf_check_page_builder_active = 1;
            } else {
                $wpf_check_page_builder_active = 0;
            }
        }*/
        if ( class_exists( 'GeoDirectory' ) ) {
            if ( $wpf_current_screen == 'gd_place_page_gd_place-settings' ) {
                $wpf_check_page_builder_active = 1;
            }
        }

        // STEP 7: Initialize the structure of sidebar HTML+PHP
        $wpf_active       = wpf_check_if_enable();
        $is_site_archived = get_site_data_by_key( 'wpf_site_archived' );
        if ( $current_user_id > 0 ) {
            $sidebar_col = "wpf_col3";
        } else {
            $sidebar_col = "wpf_col2";
        }

        $wpf_check_atarim_server = get_option( 'atarim_server_down_check' );
        $unix_time_now           = time();
        if ( $unix_time_now > $wpf_check_atarim_server ) {
            update_option( 'atarim_server_down', 'false', 'no' );
        }

        $atarim_server_down = get_option( 'atarim_server_down' );
        $restrict_plugin    = get_option( 'restrict_plugin' );
        $wpf_nonce          = wpf_wp_create_nonce();
        echo "<script>var wpf_tab_permission_display_stickers = '$wpf_tab_permission_display_stickers', wpf_tab_permission_display_task_id = '$wpf_tab_permission_display_task_id';</script>";
        require_once( WPF_PLUGIN_DIR . 'inc/wpf_popup_string.php' );
        if ( $wpf_active == 1 && $wpf_check_page_builder_active == 0 && $wpf_allow_backend_commenting != 'yes' && $wpf_current_screen != 'settings_page_menu_editor' && $wpf_current_screen != 'collaborate_page_wpfeedback_page_settings' && ( ! $is_site_archived ) ) {
            echo "<script>var wpf_reconnect_icon = '$wpf_reconnect_icon', wpf_tag_enter_img = '$wpf_tag_enter_img', disable_for_admin = '$disable_for_admin', wpf_nonce = '$wpf_nonce', wpf_current_screen = '$wpf_current_screen', current_role = '$current_role', wpf_current_role = '$wpf_current_role', current_user_name = '$current_user_name', current_user_id = '$current_user_id', wpf_website_builder = '$wpf_website_builder', wpfb_users = '$wpfb_users',  ajaxurl = '$ajax_url', current_page_url = '$current_page_url', current_page_title = '$current_page_title', current_page_id = '$current_page_id', wpf_screenshot_sound = '$sound_file', plugin_url = '$plugin_url', comment_count = '$comment_count', bubble_comment_count = '$bubble_comment_count', wpf_show_front_stikers = '$wpf_show_front_stikers', wpf_tab_permission_user = '$wpf_tab_permission_user', wpf_tab_permission_priority = '$wpf_tab_permission_priority', wpf_tab_permission_status = '$wpf_tab_permission_status', wpf_tab_permission_screenshot = '$wpf_tab_permission_screenshot', wpf_tab_permission_information = '$wpf_tab_permission_information', wpf_tab_permission_delete_task = '$wpf_tab_permission_delete_task', wpf_tab_permission_auto_screenshot = '$wpf_tab_permission_auto_screenshot', wpf_tab_permission_keyboard_shortcut = '$wpf_tab_permission_keyboard_shortcut', wpf_admin_bar = 1, restrict_plugin = '$restrict_plugin', atarim_server_down = '$atarim_server_down';</script>";
            if ( $disable_for_admin == 0 ) {
                $wpf_site_id         = get_option( 'wpf_site_id' );
                $bottom_panel_db     = get_option( 'bottom_panel', 1 );
                $bottom_button_style = 'bottom: -67px;';
                if ( isset( $bottom_panel_db ) && $bottom_panel_db == '0' ) {
                    $bottom_button_style = 'bottom: 3px;';
                }

                /* ================filter Tabs Content HTML================ */
                $wpf_task_status_filter_btn   = '<div id="wpf_filter_taskstatus" class=""><label class="wpf_filter_title">' . get_wpf_status_icon() . ' ' . __( 'Filter by Status:', 'atarim-visual-collaboration' ) . '</label>' . wp_feedback_get_texonomy_filter( "task_status" ) . '</div>';
                $wpf_task_priority_filter_btn = '<div id="wpf_filter_taskpriority" class=""><label class="wpf_filter_title">' . get_wpf_priority_icon() . ' ' . __( "Filter by Priority:", 'atarim-visual-collaboration' ) . '</label>' . wp_feedback_get_texonomy_filter( "task_priority" ) . '</div>';

                /* If compact mode enabled, load it and don't load the bottom bar => v2.1.0 */
                $enable_compact_mode = get_site_data_by_key( 'wpf_enabled_compact_mode' );
                if ( $enable_compact_mode !== 'yes' && is_feature_enabled( 'bottom_bar_enabled' ) ) {
                    $bottom_bar_html = '<div id="pushed_to_media" class="wpf_hide"><div class="wpf_notice_title">' . __( "Pushed to Media Folder.", 'atarim-visual-collaboration' ) . '</div><div class="wpf_notice_text">' . __( "The file was added to the website's media folder, you can now use it from the there.", 'atarim-visual-collaboration' ) . '</div></div>';
                    $bottom_bar_html .= '<div id="wpf_already_comment" class="wpf_hide"><div class="wpf_notice_title">' . __( "Task already exist for this element.", 'atarim-visual-collaboration' ) . '</div><div class="wpf_notice_text">' . __( "Write your message in the existing thread. <br>Here, we opened it for you.", 'atarim-visual-collaboration' ) . '</div></div>';
                    $bottom_bar_html .= '<div id="wpf_reconnecting_task" class="wpf_hide" style="display: none;"><div class="wpf_notice_title">' . __( "Remapping task....", 'atarim-visual-collaboration' ) . '</div><div class="wpf_notice_text">' . __( "Give it a few seconds. <br>Then, refresh the page to see the task in the new position.", 'atarim-visual-collaboration' ) . '</div></div>';
                    $bottom_bar_html .= '<div id="wpf_reconnecting_enabled" class="wpf_hide" style="display: none;"><div class="wpf_notice_title">' . __( "Remap task", 'atarim-visual-collaboration' ) . '</div><div class="wpf_notice_text">' . __( "Place the task anywhere on the page to pinpoint the location of the request.", 'atarim-visual-collaboration' ) . '</div></div>';
                    $bottom_bar_html .= '<div id="wpf_launcher" data-html2canvas-ignore="true">
                                            <div class="wpf_launch_buttons" style="' . $bottom_button_style . '"><div class="wpf_start_comment"><a href="javascript:enable_comment();" title="' . __( 'Click to give your feedback!', 'atarim-visual-collaboration' ) . '" data-placement="left" class="comment_btn" id="wpf_enable_comment_btn"><i class="gg-math-plus"></i></a></div><div class="wpf_expand"><a href="javascript:expand_bottom_bar()" id="wpf_expand_btn" title="Panel"><img title="Panel" src="' . get_wpf_favicon() . '" alt="icon" style="width:65px" /></a></div></div>
                                            <div class="wpf_sidebar_container" style="opacity: 0; margin-right: -380px";>
                                                <div class="wpf_sidebar_header">
                                                <!-- =================Top Tabs================-->
                                                    <div class="top_tabs">
                                                        <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab_admin(\'wpf_thispage\')" >' . __( 'This Page', 'atarim-visual-collaboration' ) . '</button>
                                                        <button class="wpf_tab_sidebar wpf_allpages"  onclick="openWPFTab_admin(\'wpf_allpages\')" >' . __( 'All Pages', 'atarim-visual-collaboration' ) . '</button>
                                                        <button class="wpf_tab_sidebar wpf_frontend"  onclick="openWPFTab_admin(\'wpf_frontend\')" >' . __( 'Frontend', 'atarim-visual-collaboration' ) . '</button>
                                                        <span id="close_sidebar" class="close_sidebar" onclick="expand_sidebar()"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 357 357" enable-background="new 0 0 357 357" xml:space="preserve"><g><g id="close"><polygon fill="#F5325C" points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 214.2,178.5 "/></g></g></svg></span>
                                                    </div>
                                                    <div id="sidebar_filters">
                                                        <ul class="icons-block">
                                                            <li class="status"> 
                                                                <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="' . __( "Filter By Status", 'atarim-visual-collaboration' ) . '" id="wpf_filter_btn_bottom_status">' . get_wpf_status_icon() . '
                                                                </a>
                                                            </li>
                                                            <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="Filter By Priority">' . get_wpf_priority_icon() . '</a></li>
                                                            <li class="search">
                                                                <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="' . __( "Search by task title", 'atarim-visual-collaboration' ) . '">                            
                                                                    <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                        <g id="surface1">
                                                                            <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                        </g>
                                                                    </svg>
                                                                </a>
                                                            </li> 
                                                        </ul>                          
                                                    </div>
                                                </div>
                                                <div class="filter_ui_content">
                                                    <div id="wpf_side_filter">
                                                        <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
                                                        <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
                                                        <div class="wpf_list wpf_hide" id="wpf_search_filter_btn"><div id="sidebar_search" class="wpf_search_box"><span class="wpf_search_box">
                                                            <svg onclick="hide_search_from_sidebar()" class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                <g id="surface1">
                                                                    <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                </g>
                                                            </svg>
                                                            <input onkeyup="wp_feedback_cat_filter(event, this)" type="text" name="wpf_search_title" class="wpf_search_title" value="" id="wpf_search_title" placeholder="' . __( "Search by task title", 'atarim-visual-collaboration' ) . '"></span></div>
                                                        </div>
                                                    </div>                        
                                                </div> 
                                                <div class="wpf_sidebar_content">
                                                    <div class="wpf_sidebar_loader wpf_hide"></div>
                                                    <div id="wpf_thispage" class="wpf_thispage_tab wpf_container wpf_active_filter"><!--<div class="custom_today">today</div>--><ul id="wpf_thispage_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_thispage_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_thispage_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_thispage_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_thispage_container_year"></ul><!-- <div class="custom_other">Other</div> --> <ul id="wpf_thispage_container_other"></ul></div>
                                                    <div id="wpf_allpages" class="wpf_allpages_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_allpages_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_allpages_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_allpages_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_allpages_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_allpages_container_year"></ul><!-- <div class="custom_other">Other</div> --> <ul id="wpf_allpages_container_other"></ul></div>
                                                    <div class="wpf_loading" style="margin-bottom: 30px">Loading...</div>
                                                    <div id="wpf_frontend" class="wpf_frontend_container wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_frontend_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_frontend_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_frontend_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_frontend_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_frontend_container_year"></ul><!-- <div class="custom_year">Other</div> --> <ul id="wpf_frontend_container_other"></ul></div>
                                                </div>
                                            </div>' . generate_bottom_part_html() . avc_menubar() .'
                                        </div>';
                } else {
                    $bottom_bar_html = '<div id="wpf_launcher" class="wpf-compact-launcher" data-html2canvas-ignore="true" style="user-select: auto;">                                        
                                            <div id="wpf_launch_buttons_wrapper">
                                                <div class="wpf_launch_buttons" style="user-select: auto; left: -56px;">     
                                                <div class="wpf_start_comment" style="user-select: auto;">
                                                    <a href="javascript:enable_comment();" title="Click to leave a comment" data-placement="left" class="comment_btn" style="user-select: auto; cursor: pointer;">
                                                        <i class="gg-math-plus" style="user-select: auto;"></i>
                                                    </a>
                                                </div>
                                                <div class="wpf_expand" style="user-select: auto;">
                                                    <a href="javascript:expand_compact_sidebar()" id="wpf_expand_btn" title="Collaboration Sidebar" style="user-select: auto; cursor: pointer;" class="tasks-btn">
                                                        <img src="' . get_wpf_favicon() . '" alt="icon" />
                                                    </a>
                                                </div>                                           
                                                <div class="wpf_general_comment">
                                                    <a class="wpf_green_btn wpf_general_btn wpf_comment_mode_general_task active_comment" id="wpf_comment_mode_general_task" href="javascript:void(0)" onclick="wpf_new_general_task(0)" title="Click to create a generic request" style="user-select: auto; cursor: crosshair;">
                                                        ' . get_wpf_exclamation_icon() . '
                                                        <span style="user-select: auto;">' . __( 'General', 'atarim-visual-collaboration' ) . '</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>			                            
                                        <div class="wpf_sidebar_container" style="opacity: 0; margin-right: -380px";>
                                            <div class="wpf_sidebar_header">
                                                <!-- =================Top Tabs================-->
                                                <div class="top_tabs">
                                                    <button class="wpf_tab_sidebar wpf_thispage wpf_active" onclick="openWPFTab_admin(\'wpf_thispage\')" >' . __( 'This Page', 'atarim-visual-collaboration' ) . '</button>
                                                    <button class="wpf_tab_sidebar wpf_allpages"  onclick="openWPFTab_admin(\'wpf_allpages\')" >' . __( 'All Pages', 'atarim-visual-collaboration' ) . '</button>
                                                    <button class="wpf_tab_sidebar wpf_frontend"  onclick="openWPFTab_admin(\'wpf_frontend\')" >' . __( 'Frontend', 'atarim-visual-collaboration' ) . '</button>
                                                    <span id="close_sidebar" class="close_sidebar" onclick="expand_compact_sidebar()"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 357 357" enable-background="new 0 0 357 357" xml:space="preserve"><g><g id="close"><polygon fill="#F5325C" points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 214.2,178.5 "/></g></g></svg></span>
                                                </div>
                                                <div id="sidebar_filters">
                                                    <ul class="icons-block">
                                                        <li class="status"> 
                                                            <a class="wpf_filter_tab_btn_bottom wpf_btm_withside" data-tag="wpf_task_status_filter_btn" href="javascript:void(0);" title="Filter By Status" id="wpf_filter_btn_bottom_status">' . get_wpf_status_icon() . '
                                                            </a>
                                                        </li>
                                                        <li class="priority"> <a id="wpf_filter_btn_bottom_priority" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_task_priority_filter_btn" title="' . __( "Filter By Priority", 'atarim-visual-collaboration' ) . '">' . get_wpf_priority_icon() . '</a></li>
                                                        <li class="search">
                                                            <a id="wpf_filter_btn_bottom_search" class="wpf_filter_tab_btn_bottom wpf_btm_withside" href="javascript:void(0);" data-tag="wpf_search_filter_btn" title="' . __( "Search by task title", 'atarim-visual-collaboration' ) . '">                            
                                                                <svg class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                                    <g id="surface1">
                                                                        <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li> 
                                                    </ul>                                                  
                                                </div>
                                            </div>
                                            <div class="filter_ui_content">
                                                <div id="wpf_side_filter">
                                                    <div class="wpf_list wpf_hide" id="wpf_task_status_filter_btn">' . $wpf_task_status_filter_btn . '</div>
                                                    <div class="wpf_list wpf_hide" id="wpf_task_priority_filter_btn">' . $wpf_task_priority_filter_btn . '</div>
                                                    <div class="wpf_list wpf_hide" id="wpf_search_filter_btn"><div id="sidebar_search" class="wpf_search_box"><span class="wpf_search_box">
                                                    <svg onclick="hide_search_from_sidebar()" class="svg-icon search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16pt" height="16pt" viewBox="0 0 16 16" version="1.1">
                                                            <g id="surface1">
                                                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(29.019608%,33.333333%,40.784314%);fill-opacity:1;" d="M 15.386719 13.554688 L 12.101562 10.269531 C 12.78125 9.246094 13.175781 8.023438 13.175781 6.707031 C 13.171875 3.132812 10.277344 0.238281 6.707031 0.234375 C 3.132812 0.238281 0.238281 3.132812 0.234375 6.707031 C 0.238281 10.277344 3.132812 13.171875 6.707031 13.175781 C 8.023438 13.175781 9.246094 12.78125 10.269531 12.101562 L 13.554688 15.386719 C 14.0625 15.890625 14.882812 15.890625 15.386719 15.386719 C 15.890625 14.882812 15.890625 14.0625 15.386719 13.558594 Z M 2.175781 6.707031 C 2.175781 4.207031 4.207031 2.175781 6.707031 2.175781 C 9.203125 2.175781 11.234375 4.207031 11.234375 6.707031 C 11.234375 9.203125 9.203125 11.234375 6.707031 11.234375 C 4.207031 11.234375 2.175781 9.203125 2.175781 6.707031 Z M 2.175781 6.707031 "/>
                                                            </g>
                                                    </svg>
                                                    <input onkeyup="wp_feedback_cat_filter(event, this)" type="text" name="wpf_search_title" class="wpf_search_title" value="" id="wpf_search_title" placeholder="' . __( "Search by task title", 'atarim-visual-collaboration' ) . '"></span></div></div>
                                                </div>                        
                                            </div> 
                                            <div class="wpf_sidebar_content">
                                                <div class="wpf_sidebar_loader wpf_hide"></div>                        
                                                <div id="wpf_thispage" class="wpf_thispage_tab wpf_container wpf_active_filter"><!--<div class="custom_today">today</div>--><ul id="wpf_thispage_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_thispage_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_thispage_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_thispage_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_thispage_container_year"></ul><!-- <div class="custom_other">Other</div> --> <ul id="wpf_thispage_container_other"></ul></div>                        
                                                <div id="wpf_allpages" class="wpf_allpages_tab wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_allpages_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_allpages_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_allpages_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_allpages_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_allpages_container_year"></ul><!-- <div class="custom_other">Other</div> --> <ul id="wpf_allpages_container_other"></ul></div>                        
                                                <div class="wpf_loading" style="margin-bottom: 30px">Loading...</div>
                                                <div id="wpf_frontend" class="wpf_frontend_container wpf_container" style="display:none";><!--<div class="custom_today">today</div>--><ul id="wpf_frontend_container_today"></ul><!--<div class="custom_yesterday">yesterday</div>--><ul id="wpf_frontend_container_yesterday"></ul><!--<div class="custom_Weekly">Weekly</div>--><ul id="wpf_frontend_container_this_week"></ul><!--<div class="custom_this_month">This Month</div>--><ul id="wpf_frontend_container_this_month"></ul><!-- <div class="custom_year">This Year</div> --> <ul id="wpf_frontend_container_year"></ul><!-- <div class="custom_year">Other</div> --> <ul id="wpf_frontend_container_other"></ul></div>
                                            </div>
                                        </div>' . generate_side_part_html() . '
                                    </div>';
                }
                echo $bottom_bar_html;
                echo '';
                require_once( WPF_PLUGIN_DIR . 'inc/frontend/wpf_general_task_modal.php' );
                require_once( WPF_PLUGIN_DIR . 'inc/frontend/wpf_restrictions_modal.php' );
            }
        }
    }
}
add_action( 'admin_footer', 'wpf_comment_button_admin' );

/*
 * This function contains the code to remove the plugin initialization if Oxygen builder is detected.
 *
 * @input NULL
 * @return NULL
 */
if ( class_exists( 'Oxygen_Gutenberg' ) || isset( $_GET['ct_builder'] ) || isset( $_GET['ct_template'] ) || isset( $_GET['ct_inner'] ) ) {
    function remove_wp_foorer_action() {
        remove_action( 'wp_footer', 'show_wpf_comment_button' );
    }
    add_action( 'admin_init', 'remove_wp_foorer_action', 99 );
}

/*
 * This function contains the code to register the JS and CSS files to the page if plugin is active
 *
 * @input NULL
 * @return NULL
 */
if ( ! function_exists( 'wpfeedback_add_stylesheet_to_admin' ) ) {
    function wpfeedback_add_stylesheet_to_admin() {
        // only enque CSS and JS if tool is allowed.
        $wpf_check_page_builder_active = wpf_check_page_builder_active();
        if ( $wpf_check_page_builder_active == 0 ) {
            $is_site_archived      = get_site_data_by_key( 'wpf_site_archived' );
            $wpf_current_screen_id = '';
            if ( is_admin() ) {
                $wpf_current_screen    = get_current_screen();
                $wpf_current_screen_id = $wpf_current_screen->id;
            }

            /*===========Removed WPF on mailpoet plugin related in all pages ==========*/
            $mailpoet_page = array( 'mailpoet_page_mailpoet-segments', 'admin_page_mailpoet-newsletter-editor', 'toplevel_page_mailpoet-newsletters', 'mailpoet_page_mailpoet-forms', 'mailpoet_page_mailpoet-subscribers', 'mailpoet_pa', 'ge_mailpoet-segments', 'mailpoet_page_mailpoet-dynamic-segments', 'mailpoet_page_mailpoet-settings', 'mailpoet_page_mailpoet-help', 'mailpoet_page_mailpoet-premium' );
            if ( in_array( $wpf_current_screen_id, $mailpoet_page ) ) {
                if ( is_plugin_active( 'mailpoet/mailpoet.php' ) ) {
                    remove_action( 'admin_footer', 'wpf_comment_button_admin' );
                }
            }
            /*===========End mailpoet plugin==========*/

            /*===========Removed WPF on gravity forms plugin related in all pages ==========*/
            $gravityf_page = array( 'forms_page_gf_new_form', 'toplevel_page_gf_edit_forms', 'forms_page_gf_entries', 'forms_page_gf_settings', 'forms_page_gf_export', 'forms_page_gf_addons', 'forms_page_gf_system_status', 'forms_page_gf_help' );
            if ( in_array( $wpf_current_screen_id, $gravityf_page ) ) {
                    remove_action( 'admin_footer', 'wpf_comment_button_admin' );
            }
            /*===========End gravity forms plugin==========*/

            $wpf_license = get_option( 'wpf_license' );

            wp_register_style( 'wpf_wpf-icons', WPF_PLUGIN_URL . 'css/wpf-icons.css', false, WPF_VERSION );
            wp_enqueue_style( 'wpf_wpf-icons' );
            
            wp_register_style( 'wpf-graphics-admin-style', WPF_PLUGIN_URL . 'css/graphics-admin.css', false, strtotime( "now" ) );
            wp_enqueue_style( 'wpf-graphics-admin-style' );
            if ( $wpf_current_screen_id != 'settings_page_menu_editor' ) {
                wp_register_style( 'wpf_admin_style', WPF_PLUGIN_URL . 'css/admin.css', false, strtotime( "now" ) );
                wp_enqueue_style( 'wpf_admin_style' );
            }

            wp_register_style( 'wpf_wpf-common', WPF_PLUGIN_URL . 'css/wpf-common.css', false, strtotime( "now" ) );
            wp_enqueue_style( 'wpf_wpf-common' );

            wp_register_style( 'wpf_rt_style', WPF_PLUGIN_URL . 'css/quill.css', false, strtotime( "now" ) );
            wp_enqueue_style( 'wpf_rt_style' );

            wp_register_script( 'wpf_rt_script', WPF_PLUGIN_URL . 'js/quill.js', array(), WPF_VERSION, true );
            wp_enqueue_script( 'wpf_rt_script' );

            wp_register_script( 'wpf_jquery_script', WPF_PLUGIN_URL . 'js/atarimjs.js', array(), WPF_VERSION, true );
            wp_enqueue_script( 'wpf_jquery_script' );

            if ( isset( $_GET['page'] ) && $_GET['page'] == "wpfeedback_page_settings" ) {
                wp_register_script( 'pickr', WPF_PLUGIN_URL . 'js/pickr.min.js', null, null, true );
                wp_enqueue_script( 'pickr' );
                wp_register_script( 'cpickr', WPF_PLUGIN_URL . 'js/cpickr.js', null, null, true );
                wp_enqueue_script( 'cpickr' );
            }
            wp_register_style( 'pickr_monolith', WPF_PLUGIN_URL . 'css/monolith.min.css' );
            wp_enqueue_style( 'pickr_monolith' );

            wp_register_script( 'wpf_admin_script', WPF_PLUGIN_URL . 'js/admin.js', array(), strtotime( "now" ), true );
            wp_enqueue_script( 'wpf_admin_script' );
            
            $wpf_user_type = wpf_user_type();
            $user          = wp_get_current_user();
            $display_name  = $user->display_name;
            $user_id       = get_current_user_id();
            $avatar_url    = get_avatar_url( $user_id, array( 'size' => 42, 'default' => '404' ) );
            $headers       = @get_headers( $avatar_url );
            if ( ! empty( $headers ) ) {
                if ( $headers[0] == 'HTTP/1.1 404 Not Found' ) {
                    $avatar_url = '';
                }
            }
            wp_localize_script( 'wpf_admin_script', 'logged_user', array( 'current_user' => $wpf_user_type, 'author_img' => $avatar_url, 'author' => $display_name ) );
            
            $feature = array();
            $edit    = is_feature_enabled( 'edit' );
            if ( ! $edit ) {
                $feature[] = 'edit';
            }
            wp_localize_script( 'wpf_admin_script', 'blocked', $feature );

            $upgrade_url = get_option( 'upgrade_url' );
            wp_localize_script( 'wpf_admin_script', 'upgrade_url', array( 'url' => $upgrade_url, 'plugin_url' => WPF_PLUGIN_URL ) );

            wp_register_script( 'wpf_jscolor_script', WPF_PLUGIN_URL . 'js/jscolor.js', array(), WPF_VERSION, true );
            wp_enqueue_script( 'wpf_jscolor_script' );

            wp_register_script( 'wpf_browser_info_script', WPF_PLUGIN_URL . 'js/wpf_browser_info.js', array(), WPF_VERSION, true );
            wp_enqueue_script( 'wpf_browser_info_script' );

            wp_register_script( 'wpf_popper_script', WPF_PLUGIN_URL . 'js/popper.min.js', array(), WPF_VERSION, true );
            wp_enqueue_script( 'wpf_popper_script' );

            wp_register_script( 'wpf_common_functions', WPF_PLUGIN_URL . 'js/wpf_common_functions.js', array(), strtotime( "now" ), true );
            wp_enqueue_script( 'wpf_common_functions' );

            wp_enqueue_media();

            /* ===========Admin Side================ */
            /*=====Start Check customize.php====*/
            /*if ( $wpf_check_page_builder_active == 0 ) {
                if ( is_customize_preview() ) {
                    $wpf_check_page_builder_active = 1;
                } else {
                    $wpf_check_page_builder_active = 0;
                }
            }*/
            if ( class_exists( 'GeoDirectory' ) ) {
                if ( $wpf_current_screen_id == 'gd_place_page_gd_place-settings' ) {
                    $wpf_check_page_builder_active = 1;
                }
            }
            /*=====END check customize.php====*/
            $enabled_wpfeedback           = wpf_check_if_enable();
            $wpf_allow_backend_commenting = get_site_data_by_key( 'wpf_allow_backend_commenting' );

            if ( $wpf_allow_backend_commenting != 'yes' ) {
                if ( ( isset( $_GET['page'] ) && $_GET['page'] != "updraftplus" ) || ( defined( 'BSF_AIOSRS_PRO_VER' ) == false && class_exists( 'BSF_AIOSRS_Pro_Markup' ) == false ) ) {
                    wp_register_script( 'wpf_jquery_ui_script', WPF_PLUGIN_URL . 'js/atarim-ui.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_jquery_ui_script' );
                }

                wp_register_script( 'wpf_popper_script', WPF_PLUGIN_URL . 'js/popper.min.js', array(), WPF_VERSION, true );
                wp_enqueue_script( 'wpf_popper_script' );

                if ( $wpf_current_screen_id != 'settings_page_menu_editor' && ( isset( $_GET['page'] ) && $_GET['page'] != "formidable" ) ) {
                    wp_register_script( 'wpf_bootstrap_script', WPF_PLUGIN_URL . 'js/bootstrap.min.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_bootstrap_script' );
                }
            }

            $wpf_exclude_page = array( "wp-feedback_page_wpfeedback_page_tasks", "wp-feedback_page_wpfeedback_page_settings", "wp-feedback_page_wpfeedback_page_graphics", "wp-feedback_page_wpfeedback_page_permissions", "wp-feedback_page_wpfeedback_page_support" );
            if ( $enabled_wpfeedback == 1 && $wpf_allow_backend_commenting != 'yes' && ( ! $is_site_archived ) ) {
                if ( ! in_array( $wpf_current_screen_id, $wpf_exclude_page ) ) {
                
                }
                if ( ! in_array( $wpf_current_screen_id, $gravityf_page ) ) {
                
                }
                if ( $wpf_check_page_builder_active == 0 && $wpf_current_screen_id != 'settings_page_menu_editor' ) {
                    //wp_register_script( 'wpf_jquery_ui_script', WPF_PLUGIN_URL . 'js/atarim-ui.js', array(), WPF_VERSION, true );
                    //wp_enqueue_script( 'wpf_jquery_ui_script' );

                    wp_register_script( 'wpf_app_script', WPF_PLUGIN_URL . 'js/admin/admin_app.js', array(), strtotime( "now" ), true );
                    wp_enqueue_script( 'wpf_app_script' );

                    wp_register_script( 'wpf_html2canvas_script', WPF_PLUGIN_URL . 'js/html2canvas.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_html2canvas_script' );

                    //wp_register_script( 'wpf_popper_script', WPF_PLUGIN_URL . 'js/popper.min.js', array(), WPF_VERSION, true );
                    //wp_enqueue_script( 'wpf_popper_script' );

                    wp_register_script( 'wpf_custompopover_script', WPF_PLUGIN_URL . 'js/custompopover.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_custompopover_script' );

                    wp_register_script( 'wpf_selectoroverlay_script', WPF_PLUGIN_URL . 'js/selectoroverlay.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_selectoroverlay_script' );

                    wp_register_script( 'wpf_xyposition_script', WPF_PLUGIN_URL . 'js/xyposition.js', array(), WPF_VERSION, true );
                    wp_enqueue_script( 'wpf_xyposition_script' );

                    if ( ! defined( 'WDT_BASENAME' ) || ! defined( 'WDT_ROOT_PATH' ) ) {
                        wp_register_script( 'wpf_bootstrap_script', WPF_PLUGIN_URL . 'js/bootstrap.min.js', array(), WPF_VERSION, true );
                        wp_enqueue_script( 'wpf_bootstrap_script' );
                    }
                }
            }
        }
    }
}
add_action( 'admin_enqueue_scripts', 'wpfeedback_add_stylesheet_to_admin' );

/*
 * This function contains the code to load all the tasks on the admin side as well as the comments inside the tasks.
 *
 * @input NULL
 * @return JSON
 */
if ( ! function_exists( 'load_wpfb_tasks_admin' ) ) {
    function load_wpfb_tasks_admin() {
        ob_clean();
        global $wpdb, $current_user;
        wpf_security_check();
        $response = array();

        if ( isset( $_POST['current_page_id'] ) && $_POST['current_page_id'] != '' && $_POST['all_page'] != 1 ) {
            $post_data = array(
                'wpf_site_id'     => get_option( 'wpf_site_id' ),
                'url'             => home_url(),
                'task_types'      => ['general','email','page'],
                "sort"            => ["task_title", "created_at"],
                "sort_by"         => "asc",
                "current_page_id" => sanitize_text_field( $_POST['current_page_id'] ),
                "url"             => home_url()
            );
            $url         = WPF_CRM_API . 'wp-api/all/task';
            $sendtocloud = json_encode( $post_data );
            $wpfb_tasks  = wpf_send_remote_post( $url, $sendtocloud );
        } else if ( isset( $_POST['wpf_current_screen'] ) && $_POST['wpf_current_screen'] != '' && $_POST['all_page'] != 1 ) {               
            global $wp;
            $post_data = array(
                'wpf_site_id'        => get_option( 'wpf_site_id' ),
                'wpf_current_screen' => sanitize_text_field( $_POST['wpf_current_screen'] ),
                'task_types'         => ['general','page'],
                "sort"               =>  ["task_title", "created_at"],
                "sort_by"            => "asc",
                'url'                => home_url(),
                "is_admin_task"      => 1
            );

            $url         = WPF_CRM_API . 'wp-api/all/task';
            $sendtocloud = json_encode( $post_data );
            $wpfb_tasks  = wpf_send_remote_post( $url, $sendtocloud );
        } else if ( isset( $_POST['task_id'] ) && $_POST['task_id'] != "") {
            $post_data = array(
                'task_id'       => sanitize_text_field( $_POST['task_id'] ),
                'post_type'     => 'wpfeedback',
                'wpf_site_id'   => get_option( 'wpf_site_id' ),
                'url'           => home_url(),
                'is_admin_task' => 1
            );

            $url         = WPF_CRM_API . 'wp-api/all/task';
            $sendtocloud = json_encode( $post_data );
            $wpfb_tasks  = wpf_send_remote_post( $url, $sendtocloud );
        } else {
            $page_no = $_POST['page_no'];
            $post_data = array(
                'wpf_site_id'     => get_option( 'wpf_site_id' ),
                'task_types'      => [],
                "current_page_id" => '',
                'post_type'       => 'wpfeedback',
                'numberposts'     => -1,
                'limit'           => 20,
                'page_no'         => $page_no,
                'post_status'     => 'any',
                'orderby'         => 'date',
                'order'           => 'DESC',
                'url'             => home_url(),
                'is_admin_task'   => 1,
                'from_frontend'   => isset( $_POST['from_frontend'] ) ? sanitize_text_field( $_POST['from_frontend'] ) : 0
            );

            $url         = WPF_CRM_API . 'wp-api/all/task';
            $sendtocloud = json_encode( $post_data );
            $wpfb_tasks  = wpf_send_remote_post( $url, $sendtocloud );
        }
        
        if ( ! empty( $wpfb_tasks ) ) {
            $response = process_task_response( $wpfb_tasks );
        }
        //update_option( 'restrict_plugin', $wpfb_tasks['limit'], 'no' );
        ob_end_clean();
        echo json_encode( $response );
        exit;
    }
}
add_action( 'wp_ajax_load_wpfb_tasks_admin', 'load_wpfb_tasks_admin' );
add_action( 'wp_ajax_nopriv_load_wpfb_tasks_admin', 'load_wpfb_tasks_admin' );

/*
 * This function contains the code to register the custom status "wpf_admin" for the Custom Post Type 'atarim-visual-collaboration' in order to identify the backend tasks
 *
 * @input NULL
 * @return NULL
 */
function wpf_custom_post_status() {
    register_post_status( 'wpf_admin',
        array(
            'label'                     => _x( 'admin', 'atarim-visual-collaboration' ),
            'public'                    => true,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => false,
            'post_type'                 => array( 'wpfeedback'),
            'label_count'               => _n_noop( 'Admin <span class="count">(%s)</span>', 'Admin <span class="count">(%s)</span>' ),
        )
    );
}
add_action( 'init', 'wpf_custom_post_status' );

/*
 * This function contains the code to disable the commenting on the admin side (If option is selected from the admin)
 *
 * @input NULL
 * @output NULL
 */
if ( ! function_exists( 'wpf_disable_comment_for_admin_page' ) ) {
    function wpf_disable_comment_for_admin_page() {
        $response = 0;
        if ( is_admin() ) {
            $wpf_current_screen = get_current_screen();
            if ( $wpf_current_screen ) {
                $wpf_current_screen_id = $wpf_current_screen->id;
                if ( $wpf_current_screen_id == 'toplevel_page_tvr-microthemer' ) {
                    remove_action( 'admin_footer', 'wpf_comment_button_admin' );
                    wp_dequeue_script( 'wpf_app_script' );
                    ?>
                    <script>
                    jQuery(window).load(function() {
                        jQuery("#viframe").contents().find("body").find("#wpf_launcher").css("display","none");
                        jQuery("#viframe").contents().find("body").find(".wpfb-point").css("display","none");
                    });
                    </script>
                <?php }
                if ( $wpf_current_screen_id == 'nav-menus' ) {
                    if ( function_exists( '_QuadMenu' ) ) {
                        remove_action( 'admin_footer', 'wpf_comment_button_admin' );
                        remove_action( 'admin_enqueue_scripts', 'wpfeedback_add_stylesheet_to_admin' );
                        wp_dequeue_script( 'wpf_app_script' );
                        wp_dequeue_script( 'wpf_bootstrap_script' );
                    }
                }
            }
        }
    }
}
add_action( 'admin_head', 'wpf_disable_comment_for_admin_page', 10 );


if ( ! function_exists( 'bottom_panel_session' ) ) {
    function bottom_panel_session() {
        ob_clean();
        update_option( 'bottom_panel', sanitize_text_field( $_POST['is_expand'] ), 'no' );
        echo json_encode( ['status' => 1] );
        exit;
    }
}
add_action( 'wp_ajax_bottom_panel_session', 'bottom_panel_session' );
add_action( 'wp_ajax_nopriv_bottom_panel_session', 'bottom_panel_session' );

// Remove admin notice of Graphic Feedback tool.
function remove_feedbacktool_notice() {
    $notice = $_POST['notice'];
    update_option('wp_feedback_notice', $notice, 'no');
}
add_action( 'wp_ajax_remove_feedbacktool_notice', 'remove_feedbacktool_notice' );
add_action( 'wp_ajax_nopriv_remove_feedbacktool_notice', 'remove_feedbacktool_notice' );