<?php
global $current_user;
if ( $current_user->display_name == '' ) {
	$wpf_user_name = $current_user->user_nicename;
} else {
	$wpf_user_name = $current_user->display_name;
}
$wpf_admin_users = get_users( array( 'role' => 'Administrator' ) );
?>
<div class="wpf_backend_initial_setup">
	<div class="wpf_logo_wizard">
		<img src="<?php echo esc_url( WPF_PLUGIN_URL . 'images/Atarim-Wizzard.svg' ); ?>" alt="Atarim">
	</div>
	<div class="wpf_backend_initial_setup_inner">
		<div class="wpf_loader_admin wpf_hide"></div>
		<form method="post" action="admin-post.php">
			<?php
			$first_step_display  = '';
			$second_step_display = 'wpf_hide';
			if ( isset( $_GET['step_one'] ) && $_GET['step_one'] == 'true' ) {
				$first_step_display  = 'wpf_hide';
				$second_step_display = '';
			}
			?>
			<div id="wpf_initial_settings_first_step" class="wpf_initial_container <?php echo $first_step_display; ?>">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Install", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">2</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Connect", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">3</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Collaborate", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Complete", 'atarim-visual-collaboration' ); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">
					<div class="wpf_title_wizard"><?php _e( "Let's Get You Up and Running", 'atarim-visual-collaboration' ); ?></div>
					<p>
						<?php
						printf( __( "Good to have you here %s!", 'atarim-visual-collaboration' ), $wpf_user_name );
						echo '<br>';
						printf( __( "We need to connect you to your Atarim account", 'atarim-visual-collaboration' ) );
						echo '. ';
						printf( __( '<a href="https://atarim.io/help/wordpress-plugin/why-you-need-to-register" target="_blank">See Why.</a>', 'atarim-visual-collaboration' ) );
						?>
					</p>
					<input type="hidden" name="action" value="save_wpfeedback_options"/>
					<?php 
						$google_sup = 'https://app.atarim.io/google-auth?activation_callback=' . Base64_encode( home_url() ) . '&activation_item_id=' . Base64_encode( WPF_EDD_SL_ITEM_ID ) . '&page_redirect=' . Base64_encode( "wpfeedback_page_settings" );
					?>
					<a href="<?php echo $google_sup; ?>" class="supg-btn">
						<span>
							<svg viewBox="0 0 48 48" width="18" height="18" style="margin-top: 2px; margin-right: 5px;"><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"></path></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"></use></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"></path><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"></path><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"></path><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"></path></svg>
						</span>   <?php _e( 'Sign Up with Google', 'atarim-visual-collaboration' ); ?>
					</a>
					<div class="supg-or"><?php _e( 'or', 'atarim-visual-collaboration' ); ?></div>
					<?php wp_nonce_field( 'wpfeedback' ); ?>    
					<!-- new activation by Pratap-->
					<div class='wpf_signup_form'>
						<label><?php _e( 'Your Full Name', 'atarim-visual-collaboration' ); ?></label><br>
						<span class='wpf-name-error'><?php _e( 'Please add your full name', 'atarim-visual-collaboration' ); ?></span>
						<input type='text' name='username' class='wpf-user-name' placeholder='Webmaster Name' /><br>
						<label><?php _e( 'Your Work Email Address', 'atarim-visual-collaboration' ); ?></label><br>
						<span class='wpf-email-error'><?php _e( 'Please add your proper email address', 'atarim-visual-collaboration' ); ?></span>
						<input type='text' name='useremail' class='wpf-user-email' placeholder='name@yourdomain.com' /><br>
						<label><?php _e( 'Set Password', 'atarim-visual-collaboration' ); ?></label><br>
						<span class='wpf-pass-error'><?php _e( 'Password field cannot be empty', 'atarim-visual-collaboration' ); ?></span>
						<input type='password' name='userpass' class='wpf-user-pass' placeholder='xxxxxxxxxx' /><br>
					</div>
					<span class='wpf-account-msg'></span>
					<div class="wpfeedback_licence_key_field">
					<?php echo '<button type="button" class="wpf_create_user" name="wpf_activate" access="false" id="ber_page4_save">Create a free Atarim Account</button>'; ?>
					</div>
					<?php
						printf( __( '<p class="wpf_tcpp">By opening an account I agree to the <a href="https://atarim.io/privacy-policy/" target="_blank">privacy policy</a>.</p>', 'atarim-visual-collaboration' ) );
					?>
					<?php
						$home_url = 'https://app.atarim.io?activation_callback=' . Base64_encode( site_url() ) . '&page_redirect=' . Base64_encode( "wpfeedback_page_settings" );
						echo '<p class="wpf_has_account" style="width:100%"><a class="wpf_account_link" href="' . $home_url . '">I already have an account (Login)</a></p>';
					?>
					<!--End new activation-->
				</div>
			</div>

			<div id="wpf_initial_settings_second_step" class="wpf_initial_container <?php echo $second_step_display; ?>">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Install", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Connect", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">3</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Collaborate", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Complete", 'atarim-visual-collaboration' ); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">				
					<div class="wpf_title_wizard"> <?php _e( "Connect Your Main Collaborator (Client)", 'atarim-visual-collaboration' ); ?></div>
					<p><?php _e( "Create OR assign an <u>admin</u> user for main collaborator (or client).",'atarim-visual-collaboration' ); ?>
					<br><?php _e( "This will allow them to recieve notifications & comment with their name.",'atarim-visual-collaboration' ); ?></p>
					<div class="wpf_toggle_user_container">
						<div class="wpf_collaborator_setting_toggle">
							<label class="wpf_switch_collaborator_setting_toggle"><span class="wpf_toggle_left"><?php _e( "<u>New</u> Wordpress Account", 'atarim-visual-collaboration' ); ?></span>
								<input type="checkbox" name="wpf_collaborator_setting" class="wpf_collaborator_setting" >
								<span class="wpf_switch_slider wpf_switch_round"></span><span class="wpf_toggle_right"><?php _e( "<u>Assign</u> a Wordpress Account", 'atarim-visual-collaboration' ); ?></span>
							</label>
						</div>
						<div class="wpf_collaborator_user_container">
							<p><label for="wpf_collaborator_name" class="wpf_text_label"><?php _e( "Main Collaborator Full Name", 'atarim-visual-collaboration' ); ?> <span>*</span></label>
							<input type="text" class="wpf_text wpf_collaborator_name" placeholder="<?php _e( "Client Name", 'atarim-visual-collaboration' ); ?>"></p>
							<p><label for="wpf_collaborator_email" class="wpf_text_label"><?php _e( "Main Collaborator Email Address", 'atarim-visual-collaboration' ); ?> <span>*</span></label>
							<input type="text" class="wpf_text wpf_collaborator_email" placeholder="<?php _e( "client@yourdomain.com", 'atarim-visual-collaboration' ); ?>"></p>  
							<p class="wpf_wizard_note"><span>* </span><?php _e( "The auto-generated password <b><u>will not</u></b> be shared with the new user.", 'atarim-visual-collaboration' ); ?></p>
						</div>
						<div class="wpf_collaborator_user_assign_container">
							<p>
								<label class="wpf_text_label"><?php _e( "Main Collaborator Account", 'atarim-visual-collaboration' ); ?></label>
								<select class="wp_feedback_filter_admin_user">
									<option value="select"><?php _e( "Select a User", 'atarim-visual-collaboration' ); ?></option>
									<?php foreach ( $wpf_admin_users as $user ) { ?>
										<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
									<?php } ?>
								</select>
							</p>
						</div>
					</div>
					<div class="wpf_guest_wrap">
						<label for="wpf_allow_guest" class="wpf_checkbox_label">
							<p><b><?php _e( 'Enable "Guest Mode"', 'atarim-visual-collaboration' ); ?></b><br>
								<?php _e( "Allow guests to create tickets and leave comments, without the need to login to the site. ideal for staging sites or during the build, but not idea for live websites.", 'atarim-visual-collaboration' ); ?></p>
						</label>
						<label class="wpf_switch">
							<input type="checkbox" name="wpf_allow_guest" value="yes" class="wpf_checkbox" id="wpf_allow_guest" <?php if ( get_site_data_by_key( 'wpf_allow_guest' ) == 'yes' ) { echo 'checked'; } ?>/>
							<span class="wpf_switch_slider wpf_switch_round"></span>
						</label>
					</div>
					<p id="wpf_global_erro_msg" class="wpf_hide" style="color: red;"><?php _e( "There seems to be some issue with enabling the global settings. Please contact support for help.", 'atarim-visual-collaboration' ); ?></p>
					<br>
					<div class="wpf_wizard_footer">
						<btn href="javascript:void(0);" class="wpf_button wpf_final_step" id="wpf_initial_setup_second_step_button"><?php _e( "Create an Account",'atarim-visual-collaboration' ); ?></btn>
					</div>
					<div class="wpf_wizard_skip_btn">
						<btn class="wpf_skip_button" onclick="wpf_skip_initial_setup( '<?php echo WPF_SITE_URL; ?>' )"><?php _e( "Skip these steps", 'atarim-visual-collaboration' ); ?></btn>
					</div>
				</div>
			</div>

			<div id="wpf_initial_settings_third_step" class="wpf_initial_container wpf_hide">
				<div class="wpf_title_wizard"><?php _e( "3. Choose notifications", 'atarim-visual-collaboration' ); ?></div>
				<p>
					<b><?php _e( "Which notifications would you like the plugin to send out?", 'atarim-visual-collaboration' ); ?></b><br>
					<?php _e( "These are global settings. Each user can then choose their own notifications out of the options selected here.", 'atarim-visual-collaboration' ); ?>
				</p>
				<div>
                    <input type="checkbox" name="wpf_every_new_task" value="yes" class="wpf_checkbox" id="wpf_every_new_task" checked />
                    <label for="wpf_every_new_task" class="wpf_checkbox_label"><?php _e( 'Send email notification for every new task', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_new_comment" value="yes" class="wpf_checkbox" id="wpf_every_new_comment" checked />
                    <label for="wpf_every_new_comment" class="wpf_checkbox_label"><?php _e( 'Send email notification for every new comment', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_new_complete" value="yes" class="wpf_checkbox" id="wpf_every_new_complete" checked />
                    <label for="wpf_every_new_complete" class="wpf_checkbox_label"><?php _e( 'Send email notification when a task is marked as complete', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_every_status_change" value="yes" class="wpf_checkbox" id="wpf_every_status_change" checked />
                    <label for="wpf_every_status_change" class="wpf_checkbox_label"><?php _e( 'Send email notification for every status change', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_daily_report" value="yes" class="wpf_checkbox" id="wpf_daily_report" checked />
                    <label for="wpf_daily_report" class="wpf_checkbox_label"><?php _e( 'Send email notification for last 24 hours report', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_weekly_report" value="yes" class="wpf_checkbox" id="wpf_weekly_report" checked />
                    <label for="wpf_weekly_report" class="wpf_checkbox_label"><?php _e( 'Send email notification for last 7 days report', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_auto_daily_report" value="yes" class="wpf_checkbox" id="wpf_auto_daily_report" checked />
                    <label for="wpf_auto_daily_report" class="wpf_checkbox_label"><?php _e( 'Auto send email notification for daily report', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <div>
                    <input type="checkbox" name="wpf_auto_weekly_report" value="yes" class="wpf_checkbox" id="wpf_auto_weekly_report" checked />
                    <label for="wpf_auto_weekly_report" class="wpf_checkbox_label"><?php _e( 'Auto send email notification for weekly report', 'atarim-visual-collaboration' ); ?></label>
                </div>
                <br>
                <hr>
                <br>
                <div class="wpf_wizard_footer">
                    <a href="javascript:void(0);" id="wpf_initial_setup_third_step_prev_button"><?php _e( "<< Back", 'atarim-visual-collaboration' ); ?></a>
                    <btn href="javascript:void(0);" class="wpf_button wpf_next" id="wpf_initial_setup_third_step_button"><?php _e( "Next >>", 'atarim-visual-collaboration' ); ?></btn>
                </div>
            </div>
            <div id="wpf_initial_settings_fourth_step" class="wpf_initial_container wpf_hide">
				<div class="wpf_wizard_progress_box">
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Install", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Connect", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_comp">
						<div class="wpf_wizard_progress_num"><i class="gg-check"></i></div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Collaborate", 'atarim-visual-collaboration' ); ?></div>
					</div>
					<div class="wpf_wizard_progress_step wpf_step_current">
						<div class="wpf_wizard_progress_num">4</div>
						<div class="wpf_wizard_progress_desc"><?php _e( "Complete", 'atarim-visual-collaboration' ); ?></div>
					</div>
				</div>
				<div class="wpf_wizard_content_box">	
					<div class="wpf_title_wizard"><?php _e( "Is This Your First Time with Atarim?", 'atarim-visual-collaboration' ); ?></div>
					<p><?php _e( "Watch the short video so that we can get your solid results. Choose whether it's your first time or you've used Atarim before.", 'atarim-visual-collaboration' ); ?></p>
					<div class="wpf_wizard_video">
						<script src="https://fast.wistia.com/embed/medias/l18ga56xuh.jsonp" async></script>
						<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
						<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0; position:relative;">
							<div class="wistia_responsive_wrapper" style="height:100%; left:0; position:absolute; top:0; width:100%;">
								<div class="wistia_embed wistia_async_l18ga56xuh videoFoam=true" style="height:100%; position:relative; width:100%">&nbsp;</div>
							</div>
						</div>
					</div>
					<div class="wpf_wizard_dual_btns">
						<btn class="wpf_button wpf_button_sec" onclick="wpf_initial_setup_done( '<?php echo WPF_SITE_URL; ?>', 'wpf_existing_user' )"><?php _e( "I've Done This Before" ); ?></btn>
						<btn class="wpf_button" onclick="wpf_initial_setup_done( '<?php echo WPF_SITE_URL; ?>', 'wpf_new_user' )"><?php _e( "It's My First Time", 'atarim-visual-collaboration' ); ?></btn>
					</div>
				</div>
            </div>
        </form>
    </div>
</div>