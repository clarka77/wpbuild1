<?php
$output    = '';
$post_data = [
    'wpf_site_id' => get_option( "wpf_site_id" )
];


// Get the posts
$url         = WPF_CRM_API . 'wp-api/list/graphics';
$sendtocloud = json_encode( $post_data );
$myposts     = wpf_send_remote_post( $url, $sendtocloud );
$output     .= '<div class="wpf_gen_col" id="all_graphics_list_container"><div  class="wpf_row"><div class="wpf-col-3" id="all_graphics_list"><a href="javascript:wpf_create_graphics_buttons();" class="wpf_create_graphics_post" data-new_graphics=""><div class="wpf_graphics_new"><div class="wpf_graphics_new_title"><i class="gg-math-plus"></i><div class="wpf_graphics_new_title_main">' . __( 'Add a New Graphic', 'atarim-visual-collaboration' ) . '</div><div class="wpf_graphics_new_title_desc">' . __( "Upload an image to collaborate visually using Atarim, perfect for screen mockups, logos, flyers or any other type of graphic you are designing.", 'atarim-visual-collaboration' ) . '</div></div></div></a></div>';
if ( isset( $myposts['data'] ) && ! empty( $myposts['data'] ) ) {
    // Loop the posts
    $i = $myposts['count'];
    foreach ( $myposts['data'] as $mypost ) {
		$post_id    = $mypost['id'];
		$image      = $mypost['image'];
		$post_title = $mypost['title'];
		$wpf_complete_graphics = '';
		if ( $mypost['status'] == '1' ) {
			$wpf_completed_calss = ' wpf_completed';
			$wpf_completed_sign  = '<span class="wpf_right"><i class="gg-check"></i></sapn>';
		} else {
			$wpf_completed_calss = '';
			$wpf_completed_sign  = '';
		}
		$link    = site_url() . '/collaborate/graphic?id=' . $post_id;
		$output .= '<div class="wpf-col-3" id="' . $post_id . '"><a title="Delete Graphics" href="javascript:wpf_delete_conformation(' . $post_id . ')" class="wpf_graphics_delete_btn"><i class="gg-trash"></i></a><a target="_blank" href="' . $link . '"><div class="wpf_graphics_thumb" style="background-image:url(' . $image . ')"><div class="wpf_graphics_title' . $wpf_completed_calss . '">' . $post_title . $wpf_completed_sign . '</div></div></a></div>';
		$link    = "";
	}
    wp_reset_postdata();
}
$output .= '</div></div>';
echo $output;