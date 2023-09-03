<?php
global $current_user;
if ( $current_user->display_name == '' ) {
    $wpf_user_name = $current_user->user_nicename;
} else {
    $wpf_user_name = $current_user->display_name;
}
$wpf_get_user_type = esc_attr( wpf_user_type() );
$wpf_final_flow    = true;


if ( ! is_admin() ) {
?>
<div class="wpf_wizard_container">
    <div class="wpf_wizard_modal wpf_set_role">
        <div class="wpf_loader wpf_loader_wizard wpf_hide"></div>

        <div id="wpf_wizard_role" class="wpf_wizard_page <?php if ( $wpf_get_user_type != '' ) { echo "wpf_hide"; $wpf_final_flow = false; }?>">
            <div class="wpf_wizard_title"><?php _e( "Hi ", 'atarim-visual-collaboration' ); echo $wpf_user_name; _e( ", Let's Collaborate", 'atarim-visual-collaboration' );?></div>
            <p><?php _e( "To optimize your experience, which one describes you best?", 'atarim-visual-collaboration' ); ?></p>
            <div class="wpf_wizard_user">
                <input type="radio" name="wpf_user_type" value="king" class="wpf_user_type wpf_hide"
                       id="king" <?php if ( $wpf_get_user_type == 'king' ) {
                    echo 'checked';
                } ?>>
                <label for="king" id="wpf_wizard_king" class="wpf_wizard_choice">
                    <img alt="client" src="<?php echo WPF_PLUGIN_URL; ?>images/wpfeedback-client.png"/>
                    <div class="wpf_wizard_choice_title"><?php echo get_site_data_by_key( 'wpf_customisations_client' )?get_site_data_by_key( 'wpf_customisations_client' ):'Client (Website Owner)'; ?></div>
                </label>
                <input type="radio" name="wpf_user_type" value="advisor" class="wpf_user_type wpf_hide"
                       id="advisor" <?php if ( $wpf_get_user_type == 'advisor' ) {
                    echo 'checked';
                } ?>>
                <label for="advisor" id="wpf_wizard_advisor" class="wpf_wizard_choice">
                    <img alt="webmaster" src="<?php echo WPF_PLUGIN_URL; ?>images/wpfeedback-webmaster.png"/>
                    <div class="wpf_wizard_choice_title"><?php echo get_site_data_by_key( 'wpf_customisations_webmaster' )?get_site_data_by_key( 'wpf_customisations_webmaster' ):'Webmaster'; ?></div>
                </label>

                <input type="radio" name="wpf_user_type" value="council" class="wp_feedback_task wpf_hide"
                       id="council" <?php if ( $wpf_get_user_type == 'council' ) {
                    echo 'checked';
                } ?>>
                <label for="council" id="wpf_wizard_council" class="wpf_wizard_choice">
                    <img alt="others" src="<?php echo WPF_PLUGIN_URL; ?>images/wpfeedback-others.png"/>
                    <div class="wpf_wizard_choice_title"><?php echo get_site_data_by_key( 'wpf_customisations_others' )?get_site_data_by_key( 'wpf_customisations_others' ):'Others'; ?></div>
                </label>
            </div>
            <p style="color:red;" class="wpf_hide wpf_wizard_error"><?php _e( "Please select anyone", 'atarim-visual-collaboration' ); ?></p>
        </div>
        <div id="wpf_wizard_final" class="wpf_wizard_page <?php if ( $wpf_final_flow ) { echo "wpf_hide"; } ?>">
            <div class="wpf_wizard_title"><?php _e( "How To Collaborate", 'atarim-visual-collaboration' ); ?></div>
            <p><?php _e( "Watch this short tutorial to get started", 'atarim-visual-collaboration' ); ?></p>
            <?php
            if ( $wpf_get_user_type == 'advisor' ) {
                ?> 
                <script src="https://fast.wistia.com/embed/medias/fgmc8amsa4.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_fgmc8amsa4 videoFoam=true" style="height:100%;position:relative;width:100%"> </div></div></div>
                <?php
            } else { 
                if ( get_site_data_by_key( 'wpf_tutorial_video' ) == '' ) {
                    ?>
                    <script src="https://fast.wistia.com/embed/medias/z4wcg6ecbd.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_z4wcg6ecbd videoFoam=true" style="height:100%;position:relative;width:100%"> </div></div></div>
                    <?php
                } else {
                    $video_url = get_site_data_by_key( 'wpf_tutorial_video' );
                    $temp      = explode( '.', $video_url );
                    $formate   = strtolower( end( $temp ) );
                    // check the formate of the video.
                    if ( $formate == 'mp4' || $formate == 'avi' || $formate == 'mpeg' || $formate == 'mpg' || $formate == 'webm' || $formate == 'ogg' ) {
                        ?>
                        <video width="100%" height="225" controls>
                            <source src="<?php echo $video_url; ?>" >
                        </video>
                        <?php
                    } else {
                        // create youtube video link.
                        function getYoutubeEmbedUrl( $url ) {
                            $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
                            $longUrlRegex  = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

                            if ( preg_match( $longUrlRegex, $url, $matches ) ) {
                                $youtube_id = $matches[count( $matches ) - 1];
                            }

                            if ( preg_match( $shortUrlRegex, $url, $matches ) ) {
                                $youtube_id = $matches[count( $matches ) - 1];
                            }
                            return 'https://www.youtube.com/embed/' . $youtube_id;
                        }
                        // check if it's youtube video
                        $parse = parse_url( $video_url );
                        if ( $parse['host'] == 'www.youtube.com' || $parse['host'] == 'youtube.com' || $parse['host'] == 'www.youtu.be' || $parse['host'] == 'youtu.be' ) {
                            $video_url = getYoutubeEmbedUrl( $video_url );
                        }
                        ?>
                        <iframe height="225px" width="100%" style="border:none;" src="<?php echo $video_url; ?>"></iframe>
                        <?php
                    }
                }
            }
            ?>
            <btn href="javascript:void(0);" class="wpf_wizard_button" id="wpf_wizard_done_button"><?php _e( "Let's Start", 'atarim-visual-collaboration' ); ?></btn>
        </div>
    </div>
</div>
<?php } ?>