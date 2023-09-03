<div class="wrap wpfeedback-settings">
    <style>
    div#wpf_launcher {
        display: none !important;
    }
    </style>
    <?php
    global $current_user;
    $wpf_user_name                  = $current_user->user_nicename;
    $wpf_user_email                 = $current_user->user_email;
    $wpfeedback_font_awesome_script = get_site_data_by_key( 'wpfeedback_font_awesome_script' );
    $wpf_user_type                  = wpf_user_type();
    $wpf_license_key                = get_option( 'wpf_license_key' );
    $wpf_license_key                = wpf_crypt_key( $wpf_license_key, 'd' );
    if ( $wpfeedback_font_awesome_script == 'yes' ) {
        ?>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <?php } ?>
    <div class="wpf_logo">
        <img src="<?php echo get_wpf_logo(); ?>" alt="Atarim">
    </div>

    <!-- ================= TOP TABS ================-->
    <div class="wpf_tabs_container" id="wpf_tabs_container">
        <button class="wpf_tab_item wpf_tasks active" onclick="location.href='admin.php?page=wpfeedback_page_tasks'" style="background-color: #efefef;"><?php _e( 'Tasks', 'atarim-visual-collaboration' ); ?></button>
        <button class="wpf_tab_item wpf_graphics" onclick="openWPFTab('wpf_graphics')" style="background-color: #efefef; display:none;"><?php _e( 'Graphics', 'atarim-visual-collaboration' ); ?></button>
        <?php
        if ( $wpf_user_type == 'advisor' || ( $wpf_user_type == '' && current_user_can( 'administrator' ) ) ) {
            ?>
            <button class="wpf_tab_item wpf_settings" onclick="location.href='admin.php?page=wpfeedback_page_settings'" style="background-color: #efefef;"><?php _e( 'Settings', 'atarim-visual-collaboration' ); ?></button>
        <?php }
        if ( $wpf_user_type == 'advisor' || ( $wpf_user_type == '' && current_user_can( 'administrator' ) ) ) {
            ?>
            <button class="wpf_tab_item wpf_misc" onclick="location.href='admin.php?page=wpfeedback_page_permissions'" style="background-color: #efefef;"><?php _e( 'Permissions', 'atarim-visual-collaboration' ); ?></button>
        <?php }
        if ( $wpf_user_type == 'advisor' ) {
            ?>
            <button class="wpf_tab_item wpf_addons" onclick="location.href='admin.php?page=wpfeedback_page_integrate'" style="background-color: #efefef;"><?php _e( 'Integrate', 'atarim-visual-collaboration' ); ?></button>
        <?php }
        if ( $wpf_user_type == 'advisor' || ( $wpf_user_type == '' && current_user_can( 'administrator' ) ) ) {
            ?>
            <a href="https://atarim.io/support-reachout" target="_blank" class="wpf_tab_item wpf_support" style="background-color: #efefef;">
                <button><?php _e( 'Support', 'atarim-visual-collaboration' ); ?></button>
            </a>
            <a href="<?php echo WPF_MAIN_SITE_URL; ?>/upgrade" target="_blank" class="wpf_tab_item" style="background-color: #efefef;">
                <button><?php _e( 'Upgrade', 'atarim-visual-collaboration' ); ?></button>
            </a>
        <?php } ?>
    </div>
    <div id="wpf_graphics" class="wpf_container" style="display:none">
        <div class="wpf_section_title"><?php _e( 'Graphic FeedBack', 'atarim-visual-collaboration' ); ?>
            <span class="wpf_report_buttons wpf_hide wpf_success" id="wpf_success_msg"><?php _e( 'Graphics Created successfully', 'atarim-visual-collaboration' ); ?></span>
        </div>
        <?php require_once( WPF_PLUGIN_DIR . '/graphics/wpf_graphics_all_list.php' ); ?>      
        <div class="wpf-graphics-modal" id="wpf_graphics_popup_form">
            <div class="graphics-modal-content">
				<div class="wpf-graphics-modal_title"><?php _e( 'Add a new Graphic Item', 'atarim-visual-collaboration' ); ?></div>
                <button type="button" class="graphics-modal-close" onclick="javascript:wpf_create_graphics_buttons();"><i class="gg-close"></i></button>
                <form class="wpf_inner_container" action="" method="post" enctype="multipart/form-data" id="add-graphics-form">
                    <div class="wpf_loader_admin hidden"></div>
                    <div class="wpfeedback_graphics">
                        <?php wp_enqueue_media(); ?>
                        <div class="wpfeedback_graphics_file">
                            <span class="wpf_success msg">Graphics Created successfully</span>
                            <div class="wpf_graphics_name_fields graphics_fields">
                                <div class="wpf_field_label"><b>Graphics Title</b></div>
                                <div class="wpf_field_input"><input onclick="wpf_hide_msg( 'graphics_name' )" type='text' placeholder="<?php _e( 'Name your new graphic item', 'atarim-visual-collaboration' ); ?>" name='wpfeedback_graphics_name' id='wpfeedback_graphics_name' value='' autocomplete="false"><span class="wpf_error graphics_name"><?php _e( "Please enter graphics title", 'atarim-visual-collaboration' ); ?></span></div>
                            </div>
                            <div class="wpf_graphics_excerpt graphics_fields">
                                <div class="wpf_field_label"><b>Graphics Description</b></div>
                                <div class="wpf_field_input">
                                    <textarea name='wpfeedback_graphics_excerpt' placeholder="<?php _e( 'Give your new graphic a description', 'atarim-visual-collaboration' ); ?>" onclick="wpf_hide_msg( 'graphics_excerpt' )" id="wpfeedback_graphics_excerpt"></textarea>
                                    <span class="wpf_error graphics_excerpt"><?php _e( 'Please enter your graphics discription', 'atarim-visual-collaboration' ); ?></span>
                                </div>
                            </div>
                            <span class="img_desc">Allowed jpg, png.</span>
                            <div class="wpf_upload_image_button graphics_fields custom_image_upload">                            
                                <div class="wpf_field_input">
                                    <div class="wpf_field_label">Upload Image</div>
                                    <i class="gg-image"></i>				
                                    <input id="upload_graphic_image" type="file" name="image" class="button" />
                                </div>
                                <span id="wpf_preview_graphic" class="wpf_preview_graphics_img wpf_hide">
                                    <img id="wpfeedback_image-preview" class="" src="" alt="" />
                                </span>
                                <span class="wpf_error graphics_img">Please select image</span>
                            </div>
                            <div class='graphic wpfeedback_image-preview-wrapper'>
                                <?php echo get_wpf_no_image(); ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="is_graphic_submit" value="1" />
                    <input type="button" value="<?php _e( "Start Collaborating", 'atarim-visual-collaboration' ); ?>" class="wpf_graphics wpf_button" onclick="wpfeedback_submit_graphics()">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if ( isset( $_POST['is_graphic_submit'] ) && $_POST['is_graphic_submit'] == 1 ) {
    global $wpdb, $current_user;
    $graphics_name = stripslashes( html_entity_decode( $_POST['wpfeedback_graphics_name'], ENT_QUOTES, 'UTF-8' ) );
    $graphics_name = esc_html( $graphics_name );
    $file_tmp      = $_FILES['image']['tmp_name'];
    $type          = $_FILES['image']['type'];
    $data          = file_get_contents( $file_tmp );
    $base64_image  = 'data:' . $type . ';base64,' . base64_encode( $data );

    $post_data = [
        'wpf_site_id' => get_option( "wpf_site_id" ),
        'title'       => $graphics_name,
        'description' => esc_html( $_POST['wpf_graphics_excerpt'] ),
        'image'       => $base64_image
    ];

    $url = WPF_CRM_API . 'wp-api/graphic/createTask';
    $sendtocloud = json_encode( $post_data );
    $graphics    = wpf_send_remote_post( $url, $sendtocloud );
    ?>
    <script type="text/javascript">
        location.reload();
    </script>
<?php } ?>

<script type="text/javascript">
    var design_id = '';
    function wpfeedback_submit_graphics(){
        jQuery("#get_masg_loader").show();
        var wpf_graphics_name = jQuery('#wpfeedback_graphics_name').val();
	    var files = jQuery('#upload_graphic_image')[0].files;
        if ( wpf_graphics_name == '' ) {
            jQuery('.wpf_error.graphics_name').show();
            return false;
        }
        if ( files.length > 0 ) {
        } else {
            jQuery('.wpf_error.graphics_img').show(); 
                return false;
        }
        jQuery('#add-graphics-form').submit();
    }

    function wpf_create_graphics_buttons(){
        jQuery("#wpf_graphics_popup_form").toggle();
    } 

    function wpf_hide_msg(filter_type){
        if ( filter_type == 'graphics_name' ) {
            jQuery('.wpf_error.graphics_name').hide();
        }

        if ( filter_type == 'graphics_img' ) {
            jQuery('.wpf_error.graphics_img').hide();
        }
        if ( filter_type == 'graphics_excerpt' ) {
            jQuery('.wpf_error.graphics_excerpt').hide();
        }
    }

    function wpf_delete_conformation( id ) {
        design_id   = id;
        var confirm = wpf_confirm( 'Delete the Graphic FeedBack?', 'Are you sure you want to Delete the Graphic FeedBack', 'Yes', 'No', 'wpf_delete_design' );
    }

    function wpf_delete_design() {
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: "wpfb_delete_grapgics",
                wpfb_grapgics_id: design_id,
                wpf_nonce: wpf_nonce,
            },
            beforeSend: function() {
                jQuery('.wpf_loader_admin').show();
            },
            success: function( data ) {
                jQuery('.wpf_loader_admin').hide();
                if ( data == 1 ) {
                    jQuery('#all_graphics_list_container #' + design_id).remove();
                }
            }
        });
    }
</script>
