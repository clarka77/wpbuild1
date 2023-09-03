<?php
$dataver = $data = get_graphic_data( sanitize_text_field( $_GET['id'] ) );

if ( empty( $data ) ) {
    return;
}

$id               = $data['id'];
$img_path         = $data['image'][0]['image'];
$name             = $data['title'];
$description      = $data['description'];
$version          = '';
$is_completed     = $data['status'] == 1 ? 1 : 0;
$color            = ! empty( $data['bg_color'] ) ? $data['bg_color'] : '';
$is_site_archived = get_site_data_by_key( 'wpf_site_archived' );

if ( isset( $_POST['is_version_update'] ) && $_POST['is_version_update'] == 1 ) {
    if ( ! isset( $_GET['id'] ) ) {
        return false;
    }

    global $wpdb, $current_user;
    $graphics_name = stripslashes( html_entity_decode( $_POST['wpfeedback_graphics_name'], ENT_QUOTES, 'UTF-8') );
    $file_tmp      = $_FILES['image']['tmp_name'];
    $type          = $_FILES['image']['type'];
    $data          = file_get_contents( $file_tmp );
    $base64_image  = 'data:' . $type . ';base64,' . base64_encode( $data );
    $post_data     = [
        'wpf_site_id' => get_option( "wpf_site_id" ),
        'graphic_id'  => sanitize_text_field( $_GET['id'] ),
        'image'       => $base64_image
    ];

    $url         = WPF_CRM_API . 'wp-api/graphic/add/image';
    $sendtocloud = json_encode( $post_data );
    $graphics    = wpf_send_remote_post( $url, $sendtocloud );
    $dataver     = $data = get_graphic_data( sanitize_text_field( $_GET['id'] ) );
}

$is_compact_mode_enabled = ( get_site_data_by_key( 'wpf_enabled_compact_mode' ) === "yes" );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11" />

    <?php wp_head(); ?>
    <style>
        #wpf_graphics .media-modal.wp-core-ui {
            width: 50%;
            margin: 0px auto;
        }
        .wpf_inner_container {
            text-align: center;
            margin-top: 10px;
        }
        span.wpf_error,span.wpf_success{
            display: none;
        }
        span.wpf_error{
            font-size: 12px;
            font-weight: bold;
            color: #cc1816;
        }
        .wpf_field_input input, .wpf_field_input textarea {
            width: 100%;
            margin: 0px auto !important;
            display: flex !important;
        }
        .wpf_inner_container .graphics_fields {
            padding: 10px 0px;
        }
        .wpf_inner_container .wpf_field_label {
            margin-bottom: 10px;
        }
        span.wpf_success {
            display: none;
            font-size: 12px;
            font-weight: bold;
            color: #008000;
        }
        input.wpf_graphics.wpf_button {
            width: 100%;
        }
        textarea#wpfeedback_graphics_excerpt {
            min-height: 75px;
            border-radius: 5px;
        }
        .wpf_disabled_info {
            position: fixed;
            top: 0;
            height: 100vh;
            display: block;
            width: 100%;
            background-color: #333;
            padding: 5%25px;
            box-sizing: border-box;
            left: 0;
            text-align: center;
        }
        .wpf_disabled_info p:before {
            content: "";
            height: 150px;
            background-image: url(/wp-content/plugins/atarim-client-interface-plugin/images/atarim-whitelabel.svg);
            width: 150px;
            margin: auto;
            display: block;
            background-size: contain;
            background-repeat: no-repeat;
            margin-bottom: 20px;
        }
        .wpf_disabled_info p {
            color: #fff;
            font-family: arial;
            width: 700px;
            max-width: 100%;
            margin: auto;
            font-size: 16px;
        }
        .wpf_disabled_info p a {
            background-color: #efefef;
            color: #333;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: 700;
        }
    </style>
</head>
<style>
    body.wpf_graphics .wpfb-point {
        z-index: 99 !important;
    }
    body.wpf_graphics .wpf_sidebar_container {
        height: 100vh;
    }
    body.wpf_graphics #wpf_launcher {
        top: 0;
        z-index: 999 !important;
    }
    .wpf_sidebar_generaltask {
        display: none !important;
    }
    @media only screen and (max-width: 855px) {
        .wpf_body {
            min-height: calc(100vh - 60px);
            margin-top: 60px;
        }
    }
    @media only screen and (max-width: 650px) {
        .wpf_topbar_left {
            width: 100%;
        }
        .wpf_body {
            min-height: calc(100vh - 102px);
            margin-top: 90px;
        }
        .wpf_compact_body .wpf_body {
            min-height: calc(100vh - 50px);
        }
        body.wpf_graphics .wpf_sidebar_container {
            height: calc(100vh - 198px);
            top: 102px;
        }
    }
    @media only screen and (max-width: 479px) {
        .wpf_body {
            min-height: calc(100vh - 73px);
            margin-top: 61px;
        }
        .wpf_compact_body .wpf_body {
            min-height: calc(100vh - 73px);
        }
    }
    <?php if ( is_user_logged_in() ) { ?>
    body.wpf_graphics .wpf_topbar {
        top: 32px;
    }
    body.wpf_graphics #wpf_launcher {
        top: 32px !important;
    }
    body.wpf_graphics .wpf_sidebar_container {
        height: calc(100vh - 32px) !important;
    }
    .wpf_body {
        min-height: calc(100vh - 136px);
    }
    .wpf_compact_body .wpf_body {
        min-height: calc(100vh - 84px);
    }
    @media only screen and (max-width: 855px) {
        body.wpf_graphics #wpf_launcher {
            top: 32px !important;
        }
        body.wpf_graphics .wpf_sidebar_container {
            height: calc(100vh - 32px) !important;
        }
    }
    @media only screen and (max-width: 782px) {
        body.wpf_graphics .wpf_topbar {
            top: 46px;
        }
        body.wpf_graphics #wpf_launcher {
            top: 46px !important;
        }
        body.wpf_graphics .wpf_sidebar_container {
            height: calc(100vh - 46px) !important;
        }
    }
    @media only screen and (max-width: 650px) {
        body.wpf_graphics #wpf_launcher {
            top: 137px !important;
        }
        body.wpf_graphics .wpf_sidebar_container {
            height: calc(100vh - 200px) !important;
            top: 0;
        }
        body.wpf_graphics.wpf_compact_body #wpf_launcher {
            top: 137px !important;
        }
        body.wpf_graphics.wpf_compact_body .wpf_sidebar_container {
            height: calc(100vh - 137px) !important;
            top: 0;
        }
        .wpf_compact_body .wpf_body {
            min-height: calc(100vh - 137px);
            margin-top: 90px;
        }
    }
    <?php } ?>
</style>
<body class="wpf_graphics <?php echo ( $is_compact_mode_enabled ) ? 'wpf_compact_body' : '' ?>">
<?php
$wpf_graphics_color_name = $color;
if ( $wpf_graphics_color_name == '' ) {
    $wpf_graphics_color_name = '#efefef';
}
global $current_user; //for this example only :)
$wpf_allow_guest = get_site_data_by_key( 'wpf_allow_guest' );
if ( is_user_logged_in() ) {
    $wpf_get_user_type = esc_attr( wpf_user_type() );
} else {
    $wpf_get_user_type = '';
}
?>
<div class="wpf-graphics-modal" id="wpf_graphics_popup_form" style="display: none;">
    <div class="graphics-modal-content">
        <div class="wpf-graphics-modal_title">Add a New Version</div>
        <button type="button" class="graphics-modal-close" onclick="javascript:wpf_create_graphics_buttons();"><i class="gg-close"></i></button>
        <form class="wpf_inner_container" action="" method="post" enctype="multipart/form-data" id="version-form">
            <div class="wpf_loader_admin hidden"></div>
            <div class="wpfeedback_graphics">
                <div class="wpfeedback_graphics_file">
                    <span class="img_desc">Allowed image types: jpg, png.</span>
                    <div class="wpf_upload_image_button graphics_fields custom_image_upload">
                        <div class="wpf_field_input"><div class="wpf_field_label">Upload Image</div><i class="gg-image"></i>
                            <input id="upload_graphic_image_version" type="file" name="image" class="button" />
                        </div>
                    </div>
                    <span id="wpf_preview_graphic" class="wpf_preview_graphics_img wpf_hide">
                                <img  id='wpfeedback_image-preview' class="" title="" src="" alt="preview"/>
                            </span>
                    <span class="wpf_error graphics_img">Please select image</span>
                </div>
                <div class='graphic wpfeedback_image-preview-wrapper'>
                    <?php echo get_wpf_no_image(); ?>
                </div>
            </div>
            <input type="hidden" name="is_version_update" value="1">
            <input type="button" value="<?php _e( "Upload New Version", 'atarim-visual-collaboration' ); ?>" class="wpf_graphics wpf_button" onclick="wpfeedback_submit_graphic_version();">
        </form>
    </div>
</div>

<div id="wpf_content" class="wpf_site-content">
    <div class="wpf-content-full wpf_container">
        <div class="wpf_body" style="background-color: <?php echo $wpf_graphics_color_name; ?>">
            <div class="wpf_topbar">
                <?php if ( get_site_data_by_key( 'enabled_wpfeedback' ) === 'yes' ) { ?>
                    <div class="wpf_topbar_left">
                        <div class="wpf_graphics_info">
                            <div class="wpf_graphics_title"><?php echo $name; ?></div>
                            <div class="wpf_graphics_description"><?php echo $description; ?></div>
                        </div>
                    </div>
                    <div class="wpf_topbar_right"><div class="wpf_topbar_icons"><a class="wpf_button_upload" href="javascript:wpf_upload_new_graphics()" title="upload design"><i class="gg-software-upload"></i></a><span id="graphics_color_picker"><a class="wpf_button_color" href="javascript:void(0)" title="Change color"><i class="gg-color-bucket"></i></a></span></div>
                        <div class="wpf_graphics_version" id="wpf_graphics_version_content"><?php  echo wpf_grapgics_version_list( $dataver, $id ); ?></div>
                        <div class="wpf_graphics_button" id="wpf_graphics_complete_content">
                            <?php 
                            if ( is_user_logged_in() ) {
                                if ( $is_site_archived ) {
                                    ?>
                                    <div class="wpf_disabled_info">
                                        <p class="wpf_logged_in_info">The collboration plugin has been disabled through the settings screen. To use the Graphic Feedback Tool, visit the Settings screen within the collaborate tab and enable the plugin. <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpfeedback_page_settings' ) ) ?>"                                                                                                                                                                                                                                                          target="_blank">Go to the settings screen</a>.</p>
                                    </div>
                                <?php
                                }
                            }
                            $wpf_complete_graphics = $is_completed;
                            if ( ! $wpf_complete_graphics ) {
                                ?>
                                <a class="wpf_button" id="wpf_graphics_complete" href="javascript:wpf_graphics_complete(<?php echo $id; ?>);"><?php _e( 'Complete', 'atarim-visual-collaboration' ); ?></a>
                                <?php
                            } else {
                                ?>
                                <a class="wpf_button wpf_complete" id="wpf_graphics_complete" href="javascript:wpf_graphics_uncomplete(<?php echo $id; ?>);"><?php _e( 'Completed', 'atarim-visual-collaboration' ); ?></a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php 
                } else { 
                    ?>
                    <div class="wpf_disabled_info">
                        <?php
                        if ( is_user_logged_in() && $is_site_archived ) {
                            ?>
                            <p class="wpf_logged_in_info">The collboration plugin has been disabled through the settings screen. To use the Graphic Feedback Tool, visit the Settings screen within the collaborate tab and enable the plugin. 
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpfeedback_page_settings' ) ) ?>" target="_blank">Go to the settings screen</a>.
                            </p>
                            <?php
                        } else {
                            ?>
                            <p>The collboration plugin has been disabled through the settings screen. To use the Graphic Feedback Tool, make sure you are logged in (or contact your webmaster), then
                                visit the Settings screen within the collaborate tab and enable the plugin. </p>
                            <?php
                        } ?>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="wpf_graphics_loader wpf_hide"></div>
            <?php
            if ( get_site_data_by_key( 'enabled_wpfeedback' ) === 'yes' ) {
                ?>
                <div class="wpf_graphics_container">
                    <img id="wpf_graphics_img" style="display:none;" alt="The graphic element" src="" alt=""/>
                    <div class="wpf_graphics_load">Loading...</div>
                </div>
                <?php
            } ?>
        </div>
    </div>

    <?php wp_footer();
    if ( get_site_data_by_key( 'enabled_wpfeedback' ) === 'yes' ) {
        ?>
        <script type="text/javascript">
            setTimeout(function() {
                jQuery('#wpf_graphics_version').trigger("change");
                jQuery('#wpf_graphics_img').show();
                jQuery('.wpf_graphics_load').hide();
            },300);
            function wpf_create_graphics_buttons() {
                jQuery("#wpf_graphics_popup_form").toggle();
            }
            <?php
            if ( ! is_user_logged_in() && $wpf_allow_guest != 'yes' ) { 
                ?>
                jQuery(window).load(function() {
                    jQuery(document).find('#wpf_login_container').show();
                });
                <?php
            } ?>
            function change_graphics_version( sel ) {
                jQuery('#wpf_graphics_img').attr("src", jQuery('option:selected', jQuery(sel)).attr('data-src'));
            }
            function wpf_upload_new_graphics() {
                <?php
                if ( ! is_user_logged_in() ) {
                    ?>
                    jQuery(document).find('#wpf_login_container').show();
                    return false;
                    <?php
                } ?>
                var get_privious_all_graphics = '<?php $get_privious_all_graphics = get_post_meta( 'wpf_graphics_img_id' ); ?>';
                jQuery('#wpf_graphics_popup_form').show();
                return false;

                (function($) {
                    var file_frame; // variable for the wp.media file_frame
                    // if the file_frame has already been created, just reuse it
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: $( this ).data( 'uploader_title' ),
                        button: {
                            text: $( this ).data( 'uploader_button_text' ),
                        },
                        multiple: false // set this to true for multiple file selection
                    });
                    file_frame.on( 'select', function() {
                        attachment = file_frame.state().get('selection').first().toJSON();
                        var wpf_graphics_img_id = attachment.id;
                        if ( wpf_graphics_img_id ) {
                            // do something with the file here
                            $('#wpf_graphics_img').attr('src',attachment.url);
                            $( '#frontend-button' ).hide();
                            $( '#frontend-image' ).attr('src', attachment.url);
                            jQuery.ajax({
                                method: "POST",
                                url: wpf_ajax_login_object.ajaxurl,
                                data: {
                                    action: "wpf_update_graphics_image",
                                    "wpf_graphics_img_id":wpf_graphics_img_id,
                                    "current_page_id":current_page_id,
                                    wpf_nonce:wpf_nonce
                                },
                                beforeSend: function() {
                                    jQuery('.wpf_graphics_loader').show();
                                },
                                success: function (data) {
                                    jQuery('.wpf_graphics_loader').hide();
                                    jQuery('#wpf_graphics_version_content').html(data);
                                }
                            });
                        }
                    });
                    file_frame.open();
                })(jQuery);
            }

            function wpfeedback_submit_graphic_version() {
                var files = jQuery('#upload_graphic_image_version')[0].files;
                if(files.length > 0 ){

                } else {
                    jQuery('.wpf_error.graphics_img').show();
                    return false;
                }
                jQuery('#version-form').submit();
            }

            function wpf_graphics_complete( wpf_graphics_id ) {
                jQuery.ajax({
                    method: "POST",
                    url: wpf_ajax_login_object.ajaxurl,
                    data: {
                        action: "wpf_completed_graphics",
                        "wpf_graphics_id":wpf_graphics_id,
                        "wpf_graphics_status":1,
                        wpf_nonce:wpf_nonce
                    },
                    success: function (data) {
                        jQuery('#wpf_graphics_complete').attr('href','javascript:wpf_graphics_uncomplete('+wpf_graphics_id+');');
                        jQuery('#wpf_graphics_complete').text('Completed');
                        jQuery('#wpf_graphics_complete').css('background-color','#3ed696');
                    }
                });
            }

            function wpf_graphics_uncomplete( wpf_graphics_id ) {
                jQuery.ajax({
                    method: "POST",
                    url: wpf_ajax_login_object.ajaxurl,
                    data: {
                        action: "wpf_completed_graphics",
                        "wpf_graphics_id":wpf_graphics_id,
                        "wpf_graphics_status":0,
                        wpf_nonce:wpf_nonce
                    },
                    success: function (data) {
                        jQuery('#wpf_graphics_complete').attr('href','javascript:wpf_graphics_complete('+wpf_graphics_id+');');
                        jQuery('#wpf_graphics_complete').text('Mark as complete');
                        jQuery('#wpf_graphics_complete').removeAttr('style');
                    }
                });
            }

            jQuery(document).ready(function() {
                jQuery('#graphics_color_picker').iris('color', '<?php echo $wpf_graphics_color_name; ?>');
            });

            jQuery('#graphics_color_picker').iris({
                target: '#graphics_color_picker',
                // or in the data-default-color attribute on the input
                defaultColor: true,
                mode: 'rgb',
                // a callback to fire whenever the color changes to a valid color
                change: function( event, ui ) {
                    let url_string = window.location.href;
                    let url = new URL(url_string);
                    let graphic_id = url.searchParams.get("id");
                    var wpf_graphics_color_name = ui.color.toString();
                    jQuery('.wpf_body').css("background-color",  wpf_graphics_color_name);
                    jQuery.ajax({
                        method: "POST",
                        url: wpf_ajax_login_object.ajaxurl,
                        data: {
                            action: "wpf_update_graphics_color",
                            "wpf_graphics_color_name":wpf_graphics_color_name,
                            'graphic_id':graphic_id
                        },
                        beforeSend: function(){
                            jQuery('.wpf_graphics_loader').show();
                        },
                        success: function (data) {
                            jQuery('.wpf_graphics_loader').hide();
                        }
                    });
                },
                // a callback to fire when the input is emptied or an invalid color
                clear: function() {},
                // hide the color picker controls on load
                hide: true,
                target: true,
                // show a group of common colors beneath the square
                palettes: true
            });
            jQuery("#graphics_color_picker").click(function(){
                jQuery("#graphics_color_picker .iris-picker.iris-border").toggle();
            });

            function readURL( input ) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        jQuery_WPF('#wpfeedback_image-preview').attr('src', e.target.result);
                        jQuery_WPF('#wpf_preview_graphic').fadeIn(1500);
                        jQuery_WPF('#wpf_preview_graphic').css({'text-align':'center','display':'block'});
                        jQuery_WPF('#wpfeedback_image-preview').css({'width':'150px','margin':'0 auto','margin-bottom':'10px'});
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            jQuery_WPF("#upload_graphic_image_version").change(function() {
                var val = jQuery_WPF(this).val().toLowerCase(),
                regex = new RegExp("(.*?)\.(jpeg|jpg|png)$");

                if (!(regex.test(val))) {
                    jQuery_WPF(this).val('');
                    alert('Please select correct file format');
                } else {
                    readURL(this);
                }
            });
        </script>
        <?php
    } ?>
</div>
</body>
</html>