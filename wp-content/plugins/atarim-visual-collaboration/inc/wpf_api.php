<?php
/*
 * wpf_api.php
 * This file contains all the code related to the APIs. APIs are used to communicate the data between the plugin and the dashboard app.
 */
define( 'WPF_CRM_API', 'https://api.atarim.io/' );
//define('WPF_CRM_API', 'https://apiv1.wpfeedback.co/');
//define('WPF_CRM_API', 'https://apiv2.wpfeedback.co/');
//define( 'WPF_CRM_API', 'https://apiv3.wpfeedback.co/' );
//define( 'WPF_CRM_API', 'https://apiv4.wpfeedback.co/' );
//define('WPF_CRM_API', 'http://127.0.0.1:8000/');
// define('WPF_CRM_API', 'http://api.atarim.loc/');

 /*
 * This function is called by the APP to get the website information when website is synced.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_details
 *
 * @input NULL
 * @return JSON
 */
function wpf_website_details() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        update_option( 'wpf_initial_sync', 1, 'no' );
        $response_array                    = array();
        $response_array['name']            = get_option( 'blogname' );
        $response_array['url']             = WPF_SITE_URL;
	    $wpf_license_key_enc               = get_option( 'wpf_license_key' );
        $wpf_license_key                   = wpf_crypt_key( $wpf_license_key_enc, 'd' );
        $response_array['wpf_license_key'] = $wpf_license_key;
        $settings                          = [];
        $val                               = get_option( "wpfeedback_color" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpfeedback_color', 'value' => $val] );
        }
        $val = get_option( "wpf_selcted_role" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_selcted_role', 'value' => $val] );
        }
        $val = get_option( "wpf_website_developer" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_website_developer', 'value' => $val] );
        }
        $val = get_option( "wpf_show_front_stikers" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_show_front_stikers', 'value' => $val] );
        }
        $val = get_option( "wpf_customisations_client" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_customisations_client', 'value' => $val] );
        }
        $val = get_option( "wpf_customisations_webmaster" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_customisations_webmaster', 'value' => $val] );
        }
        $val = get_option( "wpf_customisations_others" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_customisations_others', 'value' => $val] );
        }
        $val = get_option( "wpf_from_email" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_from_email', 'value' => $val] );
        }
        $val = get_option( "wpf_allow_guest" );
        if( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_allow_guest', 'value' =>$val] );
        }
        $val = get_option( "wpf_disable_for_admin" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_disable_for_admin', 'value' => $val] );
        }
        $val = get_option( "wpf_website_client" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_website_client', 'value' => $val] );
        }
        $val = get_option( "wpf_license" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_license', 'value' => $val] );
        }
        $val = get_option( "wpf_license_expires" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_license_expires', 'value' => $val] );
        }
        $val = get_option( "wpf_decr_key" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_decr_key', 'value' => $val] );
        }
        $val = get_option( "wpf_decr_checksum" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_decr_checksum', 'value' => $val] );
        }
        $val = get_option( "enabled_wpfeedback" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'enabled_wpfeedback', 'value' => $val] );
        }
        $val = get_option( "wpf_enabled_compact_mode" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpf_enabled_compact_mode', 'value' => $val] );
        }
        $val = get_option( "wpfeedback_font_awesome_script" );
        if ( $val != FALSE ) {
            array_push( $settings, ['name' => 'wpfeedback_font_awesome_script', 'value' => $val] );
        }
        $val = get_option( "wpf_allow_backend_commenting" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_allow_backend_commenting', 'value' => $val] );
        }
        $val = get_option( "wpf_more_emails" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_more_emails', 'value' => $val] );
        }
        $val = get_option( "wpfeedback_powered_by" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpfeedback_powered_by', 'value' => $val] );
        }
        $val = get_option( "wpf_every_new_task" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_every_new_task', 'value' => $val] );
        }
        $val = get_option( "wpf_every_new_comment" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_every_new_comment', 'value' => $val] );
        }
        $val = get_option( "wpf_every_new_complete" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_every_new_complete', 'value' => $val] );
        }
        $val = get_option( "wpf_every_status_change" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_every_status_change', 'value' => $val] );
        }
        $val = get_option( "wpf_daily_report" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_daily_report', 'value' => $val] );
        }
        $val = get_option( "wpf_weekly_report" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_weekly_report', 'value' => $val] );
        }
        $val = get_option( "wpf_auto_daily_report" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_auto_daily_report', 'value' => $val] );
        }
        $val = get_option( "wpf_auto_weekly_report" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_auto_weekly_report', 'value' => $val] );
        }
        $val = get_option( "wpf_initial_setup" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_initial_setup', 'value' => $val] );
        }
        $val = get_option( "wpf_tutorial_video" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpf_tutorial_video', 'value' => $val] );
        }
        $val = get_option( "wpf_logo" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpfeedback_logo', 'value' => $val] );
        }
        $val = get_option( "wpf_favicon" );
        if ( $val != FALSE ){
            array_push( $settings, ['name' => 'wpfeedback_favicon', 'value' => $val] );
        }

        $response_array['settings'] = $settings;
        $response                   = json_encode( $response_array );
        $response_signature         = hash_hmac( 'sha256', $response, $wpf_license_key );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_details', 'wpf_website_details' );
add_action( 'wp_ajax_nopriv_wpf_website_details', 'wpf_website_details' );

/*
 * This function is called by the APP to get the users of the website.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_users
 *
 * @input NULL
 * @return JSON
 */
function wpf_website_users() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $response = wpf_api_func_get_users();
        // fixed sync of users when user click the "sync" button on the app
        get_notif_sitedata_filterdata();
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_users', 'wpf_website_users' );
add_action( 'wp_ajax_nopriv_wpf_website_users', 'wpf_website_users' );

/*
 * This function is called by the APP to get the pages of the website.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_pages
 *
 * @input NULL
 * @return JSON
 */
function wpf_website_pages() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $response           = wpf_get_page_list( 'api' );
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_pages', 'wpf_website_pages' );
add_action( 'wp_ajax_nopriv_wpf_website_pages', 'wpf_website_pages' );

/*
 * This function is called by the APP to get the tasks of  the website.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_tasks
 *
 * @input NULL
 * @return JSON
 */
function wpf_website_tasks() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $response           = wpf_api_func_get_tasks();
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_tasks', 'wpf_website_tasks' );
add_action( 'wp_ajax_nopriv_wpf_website_tasks', 'wpf_website_tasks' );

/*
 * This function is called by the APP to get the comments of the task. This function is not used currently.
 *
 * @input ARRAY ( $_REQUEST )
 * @return JSON
 */
function wpf_website_task_comments() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $response           = wpf_api_func_get_task_comments( $_REQUEST['wpf_task_id'] );
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_task_comments', 'wpf_website_task_comments' );
add_action( 'wp_ajax_nopriv_wpf_website_task_comments', 'wpf_website_task_comments' );

/*
 * This function is called by the APP to update the task meta information when it is updated in the APP.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_task_update_meta
 * METHODS: notify_users, status, priority, new_comment, task_title, update_tags and delete_tags.
 *
 * @input JSON
 * @return JSON
 */
function wpf_website_task_update_meta() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $input_json = file_get_contents( 'php://input' );
        $input      = json_decode( $input_json );
        $task_id    = $input->task_id;
        switch ( $input->method ) {
            case 'notify_users':
                $task_notify_users = $input->value;
                $response          = wpf_api_func_update_task_notify_users( $task_id, $task_notify_users );
                break;
            case 'status':
                $task_status = $input->value;
                $response    = wpf_api_func_update_task_status( $task_id, $task_status );
                break;
            case 'priority':
                $task_priority = $input->value;
                $response      = wpf_api_func_update_task_priority( $task_id, $task_priority );
                break;
            case 'new_comment':
                $author_name = $input->author_name;
                $author_id   = $input->author_id;
                $message     = $input->value;
                $response    = wpf_api_func_task_new_comment( $task_id, $author_name, $author_id, $message );
                break;
            case 'task_title':
                $new_title = $input->value;
                if ( ! empty( $new_title ) && $task_id !='' ) {
                    $my_post = array(
                        'ID'         => $task_id,
                        'post_title' => $new_title,
                    );
                    $task_id = wp_update_post( $my_post );
                    if ( $task_id ) {
                        $response['status'] = 1;
                    } else {
                        $response['status'] = 0;
                    }
                } else {
                    $response['status'] = 0;
                }
                $response = json_encode( $response );
                break;
            case 'update_tags':
                $tag                                    = $input->value;
                $wpf_tag_slug                           = strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $tag ) ) );
                $wpf_task_tag_info['wpf_task_tag_slug'] = $wpf_tag_slug;
                $wpf_task_tags_obj                      = get_the_terms( $task_id, 'wpf_tag' );
                if ( ! empty( $wpf_task_tags_obj ) && ! is_wp_error( $wpf_task_tags_obj ) ) {
                    $task_list_tags_array = wp_list_pluck( $wpf_task_tags_obj, 'slug' );
                }
                if ( in_array( $wpf_tag_slug, $task_list_tags_array ) ) {
                    $response['wpf_task_tag_name'] = $tag;
                    $response['wpf_msg']           = 0;
                    $response['wpf_tag_type']      = 'already_exit';
                } else {
                    $wpf_term = term_exists( $wpf_tag_slug, 'wpf_tag' );
                    if ( $wpf_term !== 0 && $wpf_term !== null ) {
                        $wpf_term = wp_set_object_terms( $task_id, $wpf_tag_slug, 'wpf_tag', true );
                        if ( $wpf_term ) {
                            $response['wpf_task_tag_name'] = $tag;
                            $response['wpf_task_tag_slug'] = $wpf_tag_slug;
                        } else {
                            $response['wpf_msg'] = 0;
                        }
                    } else {
                        $wpf_term = wp_set_object_terms( $task_id, $tag, 'wpf_tag', true );
                        if ( $wpf_term ) {
                            $response['wpf_task_tag_slug'] = $wpf_tag_slug;
                            $response['wpf_task_tag_name'] = $tag;
                        } else {
                            $response['wpf_msg'] = 0;
                        }
                    }
                }
                $response = json_encode( $response );
                break;
            case 'delete_tags':
                $wpf_task_tag_slug = $input->slug;
                if ( $wpf_task_tag_slug !='' && $task_id != '' ) {
                    $wpf_delete_term =  wp_remove_object_terms( $task_id, $wpf_task_tag_slug, 'wpf_tag' );
                    if ( $wpf_delete_term ) {
                        $response['wpf_task_tag_slug'] = $wpf_task_tag_slug;
                        $response['wpf_task_id']       = $task_id;
                        $response['wpf_msg']           = 1;
                    } else {
                        $response['wpf_msg'] = 0;
                    }
                } else {
                    $response['wpf_msg'] = 0;
                }
                $response = json_encode( $response );
                break;
            default:
                echo 0;
        }
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_task_update_meta', 'wpf_website_task_update_meta' );
add_action( 'wp_ajax_nopriv_wpf_website_task_update_meta', 'wpf_website_task_update_meta' );

/*
 * This function is called by the APP to delete the task on website when deleted from APP.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_delete_task
 *
 * @input JSON
 * @return BOOLEAN
 */
function wpf_website_delete_task() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $input_json = file_get_contents( 'php://input' );
        $input      = json_decode( $input_json );
        $task_id    = $input->task_id;
        if ( wp_delete_post( $task_id ) ) {
            $response = 1;
        } else {
            $response = 0;
        }
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_delete_task', 'wpf_website_delete_task' );
add_action( 'wp_ajax_nopriv_wpf_website_delete_task', 'wpf_website_delete_task' );

/*
 * This function is called by the APP to create a general task on the website when created from APP.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_new_general_task
 *
 * @input JSON
 * @return JSON || invalid request
 */
function wpf_website_new_general_task() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $input_json         = file_get_contents( 'php://input' );
        $task_info          = json_decode( $input_json );
        $response           = wpf_api_func_task_new_general_task( $task_info );
        $response_signature = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_new_general_task', 'wpf_website_new_general_task' );
add_action( 'wp_ajax_nopriv_wpf_website_new_general_task', 'wpf_website_new_general_task' );

/*
 * This function is called from APP when website is requested to resync. This function is also called from the website when the button "Resync the Central Dashboard" button is clicked.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_website_resync
 *
 * @input NULL
 * @return JSON || invalid request
 */
function wpf_website_resync() {
    $valid = wpf_api_request_verification();
    if ( $valid == 1 ) {
        $wpf_license_key_enc = get_option( 'wpf_license_key' );
        $wpf_license_key     = wpf_crypt_key( $wpf_license_key_enc, 'd' );
        $response            = wpf_initial_sync( $wpf_license_key );
        $response_signature  = wpf_generate_response_signature( $response );
        header( "response-signature: " . $response_signature );
    } else {
        $response = 'invalid request';
    }
    echo $response;
    exit;
}
add_action( 'wp_ajax_wpf_website_resync', 'wpf_website_resync' );
add_action( 'wp_ajax_nopriv_wpf_website_resync', 'wpf_website_resync' );

/*
 * Support functions start here
 */

/*
 * This function is called by all the functions for the verification of authentication.
 *
 * @input Array ( $_SERVER )
 * @return Boolean
 */
function wpf_api_request_verification() {
    $response            = 0;
    $request_reference   = $_SERVER['HTTP_REQUEST_REFERENCE'];
    $request_signature   = $_SERVER['HTTP_REQUEST_SIGNATURE'];
    $wpf_license_key_enc = get_option( 'wpf_license_key' );
    $wpf_license_key     = wpf_crypt_key( $wpf_license_key_enc, 'd' );
    if ( $request_signature == hash_hmac( 'sha256', $request_reference, $wpf_license_key ) ) {
        $response = 1;
    }
    return $response;
}

/*
 * This function is called by function to get all the users of website.
 *
 * @input NULL
 * @return JSON
 */
 function wpf_api_func_get_users() {
    $response              = array();
    $wpf_website_developer = get_option( 'wpf_website_developer' );
    $selected_roles        = get_site_data_by_key( 'wpf_selcted_role' );
    $selected_roles        = explode( ',', $selected_roles );
    $wpfb_users            = get_users( array( 'role__in' => $selected_roles ) );
    // Get invited user using Share version 2 by Pratap.
    $args = array(
        'meta_key' => 'avc_user_token',
        'meta_value' => '',
        'meta_compare' => '!='
    );
    $user_query = new WP_User_Query( $args );
    $guest_user_list = $user_query->get_results();
    if ( ! empty( $guest_user_list ) ) {
        foreach ( $guest_user_list as $user ) {
            $wpfb_users[] = $user;
        }
    }
    $wpf_temp_count        = 0;
    foreach ( $wpfb_users as $user ) {
        if ( $user->ID == $wpf_website_developer ) {
            $response[$wpf_temp_count]['is_admin'] = 1;
        } else {
            $response[$wpf_temp_count]['is_admin'] = 0;
        }
        $response[$wpf_temp_count]['wpf_id']            = $user->ID;
        $response[$wpf_temp_count]['wpf_display_name']  = htmlspecialchars( $user->display_name, ENT_QUOTES, 'UTF-8' );
        $response[$wpf_temp_count]['wpf_name']          = htmlspecialchars( $user->display_name, ENT_QUOTES, 'UTF-8' );
        $response[$wpf_temp_count]['wpf_email']         = $user->user_email;
        $response[$wpf_temp_count]['first_name']        = get_user_meta( $user->ID, 'first_name', true );
        $response[$wpf_temp_count]['last_name']         = get_user_meta( $user->ID, 'last_name', true );
        $response[$wpf_temp_count]['role']              = implode( ',', $user->roles );
        $wpf_temp_count++;
    }
    return json_encode( $response );
 }

/*
 * This function is called by function wpf_website_tasks to get all the tasks of the website.
 *
 * @input NULL
 * @return JSON
 */
function wpf_api_func_get_tasks() {
    $response = array();
    $args     = array(
        'post_type'   => 'wpfeedback',
        'numberposts' => -1,
        'post_status' => array( 'publish', 'wpf_admin' ),
        'orderby'     => 'date',
        'order'       => 'ASC'
    );
    $wpfb_tasks     = get_posts( $args );
    $wpf_temp_count = 0;
    if ( ! empty( $wpfb_tasks ) ) {
	    foreach ( $wpfb_tasks as $wpfb_task ) {
            $task_date                                 = get_the_time( 'Y-m-d H:i:s', $wpfb_task->ID );
            $metas                                     = get_post_meta( $wpfb_task->ID );
            $task_priority                             = get_the_terms( $wpfb_task->ID, 'task_priority' );
            $task_status                               = get_the_terms( $wpfb_task->ID, 'task_status' );
            $task_img                                  = get_post_meta( $wpfb_task->ID, 'wpf_task_screenshot', true );
            $response[$wpf_temp_count]['wpf_task_id']  = $wpfb_task->ID;
            $response[$wpf_temp_count]['wpf_task_url'] = wpf_api_func_get_task_url( $wpfb_task->ID );

            if ( $wpfb_task->post_status == 'wpf_admin' ) {
                $response[$wpf_temp_count]['is_admin_task'] = 1;
            } else {
                $response[$wpf_temp_count]['is_admin_task'] = 0;
            }
            if ( ! empty( $metas ) ) {
                foreach ( $metas as $key => $value ) {
                    $response[$wpf_temp_count][$key] = $value[0];
                    if ( ! is_wp_error( $task_priority ) ) {
                        $response[$wpf_temp_count]['task_priority'] = $task_priority[0]->slug;
                    } else {
                        $response[$wpf_temp_count]['task_priority'] = 'low';
                    }
                    if ( ! is_wp_error( $task_priority ) ) {
                        $response[$wpf_temp_count]['task_status'] = $task_status[0]->slug;
                    } else {
                        $response[$wpf_temp_count]['task_status'] = 'open';
                    }
                    $response[$wpf_temp_count]['wpf_task_screenshot'] = $task_img;
                    $task_date1                                       = date_create( $task_date );
                    $wpf_wp_current_timestamp                         = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
                    $task_date2                                       = date_create( $wpf_wp_current_timestamp );
                    $curr_comment_time                                = wpfb_time_difference( $task_date1, $task_date2 );
                    $response[$wpf_temp_count]['task_time']           = $task_date;
                    $response[$wpf_temp_count]['current_page_id']     = url_to_postid( $metas['task_page_url'][0] );
                }
            }
            $task_tags        = get_the_terms( $wpfb_task->ID, 'wpf_tag' );
            $temp_tag_counter = 0;
            if ( ! empty( $task_tags ) ) {
                foreach ( $task_tags as $task_tag => $task_tags_value ) {
                    $count_plus = 0;
                    if ( isset( $task_tags_value->slug ) && $task_tags_value->slug != '' ) {
                        $count_plus = 1;
                        $response[$wpf_temp_count]['wpf_tags'][$temp_tag_counter]['slug'] = $task_tags_value->slug;
                    }
                    if ( isset( $task_tags_value->name ) && $task_tags_value->name != '' ) {
                        $count_plus = 1;
                        $response[$wpf_temp_count]['wpf_tags'][$temp_tag_counter]['name'] = $task_tags_value->name;
                    }
                    if ( $count_plus == 1 ) {
                        $temp_tag_counter++;
                    }
                }
            }

            $comments_args = array(
                'post_id' => $wpfb_task->ID,
                'type'    => 'wp_feedback'
            );
            $comments_info = get_comments( $comments_args );
            foreach ( $comments_info as $comment ) {
                if ( $comment->comment_date_gmt == '0000-00-00 00:00:00' ) {
                    $comment->comment_date_gmt = get_gmt_from_date( $comment->comment_date );
                }
                $comment->comment_type = wpf_api_func_get_comment_type( $comment );
                $comment->wpf_user_id  = $comment->user_id;
                $comment->user_id      = '';
            }
	        $response[$wpf_temp_count]['comments'] = $comments_info;
	        $wpf_temp_count++;
	    }
    }
    return json_encode( $response );
}

/*
 * This function is called by function wpf_api_func_get_task_comments to get all the comments of task.
 *
 * @input Int
 * @return JSON
 */
function wpf_api_func_get_task_comments( $task_id ) {
    $comments_args = array(
        'post_id' => $task_id,
        'type'    => 'wp_feedback'
    );
    $comments_info = get_comments( $comments_args );
    foreach ( $comments_info as $comment ) {
        if ( $comment->comment_date_gmt == '0000-00-00 00:00:00' ) {
            $comment->comment_date_gmt = get_gmt_from_date( $comment->comment_date );
        }
        $comment->comment_type = wpf_api_func_get_comment_type( $comment );
        $comment->wpf_user_id  = $comment->user_id;
        $comment->user_id      = '';
    }
    return json_encode( $comments_info );
}

/*
 * This function is called by function wpf_website_task_update_meta to update the notify users of the task.
 *
 * @input Int, Array
 * @return Boolean
 */
function wpf_api_func_update_task_notify_users( $task_id, $task_notify_users ) {
    $task_notify_users = filter_var( $task_notify_users, FILTER_SANITIZE_STRING );
    if ( update_post_meta( $task_id, 'task_notify_users', $task_notify_users ) ) {
        $response = 1;
    } else {
        $response = 0;
    }
    return $response;
}

/*
 * This function is called by function wpf_website_task_update_meta to update the status of the task.
 *
 * @input Int, String
 * @return Boolean
 */
function wpf_api_func_update_task_status( $task_id, $task_status ) {
    $wpf_every_status_change = get_option( 'wpf_every_status_change' );
    $wpf_every_new_complete  = get_option( 'wpf_every_new_complete' );
    $wpf_task_notify_users   = get_option( 'task_notify_users' );
    if ( wp_set_object_terms( $task_id, $task_status, 'task_status', false ) ) {
        $response = 1;
    } else {
        $response = 0;
    }
    return $response;
}

/*
 * This function is called by function wpf_website_task_update_meta to update the priority of the task.
 *
 * @input Int, String
 * @return Boolean
 */
function wpf_api_func_update_task_priority( $task_id, $task_priority ) {
    if ( wp_set_object_terms( $task_id, $task_priority, 'task_priority', false ) ) {
        $response = 1;
    } else {
        $response = 0;
    }
    return $response;
}

/*
 * This function is called by function wpf_website_task_update_meta to add new comment to the task.
 *
 * @input Int, String, Int, String
 * @return Boolean
 */
function wpf_api_func_task_new_comment( $task_id, $author_name, $author_id, $message ) {
    global $wpdb;
    $enabled_wpfeedback    = wpf_check_if_enable( $author_id );
    $wpf_every_new_comment = get_option( 'wpf_every_new_comment' );
    $task_notify_users     = get_post_meta( $task_id, 'task_notify_users', true );
    if ( $enabled_wpfeedback == 1 ) {
        $comment_time         = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
        $task_comment_message = wpf_test_input( $message );
        $commentdata          = array(
            'comment_post_ID'      => $task_id,
            'comment_author'       => $author_name,
            'comment_author_email' => '',
            'comment_author_url'   => '',
            'comment_content'      => $task_comment_message,
            'comment_type'         => 'wp_feedback',
            'comment_parent'       => 0,
            'user_id'              => $author_id,
            'comment_date'         => $comment_time
        );

        $comment_id = $wpdb->insert( "{$wpdb->prefix}comments", $commentdata );
        $comment_id = $wpdb->insert_id;
        $response   = json_encode( array( 'comment_ID' => $comment_id ) );
    } else {
        $response = 0;
    }
    return $response;
}

/*
 * This function is used to get the task url based on task id.
 *
 * @input Int
 * @return String
 */
function wpf_api_func_get_task_url( $task_id ) {
    $task_page_url   = get_post_meta( $task_id, 'task_page_url', true );
    $task_type       = get_post_meta( $task_id, 'task_type', true );
    $task_comment_id = get_post_meta( $task_id, 'task_comment_id', true );
    if ( $task_type == 'general' ) {
        if ( strpos( $task_page_url, 'wpf_taskid' ) !== false ) {
            $filter_task_page_url = explode( "?wpf_taskid", $task_page_url, 2 );
            $task_page_url        = $filter_task_page_url[0];
            $task_reply_url       = $task_page_url . '?wpf_general_taskid=' . $task_id . '&wpf_login=1';
        } else {
            $task_reply_url = $task_page_url . '?wpf_general_taskid=' . $task_id . '&wpf_login=1';
        }
    } else {
        if ( strpos( $task_page_url, 'wpf_general_taskid' ) !== false ) {
            $filter_task_page_url = explode( "?wpf_general_taskid", $task_page_url, 2 );
            $task_page_url        = $filter_task_page_url[0];
            $task_reply_url       = $task_page_url . '?wpf_taskid=' . $task_id . '&wpf_login=1';
        } else {
            $task_reply_url = $task_page_url . '?wpf_taskid=' . $task_comment_id . '&wpf_login=1';
        }
    }
    return $task_reply_url;
}

/*
 * This function is used to get the comment type based comment object.
 *
 * @input Object
 * @return String
 */
function wpf_api_func_get_comment_type( $comment ) {
    if ( strpos( $comment->comment_content, 'wpfeedback-image.s3' ) !== false ) {
        if ( $comment->comment_type == 'image/png' || $comment->comment_type == 'image/gif' || $comment->comment_type == 'image/jpeg' ) {
            $comment_type = 'image';
        } else {
            $comment_type = 'file';
        }
    } elseif ( wp_http_validate_url( $comment->comment_content ) && ! strpos( $comment->comment_content, 'wpfeedback-image.s3' ) ) {
        $idVideo = $comment->comment_content;
        $link    = explode( "?v=", $idVideo );
        if ( $link[0] == 'https://www.youtube.com/watch' ) {
            $youtubeUrl = "http://www.youtube.com/oembed?url=$idVideo&format=json";
            $docHead    = get_headers( $youtubeUrl );
            if ( substr( $docHead[0], 9, 3 ) !== "404" ) {
                $comment_type = 'youtube_video';
            } else {
                $comment_type = 'normal_text';
            }
        } else {
            $comment_type = 'normal_text';
        }
    } else {
        $comment_type = 'normal_text';
    }
    return $comment_type;
}

/*
 * This function is called by function wpf_website_new_general_task to create a new general task when created from APP.
 *
 * @input Array
 * @return JSON
 */
function wpf_api_func_task_new_general_task( $task_info ) {
    global $wpdb;
    $current_user       = get_userdata( get_option( 'wpf_website_developer' ) );
    $user_id            = $current_user->ID;
    $table              = $wpdb->prefix . 'postmeta';
    $comment_count      = get_last_task_id();
    $wpf_every_new_task = get_option( 'wpf_every_new_task' );
    $task_data          = (array)$task_info;

    foreach ( $task_data as $key => $val ) {
        $task_data[$key] = filter_var( $val, FILTER_SANITIZE_STRING );
    }
    if ( $task_data['task_page_title'] == '' ) {
        $task_data['task_page_title'] = get_the_title( $task_data['current_page_id'] );
    }
    if ( $task_data['task_page_url'] == '' ) {
        $task_data['task_page_url'] = $current_page_url = get_permalink( $task_data['current_page_id'] );
    }
    if ( $task_data['wpf_current_screen'] != '' ) {
        $wpf_current_screen = $task_data['wpf_current_screen'];
        $post_status        = 'wpf_admin';
    } else {
        $wpf_current_screen = '';
        $post_status        = 'publish';
    }

    $new_task = array(
        'post_content' => '',
        'post_status'  => $post_status,
        'post_title'   => stripslashes( html_entity_decode( $task_data['task_title'], ENT_QUOTES, 'UTF-8' ) ),
        'post_type'    => 'wpfeedback',
        'post_author'  => $user_id,
        'post_parent'  => $task_data['current_page_id']
    );
    $task_id = wp_insert_post( $new_task );

    add_post_meta( $task_id, 'task_config_author_browser', $task_data['task_config_author_browser'] );
    add_post_meta( $task_id, 'task_config_author_browserVersion', $task_data['task_config_author_browserVersion'] );
    add_post_meta( $task_id, 'task_config_author_browserOS', $task_data['task_config_author_browserOS'] );
    add_post_meta( $task_id, 'task_config_author_name', $task_data['task_config_author_name'] );
    add_post_meta( $task_id, 'task_config_author_id', $user_id );
    add_post_meta( $task_id, 'task_config_author_resX', $task_data['task_config_author_resX'] );
    add_post_meta( $task_id, 'task_config_author_resY', $task_data['task_config_author_resY'] );
    add_post_meta( $task_id, 'task_title', $task_data['task_title'] );
    add_post_meta( $task_id, 'task_page_url', $task_data['task_page_url'] );
    add_post_meta( $task_id, 'task_page_title', $task_data['task_page_title'] );
    add_post_meta( $task_id, 'task_comment_message', $task_data['task_comment_message'] );
    add_post_meta( $task_id, 'task_element_path', $task_data['task_element_path'] );
    add_post_meta( $task_id, 'wpfb_task_bubble', $task_data['task_clean_dom_elem_path'] );
    add_post_meta( $task_id, 'task_element_html', $task_data['task_element_html'] );
    add_post_meta( $task_id, 'task_X', $task_data['task_X'] );
    add_post_meta( $task_id, 'task_Y', $task_data['task_Y'] );
    add_post_meta( $task_id, 'task_elementX', $task_data['task_elementX'] );
    add_post_meta( $task_id, 'task_elementY', $task_data['task_elementY'] );
    add_post_meta( $task_id, 'task_relativeX', $task_data['task_relativeX'] );
    add_post_meta( $task_id, 'task_relativeY', $task_data['task_relativeY'] );
    add_post_meta( $task_id, 'task_notify_users', $task_data['task_notify_users'] );
    add_post_meta( $task_id, 'task_element_height', $task_data['task_element_height'] );
    add_post_meta( $task_id, 'task_element_width', $task_data['task_element_width'] );
    add_post_meta( $task_id, 'task_comment_id', $comment_count );
    add_post_meta( $task_id, 'task_type', $task_data['task_type'] );
    add_post_meta( $task_id, 'task_top', $task_data['task_top'] );
    add_post_meta( $task_id, 'task_left', $task_data['task_left'] );

    if ( $wpf_current_screen != '' ) {
        add_post_meta( $task_id, 'wpf_current_screen', $wpf_current_screen );
    }
    wp_set_object_terms( $task_id, $task_data['task_status'], 'task_status', true );
    wp_set_object_terms( $task_id, $task_data['task_priority'], 'task_priority', true );

    $task_comment_message = wpf_test_input( $task_data['task_comment_message'] );
    $comment_time         = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
    $commentdata          = array(
        'comment_post_ID'      => $task_id,
        'comment_author'       => $task_data['task_config_author_name'],
        'comment_author_email' => '',
        'comment_author_url'   => '',
        'comment_content'      => stripslashes( html_entity_decode( $task_comment_message, ENT_QUOTES, 'UTF-8' ) ),
        'comment_type'         => 'wp_feedback',
        'comment_parent'       => 0,
        'user_id'              => $user_id,
        'comment_date'         => $comment_time
    );
    $comment_id                       = $wpdb->insert( "{$wpdb->prefix}comments", $commentdata );
    $response                         = array();
    $response['task_id']              = $task_id;
    $response['task_page_url']        = $task_data['task_page_url'];
    $response['task_comment_id']      = $comment_count;
    $response['wpf_task_url']         = wpf_api_func_get_task_url( $task_id );
    $response['task_comment_message'] = $task_comment_message;
    $response['wpfb_task_bubble']     = '';
    return json_encode( $response );
}

/*
 * Functions to update CRM
 */

/*
 * This function is used to update the notify uses of task on APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_update_notify_users( $task_info ) {
    $url                 = WPF_CRM_API . 'wp-api/task/update-task-details';
    $current_user        = wp_get_current_user();
    $data['user_id']     = $current_user->ID;
    $data['method']      = 'notify_users';
    $data['task_id']     = $task_info['task_id'];
    $data['value']       = $task_info['task_notify_users'];
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_update_notify_users_action', 'wpf_crm_update_notify_users' );

/*
 * This function is used to update the status of task on APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_update_task_status( $task_info ) {
    $url                 = WPF_CRM_API . 'wp-api/task/update-task-details';
    $current_user        = wp_get_current_user();
    $data['user_id']     = $current_user->ID;
    $data['method']      = 'status';
    $data['task_id']     = $task_info['task_id'];
    $data['value']       = $task_info['task_status'];
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_update_task_status_action', 'wpf_crm_update_task_status' );

/*
 * This function is used to mark the task internal on APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_mark_as_internal( $task_info ) {
    $url                 = WPF_CRM_API . 'wp-api/task/internal';
    $current_user        = wp_get_current_user();
    $data['user_id']     = $current_user->ID;
    $data['task_id']     = $task_info['task_id'];
    $data['internal']    = $task_info['internal'];
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    $response            = wpf_send_remote_post( $url, $response );  
    echo json_encode( $response );
}
add_action( 'wpf_crm_mark_as_internal', 'wpf_crm_mark_as_internal' );

/*
 * This function is used to update the priority of task on APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_update_task_priority( $task_info ) {
    $url                 = WPF_CRM_API . 'wp-api/task/update-task-details';
    $current_user        = wp_get_current_user();
    $data['user_id']     = $current_user->ID;
    $data['method']      = 'priority';
    $data['task_id']     = $task_info['task_id'];
    $data['value']       = $task_info['task_priority'];
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_update_task_priority_action', 'wpf_crm_update_task_priority' );

/*
 * This function is used to update the tags of task on APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_update_task_tags( $wpf_task_tag_info ) {
    $url                       = WPF_CRM_API . 'wp-api/task/update-task-details';
    $current_user              = wp_get_current_user();
    $data['user_id']           = $current_user->ID;
    $data['method']            = 'wpf_tags';
    $data['action']            = 'add_tags';
    $data['task_id']           = $wpf_task_tag_info['wpf_task_id'];
    $data['wpf_task_tag_name'] = $wpf_task_tag_info['wpf_task_tag_name'];
    $data['wpf_task_tag_slug'] = $wpf_task_tag_info['wpf_task_tag_name'];
    $data['from_wp']           = 1;
    $data['wpf_site_id']       = get_option( 'wpf_site_id' );
    $response                  = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_update_tag_action', 'wpf_crm_update_task_tags' );

/*
 * This function is used to delete the tasg of task on APP when deleted on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_delete_task_tags( $wpf_task_tag_info ) {
    $url                       = WPF_CRM_API . 'wp-api/task/update-task-details';
    $current_user              = wp_get_current_user();
    $data['user_id']           = $current_user->ID;
    $data['method']            = 'wpf_tags';
    $data['action']            = 'delete_tags';
    $data['task_id']           = $wpf_task_tag_info['wpf_task_id'];
    $data['wpf_task_tag_name'] = $wpf_task_tag_info['wpf_task_tag_name'];
    $data['wpf_task_tag_slug'] = $wpf_task_tag_info['wpf_task_tag_slug'];
    $data['from_wp']           = 1;
    $data['wpf_site_id']       = get_option( 'wpf_site_id' );
    $response                  = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_delete_task_tags_action', 'wpf_crm_delete_task_tags' );

/*
 * This function is used to create a new comment of task on APP when created on the website.
 *
 * @input Int
 * @return NULL
 */
function wpf_crm_new_comment( $comment_id ) {
    $url         = WPF_CRM_API . 'wp-api/comment/store';
    $new_comment = get_comment( $comment_id );
    if ( $new_comment->comment_date_gmt == '0000-00-00 00:00:00' ) {
        $new_comment->comment_date_gmt = get_gmt_from_date( $new_comment->comment_date );
    }
    $new_comment->comment_type = wpf_api_func_get_comment_type( $new_comment );
    $new_comment->task_id      = $new_comment->comment_post_ID;
    $new_comment->wpf_site_id  = get_option( 'wpf_site_id' );
    $response                  = json_encode( $new_comment );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_new_comment_action', 'wpf_crm_new_comment' );

/*
 * This function is used to create a new task on APP when created on the website.
 *
 * @input Int
 * @return NULL
 */
function wpf_crm_new_task( $task_id ) {
    $url                      = WPF_CRM_API . 'wp-api/task/store-task-from-wp';	
	$actual_link              = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $task_date                = get_the_time( 'Y-m-d H:i:s', $task_id );
    $metas                    = get_post_meta( $task_id );
    $task_priority            = get_the_terms( $task_id, 'task_priority' );
    $task_status              = get_the_terms( $task_id, 'task_status' );
    $wpf_site_id              = get_option( 'wpf_site_id' );
    $response['wpf_site_id']  = $wpf_site_id;
    $response['wpf_task_id']  = $task_id;
    $response['wpf_task_url'] = wpf_api_func_get_task_url( $task_id );
    
	if ( strpos( $actual_link, 'wp-admin' ) ) {
        $response['is_admin_task'] = 1;
    } else {
        $response['is_admin_task'] = 0;
    }
    foreach ( $metas as $key => $value ) {
        $response[$key]            = $value[0];
        $response['task_priority'] = $task_priority[0]->slug;
        $response['task_status']   = $task_status[0]->slug;
        $task_date1                = date_create( $task_date );
        $wpf_wp_current_timestamp  = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
        $task_date2                = date_create( $wpf_wp_current_timestamp );
        $curr_comment_time         = wpfb_time_difference( $task_date1, $task_date2 );
        $response['task_time']     = $task_date;
    }

    $comments_args = array(
        'post_id' => $task_id,
        'type'    => 'wp_feedback'
    );
    $comments_info = get_comments( $comments_args );
    foreach ( $comments_info as $comment ) {
        if ( $comment->comment_date_gmt == '0000-00-00 00:00:00' ) {
            $comment->comment_date_gmt = get_gmt_from_date( $comment->comment_date );
        }
        $comment->comment_type = wpf_api_func_get_comment_type( $comment );
        $comment->wpf_user_id  = $comment->user_id;
        $comment->user_id      = '';
    }
    $response['comments'] = $comments_info;
    $response             = json_encode( $response );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_new_task_action', 'wpf_crm_new_task' );

/*
 * This function is used to create a new screenshot of the task on APP when created on the website.
 *
 * @input Int
 * @return NULL
 */
function wpf_crm_new_task_screenshot( $task_id ) {
    $url                 = WPF_CRM_API . 'wp-api/task/update-task-details';
    $wpf_task_screenshot = get_post_meta( $task_id, 'wpf_task_screenshot', true );
    $data['method']      = 'screenshot';
    $data['task_id']     = $task_id;
    $data['value']       = $wpf_task_screenshot;
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_new_task_screenshot_action', 'wpf_crm_new_task_screenshot' );


/*
 * This function is used to update the title of the task on the APP when updated on the website.
 *
 * @input Array
 * @return NULL
 */
function wpf_crm_update_task_title( $task_info ) {
    $url                 = WPF_CRM_API . 'wp-api/task/update-task-details';
    $data['method']      = 'task_title';
    $data['task_id']     = $task_info['wpf_task_id'];
    $data['value']       = $task_info['wpf_new_task_title'];
    $data['from_wp']     = 1;
    $data['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response            = json_encode( $data );
    wpf_send_remote_post( $url, $response );
}
add_action( 'wpf_crm_update_task_title_action', 'wpf_crm_update_task_title' );

/*
 * This function is used to register a new API route for the auto login feature.
 *
 * @input NULL
 * @return NULL
 */
function wpf_autologin_api_hook() {
    register_rest_route(
        'wpf_api', '/wpf_autologin/',
        array(
            'methods'  => 'GET',
            'callback' => 'wpf_autologin',
            'permission_callback' => '__return_true'
        )
    );
}
add_action( 'rest_api_init', 'wpf_autologin_api_hook' );

/*
 * This function is used to auto login the user to website based on the token.
 *
 * @input NULL
 * @return NULL
 */
add_action( 'init', 'wpf_autologin_init_hook' );
function wpf_autologin_init_hook() {
    if ( isset( $_GET['wpf_token'] ) ) {
        $wpf_token_request = array();
        $wpf_token_request = array_merge( $_GET, $_SERVER );
        wpf_autologin( $wpf_token_request );
    }
}

/*
 * This function is called by wpf_autologin_init_hook to auto login the user to website.
 *
 * @input Array
 * @return NULL
 */
function wpf_autologin( $request ) {
	global $site_url, $wpdb;
	if ( $_GET['wpf_taskid'] ) {
		$comment_id = sanitize_text_field( $_GET['wpf_taskid'] );
	} elseif ( $_GET['wpf_general_taskid'] ) {
		$comment_id = sanitize_text_field( $_GET['wpf_general_taskid'] );
	} else {
		$comment_id = '';
	}
    $url                     = WPF_CRM_API . 'wp-api/user/verify-access';
    $response                = array();
    $response['wpf_site_id'] = get_option( 'wpf_site_id' );
    $response                = json_encode( $response );
    $res                     = wpf_send_remote_post( $url, $response, $request['wpf_token'] );
    if ( $res['status'] == 1 ) {
        if ( ! is_user_logged_in() ) {
            $user = get_user_by( 'email', $request['wpf_username'] );
            if ( ! is_wp_error( $user ) ) {
                wp_clear_auth_cookie();
                wp_set_current_user ( $user->ID );
                wp_set_auth_cookie  ( $user->ID );
                setcookie('_wordpress_test_cookie', 'wpf_test', 900, '/' );

                if ( $comment_id != '' ) {
                    $prepared_sql = $wpdb->prepare(
                        "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'task_comment_id' and meta_value = %d",
                        $comment_id
                    );
                    //$sql       = "select post_id from {$wpdb->prefix}wp_postmeta where meta_key='task_comment_id' and meta_value='" . $comment_id . "'";
                    $task_info = $wpdb->get_results( $prepared_sql, OBJECT );
                    $task_id   = $task_info[0]->post_id;
                    $task_url  = get_post_meta( $task_id, 'task_page_url', true );
                    if ( $_GET['wpf_taskid'] ) {
                        if ( ( ( strpos( $task_url, 'wp-admin' ) ) && ( ( strpos( $task_url, 'post_type' ) ) ) || ( ( strpos( $task_url, 'page' ) ) ) ) !== false ) {
                            $redirect_to = $task_url . '&wpf_taskid=' . $comment_id;
                        } else {
                            $redirect_to = $task_url . '?wpf_taskid=' . $comment_id;	
                        }
                    } else {
                        $prepared_sql = $wpdb->prepare(
                            "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d and AND meta_key = 'task_page_url'",
                            $comment_id
                        );
                        //$sql       = "SELECT * FROM {$wpdb->prefix}wp_postmeta WHERE `post_id` = " . $comment_id . " AND `meta_key` = 'task_page_url'";
                        $task_info = $wpdb->get_results( $prepared_sql, OBJECT );
                        $task_url  = $task_info[0]->meta_value;                        
                        if ( ( ( strpos( $task_url, 'wp-admin' ) ) && ( strpos( $task_url, 'post_type=page' ) ) ) !== false ) {
                            $redirect_to = $task_url . '&wpf_general_taskid=' . $comment_id;		
                        } else {
                            $redirect_to = $task_url . '?wpf_general_taskid=' . $comment_id;
                        }
                    }                
                } else {
                    $redirect_to = $site_url;
                }
                wp_safe_redirect( $redirect_to );
                exit();
            } else {
                $redirect_to = user_admin_url();
                wp_safe_redirect( $redirect_to );
                exit();
            }
        } else {
            if ( $comment_id != '' ) {
                $prepared_sql = $wpdb->prepare(
                    "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'task_comment_id' AND meta_value = %d",
                    $comment_id
                );
                //$sql       = "select post_id from {$wpdb->prefix}wp_postmeta where meta_key='task_comment_id' and meta_value='" . $comment_id . "'";
                $task_info = $wpdb->get_results( $prepared_sql, OBJECT );
                $task_id   = $task_info[0]->post_id;
                $task_url  = get_post_meta( $task_id, 'task_page_url', true );
                if ( $_GET['wpf_taskid'] ) {
                    if ( ( ( strpos( $task_url, 'wp-admin' ) ) && ( ( strpos( $task_url, 'post_type' ) ) ) || ( ( strpos( $task_url, 'page' ) ) ) ) !== false ) {
                        $redirect_to = $task_url . '&wpf_taskid=' . $comment_id;
                    } else {
                        $redirect_to = $task_url . '?wpf_taskid=' . $comment_id;	
                    }
                } else {
                    $prepared_sql = $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d AND meta_key = 'task_page_url'",
                        $comment_id
                    );
                    //$sql       = "SELECT * FROM {$wpdb->prefix}wp_postmeta WHERE `post_id` = " . $comment_id . " AND `meta_key` = 'task_page_url'";
                    $task_info = $wpdb->get_results( $prepared_sql, OBJECT );
                    $task_url  = $task_info[0]->meta_value;                    
                    if ( ( ( strpos( $task_url, 'wp-admin' ) ) && ( strpos( $task_url, 'post_type=page' ) ) ) !== false ) {
                        $redirect_to = $task_url . '&wpf_general_taskid=' . $comment_id;		
                    } else {
                        $redirect_to = $task_url . '?wpf_general_taskid=' . $comment_id;
                    }
                }      
            } else {
                $redirect_to = $site_url;
            }
            wp_safe_redirect( $redirect_to );
            exit();
        }
    } else {
        $redirect_to = user_admin_url();
        wp_safe_redirect( $redirect_to );
        exit;
    }
}

/*
 * General Functions
 */

/*
 * This function is called from APP when website is requested to resync. This function is also called from the website when the button "Resync the Central Dashboard" button is clicked.
 * URL: DOMAIN/wp-admin/admin-ajax.php?action=wpf_initial_sync
 *
 * @input String
 * @return Boolean
 */
function wpf_initial_sync( $wpf_license_key ) { 
    $url                     = WPF_CRM_API . 'sitedata/sync';
    $response                = array();
    $wpf_site_id             = get_option( 'wpf_site_id' );
    $response['license_key'] = $wpf_license_key;
    $response['wpf_site_id'] = $wpf_site_id;
    $response['url']         = WPF_SITE_URL;
    $response['name']        = get_option( 'blogname' );
    $response['from_plugin'] = 1;
    $response['users']       = json_decode( wpf_api_func_get_users() );
    $settings[]              = ['name' => 'wpf_website_developer', 'value' => get_option( "wpf_website_developer" )];
    $response['settings']    = $settings;
    $body                    = json_encode( $response );  
    $res                     = wpf_send_remote_post( $url, $body );
    
    if ( isset( $res['status'] ) && $res['status'] == 1 ) {
	    update_default_site_data();
        $res=0;
    } else {
        $res = 1;
    }
    return $res;
}
add_action( 'wpf_initial_sync', 'wpf_initial_sync', 1 );

/*
 * This function is used to get the global settings from the APP when requested from the website.
 *
 * @input NULL
 * @return Boolean
 */
function wpf_get_global_settings() {
    $url                     = WPF_CRM_API . 'wp-api/user/global-settings';
    $response                = array();
    $response['wpf_site_id'] = get_option( 'wpf_site_id' );
    $body                    = json_encode( $response );
    $res                     = wpf_send_remote_post( $url, $body );
    if ( $res['status'] == 1 ) {
        wpf_api_update_general_settings($res['data']);
        return 1;
    } else {
        return 0;
    }
}

/*
 * This function is called by wpf_get_global_settings to update all the general settings when requested from APP.
 *
 * @input Object
 * @return NULL
 */
function wpf_api_update_general_settings( $settings ) {
    foreach ( $settings->general_setting as $general_setting ) {
        if ( $general_setting->value == 1 ) {
            $temp_val = "yes";
        } else {
            $temp_val = "";
        }
        if ( $general_setting->key == 'wpfeedback_font_awesome_script' ) {
            $general_setting->key = 'wpfeedback_font_awesome_script';
        }
        update_option( $general_setting->key, $temp_val, 'no' );
    }
    foreach ( $settings->white_label as $key => $val ) {
        if ( $key == 'wpfeedback_powered_by' ) {
            if ( $val->value == 1 ) {
                $temp_val = "yes";
            } else {
                $temp_val = "";
            }
            update_option( 'wpf_powered_by', $temp_val, 'no' );
        } else {
            if ( $key == 'wpfeedback_logo' ) {
                $key = 'wpf_logo';
            }
            if ( $key == 'wpfeedback_favicon' ) {
                $key = 'wpf_favicon';
            }
            if ( $key == 'wpfeedback_color' ) {
                $key = 'wpf_color';
                $val = str_replace( '#', '', $val );
            }
            update_option( $key, $val, 'no' );
        }
    }
    foreach ( $settings->notification_setting as $key => $val ) {
        if ( $key == 'email_notification' ) {
            foreach ( $val as $notification ) {
                if ( $notification->value == 1 ) {
                    $temp_val = "yes";
                } else {
                    $temp_val = "";
                }
                update_option( $notification->key, $temp_val, 'no' );
            }
        } elseif ( $key == 'wpfeedback_more_emails' ) {
            update_option( 'wpf_more_emails', $val, 'no' );
        } else {
            update_option( $key, $val, 'no' );
        }
    }
}

/*
 * This function is used to upload the logo to website when uploaded to APP. This function is not used currently.
 *
 * @input String, Int
 * @return Int
 */
function wpf_upload_logo_from_dashboard( $image_url, $parent_id = '' ) {
    $image = $image_url;
    $get   = wp_remote_get( $image );
    $type  = wp_remote_retrieve_header( $get, 'content-type' );

    if ( ! $type ) {
        return false;
    }

    $mirror     = wp_upload_bits( basename( $image ), '', wp_remote_retrieve_body( $get ) );
    $attachment = array(
        'post_title'     => basename( $image ),
        'post_mime_type' => $type
    );

    $attach_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
}


/*
 * This function is used to upload the favicon to website when uploaded to APP. This function is not used currently.
 *
 * @input String, Int
 * @return Int
 */
function wpf_upload_favicon_from_dashboard( $image_url, $parent_id = '' ) {
    $image = $image_url;
    $get   = wp_remote_get( $image );
    $type  = wp_remote_retrieve_header( $get, 'content-type' );

    if ( ! $type ) {
        return false;
    }

    $mirror     = wp_upload_bits( basename( $image ), '', wp_remote_retrieve_body( $get ) );
    $attachment = array(
        'post_title'     => basename( $image ),
        'post_mime_type' => $type
    );

    $attach_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
}

/*
 * This function is used to generate the response signature based on the input.
 *
 * @input String
 * @return String
 */
function wpf_generate_response_signature( $response ) {
    $wpf_license_key_enc = get_option( 'wpf_license_key' );
    $wpf_license_key     = wpf_crypt_key( $wpf_license_key_enc, 'd' );    
    $response_signature  = hash_hmac( 'sha256', $response, $wpf_license_key );
    return $response_signature;
}

/*
 * This function is used to communicate between the website and the APP.
 *
 * @input String, String, String
 * @return JSON
 */
function wpf_send_remote_post( $url, $response, $wpf_token = '', $is_print = 0 ) {
    if ( get_option( 'atarim_server_down' ) == 'true' && ( get_option( 'atarim_server_check_count' ) == '2' ) ) {
        return;
    }
    $response_signature = wpf_generate_response_signature( $response );
    if ( $wpf_token == '' ) {
        $header = array( 'Content-Type' => 'application/json; charset=utf-8', 'Accept' => 'application/json', 'response-signature' => $response_signature );
    } else {
        $header = array( 'Content-Type' => 'application/json; charset=utf-8', 'Accept' => 'application/json', 'response-signature' => $response_signature, 'Authorization' => 'Bearer ' . $wpf_token );
    }

    $args = array(
        'headers'     => $header,
        'body'        => $response,
        'method'      => 'POST',
        'data_format' => 'body',
        'timeout'     => 100,
    );

    // bypass SSL error
    add_filter( 'https_ssl_verify', '__return_false' );

    $response = wp_remote_post( $url, $args );
    /*$ip = "49.43.32.130";
	if (filter_var($ip, FILTER_VALIDATE_IP)) {
		echo '<pre>';
		print_r($response);
	}*/

    if ( ! is_wp_error( $response ) ) {
        if ( $response['response']['code'] == 504 ) {
            return json_encode( $response['response'], true );
        } elseif ( $response['response']['code'] == 403 ) {
           update_option( 'wpf_license', 'invalid' );
        }
    }

    if ( is_wp_error( $response ) ) {
        $unix_time_now  = time();
        $unix_time_now += 1800;
        update_option( 'atarim_server_down', 'true', 'no' );
        $atarim_server_check_count = get_option( 'atarim_server_check_count' );
        if ( $atarim_server_check_count == '1' ) {
            update_option( 'atarim_server_check_count', '2', 'no' );
        } else {
            update_option( 'atarim_server_check_count', '1', 'no' );
        }

        update_option( 'atarim_server_down_check', $unix_time_now, 'no' );
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
    } else {
        $real_response = json_decode( wp_remote_retrieve_body( $response ), true );        
        update_option( 'atarim_server_check_count', '0', 'no' );
        if ( ! empty( $real_response['wpf_license'] ) ) {
            //update_option( 'wpf_license', base64_decode( $real_response['wpf_license'] ) );
        }
        return $real_response;
    }
}

/*
 * This function is used to move
 * all wordpress wpf data to api
 * when plugin 2.0 updated
 */
//add_action('wpf_move_all_data', 'move_all_old_data', 1);

function move_all_old_data() {
    // get URL without query parameter
    $url = home_url( $_SERVER['REQUEST_URI'] );
    if ( strpos( $url, '?' ) !== FALSE ) {
        $parts = explode( '?', $url );
        $url   = $parts[0];
    }

    $response_array                    = array();
    $settings                          = $site = [];
    $site['name']                      = get_option('blogname');
    $site['image']                     = "image";
    $site['favicon']                   = "icon";
    $site['url']                       = $url;
    $wpf_license_key_enc               = get_option( 'wpf_license_key' );
    $wpf_license_key                   = wpf_crypt_key( $wpf_license_key_enc, 'd' );
    $response_array['wpf_license_key'] = $wpf_license_key;
    $response_array['wpf_site_id']     = get_option( 'wpf_site_id' );

    /* site data */
    array_push( $settings, ['name' => 'wpf_site_id', 'value' => get_option( 'wpf_site_id' )] );
    array_push( $settings, ['name' => 'wpf_initial_sync', 'value' => get_option( 'wpf_initial_sync' )] );
    array_push( $settings, ['name' => 'wpfeedback_color', 'value' => get_option( 'wpf_color' )] );
    array_push( $settings, ['name' => 'wpf_selcted_role', 'value' => get_option( 'wpf_selcted_role' )] );
    array_push( $settings, ['name' => 'wpf_website_developer', 'value' => get_option( 'wpf_website_developer' )] );
    array_push( $settings, ['name' => 'wpf_show_front_stikers', 'value' => get_option( 'wpf_show_front_stikers') == 'yes' ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_customisations_client', 'value' => get_option( 'wpf_customisations_client' )] );
    array_push( $settings, ['name' => 'wpf_customisations_webmaster', 'value' => get_option( 'wpf_customisations_webmaster' )] );
    array_push( $settings, ['name' => 'wpf_customisations_others', 'value' => get_option( 'wpf_customisations_others' )] );
    array_push( $settings, ['name' => 'wpf_from_email', 'value' => get_option( 'wpf_from_email' )] );
    array_push( $settings, ['name' => 'wpf_allow_guest', 'value' => get_option( 'wpf_allow_guest') == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_global_settings', 'value' => get_option( 'wpf_global_settings') == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_disable_for_admin', 'value' => get_option( 'wpf_disable_for_admin') == "yes" ? "yes" : "no"] );
    array_push( $settings, ['name' => 'wpf_allow_backend_commenting', 'value' => get_option( 'wpf_allow_backend_commenting') == "yes" ? "yes" : "no"] );
    array_push( $settings, ['name' => 'wpf_website_client', 'value' => get_option( 'wpf_website_client' )] );
    array_push( $settings, ['name' => 'wpf_license', 'value' => get_option( 'wpf_license' )] );
    array_push( $settings, ['name' => 'wpf_license_expires', 'value' => get_option( 'wpf_license_expires' )] );
    array_push( $settings, ['name' => 'wpf_decr_key', 'value' => get_option( 'wpf_decr_key' )] );
    array_push( $settings, ['name' => 'wpf_decr_checksum', 'value' => get_option( 'wpf_decr_checksum' )] );
    array_push( $settings, ['name' => 'enabled_wpfeedback', 'value' => get_option( 'wpf_enabled' ) == 'yes' ? 'yes' : 'no'] );
    // => v2.1.0
    array_push( $settings, ['name' => 'wpf_enabled_compact_mode', 'value' => get_option( 'wpf_enabled_compact_mode' ) == 'yes' ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_allow_backend_commenting', 'value' => get_option( 'wpf_allow_backend_commenting' ) == "yes" ? get_option( 'wpf_allow_backend_commenting' ) : "no"] );
    array_push( $settings, ['name' => 'wpf_more_emails', 'value' => get_option( 'wpf_more_emails' )] );
    array_push( $settings, ['name' => 'wpfeedback_powered_by', 'value' => get_option( 'wpfeedback_powered_by' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_every_new_task', 'value' => get_option( 'wpf_every_new_task' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_every_new_comment', 'value' => get_option( 'wpf_every_new_comment' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_every_new_complete', 'value' => get_option( 'wpf_every_new_complete' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_every_status_change', 'value' => get_option( 'wpf_every_status_change' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_daily_report', 'value' => get_option( 'wpf_daily_report' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_weekly_report', 'value' => get_option( 'wpf_weekly_report' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_auto_daily_report', 'value' => get_option( 'wpf_auto_daily_report' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_auto_weekly_report', 'value' => get_option( 'wpf_auto_weekly_report' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_initial_setup', 'value' => get_option( 'wpf_initial_setup' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_tutorial_video', 'value' => get_option( 'wpf_tutorial_video' )] );
    $logo      = get_post( get_option( 'wpf_logo' ) );
    $logo_file = [];
    if ( isset( $logo->guid ) ) {
        $logo_file['file'] = 'data:' . $logo->post_mime_type . ';base64,' . base64_encode( file_get_contents( $logo->guid ) );
        $logo_file['name'] = $logo->post_name;
    }
    $favicon      = get_post( get_option( 'wpf_favicon' ) );
    $favicon_file = [];
    if ( isset( $favicon->guid ) ) {
        $favicon_file['file'] = 'data:' . $favicon->post_mime_type . ';base64,' . base64_encode( file_get_contents( $favicon->guid ) );
        $favicon_file['name'] = $favicon->post_name;
    }

    array_push( $settings, ['name' => 'wpf_enable_clear_cache', 'value' => get_option( 'wpf_enable_clear_cache' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'delete_data_wpfeedback', 'value' => get_option( 'delete_data_wpfeedback' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpfeedback_disable_for_app', 'value' => get_option( 'wpfeedback_disable_for_app' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_user_client', 'value' => get_option( 'wpf_tab_permission_user_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_user_webmaster', 'value' => get_option( 'wpf_tab_permission_user_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_user_others', 'value' => get_option( 'wpf_tab_permission_user_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_user_guest', 'value' => get_option( 'wpf_tab_permission_user_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_priority_client', 'value' => get_option( 'wpf_tab_permission_priority_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_priority_webmaster', 'value' => get_option( 'wpf_tab_permission_priority_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_priority_others', 'value' => get_option( 'wpf_tab_permission_priority_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_priority_guest', 'value' => get_option( 'wpf_tab_permission_priority_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_status_client', 'value' => get_option( 'wpf_tab_permission_status_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_status_webmaster', 'value' => get_option( 'wpf_tab_permission_status_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_status_others', 'value' => get_option( 'wpf_tab_permission_status_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_status_guest', 'value' => get_option( 'wpf_tab_permission_status_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_screenshot_client', 'value' => get_option( 'wpf_tab_permission_screenshot_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_screenshot_webmaster', 'value' => get_option( 'wpf_tab_permission_screenshot_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_screenshot_others', 'value' => get_option( 'wpf_tab_permission_screenshot_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_screenshot_guest', 'value' => get_option( 'wpf_tab_permission_screenshot_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_information_client', 'value' => get_option( 'wpf_tab_permission_information_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_information_webmaster', 'value' => get_option( 'wpf_tab_permission_information_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_information_others', 'value' => get_option( 'wpf_tab_permission_information_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_information_guest', 'value' => get_option( 'wpf_tab_permission_information_guest' ) == "yes" ? 'yes' : 'no'] );  
    array_push( $settings, ['name' => 'wpf_tab_auto_screenshot_task_guest', 'value' => get_option( 'wpf_tab_auto_screenshot_task_guest' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_tab_auto_screenshot_task_others', 'value' => get_option( 'wpf_tab_auto_screenshot_task_others' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_tab_auto_screenshot_task_webmaster', 'value' => get_option( 'wpf_tab_auto_screenshot_task_webmaster' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_tab_auto_screenshot_task_client', 'value' => get_option( 'wpf_tab_auto_screenshot_task_client' ) == "yes" ? 'yes' : "no"] );
    array_push( $settings, ['name' => 'wpf_tab_permission_delete_task_client', 'value' => get_option( 'wpf_tab_permission_delete_task_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_delete_task_webmaster', 'value' => get_option( 'wpf_tab_permission_delete_task_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_delete_task_others', 'value' => get_option( 'wpf_tab_permission_delete_task_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_delete_task_guest', 'value' => get_option( 'wpf_tab_permission_delete_task_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_stickers_client', 'value' => get_option( 'wpf_tab_permission_display_stickers_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_stickers_webmaster', 'value' => get_option( 'wpf_tab_permission_display_stickers_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_stickers_others', 'value' => get_option( 'wpf_tab_permission_display_stickers_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_stickers_guest', 'value' => get_option( 'wpf_tab_permission_display_stickers_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_task_id_client', 'value' => get_option( 'wpf_tab_permission_display_task_id_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_task_id_webmaster', 'value' => get_option( 'wpf_tab_permission_display_task_id_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_task_id_others', 'value' => get_option( 'wpf_tab_permission_display_task_id_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_display_task_id_guest', 'value' => get_option( 'wpf_tab_permission_display_task_id_guest' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_keyboard_shortcut_client', 'value' => get_option( 'wpf_tab_permission_keyboard_shortcut_client' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_keyboard_shortcut_webmaster', 'value' => get_option( 'wpf_tab_permission_keyboard_shortcut_webmaster' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_keyboard_shortcut_others', 'value' => get_option( 'wpf_tab_permission_keyboard_shortcut_others' ) == "yes" ? 'yes' : 'no'] );
    array_push( $settings, ['name' => 'wpf_tab_permission_keyboard_shortcut_guest', 'value' => get_option( 'wpf_tab_permission_keyboard_shortcut_guest' ) == "yes" ? 'yes' : 'no'] );
    // users
    $users                      = json_decode( wpf_api_func_get_users(), true );
    $response_array['site']     = $site;
    $response_array['settings'] = $settings;
    $response_array['pages']    = json_decode( wpf_get_page_list( 'api' ) );
    $response_array['tasks']    = json_decode( wpf_api_func_get_tasks() );

    if ( ! empty( $users ) ) {
        foreach ( $users as $key => $user ) {
            $users[$key]['notification_arr']['every_new_task'] = ( get_user_meta( $user['wpf_id'], 'every_new_task', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['every_new_comment'] = ( get_user_meta( $user['wpf_id'], 'every_new_comment', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['every_status_change'] = ( get_user_meta( $user['wpf_id'], 'every_status_change', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['daily_report'] = ( get_user_meta( $user['wpf_id'], 'daily_report', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['weekly_report'] = ( get_user_meta( $user['wpf_id'], 'weekly_report', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['wpf_every_new_complete'] = ( get_user_meta( $user['wpf_id'], 'wpf_every_new_complete', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['wpf_auto_daily_report'] = ( get_user_meta( $user['wpf_id'], 'wpf_auto_daily_report', true ) == "1" ) ? "1" : "0";
            $users[$key]['notification_arr']['wpf_auto_weekly_report'] = ( get_user_meta( $user['wpf_id'], 'wpf_auto_weekly_report', true ) == "1" ) ? "1" : "0";
        }
    }
    $response_array['users'] = $users;
    $url                     = WPF_CRM_API . 'wp-api/move-site-old-data';
    $body                    = json_encode( $response_array );
    $res                     = wpf_send_remote_post( $url, $body );
    
    if( $res['status'] == 1 ) {
	    update_option( 'is_wpf_data_moved', 'yes' );
    } else {
	    update_option( 'is_wpf_data_moved', 'no' );
    }
}

/**
 * Grab data from database and send it to be used inside Visual Composer plugin.
 *
 * @param array $data returns the values.
 */
function wpf_visual_composer_api() {
    $wpfb_users    = do_shortcode( '[wpf_user_list_front]' );
    $wpf_license   = get_option( 'wpf_license' );
    $wpf_user_plan = get_option( 'wpf_user_plan', false );
    if ( $wpf_user_plan ) {
        $wpf_user_plan = unserialize( $wpf_user_plan );
    }
    $data = array(
        'wpf_site_id'   => get_option( 'wpf_site_id' ),
        'wpf_license'   => $wpf_license,
        'url'           => home_url(),
        'task_types'    => ['general', 'email', 'page'],
        'sort'          => ['task_title', 'created_at'],
        'sort_by'       => 'asc',
        'notify_user'   => $wpfb_users,
        'wpf_user_plan' => $wpf_user_plan
    );
    return $data;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'atarim/v1', '/db/vc', array(
        'methods' => 'GET',
        'callback' => 'wpf_visual_composer_api',
        'permission_callback' => '__return_true'
    ) );
} );