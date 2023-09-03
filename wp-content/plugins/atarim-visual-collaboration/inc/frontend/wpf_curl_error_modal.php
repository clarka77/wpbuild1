<?php 
$wpf_skip_btn = '';
$wpf_colse_btn = '';
    $wpf_colse_btn = '<a href="javascript:void(0)" class="wpf_login_close" data-dismiss="alert">×</a>';
?>
<div class="wpf_wizard_container wpf_comment_container wpf_hide" data-html2canvas-ignore="true" id="wpf_login_container">
    <div class="wpf_wizard_modal">
    	<?php echo $wpf_colse_btn; ?>
    	<div class='wpfeedback_image-preview-wrapper'><img alt="Logo" title="Logo" id='wpf_image-preview' src='<?php echo get_wpf_logo(); ?>' height='100'></div>
        <div class="wpf_loader wpf_loader_wizard wpf_hide"></div>
        <!-- <?php echo do_shortcode('[wpf_error_message]');?> -->
        <?php echo $wpf_skip_btn; ?>
    </div>
</div>