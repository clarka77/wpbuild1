var box = '', comments=false, browser='', device_type='', resolution = window.screen.width+' x '+window.screen.height, new_task = [], task_screenshot=[], rightArrowParents = [], current_html_element='', relative_location={}, html_element_location={}, html_element_height=0, html_element_width=0, tasks_on_page = [], onload_wpfb_tasks = [], all_page_tasks_loaded=0, wpf_tasks_loaded=false,all_backend_tasks_loaded=0, wpf_clean_dom_elem_path='', temp_tasks = [], wpf_tab_permission = {'user':false,'status':false,'priority':false,'screenshot':false,'information':false,'delete_task':'no'},img_dwn_icon = "",open_per=0,in_progress_per=0,pending_review_per=0,complete_per=0,total_task=0;
var upg_url = upgrade_url.url;
var plugin_url = upgrade_url.plugin_url;
var internal_icon_html='<span class="wpf_chevron_wrapper"><img src="' + plugin_url + 'images/eye-off-white.svg" class="wpf-internal-img"></span>';
wpf_mark_internal_btn='<img src="' + plugin_url + 'images/eye-off.svg" class="wpf-internal-img"><span class="wpf_tooltiptext unmark_internal_tooltip_text">'+ switch_to_normal +'</span><span class="wpf_tooltiptext new_internal_tooltip_text">'+ create_internal_task +'</span><span class="wpf_tooltiptext mark_internal_tooltip_text">'+ switch_to_internal +'</span>';
var edit_comment_text="Update";
var cancel_edit_comment_text="Cancel";
var unsaved_task = true;

//show upgrade notice popup for edit/delete by Pratap
function edit_delete_plan() {
    jQuery_WPF('.wpf-uf-popup-image img').attr('src', plugin_url + '/images/edit-delete.png');
    jQuery_WPF('.wpf-uf-plan-title').text('Edit/Delete comment');
    jQuery_WPF('.wpf-uf-plan-detail').html('Make it easy to collaborate with your team on tasks that your clients cannot see with internal tasks. Change the logo, icon and main color to give your clients’ a unique experience.');
    jQuery_WPF('.wpf-uf-plan-link').attr('href', upg_url + '?&feature=edit');
    jQuery_WPF('.wpf-uf-pop-wrapper').show();
}

//close upgrade notice popup by Pratap
jQuery_WPF(document).on('click', '.wpf-uf-pop-wrapper, .wpf-uf-pop-container, .wpf-uf-close-popup .gg-close', function(e){
    if(e.target == this){
        jQuery_WPF('.wpf-uf-pop-wrapper').hide();
        jQuery_WPF('.wpf-uf-plan-title').text('');
        jQuery_WPF('.wpf-uf-plan-detail').html('');
        jQuery_WPF('.wpf-uf-plan-link').attr('href', '#');
    }
});

jQuery_WPF(document).ready(function(){
    wpf_tab_permission.user=wpf_tab_permission_user;
    wpf_tab_permission.priority=wpf_tab_permission_priority;
    wpf_tab_permission.status=wpf_tab_permission_status;
    wpf_tab_permission.screenshot=wpf_tab_permission_screenshot;
    wpf_tab_permission.information=wpf_tab_permission_information;
    wpf_tab_permission.delete_task=wpf_tab_permission_delete_task;
    wpf_tab_permission.auto_screenshot = wpf_tab_permission_auto_screenshot;
    wpf_tab_permission.display_stickers = wpf_tab_permission_display_stickers;
    wpf_tab_permission.display_task_id = wpf_tab_permission_display_task_id;

    /* keyboard shortcut permission v2.1.0 */
    wpf_tab_permission.keyboard_shortcut = wpf_tab_permission_keyboard_shortcut;

    jQuery_WPF(document).on('click', '.wpfbpriority', function(e){
        var elemid = jQuery_WPF(this).attr('data-elemid');
        set_task_prioirty(elemid);
        e.stopImmediatePropagation();
    });

    img_dwn_icon  = "<span id='wpf_push_media' class='wpf_push_media wpf_image_download'>"+push_to_media_icon+"</span><span id='wpf_image_download' class='wpf_image_download' onclick='wpf_image_download_action(this)'>"+image_download_icon+"</span><span id='' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>"+image_open_icon+"</span><span class='wpf_image_delete' onclick='wpf_task_image_delete(this)'>"+image_close_icon+"</span>";

    jQuery_WPF(document).on('click','.wpf_push_media',function(){
        let media_link=jQuery_WPF(this).parent().find('a').attr('href');
        let id_element=jQuery_WPF(this).parent().parent().parent().attr('id');
        let current_id = id_element.split("_")[2];
        
        jQuery_WPF.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {action:'push_to_media',wpf_nonce:wpf_nonce,media_link:media_link },
            beforeSend: function(){
                jQuery_WPF('.wpf_loader_'+current_id).show();
            },
            success: function(data){
                jQuery_WPF('.wpf_loader_'+current_id).hide();
                if(data==1){
                    jQuery_WPF('#pushed_to_media').show().delay(5000).fadeOut();
                }else{
                    jQuery_WPF('#wpf_push_to_media_error_'+current_id).show().delay(5000).fadeOut();
                }
            }
        });
    })


    jQuery_WPF(document).on('change','.mark_as_complete_checkbox',function() {
        if(this.checked) {
            let task_id = jQuery_WPF(this).attr('data-id');
            let status = "complete";
            jQuery("#wpfbtaskstatus-"+task_id+" .anim-slider input").prop('checked', false);
            jQuery("#wpfbtaskstatus-"+task_id+" .anim-slider .complete_radio").prop('checked', true);
            set_task_status(task_id,status);
        }else{
                let task_id = jQuery_WPF(this).attr('data-id');
                let status = "open";
                jQuery("#wpfbtaskstatus-"+task_id+" .anim-slider input").prop('checked', false);
                jQuery("#wpfbtaskstatus-"+task_id+" .anim-slider .open_radio").prop('checked', true);
                set_task_status(task_id,status);
        }
    });



    /* Keyboard shortcut => v2.1.0 */
    if (wpf_tab_permission.keyboard_shortcut == 'yes') {
        keyboard_shortcut(jQuery_WPF(window).width());
    }

});

function keyboard_shortcut(windowsize) {

    let isShiftKeyPressed = false;

    jQuery_WPF(document).on('keydown', function (e) {

        // check if the target is an input field or not
        // This check is needed because user can type C (SHIFT + c) on any input area
        // If that happen, we don't need to fire the Keyboard Shortcut functionality
        const target = jQuery_WPF( e.target );
        if ( !(target.is('input')) && !(target.is('textarea')) && !(target.is('select')) && !(target.is('.CodeMirror-code'))  && !(target.is('.block-editor-rich-text__editable')) ) {

            // if SHIFT key is pressed
            if (!isShiftKeyPressed) {
                if (e.keyCode == 16) {
                    isShiftKeyPressed = true;
                }
            } else {
                switch (e.keyCode) {

                    case 66:
                        e.preventDefault();
                        break;

                    case 70:
                        enable_comment(); // key F - open comment popup => v2.1.1
                        break;

                    case 71:
                        wpf_new_general_task(0); // key G - open general comment popup
                        break;

                    case 83:
                        if (windowsize <= 650) {
                            expand_compact_sidebar();
                        }
                        break;
                }
            }
        }
    }).on('keyup', function (e1) {

        if (e1.keyCode == 16) {
            isShiftKeyPressed = false;
        }
    });
}

jQuery_WPF(window).resize(function() {
    const windowsize = jQuery_WPF(window).width();
    keyboard_shortcut(windowsize);
});

function enable_comment(){
    if(restrict_plugin==1){
        jQuery(".wpf_locked_modal_container").show();
        return;
    }
    comments = true;
    jQuery_WPF("#wpf_enable_comment").show();
    jQuery_WPF("#wpf_panel").hide();

    var wpf_tmp_show_task_checkbox_obj = jQuery_WPF("#wpfb_display_tasks");
    if(wpf_tmp_show_task_checkbox_obj.prop('checked')==false){
        wpf_tmp_show_task_checkbox_obj.prop('checked',true);
    }
    wpf_display_tasks(wpf_tmp_show_task_checkbox_obj);

    jQuery_WPF("a").each(function(){
        if(this.id!='disable_comment_a'){
            jQuery_WPF(this).addClass('active_comment');
        }
    });
    jQuery_WPF('input[type="button"]').each(function(){
        if(this.id!='disable_comment_a'){
            jQuery_WPF(this).addClass('active_comment');
        }
    });
    jQuery_WPF('form').each(function(){
        if(this.id!='disable_comment_a'){
            jQuery_WPF(this).addClass('active_comment');
        }
    });
    jQuery_WPF('body').addClass('active_comment');
    jQuery_WPF('body').css('cursor','crosshair !important');
    jQuery_WPF('a').css('cursor','crosshair !important');

    if(comments==true){
        jQuery_WPF(this).addClass('wpf_enable_comment');
        jQuery_WPF(document).on('keyup',function(evt) {
            if (evt.keyCode == 27) {
               disable_comment();
            }
        });
    }

    // => v2.1.0
    if (temp_tasks) {
        temp_tasks = [];
    }
}

function disable_comment() {
    comments = false;
    jQuery_WPF("#wpf_enable_comment").hide();
    jQuery_WPF("#wpf_panel").show();
    jQuery_WPF("a").each(function(){
        jQuery_WPF(this).removeClass('active_comment');
    });
    jQuery_WPF('body').removeClass('active_comment');
    jQuery_WPF('body').css('cursor','default');
    jQuery_WPF('a').css('cursor','pointer');
    box = {};
}

function screenshot(id){
    const rollSound = new Audio(wpf_screenshot_sound);
    if(tasks_on_page[id] > 0){
        rollSound.play();

        html2canvas(document.body,{
            x: window.scrollX,
            y: window.scrollY,
            width: window.innerWidth,
            height: window.innerHeight,
            useCORS: true,
            logging: true,}).then(function(canvas) {

            var base64URL = canvas.toDataURL('image/jpeg',1);

            task_screenshot['post_id']=tasks_on_page[id];
            task_screenshot['task_config_author_name']=current_user_name;
            task_screenshot['task_config_author_id']=current_user_id;
            var new_task_screenshot_obj = jQuery_WPF.extend({}, task_screenshot);
            jQuery_WPF('body').addClass('wpfb_screenshot_class');
            setTimeout(function(){
                jQuery_WPF('body').removeClass('wpfb_screenshot_class');
            }, 500);

            jQuery_WPF.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {action:'wpfb_save_screenshot',wpf_nonce:wpf_nonce,task_screenshot:new_task_screenshot_obj, image: base64URL},
                beforeSend: function(){
                    jQuery_WPF('.wpf_loader_'+id).show();
                },
                success: function(data){
                    jQuery_WPF('.wpf_loader_'+id).hide();
                    var comment_html = '<li class="wpf_author"><level class="task-author">'+current_user_name+'</level><div class="meassage_area_main"><img src="'+data+'" /></div></li>';
                    jQuery_WPF('#task_comments_'+id).append(comment_html);
                    jQuery_WPF('#task_comments_'+id).animate({scrollTop: jQuery_WPF('#task_comments_'+id).prop("scrollHeight")}, 2000);
                }
            });
        });
    }
    else{
        jQuery_WPF('#wpf_error_'+id).hide();
        jQuery_WPF('#wpf_task_error_'+id).show();
    }
}

function new_task_screenshot(id){
    if(tasks_on_page[id] > 0){

        html2canvas(document.body,{
            x: window.scrollX,
            y: window.scrollY,
            width: window.innerWidth,
            height: window.innerHeight,
            useCORS: true,
            proxy: plugin_url+'imagehelper.php',
            logging: true,}).then(function(canvas) {

            var base64URL = canvas.toDataURL('image/jpeg',1);

            task_screenshot['post_id']=tasks_on_page[id];
            task_screenshot['task_config_author_name']=current_user_name;
            task_screenshot['task_config_author_id']=current_user_id;
            var new_task_screenshot_obj = jQuery_WPF.extend({}, task_screenshot);

                jQuery_WPF.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {action:'wpfb_save_screenshot',wpf_nonce:wpf_nonce,task_screenshot:new_task_screenshot_obj, image: base64URL, autoscreen:1},
                    success: function(data){
                        jQuery_WPF('#screenshot_img_'+id).parent().attr('href', data);
                        jQuery_WPF('#screenshot_img_'+id).attr('src', data);
                        jQuery_WPF('#screenshot_img_'+id).show();
                    }
                });
        });
    }
    else{
        jQuery_WPF('#wpf_error_'+id).hide();
        jQuery_WPF('#wpf_task_error_'+id).show();
        }
}

jQuery_WPF(window).on('load', function(){
    if(disable_for_admin == 0){
        load_wpfb_tasks();
        jQuery_WPF( function() {
            // added drag on the parent of the wpf_launch_buttons => v2.1.0
            jQuery_WPF( "#wpf_launcher > #wpf_launch_buttons_wrapper" ).draggable({ containment: "body" });
        });
    }

    jQuery_WPF('a').click(function(e) {
        if(jQuery_WPF(this).hasClass("active_comment")){
            e.preventDefault();
        }
        else{
        }
    });

    jQuery_WPF('a img').click(function(e) {
        if(jQuery_WPF(this).parent().hasClass("active_comment")){
            e.preventDefault();
        }
        else{
        }
    });

    jQuery_WPF('input[type="button"]').click(function(e) {
        if(jQuery_WPF(this).hasClass("active_comment")){
            e.preventDefault();
            return false;
        }
        else{
        }
    });

    jQuery_WPF("form").submit(function(e){
        if(comments==true){
            e.preventDefault();
        }
    });

    jQuery_WPF('.wpf_general_task_close').on('click',function () {
        jQuery_WPF('#wpf_general_comment_tabs').html('');
        jQuery_WPF('#wpf_general_comment').hide();
    });

    jQuery_WPF('#wpf_graphics_img').on('click', function(event) {
        var no_of_elements = jQuery_WPF(this).parents().addBack().not('html').length-1, temp_count = 0;
        if(this.id!="disable_comment_a"){
            if(comments==true){
                rightArrowParents = [];
                temp_tasks[comment_count] = [];
                curr_browser_temp = get_browser();
                browser = curr_browser_temp['name']+' '+curr_browser_temp['version'];
                relative_location = mousePositionElement(event);
                temp_tasks[comment_count]['relative_location']=relative_location;
                html_element_location = getOffset(this);
                temp_tasks[comment_count]['html_element_location']=html_element_location;
                html_element_width = jQuery_WPF(this).width();
                temp_tasks[comment_count]['html_element_width']=html_element_width;
                html_element_height = jQuery_WPF(this).height();
                temp_tasks[comment_count]['html_element_height']=html_element_height;
                temp_tasks[comment_count]['left'] = event.pageX - jQuery_WPF(this).offset().left;
                temp_tasks[comment_count]['top'] = event.pageY - jQuery_WPF(this).offset().top;

                var wpfb_users_arr = JSON.parse(wpfb_users);
                var wpfb_users_html = '<ul class="wp_feedback_filter_checkbox user">';

                for (var key in wpfb_users_arr) {
                    if (wpfb_users_arr.hasOwnProperty(key)) {
                        var user_name = wpfb_users_arr[key]['displayname'];
                        if(current_user_id==key || wpf_website_builder==key){
                            wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'" checked><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                        }
                        else{
                            wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'"><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                        }
                    }
                }
                wpfb_users_html+='</ul>';

                var element_center = getelementcenter(rightArrowParents.join(" > "));
                element_center['left']=element_center['left']-25;
                element_center['top']=element_center['top']-25;
                jQuery_WPF(rightArrowParents.join(" > ")).addClass('wpfb_task_bubble');
                jQuery_WPF(rightArrowParents.join(" > ")).attr('data-task_id', comment_count);

                //remove sticker if task not saved by Pratap
                if ( unsaved_task ) {
                    var popid = jQuery_WPF( '#bubble-' + bubble_comment_count ).attr('aria-describedby');
                    jQuery_WPF( '#bubble-' + bubble_comment_count ).remove();
                    jQuery_WPF( '#' + popid ).remove();
                }

                var wpf_popover_html = wpf_task_popover_html(1,comment_count,0,'',wpfb_users_html,'','','',1);
                jQuery_WPF('#wpf_launcher').after(wpf_popover_html);
                init_custom_popover(comment_count);
                tasks_on_page[comment_count]=0;
                unsaved_task = true;
                
                event.preventDefault();
            }
        }
        if(comments==true){
            disable_comment();
            event.stopPropagation();
        }
    });
});

function set_task_prioirty(id){
    var task_info = [];
    var task_priority = jQuery_WPF('input[name=wpfbpriority'+id+']:checked').val();
    let custom_status_class = '';

    task_info['task_id'] = tasks_on_page[id];
    task_info['task_priority']=task_priority;

    var task_info_obj = jQuery_WPF.extend({}, task_info);
    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_set_task_priority",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success : function(data){
            jQuery_WPF('.wpf_loader_'+id).hide();
            jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").removeClass('low').removeClass('medium').removeClass('high').removeClass('critical');
            jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").addClass(task_priority);
            jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").find('span.sticker').removeAttr('class').addClass('sticker');
            if(wpf_tab_permission.display_stickers == 'yes'){
                custom_status_class = task_priority+'_custom';
                jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").find('span.sticker').addClass(task_priority).addClass(custom_status_class).addClass('sticker');
                jQuery_WPF('#bubble-'+id+'  span.sticker').removeAttr('class').addClass('sticker');
                jQuery_WPF('#bubble-'+id+'  span.sticker').addClass(task_priority+'_custom');
            }

            
            if(data != '') {
                jQuery("#task_comments_"+id).append('<li class="wpf_other  is_info" data-comment_id=""><level class="task-author"><span>1 second ago</span></level><div class="meassage_area_main"><div class="chat_text">'+data+'</div></div></li>');
            }

            jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").closest('li').find('span.priority').attr('title','Priority : '+task_priority);

            const $li = jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'], #wpf_allpages [data-taskid='" + id + "']");

            // clear old priority class from the element
            if ( $li.hasClass('low') ) {
                $li.removeClass('low');
            } else if ($li.hasClass('high')) {
                $li.removeClass('high');
            } else if ( $li.hasClass('medium') ) {
                $li.removeClass('medium');
            } else {
                $li.removeClass('critical');
            }

            // add new priority class to the element
            $li.addClass(task_priority);

            // fix scroll to bottom when status update => v2.1.0
            jQuery_WPF('#task_comments_'+id).animate({scrollTop: jQuery_WPF('#task_comments_'+id).prop("scrollHeight")}, 2000);
        }
    });
}

function set_task_status(id,status = ''){
    var task_info = [];

    if(status != ''){
        var task_status = status;    
    }else{
        var task_status = jQuery_WPF('input[name=wpfbtaskstatus'+id+']:checked').val();    
    }

    var task_notify_users = [];
    var task_comment = jQuery_WPF('#comment-'+id).val();
    jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
        task_notify_users.push(jQuery_WPF(this).val());
    });
    task_notify_users =task_notify_users.join(",");
    let custom_status_class = '';

    task_info['task_id'] = tasks_on_page[id];
    task_info['task_status']=task_status;
    task_info['task_notify_users']=task_notify_users;

    var task_info_obj = jQuery_WPF.extend({}, task_info);

    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_set_task_status",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success : function(data){
            jQuery_WPF('.wpf_loader_'+id).hide();
            var status_class_str = "";
            if(wpf_tab_permission.display_stickers == 'yes'){
                status_class_str = " "+task_status+"_custom";
            }
            if(jQuery_WPF('#wpf_mark_internal_'+id).hasClass('wpf_is_internal')){
                internal_icon=internal_icon_html;
            }else{
                internal_icon='';
            }
            var view_id='<span class="wpf_bubble_num_wrapper">'+jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number,#wpf_allpages [data-taskid='" + id + "'] .wpf_task_number,#wpf_backend [data-taskid='" + id + "'] .wpf_task_number").attr("data-disp-id")+'</span>'+internal_icon;
            var view_id_sidebar='<span class="wpf_bubble_num_wrapper">'+jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number,#wpf_allpages [data-taskid='" + id + "'] .wpf_task_number,#wpf_backend [data-taskid='" + id + "'] .wpf_task_number").attr("data-disp-id")+'</span>';

            if(task_status == 'complete'){

                jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').removeClass('complete_custom').removeClass('open_custom').removeClass('pending-review_custom').removeClass('in-progress_custom');
                jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").addClass(task_status+status_class_str);
                let task_priority = jQuery_WPF('input[name=wpfbpriority'+id+']:checked').val();

                let display_check_mark = '';
                if(wpf_tab_permission.display_task_id != 'yes'){
                     display_check_mark = '<i class="gg-check"></i>';
                }else{
                    display_check_mark = view_id;
                 }


                if(wpf_tab_permission.display_stickers == 'yes'){
                    jQuery_WPF('#bubble-'+id).html('<span class="sticker '+task_priority+' '+task_priority+'_custom"></span> '+display_check_mark);
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number,#wpf_allpages [data-taskid='" + id + "'] .wpf_task_number,#wpf_backend [data-taskid='" + id + "'] .wpf_task_number").html('<span class="sticker '+task_priority+' '+task_priority+'_custom"></span> '+display_check_mark);
                }else{
                    jQuery_WPF('#bubble-'+id).html(display_check_mark);
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number,#wpf_allpages [data-taskid='" + id + "'] .wpf_task_number,#wpf_backend [data-taskid='" + id + "'] .wpf_task_number").html(display_check_mark);
                }

                jQuery_WPF('#bubble-'+id).removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').removeClass('complete_custom').removeClass('open_custom').removeClass('pending-review_custom').removeClass('in-progress_custom').addClass(task_status+' '+status_class_str);
                jQuery_WPF('#bubble-'+id).css('display',"block");

                // checked the mark complete checkbox
                jQuery_WPF("#mark_complete_" + id).attr("checked", true);

            }else{
                let task_priority = jQuery_WPF('input[name=wpfbpriority'+id+']:checked').val();
                jQuery_WPF('#bubble-'+id).removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').removeClass('complete_custom').removeClass('open_custom').removeClass('pending-review_custom').removeClass('in-progress_custom').addClass(task_status+' '+status_class_str);
                jQuery_WPF('#bubble-'+id).find('.gg-check').remove();
                if(jQuery_WPF('#bubble-'+id).find('span .'+task_priority).length == 0 && wpf_tab_permission.display_stickers == 'yes'){
                    jQuery_WPF('#bubble-'+id).html('<span class="sticker '+task_priority+' '+task_priority+'_custom"></span>'+view_id);    
                }else{
                    jQuery_WPF('#bubble-'+id).html(view_id);
                }


                if(jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").find('span .'+task_priority).length == 0 && wpf_tab_permission.display_stickers == 'yes'){
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").html('<span class="'+task_priority+' '+task_priority+'_custom"></span>'+view_id_sidebar);
                }else{
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'] .wpf_task_number").html(view_id_sidebar);
                }

                
                jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").removeClass('open').removeClass('complete').removeClass('pending-review').removeClass('in-progress').removeClass('complete_custom').removeClass('open_custom').removeClass('pending-review_custom').removeClass('in-progress_custom');

                if(wpf_tab_permission.display_stickers == 'yes'){
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").addClass(task_status+' '+task_status+'_custom');    
                }else{
                    jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "']").addClass(task_status);
                }

                // unchecked the mark complete checkbox
                jQuery_WPF("#mark_complete_" + id).attr("checked", false);
            }

            
            if(data != '') {
                jQuery("#task_comments_"+id).append('<li class="wpf_other  is_info" data-comment_id=""><level class="task-author"><span>1 second ago</span></level><div class="meassage_area_main"><div class="chat_text">'+data+'</div></div></li>');
            }

            jQuery_WPF(document).find("#wpf_thispage [data-taskid='" + id + "'], #wpf_allpages [data-taskid='" + id + "']").closest('li').find('span.task_status').attr('title','Status : '+task_status);

            // fix scroll to bottom when status update => v2.1.0
            jQuery_WPF('#task_comments_'+id).animate({scrollTop: jQuery_WPF('#task_comments_'+id).prop("scrollHeight")}, 2000);
        }
    });
}

function set_task_notify_users(id) {
    var task_info = [];
    var task_notify_users = [];

    jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
        task_notify_users.push(jQuery_WPF(this).val());
    });
    task_notify_users =task_notify_users.join(",");

    task_info['task_id'] = tasks_on_page[id];
    task_info['task_notify_users']=task_notify_users;

    var task_info_obj = jQuery_WPF.extend({}, task_info);

    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_set_task_notify_users",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success : function(data){
            jQuery_WPF('.wpf_loader_'+id).hide();
        }
    });
}

function new_comment(id, internal=0){
    if(restrict_plugin==1){
        jQuery(".wpf_locked_modal_container").show();
        return;
    }

    if(tasks_on_page[id]!=0){
        if(internal==1){
            if(jQuery_WPF('#wpf_mark_internal_'+id).hasClass('wpf_is_internal')){
                mark_internal(id,0);
            }else{
                mark_internal(id,1);
            }
            return;
        }
    }

    if(jQuery_WPF.trim(jQuery_WPF('#comment-'+id).val()).length==0){
        jQuery_WPF('#comment-'+id).attr('style','border: 1px solid red;');
        jQuery_WPF('#comment-'+id).focus();
        jQuery_WPF('#wpf_task_error_'+id).show();
        jQuery_WPF('#wpf_error_'+id).hide();
        return false;
    }
    else{
        if ( internal == 0 ) {
            if(jQuery_WPF('input[name="author_list_'+id+'"]:checked').length > 0){
            }
            else{
                jQuery_WPF('#wpfbuser-tab-'+id).click();
                jQuery_WPF('#wpf_task_error_'+id).hide();
                jQuery_WPF('#wpf_error_'+id).show();
                return false;
            }
        }
    }

    if(tasks_on_page[id]==0){
        generate_task(id,internal);
    }
    else{
        generate_comment(id);
    }
}

function mark_internal(id,internal){
    var task_info = [];

    var task_notify_users = [];
    var task_comment = jQuery_WPF('#comment-'+id).val();
    jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
        task_notify_users.push(jQuery_WPF(this).val());
    });

    task_info['task_id'] = tasks_on_page[id];
    task_info['internal']=internal;

    var task_info_obj = jQuery_WPF.extend({}, task_info);

    var task_info_obj = jQuery_WPF.extend({}, task_info);
    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_mark_as_internal",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
            jQuery_WPF(document).find('.wpf_loader_'+id).show();
        },
        success : function(data){
            const jsonData = JSON.parse(data);
            if ( jsonData['limit'] === true ) {

                // task lock popup
                if ( jsonData['type'] === 'task' )
                    jQuery("#wpf_task_locked_modal").show();
                else if ( jsonData['type'] === 'internal_task' )
                    jQuery("#wpf_internal_task_locked_modal").show();

                return;
            }
            
            let tagged_area_container = jQuery_WPF(".wpf_sidebar_content #wpf_thispage [data-taskid="+ id +"]");
            if(internal=='1'){
                jQuery_WPF('#wpf_mark_internal_'+id).addClass('wpf_is_internal').removeClass('mark_internal_tooltip').addClass('unmark_internal_tooltip');
                jQuery_WPF('#bubble-'+id).addClass('wpfb-internal');
                jQuery_WPF('#bubble-'+id).append(internal_icon_html);

                // hide the notifu users block
                const notify_user_block = jQuery_WPF('#wpf_mark_internal_'+id).parents('.popover-body').find('#wpfbuser-'+id);
                jQuery_WPF(notify_user_block).find('ul').hide();
                jQuery_WPF(notify_user_block).find('ul').before('<span class="wpf_hide_users_for_internal_tasks" id="wpf_success_wpf_share_page_link">Notifications for internal tasks are handled through your Agency Dashboard</span>');

                // show the tag from the sidebar
                if ( jQuery_WPF(tagged_area_container).find(".wpf_task_type").length > 1 ) {
                    jQuery_WPF(".wpf_sidebar_content #wpf_thispage [data-taskid="+ id +"] .wpf_task_type").before('<span class="wpf_task_type" title="Task type">Internal</span>');
                } else {
                    jQuery_WPF(tagged_area_container).find(".wpf_task_meta").before('<span class="wpf_task_type" title="Task type">Internal</span>');
                }
            }else{
                jQuery_WPF('#wpf_mark_internal_'+id).removeClass('wpf_is_internal').removeClass('unmark_internal_tooltip').addClass('mark_internal_tooltip');
                jQuery_WPF('#bubble-'+id).removeClass('wpfb-internal');
                jQuery_WPF('#bubble-'+id).find('.wpf_chevron_wrapper').remove();

                // diplay the notifu users block
                const notify_user_block = jQuery_WPF('#wpf_mark_internal_'+id).parents('.popover-body').find('#wpfbuser-'+id);
                jQuery_WPF(notify_user_block).find('span').remove();
                jQuery_WPF(notify_user_block).find('ul').show();

                // remove the tag from the sidebar
                jQuery_WPF(tagged_area_container).find(".wpf_task_type:first").remove();
            }
            jQuery_WPF(document).find('.wpf_loader_'+id).hide();
          
        }
    });
}

function generate_task(id,internal){

    let url_string = window.location.href;
    let url = new URL(url_string);
    let graphic_id = url.searchParams.get("id");

    var curr_browser = get_browser();
    var new_task = Array();
    var task_priority = jQuery_WPF('input[name=wpfbpriority'+id+']:checked').val();
    var task_status = jQuery_WPF('input[name=wpfbtaskstatus'+id+']:checked').val();
    var task_notify_users = [];
    var task_comment = jQuery_WPF('#comment-'+id).val();
    jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
        task_notify_users.push(jQuery_WPF(this).val());
    });
    task_notify_users =task_notify_users.join(",");
    new_task['graphic_id'] = graphic_id;
    new_task['task_number']=id;
    new_task['task_priority']=task_priority;
    new_task['task_status']=task_status;
    new_task['task_config_author_browser']=curr_browser['name'];
    new_task['task_config_author_browserVersion']=curr_browser['version'];
    new_task['task_config_author_browserOS']=curr_browser['OS'];
    new_task['task_config_author_name']=current_user_name;
    new_task['task_config_author_id']=current_user_id;
    new_task['task_config_author_resX']=window.screen.width;
    new_task['task_config_author_resY']=window.screen.height;
    new_task['task_title']=task_comment;
    new_task['task_page_url']=url_string;
    new_task['task_page_title']= jQuery_WPF('.wpf_topbar .wpf_graphics_title').text();
    new_task['current_page_id']=graphic_id;
    new_task['task_comment_message']=task_comment;
    new_task['task_notify_users']=task_notify_users;
    new_task['task_type'] = 'general';

    if(internal==1){
        new_task['internal']='1';
    }

    // temp_tasks are present for common tasks only
    // the condition is checking if the task general or common and based on that it will store info => 2.1.0
    if (temp_tasks.length) {
        new_task['task_clean_dom_elem_path'] = temp_tasks[id]['wpf_clean_dom_elem_path'];
        new_task['task_element_html'] = temp_tasks[id]['current_html_element'];
        new_task['task_X'] = temp_tasks[id]['relative_location'].x;
        new_task['task_Y'] = temp_tasks[id]['relative_location'].y;
        new_task['task_elementX'] = temp_tasks[id]['html_element_location'].x;
        new_task['task_elementY'] = temp_tasks[id]['html_element_location'].y;
        new_task['task_element_height'] = temp_tasks[id]['html_element_height'];
        new_task['task_element_width'] = temp_tasks[id]['html_element_width'];
        new_task['task_left'] = temp_tasks[id]['left'];
        new_task['task_top'] = temp_tasks[id]['top'];
        new_task['task_type'] = 'graphics';
    }

    new_task['task_relativeX'] = '';
    new_task['task_relativeY'] = '';
    new_task['page_type'] = 'default';
    var temp_task_text = task_comment;
    if(wpf_is_valid_url(task_comment) == true){
        if ( is_video_Url( task_comment ) )
            temp_task_text = wpf_is_valid_video_url(task_comment);
        else
            temp_task_text = URLify(task_comment);
    } else {
        temp_task_text = URLify(task_comment);
    }
    var new_task_obj = jQuery_WPF.extend({}, new_task);
    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpf_add_new_task",wpf_nonce:wpf_nonce,new_task:new_task_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success : function(data){
            try {
                const jsonData = JSON.parse(data);
                if ( jsonData['limit'] === true ) {

                    // site lock popup
                    if ( jsonData['type'] === 'site' )
                        jQuery(".wpf_locked_modal_container").show();

                    // task lock popup
                    if ( jsonData['type'] === 'task' )
                        jQuery("#wpf_task_locked_modal").show();
                    else if ( jsonData['type'] === 'internal_task' )
                        jQuery("#wpf_internal_task_locked_modal").show();

                    return;
                }
            } catch (excep) {}

            jQuery_WPF('#wpf_error_'+id).hide();
            jQuery_WPF('#wpf_task_error_'+id).hide();
            jQuery_WPF('.wpf_loader_'+id).hide();
            if(data!=0){
                tasks_on_page[id]=data;
                if(wpf_tab_permission.auto_screenshot=='yes'){
                    jQuery_WPF('.wpf_loader_'+id).hide();
                    new_task_screenshot(id);
                }else{
                    jQuery_WPF('.wpf_loader_'+id).hide();
                }
                jQuery_WPF('#wpfbsysinfo_task_id-'+id).html(tasks_on_page[id]);

                var wpf_task_status_label= '<div class="wpf_task_label"><span class="task_status wpf_'+new_task['task_status']+'" title="Status: '+new_task['task_status']+'">'+status_icon+'</span>';
                var wpf_task_priority_label= '<span class="priority wpf_'+new_task['task_priority']+'" title="Priority: '+new_task['task_priority']+'">'+priority_icon+'</span></div>';
                var wpfb_tags_html = '';

                if(wpf_tab_permission.display_stickers == true){
                    var wpf_task_status_label= '<div class="wpf_task_label"><span class="task_status wpf_'+new_task['task_status']+' wpf_'+new_task['task_status']+'_custom"><i class="gg-thermostat"></i></span>';
                    var wpf_task_priority_label= '<span class="priority wpf_'+new_task['task_priority']+' wpf_'+new_task['task_priority']+'_custom"><i class="gg-danger"></i></span></div>';
                }else{
                    var wpf_task_status_label= '<div class="wpf_task_label"><span class="task_status wpf_'+new_task['task_status']+'"><i class="gg-thermostat"></i></span>';
                    var wpf_task_priority_label= '<span class="priority wpf_'+new_task['task_priority']+'"><i class="gg-danger"></i></span></div>';
                }

                var wpfb_tags_html = '';
                if(new_task['task_type']=='general'){
                    var view_id = bubble_comment_count;
                    bubble_comment_count++;
                }else{
                    var view_id = bubble_comment_count;
                }

                if(internal==1){
                    var internal_icon=internal_icon_html;
                    jQuery_WPF('#bubble-'+id).append(internal_icon);
                    jQuery_WPF('#wpf_mark_internal_'+id).addClass('wpf_is_internal').removeClass('new_internal_tooltip').addClass('unmark_internal_tooltip');
                    jQuery_WPF('#bubble-'+id).addClass('wpfb-internal');

                    // hide the notifu users block
                    const notify_user_block = jQuery_WPF('#wpf_mark_internal_'+id).parents('.popover-body').find('#wpfbuser-'+id);
                    jQuery_WPF(notify_user_block).find('ul').hide();
                    jQuery_WPF(notify_user_block).find('ul').before('<span class="wpf_hide_users_for_internal_tasks" id="wpf_success_wpf_share_page_link">Notifications for internal tasks are handled through your Agency Dashboard</span>');
                }else{
                    internal_icon='';

                    // diplay the notifu users block
                    const notify_user_block = jQuery_WPF('#wpf_mark_internal_'+id).parents('.popover-body').find('#wpfbuser-'+id);
                    jQuery_WPF(notify_user_block).find('span').remove();
                    jQuery_WPF(notify_user_block).find('ul').show();
                }

                let internal_tag_html = '';
                if ( internal ) {
                    internal_tag_html = '<span class="wpf_task_type" title="Task type">Internal</span>';
                }

                if(new_task['task_type']=='general'){
                    jQuery_WPF(document).find("#wpf_delete_container_"+id).on("click",".wpf_task_delete_btn",function(e) {
                        var btn_elemid = jQuery_WPF(this).data('btn_elemid');
                        jQuery_WPF('.wpfbsysinfo_delete_task_id_'+btn_elemid).show();
                    });
                    jQuery_WPF(document).find("#wpf_delete_container_"+id).on("click",".wpf_task_delete",function(e) {
                        var elemid = jQuery_WPF(this).data('elemid');
                        var task_id = jQuery_WPF(this).data('taskid');
                        wpf_delete_task(elemid,task_id);
                        jQuery_WPF('#wpf_general_comment .wpf_general_task_close').trigger('click');
                        comment_count--;
                    });

                    const general_tag_html = '<span class="wpf_task_type" title="Task type">General</span>';

                    if(wpf_tab_permission.display_stickers == 'yes'){
                        jQuery_WPF('#wpf_thispage_container_today').prepend('<li class="current_page_general_task '+task_status+' '+task_status+'_custom" data-taskid="'+id+'" data-postid="'+tasks_on_page[id]+'"><div class="wpf_task_number" data-disp-id="'+view_id+'"><span class="'+task_priority+'_custom"></span>'+view_id+'</div><div class="wpf_task_sum"><level class="task-author">'+current_user_name+'<span>'+wpf_just_now+'</span></level><div class="current_page_task_list">'+task_comment+'</div></div>'+internal_tag_html+''+general_tag_html+'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details"><span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span> <span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span>'+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>');
                    }else{
                        jQuery_WPF('#wpf_thispage_container_today').prepend('<li class="current_page_general_task '+task_status+'" data-taskid="'+id+'" data-postid="'+tasks_on_page[id]+'"><div class="wpf_task_number" data-disp-id="'+view_id+'">'+view_id+'</div><div class="wpf_task_sum"><level class="task-author">'+current_user_name+'<span>'+wpf_just_now+'</span></level><div class="current_page_task_list">'+task_comment+'</div></div>'+internal_tag_html+''+general_tag_html+'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details"><span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span> <span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span>'+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>');
                    }
                }else{
                    
                    if(wpf_tab_permission.display_stickers == 'yes'){
                        jQuery_WPF('#wpf_thispage_container_today').prepend('<li class="current_page_task '+task_status+' '+task_status+'_custom" data-taskid="'+id+'"><div class="wpf_task_number" data-disp-id="'+view_id+'"> <span class="'+task_priority+'_custom"></span>'+view_id+'</div><div class="wpf_task_sum"><level class="task-author">'+current_user_name+'<span>'+wpf_just_now+'</span></level><div class="current_page_task_list">'+task_comment+'</div></div>'+internal_tag_html+'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details"><span class="wpf_task_type">'+wpf_graphics_tag+'</span>'+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>');
                    }else{
                        jQuery_WPF('#wpf_thispage_container_today').prepend('<li class="current_page_task '+task_status+'" data-taskid="'+id+'"><div class="wpf_task_number" data-disp-id="'+view_id+'">'+view_id+'</div><div class="wpf_task_sum"><level class="task-author">'+current_user_name+'<span>'+wpf_just_now+'</span></level><div class="current_page_task_list">'+task_comment+'</div></div><div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details"><span class="wpf_task_type">'+wpf_graphics_tag+'</span>'+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>');
                    }
                }
                img_dwn_icon = "";
                var comment_html = '<li class="wpf_author"><level class="task-author">'+current_user_name+'<span>'+wpf_just_now+'</span></level><div class="meassage_area_main"><div class="chat_text">'+temp_task_text+'</div></div></li>';
                jQuery_WPF('#task_comments_'+id).append(comment_html);

                jQuery_WPF('#wpf_delete_container_'+id).html('<span class="wpfbsysinfo_delete_btn_task_id_'+id+'"><a href="javascript:void(0)" class="wpf_task_delete_btn" data-btn_elemid="'+data+'" style="color:red;"><i class="gg-trash"></i>'+wpf_delete_ticket+'</a></span><p class="wpfbsysinfo_delete_task_id_'+data+' wpf_hide" ><b>'+wpf_delete_conform_text1+'</b><br>'+wpf_delete_conform_text2+' <a href="javascript:void(0)" class="wpf_task_delete" data-taskid="'+data+'" data-elemid="'+id+'" style="color:red;">'+wpf_yes+'</a></p>');
                jQuery_WPF('#comment-'+id).val('');
                jQuery_WPF('.wpfbpriority').click(function(e){
                    set_task_prioirty(id);
                    e.stopImmediatePropagation();
                });
                jQuery_WPF('.wpfbtaskstatus').click(function(e){
                    set_task_status(id);
                    e.stopImmediatePropagation();
                });
                jQuery_WPF('.wpfbtasknotifyusers').click(function(){
                    set_task_notify_users(id);
                });
                // empty Task center rich text editor by Pratap
                jQuery_WPF('#comment-'+id).closest('.form-group').find('.ql-editor').html('');
                jQuery_WPF('#task_comments_'+id).animate({scrollTop: jQuery_WPF('#task_comments_'+id).prop("scrollHeight")}, 2000);

                comment_count++;
                bubble_comment_count++;
                unsaved_task = false;
            }
        }
    });
}


function generate_comment(id){
    var new_task = Array();
    var task_priority = jQuery_WPF('input[name=wpfbpriority'+id+']:checked').val();
    var task_status = jQuery_WPF('input[name=wpfbtaskstatus'+id+']:checked').val();
    var task_comment = jQuery_WPF('#comment-'+id).val();
    var temp_task_text =task_comment;
    var task_notify_users = [];
    jQuery_WPF.each(jQuery_WPF('input[name=author_list_'+id+']:checked'), function(){
        task_notify_users.push(jQuery_WPF(this).val());
    });
    task_notify_users =task_notify_users.join(",");

    new_task['task_id']=tasks_on_page[id];
    new_task['user_id']=(eval(current_user_id) > 0) ? current_user_id : 0 ; // => v2.1.0
    new_task['comment_content']=task_comment;
    new_task['is_note']="0";

    if(wpf_is_valid_url(task_comment) == true){
        if ( is_video_Url( task_comment ) )
            temp_task_text = wpf_is_valid_video_url(task_comment);
        else
            temp_task_text = URLify(task_comment);
    }
    var new_task_obj = jQuery_WPF.extend({}, new_task);

    jQuery_WPF.ajax({
        method:"POST",
        url: ajaxurl,
        data: {action:'wpfb_add_comment',wpf_nonce:wpf_nonce,new_task:new_task_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success: function(data){
            let responseData = null;
            try {
                responseData = JSON.parse(data);
                if ( responseData['limit'] === true ) {
                    jQuery(".wpf_locked_modal_container").show();
                    return;
                }
            } catch(ex){}
            
            jQuery_WPF('#wpf_error_'+id).hide();
            jQuery_WPF('.wpf_loader_'+id).hide();

            const comment_data = responseData['data'];

            // edit / delete button
            var edit_delete_button_html = '';
            if (jQuery_WPF.inArray('edit', blocked) >= 0) {
                edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="edit_delete_plan()" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="edit_delete_plan()" ><i class="gg-trash"></i></a></div>`;
            } else {
                edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="wpf_edit_box_active(${comment_data['id']})" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="wpf_delete_comment(${comment_data['id']})" ><i class="gg-trash"></i></a></div>`;
            }

            var comment_html = '<li class="wpf_author"><level class="task-author">'+current_user_name+' '+wpf_just_now+'</level><div class="meassage_area_main"><div class="chat_text" id="wpf-chat-text-'+comment_data['id']+'">'+temp_task_text+'</div>'+edit_delete_button_html+'</div><div id="wpfb-edit-comment-wrapper-'+comment_data['id']+'" class="wpfb-edit-comment-wrapper"><div class="wpf-editor"></div><textarea class="form-control wpfb-edit-comment" data-comment_id="'+comment_data['id']+'" placeholder="Edit the comment..." spellcheck="false">'+comment_data['comment_content']+'</textarea><button class="wpf_edit_comment_btn" onclick="wpfb_edit_comment('+comment_data['id']+')">'+edit_comment_text+'</button><a class="wpf-cancel-edit-comment" onclick="wpfb_cancel_edit_comment('+comment_data['id']+')" href="javascript:void(0)">'+cancel_edit_comment_text+'</a></div></li>';
            jQuery_WPF('#task_comments_'+id).append(comment_html);
            jQuery_WPF('#comment-'+id).val('');
            // empty Task center rich text editor by Pratap
            jQuery_WPF('#comment-'+id).closest('.form-group').find('.ql-editor').html('');
            jQuery_WPF('#task_comments_'+id).animate({scrollTop: jQuery_WPF('#task_comments_'+id).prop("scrollHeight")}, 2000);
        }
    });
}

function load_wpfb_tasks(){
    if(atarim_server_down=='true'){
        return;
    }
    let url_string = window.location.href;
    let url = new URL(url_string);
    let graphic_id = url.searchParams.get("id");
        jQuery_WPF.ajax({
            method:"POST",
            url: ajaxurl,
            data: {action:'load_wpfb_graphic_tasks',wpf_nonce:wpf_nonce,page_type:page_type,'is_graphics':1,'graphic_id':graphic_id},
            success: function(data){
                var uncompleted_task;
		jQuery_WPF('.wpf_page_loader').hide();
                wpf_tasks_loaded = true;
                jQuery_WPF('body #wpf_launcher').addClass("wpf_launcher_loaded");
                onload_wpfb_tasks = JSON.parse(data);
                
                if(onload_wpfb_tasks !== '' && onload_wpfb_tasks !== null && onload_wpfb_tasks !== undefined){
                    const k = Object.keys(onload_wpfb_tasks).sort(timeSort);
                    comment_count_initial = Object.keys(onload_wpfb_tasks).length;

                    jQuery_WPF.each(k,function (index, value) {
                        if(onload_wpfb_tasks[value].is_internal=='1'){
                            if(wpf_current_role!='advisor'){
                                return;
                            }
                        }
                        tasks_on_page[onload_wpfb_tasks[value].task_comment_id]=value;

                        if(onload_wpfb_tasks[value].task_status == "open"){
                            open_per = open_per+1;
                        }

                        if(onload_wpfb_tasks[value].task_status == "in-progress"){
                            in_progress_per = in_progress_per+1;
                        }

                        if(onload_wpfb_tasks[value].task_status == "pending-review"){
                            pending_review_per = pending_review_per+1;
                        }

                        if(onload_wpfb_tasks[value].task_status == "complete"){
                            complete_per = complete_per+1;
                        }

                        total_task = total_task+1;

                        generate_wpfb_task_html(value,onload_wpfb_tasks[value]);
                        jQuery_WPF( function() {
                            jQuery_WPF( "#bubble-"+onload_wpfb_tasks[value].task_comment_id).draggable({ containment: "body", stop: function(event) {
                                    wpf_update_bubble_position(value,onload_wpfb_tasks[value],event.pageX - jQuery_WPF('#wpf_graphics_img').offset().left,event.pageY - jQuery_WPF('#wpf_graphics_img').offset().top);
                                } })
                        });
                        comment_count_initial--;
                    });

                    if(open_per > 0){
                        open_per = (open_per*100)/total_task;
                    }

                    if(in_progress_per > 0){
                        in_progress_per = (in_progress_per*100)/total_task;
                    }

                    if(pending_review_per > 0){
                        pending_review_per = (pending_review_per*100)/total_task;
                    }

                    if(complete_per > 0){
                        complete_per = (complete_per*100)/total_task;
                    }

                    uncompleted_task = (((open_per/100)*total_task) + ((in_progress_per/100) *total_task) + ((pending_review_per/100)*total_task));

                    jQuery_WPF('.wpf_progress_bar #open_progress').css("width", parseFloat(open_per).toFixed(2)+'%');
                    jQuery_WPF('.wpf_progress_bar #inprogress_progress').css('width',parseFloat(in_progress_per).toFixed(2)+'%');
                    jQuery_WPF('.wpf_progress_bar #pending_progress').css('width',parseFloat(pending_review_per).toFixed(2)+'%');
                    jQuery_WPF('.wpf_progress_bar #completed_progress').css('width',parseFloat(complete_per).toFixed(2)+'%');
                    jQuery_WPF('#wpf_total_task_number').text(uncompleted_task).removeClass('wpf_hide');
                }

                jQuery_WPF('.wpfbpriority').click(function(e){
                    var elemid = jQuery_WPF(this).attr('data-elemid');
                    set_task_prioirty(elemid);
                    e.stopImmediatePropagation();
                });
                jQuery_WPF('.wpfbtaskstatus').click(function(e){
                    var elemid = jQuery_WPF(this).attr('data-elemid');
                    set_task_status(elemid);
                    e.stopImmediatePropagation();
                });
                jQuery_WPF('.wpfbtasknotifyusers').click(function(){
                    var elemid = jQuery_WPF(this).attr('data-elemid');
                    set_task_notify_users(elemid);
                });

                jQuery_WPF(document).on("click", ".close" , function(e){
                    jQuery_WPF(this).parents(".popover").popover('hide');
                });


                jQuery_WPF('.wpf_task_delete_btn').click(function(){
                    var btn_taskid = jQuery_WPF(this).data('btn_taskid');
                    jQuery_WPF('.wpfbsysinfo_delete_task_id_'+btn_taskid).show();
                });
                jQuery_WPF('.wpf_task_delete').click(function(){
                    var elemid = jQuery_WPF(this).data('elemid');
                    var task_id = jQuery_WPF(this).data('taskid');
                    wpf_delete_task(elemid,task_id);
                });

                jQuery_WPF(document).find("#wpf_thispage").on("click","li.current_page_task",function(e) {
                    var wpf_tmp_show_task_checkbox_obj = jQuery_WPF("#wpfb_display_tasks");
                    if(wpf_tmp_show_task_checkbox_obj.prop('checked')==false){
                        wpf_tmp_show_task_checkbox_obj.prop('checked',true);
                    }
                    wpf_display_tasks(wpf_tmp_show_task_checkbox_obj);
                    var taskid = jQuery_WPF(this).data('taskid');
                    
                    if(jQuery_WPF("#bubble-"+taskid).hasClass('complete')){
                        jQuery_WPF("#bubble-"+taskid).toggle();
                    }

                    jQuery_WPF('[rel="popover-'+taskid+'"]').trigger('click');
                    jQuery_WPF('html, body').animate({
                        scrollTop: jQuery_WPF("#bubble-"+taskid).offset().top - 200
                    }, 1000);
                });

                // for general task => 2.1.0
                jQuery_WPF(document).find("#wpf_thispage").on("click","li.current_page_general_task",function(e) {
                    var taskid = jQuery_WPF(this).data('postid'); // fixed to get the id => 2.1.0
                    jQuery_WPF('#wpf_thispage li.current_page_task .wpf_task_sum').parent().removeClass('wpf_active');
                    jQuery_WPF('#wpf_thispage li.current_page_general_task .wpf_task_sum').parent().removeClass('wpf_active');
                    jQuery_WPF(this).parent().addClass('wpf_active');
                    wpf_load_general_task(taskid);
                });

                jQuery_WPF(document).find("#wpf_allpages_container_today,#wpf_allpages_container_yesterday,#wpf_allpages_container_this_week,#wpf_allpages_container_this_month,#wpf_allpages_container_year,#wpf_allpages_container_other").on("click","li.current_page_task",function(e) {
                    var task_url = jQuery_WPF(this).attr('data-task_url');
                    window.location.assign(task_url);
                });

                jQuery_WPF(document).find("#wpf_backend_container_today,#wpf_backend_container_yesterday,#wpf_backend_container_this_week,#wpf_backend_container_this_month,#wpf_backend_container_year,#wpf_backend_container_other").on("click","li.current_page_task",function(e) {
                    var task_url = jQuery_WPF(this).attr('data-task_url');
                    window.open(task_url);
                });

                jQuery_WPF(document).find("#wpf_allpages_container_today,#wpf_allpages_container_yesterday,#wpf_allpages_container_this_week,#wpf_allpages_container_this_month,#wpf_allpages_container_year,#wpf_allpages_container_other").on("click","li.current_page_general_task",function(e) {
                    var taskid = jQuery_WPF(this).data('postid');
                    wpf_load_general_task(taskid);
                });

                jQuery_WPF(document).find("#wpf_backend_container_today,#wpf_backend_container_yesterday,#wpf_backend_container_this_week,#wpf_backend_container_this_month,#wpf_backend_container_year,#wpf_backend_container_other").on("click","li.current_page_general_task",function(e) {
                    var taskid = jQuery_WPF(this).data('postid');
                    wpf_load_general_task(taskid);
                });
                trigger_bubble_label();
            }
        });
}

function wpf_get_page_id_by_url() {
    let url_string = window.location.href;
    let url = new URL(url_string);
    let graphic_id = url.searchParams.get("id");
    
    current_page_id = graphic_id;
    current_page_url = url_string;
    current_page_title = jQuery_WPF('.wpf_topbar .wpf_graphics_title').text();
    load_wpfb_tasks();
}


function timeSort(a, b) {
    return b-a
}

function generate_wpfb_task_html(wpfb_task_id,wpfb_metas){
    var notify_users = '';
    if(wpfb_metas.task_notify_users !== '' && wpfb_metas.task_notify_users  !== null && wpfb_metas.task_notify_users !== undefined){
        notify_users =  wpfb_metas.task_notify_users.split(',');
    }
    var wpfb_users_arr = JSON.parse(wpfb_users);
    var comment_count = wpfb_metas.task_comment_id;
    var wpf_graphics_tag_name = '';
    let sticker_span = '';
    let custom_status_class = '';
    let sidebar_class = "";
    if(wpfb_metas.class !== ""){
        sidebar_class = "_"+wpfb_metas.class; 
    }
    
    if(wpf_tab_permission.display_stickers){
        custom_status_class = wpfb_metas.task_status+'_custom';
    }

    
    if(wpf_tab_permission.display_stickers){
        sticker_span = '<span class="sticker '+wpfb_metas.task_priority+'_custom"></span> ';
    }

    let display_check_mark = '';
    if(wpf_tab_permission.display_task_id != 'yes'){
        display_check_mark = '<i class="gg-check"></i>';
    }else{
        display_check_mark = wpfb_metas.site_task_id;//comment_count;
    }

    if(wpfb_metas.task_status=='complete'){
        var bubble_label = sticker_span+display_check_mark;
    }
    else{
        var bubble_label = sticker_span+wpfb_metas.site_task_id;//comment_count;
    }

    var internal_icon='';
    var internal='';
    if(wpfb_metas.is_internal=='1'){
        var internal_icon='';//internal_icon_html;
        var internal='<span class="wpf_task_type" title="Task type">Internal</span>';
    }

    var wpf_task_status_label= '<div class="wpf_task_label"><span class="task_status wpf_'+wpfb_metas.task_status+'" title="Status: '+wpfb_metas.task_status+'">'+status_icon+'</span>';
    var wpf_task_priority_label= '<span class="priority wpf_'+wpfb_metas.task_priority+'" title="Priority: '+wpfb_metas.task_priority+'">'+priority_icon+'</span></div>';
    var wpfb_tags_html = '';

    for (var usr in wpfb_users_arr) {
        if (wpfb_users_arr.hasOwnProperty(usr)) {
            if(wpfb_users_arr[usr]['username']==wpfb_metas.task_config_author_name){
                wpfb_metas.task_config_author_name = wpfb_users_arr[usr]['displayname'];
                break;
            }
        }
    }

    if(wpfb_metas.task_type=='general'){
        const general_tag_html = '<span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span>';
        var wpfb_current_page_task_list_html = '<li class="current_page_general_task '+wpfb_metas.task_status+ " " +custom_status_class+" "+ wpfb_metas.task_priority+'" data-taskid="'+comment_count+'" data-postid="'+wpfb_task_id+'"><div class="wpf_task_number" data-disp-id="'+wpfb_metas.site_task_id+'">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+wpfb_metas.task_config_author_name+'<span>'+wpfb_metas.task_time+'</span></level><div class="current_page_task_list">'+wpfb_metas.task_title+'</div></div>'+internal+''+general_tag_html+'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details"><span class="wpf_task_type" title="Task type">'+wpf_general_tag+'</span>'+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>';
        jQuery_WPF("#wpf_thispage_container"+sidebar_class).append(wpfb_current_page_task_list_html);
    }
    else{
        var wpfb_users_html = '';
        if( wpfb_metas.is_internal == 0 ) {
            wpfb_users_html = '<ul class="wp_feedback_filter_checkbox user">';
        } else {
            wpfb_users_html = '<span class="wpf_hide_users_for_internal_tasks" id="wpf_success_wpf_share_page_link">Notifications for internal tasks are handled through your Agency Dashboard</span>';
            wpfb_users_html += '<ul class="wp_feedback_filter_checkbox user wpf_hide">';
        }

        for (var key in wpfb_users_arr) {
            if (wpfb_users_arr.hasOwnProperty(key)) {
                var user_name = wpfb_users_arr[key]['displayname'];
                if(notify_users.includes(key)){
                    wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'" checked><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                }
                else{
                    wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'"><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                }
            }
        }
        wpfb_users_html+='</ul>';

        var temp_task_priority_low=temp_task_priority_medium=temp_task_priority_high=temp_task_priority_critical='';
        if(wpfb_metas.task_priority=='low'){temp_task_priority_low='checked';}
        else if(wpfb_metas.task_priority=='medium'){temp_task_priority_medium='checked';}
        else if(wpfb_metas.task_priority=='high'){temp_task_priority_high='checked';}
        else{temp_task_priority_critical='checked';}

        if(wpf_tab_permission.display_stickers == 'yes'){
            var wpfb_task_priority_html='<input id="priority_low-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="low" class="wpfbpriority low_radio" '+temp_task_priority_low+'><label for="priority_low-'+comment_count+'" class="low_label">'+wpf_priority_low+'</label><input id="priority_medium-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="medium" class="wpfbpriority medium_radio" '+temp_task_priority_medium+'><label for="priority_medium-'+comment_count+'" class="medium_label">'+wpf_priority_medium+'</label><input id="priority_high-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="high" class="wpfbpriority high_radio" '+temp_task_priority_high+'><label for="priority_high-'+comment_count+'" class="high_label">'+wpf_priority_high+'</label><input id="priority_critical-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="critical" class="wpfbpriority critical_radio" '+temp_task_priority_critical+'><label for="priority_critical-'+comment_count+'" class="critical_label">'+wpf_priority_critical+'</label>';
        }else{
            var wpfb_task_priority_html='<input id="priority_low-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="low" class="wpfbpriority " '+temp_task_priority_low+'><label for="priority_low-'+comment_count+'" class="">'+wpf_priority_low+'</label><input id="priority_medium-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="medium" class="wpfbpriority " '+temp_task_priority_medium+'><label for="priority_medium-'+comment_count+'" class="">'+wpf_priority_medium+'</label><input id="priority_high-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="high" class="wpfbpriority " '+temp_task_priority_high+'><label for="priority_high-'+comment_count+'" class="">'+wpf_priority_high+'</label><input id="priority_critical-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="critical" class="wpfbpriority " '+temp_task_priority_critical+'><label for="priority_critical-'+comment_count+'" class="">'+wpf_priority_critical+'</label>';
        }

        var temp_task_status_open=temp_task_status_inprogress=temp_task_status_pending_review=temp_task_status_complete='';
        if(wpfb_metas.task_status=='open'){temp_task_status_open='checked';}
        else if(wpfb_metas.task_status=='in-progress'){temp_task_status_inprogress='checked';}
        else if(wpfb_metas.task_status=='pending-review'){temp_task_status_pending_review='checked';}
        else{temp_task_status_complete='checked';}

        if(wpf_tab_permission.display_stickers == 'yes'){
            var wpfb_task_status_html='<input id="status_open-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="open" data-elemid="'+comment_count+'" class="wpfbtaskstatus open_radio" '+temp_task_status_open+'><label for="status_open-'+comment_count+'" class="open_label">'+wpf_status_open_task+'</label><input id="status_progress-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="in-progress" data-elemid="'+comment_count+'" class="wpfbtaskstatus in_progress_radio " '+temp_task_status_inprogress+' ><label for="status_progress-'+comment_count+'" class="in_progress_label">'+wpf_status_in_progress+'</label><input id="status_pending-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="pending-review" data-elemid="'+comment_count+'" class="wpfbtaskstatus  pending_radio" '+temp_task_status_pending_review+' ><label for="status_pending-'+comment_count+'" class="pending_label">'+wpf_status_pending_review+'</label><input id="status_complete-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="complete" data-elemid="'+comment_count+'" class="wpfbtaskstatus complete_radio" '+temp_task_status_complete+'><label for="status_complete-'+comment_count+'" class="complete_label">'+wpf_status_complete+'</label>';
        }else{
            var wpfb_task_status_html='<input id="status_open-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="open" data-elemid="'+comment_count+'" class="wpfbtaskstatus " '+temp_task_status_open+'><label for="status_open-'+comment_count+'" class="">'+wpf_status_open_task+'</label><input id="status_progress-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="in-progress" data-elemid="'+comment_count+'" class="wpfbtaskstatus  " '+temp_task_status_inprogress+' ><label for="status_progress-'+comment_count+'" class="">'+wpf_status_in_progress+'</label><input id="status_pending-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="pending-review" data-elemid="'+comment_count+'" class="wpfbtaskstatus  " '+temp_task_status_pending_review+' ><label for="status_pending-'+comment_count+'" class="">'+wpf_status_pending_review+'</label><input id="status_complete-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="complete" data-elemid="'+comment_count+'" class="wpfbtaskstatus " '+temp_task_status_complete+'><label for="status_complete-'+comment_count+'" class="">'+wpf_status_complete+'</label>';
        }

        var wpfb_messages_html='';
        for (var key in wpfb_metas.comments) {

            let new_class = "";
            let author = "";
            
            for (var usr in wpfb_users_arr) {
                if (wpfb_users_arr.hasOwnProperty(usr)) {
                    if(wpfb_users_arr[usr]['username']==wpfb_metas.comments[key].author){
                        wpfb_metas.comments[key].author = wpfb_users_arr[usr]['displayname'];
                        break;
                    }
                }
            }

            author = wpfb_metas.comments[key].author;
            if(wpfb_metas.comments[key].is_log == 1){
                    new_class = " is_info";
                    author = "";
            }

            if(wpfb_metas.comments[key].wpf_user_id==wpfb_metas.current_user_id){
                var task_author = "wpf_author"+new_class;
            }
            else{
                var task_author = "wpf_other"+new_class;
            }

            var edited_html='';
            var edit_delete_button_html = '';            

            if(wpfb_metas.comments[key].is_edited && !wpfb_metas.comments[key].is_deleted){
                var edited_html='<span class="wpf-is-edited">(edited)<span class="wpf_tooltiptext edit_tooltip_text">'+wpfb_metas.comments[key].updated_at+'</span></span>';
            }else{
                var edited_html='';
            }

            if ( ( eval(current_user_id) === eval(wpfb_metas.comments[key]['wpf_user_id']) ) && (!wpfb_metas.comments[key]['is_log']) && (!wpfb_metas.comments[key].is_deleted) ) {
                if (jQuery_WPF.inArray('edit', blocked) >= 0) {
                    edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="edit_delete_plan()" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="edit_delete_plan()" ><i class="gg-trash"></i></a></div>`;
                } else {
                    edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="wpf_edit_box_active(${key})" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="wpf_delete_comment(${key})" ><i class="gg-trash"></i></a></div>`;
                }
            }

            if(wpfb_metas.comments[key].comment_type==0){
                let img_dwn_icon = "";
                wpfb_messages_html += '<li class="'+task_author+'" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span>'+ edit_delete_button_html +'</level><div class="meassage_area_main"><div id="wpf-chat-text-'+key+'" class="chat_text">'+ wpfb_metas.comments[key].message +'</div>'+edited_html+'</div><div id="wpfb-edit-comment-wrapper-'+key+'" class="wpfb-edit-comment-wrapper"><div class="wpf-editor"></div><textarea class="form-control wpfb-edit-comment" data-comment_id="'+key+'" placeholder="Edit the comment..." spellcheck="false">'+ wpfb_metas.comments[key].message +'</textarea><button class="wpf_edit_comment_btn" onclick="wpfb_edit_comment('+key+')">'+edit_comment_text+'</button><a class="wpf-cancel-edit-comment" onclick="wpfb_cancel_edit_comment('+key+')" href="javascript:void(0)">'+cancel_edit_comment_text+'</a></div></li>';
            }
            else if (wpfb_metas.comments[key].comment_type==1) {
                img_dwn_icon  = "<span id='wpf_push_media' class='wpf_push_media wpf_image_download'>"+push_to_media_icon+"</span><span id='wpf_image_download' class='wpf_image_download' onclick='wpf_image_download_action(this)'>"+image_download_icon+"</span><span id='' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>"+image_open_icon+"</span><span class='wpf_image_delete' onclick='wpf_task_image_delete(this)'>"+image_close_icon+"</span>";
                wpfb_messages_html += '<li class="'+task_author+' is_image" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><a href="'+wpfb_metas.comments[key].message+'" target="_blank">'+'</a>'+img_dwn_icon+'<img src="'+wpfb_metas.comments[key].message+'" class="wpf_task_uploaded_image" /></div></li>';
            }
            else if (wpfb_metas.comments[key].comment_type==3) {
                let img_dwn_icon = "";
                wpfb_messages_html += '<li class="'+task_author+'" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><iframe width="100%" height="120" src="https://www.youtube.com/embed/'+wpfb_metas.comments[key].message+'"></iframe></div></li>';
            }
            else{
                let img_dwn_icon = "";
                var wpf_download_file  = wpfb_metas.comments[key].message.split("/").pop();
                wpfb_messages_html += '<li class="'+task_author+' is_file" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><a href="'+wpfb_metas.comments[key].message+'" download="'+wpf_download_file+'" target="_blank"><i class="gg-software-download"></i> '+ wpf_download_file+'</a></div></li>';
            }
            jQuery_WPF('#task_comments_'+comment_count).append(wpfb_messages_html);

        }
         if(wpfb_metas.task_type=='graphics'){
            var wpf_graphics_tag_name = '<span class="wpf_task_type" title="Task type">'+wpf_graphics_tag+'</span>';
        }
        var wpfb_current_page_task_list_html = '<li class="current_page_task '+wpfb_metas.task_status+  " " +custom_status_class+" "+wpfb_metas.task_priority+'" data-taskid="'+comment_count+'"><div class="wpf_task_number" data-disp-id="'+wpfb_metas.site_task_id+'">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+wpfb_metas.task_config_author_name+'<span>'+wpfb_metas.task_time+'</span></level><div class="current_page_task_list">'+wpfb_metas.task_title+'</div></div>'+internal+'<div class="wpf_task_meta"><div class="wpf_task_meta_icon"><i class="gg-chevron-left"></i></div><div class="wpf_task_meta_details">'+wpf_graphics_tag_name+wpf_task_status_label+wpf_task_priority_label+wpfb_tags_html+'</div></div></li>';
        jQuery_WPF("#wpf_thispage_container"+sidebar_class).append(wpfb_current_page_task_list_html);

        response_html_new = wpf_task_popover_html(0,comment_count,wpfb_task_id,wpfb_metas,wpfb_users_html,wpfb_task_priority_html,wpfb_task_status_html,wpfb_messages_html);
        jQuery_WPF('#wpf_launcher').after(response_html_new);

        wpf_bubble_tracker(comment_count,wpfb_metas.task_clean_dom_elem_path);
        init_custom_popover_first(comment_count);
    }
}
function wpfb_edit_comment(comment_id) {
    var task_info = [];

    task_info['comment_id'] = comment_id;
    task_info['comment_content']=jQuery_WPF("#wpfb-edit-comment-wrapper-"+comment_id+" textarea").val();

    var task_info_obj = jQuery_WPF.extend({}, task_info);

    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_edit_comment",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
        },
        success : function(data){
            jQuery_WPF("#wpf-chat-text-"+comment_id).text(task_info['comment_content']);
            jQuery_WPF("#wpfb-edit-comment-wrapper-"+comment_id).hide();
        }
    });
}

function wpf_edit_box_active(comment_id){
    jQuery_WPF("#wpfb-edit-comment-wrapper-"+comment_id).show();
}
function wpfb_cancel_edit_comment(comment_id){
    jQuery_WPF("#wpfb-edit-comment-wrapper-"+comment_id).hide();
}
function wpf_delete_comment(comment_id){
        var task_info = [];
    
        task_info['comment_id'] = comment_id;
        task_info['from_wp'] = true;
    
        var task_info_obj = jQuery_WPF.extend({}, task_info);
    
        jQuery_WPF.ajax({
            method : "POST",
            url : ajaxurl,
            data : {action: "wpf_delete_comment",wpf_nonce:wpf_nonce,task_info:task_info_obj,current_user_id:current_user_id},
            beforeSend: function(){
            },
            success : function(data){
                data = JSON.parse( data );
                jQuery_WPF("#wpf-chat-text-"+comment_id).html(data['comment']);
                jQuery_WPF("#wpfb-edit-comment-wrapper-"+comment_id).hide();

                // remove the edit delete UI
                jQuery_WPF("#wpf-chat-text-"+comment_id).closest('li.wpf_author').find('div.wpf-edit-delete-wrapper').remove();
            }
        });
}
function wpf_generate_general_task_html(wpfb_task_id,wpfb_metas){
    var notify_users = '';
    if(wpfb_metas.task_notify_users !== '' && wpfb_metas.task_notify_users  !== null && wpfb_metas.task_notify_users !== undefined){
        notify_users =  wpfb_metas.task_notify_users.split(',');
    }
    
    var wpfb_users_arr = JSON.parse(wpfb_users);
    var comment_count = wpfb_metas.task_comment_id;

    var wpfb_users_html = '<ul class="wp_feedback_filter_checkbox user">';
    for (var key in wpfb_users_arr) {
        if (wpfb_users_arr.hasOwnProperty(key)) {
            var user_name = wpfb_users_arr[key]['displayname'];
            if(notify_users.includes(key)){
                wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'" checked><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
            }
            else{
                wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'"><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
            }
        }
    }
    wpfb_users_html+='</ul>';

    var temp_task_priority_low=temp_task_priority_medium=temp_task_priority_high=temp_task_priority_critical='';
    if(wpfb_metas.task_priority=='low'){temp_task_priority_low='checked';}
    else if(wpfb_metas.task_priority=='medium'){temp_task_priority_medium='checked';}
    else if(wpfb_metas.task_priority=='high'){temp_task_priority_high='checked';}
    else{temp_task_priority_critical='checked';}

    if(wpf_tab_permission.display_stickers == 'yes'){
        var wpfb_task_priority_html='<input id="priority_low-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="low" class="wpfbpriority low_radio" '+temp_task_priority_low+'><label for="priority_low-'+comment_count+'" class="low_label">'+wpf_priority_low+'</label><input id="priority_medium-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="medium" class="wpfbpriority medium_radio" '+temp_task_priority_medium+'><label for="priority_medium-'+comment_count+'" class="medium_label">'+wpf_priority_medium+'</label><input id="priority_high-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="high" class="wpfbpriority high_radio" '+temp_task_priority_high+'><label for="priority_high-'+comment_count+'" class="high_label">'+wpf_priority_high+'</label><input id="priority_critical-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="critical" class="wpfbpriority critical_radio" '+temp_task_priority_critical+'><label for="priority_critical-'+comment_count+'" class="critical_label">'+wpf_priority_critical+'</label>';
    }else{
        var wpfb_task_priority_html='<input id="priority_low-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="low" class="wpfbpriority" '+temp_task_priority_low+'><label for="priority_low-'+comment_count+'" class="">'+wpf_priority_low+'</label><input id="priority_medium-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="medium" class="wpfbpriority" '+temp_task_priority_medium+'><label for="priority_medium-'+comment_count+'" class="">'+wpf_priority_medium+'</label><input id="priority_high-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="high" class="wpfbpriority " '+temp_task_priority_high+'><label for="priority_high-'+comment_count+'" class="">'+wpf_priority_high+'</label><input id="priority_critical-'+comment_count+'" type="radio" name="wpfbpriority'+comment_count+'" data-elemid="'+comment_count+'" value="critical" class="wpfbpriority " '+temp_task_priority_critical+'><label for="priority_critical-'+comment_count+'" class="">'+wpf_priority_critical+'</label>';
    }
    var temp_task_status_open=temp_task_status_inprogress=temp_task_status_pending_review=temp_task_status_complete='';
    if(wpfb_metas.task_status=='open'){temp_task_status_open='checked';}
    else if(wpfb_metas.task_status=='in-progress'){temp_task_status_inprogress='checked';}
    else if(wpfb_metas.task_status=='pending-review'){temp_task_status_pending_review='checked';}
    else{temp_task_status_complete='checked';}

    if(wpf_tab_permission.display_stickers == 'yes'){
        var wpfb_task_status_html='<input id="status_open-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="open" data-elemid="'+comment_count+'" class="wpfbtaskstatus open_radio" '+temp_task_status_open+'><label for="status_open-'+comment_count+'" class="open_label">'+wpf_status_open_task+'</label><input id="status_progress-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="in-progress" data-elemid="'+comment_count+'" class="wpfbtaskstatus '+wpfb_metas.task_status+'_radio " '+temp_task_status_inprogress+' ><label for="status_progress-'+comment_count+'" class="'+wpfb_metas.task_status+'_label">'+wpf_status_in_progress+'</label><input id="status_pending-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="pending-review" data-elemid="'+comment_count+'" class="wpfbtaskstatus '+wpfb_metas.task_status+'_radio " '+temp_task_status_pending_review+' ><label for="status_pending-'+comment_count+'" class="'+wpfb_metas.task_status+'_label">'+wpf_status_pending_review+'</label><input id="status_complete-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="complete" data-elemid="'+comment_count+'" class="wpfbtaskstatus '+wpfb_metas.task_status+'_radio " '+temp_task_status_complete+'><label for="status_complete-'+comment_count+'" class="'+wpfb_metas.task_status+'_label">'+wpf_status_complete+'</label>';
    }else{
        var wpfb_task_status_html='<input id="status_open-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="open" data-elemid="'+comment_count+'" class="wpfbtaskstatus " '+temp_task_status_open+'><label for="status_open-'+comment_count+'" >'+wpf_status_open_task+'</label><input id="status_progress-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="in-progress" data-elemid="'+comment_count+'" class="wpfbtaskstatus " '+temp_task_status_inprogress+' ><label for="status_progress-'+comment_count+'" >'+wpf_status_in_progress+'</label><input id="status_pending-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="pending-review" data-elemid="'+comment_count+'" class="wpfbtaskstatus  " '+temp_task_status_pending_review+' ><label for="status_pending-'+comment_count+'" >'+wpf_status_pending_review+'</label><input id="status_complete-'+comment_count+'" type="radio" name="wpfbtaskstatus'+comment_count+'" value="complete" data-elemid="'+comment_count+'" class="wpfbtaskstatus " '+temp_task_status_complete+'><label for="status_complete-'+comment_count+'" >'+wpf_status_complete+'</label>';
    }

    var wpfb_messages_html='';
    for (var key in wpfb_metas.comments) {
        let new_class = "";
        
        for (var usr in wpfb_users_arr) {
            if (wpfb_users_arr.hasOwnProperty(usr)) {
                if(wpfb_users_arr[usr]['username']==wpfb_metas.comments[key].author){
                    wpfb_metas.comments[key].author = wpfb_users_arr[usr]['displayname'];
                    break;
                }
            }
        }

        let author = wpfb_metas.comments[key].author;
            if(wpfb_metas.comments[key].is_log == 1){
                    new_class = " is_info";
                    author = "";
            }

        if(wpfb_metas.comments[key].wpf_user_id==wpfb_metas.current_user_id){
            var task_author = "wpf_author"+new_class;
        }
        else{
            var task_author = "wpf_other"+new_class;
        }

        var edited_html='';
            var edit_delete_button_html = '';            

            if(wpfb_metas.comments[key].is_edited && !wpfb_metas.comments[key].is_deleted){
                var edited_html='<span class="wpf-is-edited">(edited)<span class="wpf_tooltiptext edit_tooltip_text">'+wpfb_metas.comments[key].updated_at+'</span></span>';
            }else{
                var edited_html='';
            }

            if ( ( eval(current_user_id) === eval(wpfb_metas.comments[key]['wpf_user_id']) ) && (!wpfb_metas.comments[key]['is_log']) && (!wpfb_metas.comments[key].is_deleted) ) {
                if (jQuery_WPF.inArray('edit', blocked) >= 0) {
                    edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="edit_delete_plan()" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="edit_delete_plan()" ><i class="gg-trash"></i></a></div>`;
                } else {
                    edit_delete_button_html = `<div class="wpf-edit-delete-wrapper"><a href="javascript:void(0)" onclick="wpf_edit_box_active(${key})" class="wpf_edit_box_active" id="wpf_edit_box_active"><i class="gg-pen"></i></a><a href="javascript:void(0)" class="wpf_comment_delete_btn"  onclick="wpf_delete_comment(${key})" ><i class="gg-trash"></i></a></div>`;
                }
            }

        if(wpfb_metas.comments[key].comment_type==0){
            let img_dwn_icon = "";
            wpfb_messages_html += '<li class="'+task_author+'" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span>'+edit_delete_button_html+'</level><div class="meassage_area_main"><div id="wpf-chat-text-'+key+'" class="chat_text">'+ wpfb_metas.comments[key].message +'</div>'+edited_html+'</div><div id="wpfb-edit-comment-wrapper-'+key+'" class="wpfb-edit-comment-wrapper"><div class="wpf-editor"></div><textarea class="form-control wpfb-edit-comment" data-comment_id="'+key+'" placeholder="Edit the comment..." spellcheck="false">'+ wpfb_metas.comments[key].message +'</textarea><button class="wpf_edit_comment_btn" onclick="wpfb_edit_comment('+key+')">'+edit_comment_text+'</button><a class="wpf-cancel-edit-comment" onclick="wpfb_cancel_edit_comment('+key+')" href="javascript:void(0)">'+cancel_edit_comment_text+'</a></div></li>';
        }
        else if (wpfb_metas.comments[key].comment_type==1) {
            img_dwn_icon  = "<span id='wpf_push_media' class='wpf_push_media wpf_image_download'>"+push_to_media_icon+"</span><span id='wpf_image_download' class='wpf_image_download' onclick='wpf_image_download_action(this)'>"+image_download_icon+"</span><span id='' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>"+image_open_icon+"</span><span class='wpf_image_delete' onclick='wpf_task_image_delete(this)'>"+image_close_icon+"</span>";
            wpfb_messages_html += '<li class="'+task_author+' is_image" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><a href="'+wpfb_metas.comments[key].message+'" target="_blank">'+'</a>'+img_dwn_icon+'<img src="'+wpfb_metas.comments[key].message+'" class="wpf_task_uploaded_image" /></div></li>';
        }
        else if (wpfb_metas.comments[key].comment_type==3) {
            let img_dwn_icon = "";
            wpfb_messages_html += '<li class="'+task_author+'" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><iframe width="100%" height="120" src="https://www.youtube.com/embed/'+wpfb_metas.comments[key].message+'"></iframe></div></li>';
        }
        else{
            let img_dwn_icon = "";
            var wpf_download_file  = wpfb_metas.comments[key].message.split("/").pop();
            wpfb_messages_html += '<li class="'+task_author+' is_file" data-comment_id="'+key+'"><level class="task-author">'+author+'<span>'+wpfb_metas.comments[key].time+'</span></level><div class="meassage_area_main"><a href="'+wpfb_metas.comments[key].message+'" download="'+wpf_download_file+'" target="_blank"><i class="gg-software-download"></i> '+ wpf_download_file+'</a></div></li>';
        }
        jQuery_WPF('#task_comments_'+comment_count).append(wpfb_messages_html);

    }

    var wpf_tmp_general_response_html = wpf_task_popover_html(3,comment_count,wpfb_task_id,wpfb_metas,wpfb_users_html,wpfb_task_priority_html,wpfb_task_status_html,wpfb_messages_html);
    jQuery_WPF('#wpf_general_comment_tabs').html(wpf_tmp_general_response_html);
}

function wpf_task_popover_html(wpf_task_type,comment_count,wpfb_task_id,wpfb_metas,wpfb_users_html,wpfb_task_priority_html,wpfb_task_status_html,wpfb_messages_html,new_task_popover) {
    if(wpf_task_type==2 || wpf_task_type==3){
        curr_browser_temp = get_browser();
        browser = curr_browser_temp['name']+' '+curr_browser_temp['version'];
        var wpf_config_resolution = resolution;
        var wpf_config_browser = browser;
        var wpf_config_author_name = current_user_name;
        if(wpfb_task_id != 0){
            var wpf_config_task_id = wpfb_task_id;
        }else{
            var wpf_config_task_id = '';
        }

    }
    else if(wpf_task_type==1){
        var wpf_config_resolution = resolution;
        var wpf_config_browser = browser;
        var wpf_config_author_name = current_user_name;
        var wpf_config_task_id = '';
        var element_center = [];
        var wpf_graphics_img_offset = jQuery_WPF('#wpf_graphics_img').offset();
        element_center['left']=wpf_graphics_img_offset.left + temp_tasks[comment_count]['left'];
        element_center['top']=wpf_graphics_img_offset.top + temp_tasks[comment_count]['top'];
    }
    else{
        var wpf_config_resolution = wpfb_metas.task_config_author_resX+' x '+wpfb_metas.task_config_author_resY;
        var wpf_config_browser = wpfb_metas.task_config_author_browser;
        var wpf_config_author_name = wpfb_metas.task_config_author_name;
        var wpf_config_task_id = wpfb_task_id;
        var element_center = getelementcenter(wpfb_metas.wpfb_task_bubble);
    }

    if(wpf_task_type==2 || wpf_task_type==3){
        var button_html ='';
    }
    else{
        if(wpfb_metas.task_status!='complete'){
            jQuery_WPF(wpfb_metas.task_element_path).addClass('wpfb_task_bubble');
            jQuery_WPF(wpfb_metas.task_element_path).attr('data-task_id', wpfb_metas.task_comment_id);
        }

        if(element_center['left']==0 && element_center['top']==0){
            var x_per = 100*(wpfb_metas.task_elementX/wpfb_metas.task_config_author_resX);
            var y_per = 100*(wpfb_metas.task_elementY/wpfb_metas.task_config_author_resY);
            element_center['left']=(x_per*window.screen.width)/100;
            element_center['top']=(y_per*window.screen.height)/100;
        }
        else{
            element_center['left']=element_center['left']-25;
            element_center['top']=element_center['top']-25;
        }


        let display_check_mark = '';
        if(wpf_tab_permission.display_task_id != 'yes'){
            display_check_mark = '<i class="gg-check"></i>';
        }else{
            display_check_mark = wpfb_metas.site_task_id;//comment_count;
        }

        if(wpfb_metas.task_status=='complete'){
            var bubble_label = display_check_mark;
        }
        else{
            var bubble_label = '<span class="wpf_bubble_num_wrapper">'+wpfb_metas.site_task_id+'</span>';//comment_count;
        }

            if(wpf_tab_permission.display_stickers == 'yes'){

                if(wpfb_metas.task_priority == undefined || wpfb_metas.task_priority == ''){
                    var display_sticker = '<span class="sticker low low_custom" ></span>';
                }else{
                    var display_sticker = '<span class="sticker '+wpfb_metas.task_priority+' '+wpfb_metas.task_priority+'_custom" ></span>';
                }

                if(wpfb_metas.task_status == undefined || wpfb_metas.task_status == ''){
                    var custom_class = 'open open_custom';
                }else{
                    var custom_class = wpfb_metas.task_status+'_custom';
                }
                
            }else{
                var display_sticker = '';
                var custom_class = '';
            }
    

        if(wpf_task_type==1){
            var button_html = '<a id="bubble-'+comment_count+'" data-html2canvas-ignore="true" href="javascript:void(0)" rel="popover-'+comment_count+'" data-html="true" data-trigger="focus" data-popover-content="#popover-content-c'+comment_count+'" class="wpfb-point wpfb_task_bubble '+custom_class+'" style="top:'+element_center['top']+'px;left:'+element_center['left']+'px;" data-toggle="popover" data-disp-id="'+wpfb_metas.site_task_id+'">'+display_sticker+'<span class="wpf_bubble_num_wrapper">'+bubble_comment_count+'</span></a>';
        }
        else{
            var wpf_graphics_img_position = jQuery_WPF('#wpf_graphics_img').position();
            var wpf_graphics_img_offset = jQuery_WPF('#wpf_graphics_img').offset();
            var old_coordinates = [];
            old_coordinates['left'] = parseInt(wpfb_metas.task_left);
            old_coordinates['top'] = parseInt(wpfb_metas.task_top);

            var old_size = [];
            old_size['width'] = parseInt(wpfb_metas.task_element_width);
            old_size['height'] = parseInt(wpfb_metas.task_element_height);

            var new_size = [];
            new_size['width'] = jQuery_WPF('#wpf_graphics_img').width();
            new_size['height'] = jQuery_WPF('#wpf_graphics_img').height();

            var new_coordinates = wpf_relative_position(old_coordinates,old_size,new_size);

            var temp_elem_top = wpf_graphics_img_offset.top + new_coordinates['top']-25;
            var temp_elem_left = wpf_graphics_img_offset.left + new_coordinates['left']-25;
            if(wpf_show_front_stikers=='yes'){
                var wpf_hide_class = '';
            }
            else{
                var wpf_hide_class = 'wpf_hide';
            }

            if(wpfb_metas.is_internal=='1'){
            var button_html = '<a id="bubble-'+comment_count+'" data-html2canvas-ignore="true" href="javascript:void(0)" rel="popover-'+comment_count+'" data-html="true" data-trigger="focus" data-popover-content="#popover-content-c'+comment_count+'" class=" wpfb-internal wpfb-point wpfb_task_bubble '+wpfb_metas.task_status+' '+custom_class+' '+wpf_hide_class+'" style="top:'+temp_elem_top+'px;left:'+temp_elem_left+'px;" data-toggle="popover" data-disp-id="'+wpfb_metas.site_task_id+'">'+display_sticker +bubble_label + '<span class="wpf_chevron_wrapper"><img src="' + plugin_url + 'images/eye-off-white.svg" class="wpf-internal-img"></span></a>';//+bubble_label+'</a>';
            }else{
                var button_html = '<a id="bubble-'+comment_count+'" data-html2canvas-ignore="true" href="javascript:void(0)" rel="popover-'+comment_count+'" data-html="true" data-trigger="focus" data-popover-content="#popover-content-c'+comment_count+'" class="wpfb-point wpfb_task_bubble '+wpfb_metas.task_status+' '+custom_class+' '+wpf_hide_class+'" style="top:'+temp_elem_top+'px;left:'+temp_elem_left+'px;" data-toggle="popover" data-disp-id="'+wpfb_metas.site_task_id+'">'+display_sticker+bubble_label+'</a>';
            }
        }
    }

    if(wpf_task_type==2 || wpf_task_type==3){
        var popover_container = '<div class=""><div data-html2canvas-ignore="true" id="popover-content-c'+comment_count+'"><div class="wpf_loader wpf_loader_'+comment_count+' wpf_hide"></div>';
    }
    else{
        var popover_container = '<div class=""><div data-html2canvas-ignore="true" id="popover-content-c'+comment_count+'" class="hide"><div class="wpf_loader wpf_loader_'+comment_count+' wpf_hide"></div><a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>';
    }

    var tab_nav_html = '<ul class="nav nav-tabs" id="myTab-'+comment_count+'" role="tablist">';
    if(wpf_tab_permission.user=='yes'){
        tab_nav_html+='<li class="nav-item"><a class="nav-link wpf_user_tab" id="wpfbuser-tab-'+comment_count+'" data-toggle="tab" href="#wpfbuser-'+comment_count+'" role="tab" aria-controls="wpfbuser" aria-selected="false">'+user_icon+'</a></li>';
    }
    if(wpf_tab_permission.priority=='yes'){
        tab_nav_html+='<li class="nav-item"><a class="nav-link wpf_prio_tab" id="wpfbpriority-tab-'+comment_count+'" data-toggle="tab" href="#wpfbpriority-'+comment_count+'" role="tab" aria-controls="wpfbpriority" aria-selected="false">'+priority_icon+'</a></li>';
    }
    if(wpf_tab_permission.status=='yes'){
        tab_nav_html+='<li class="nav-item"><a class="nav-link wpf_stat_tab" id="wpfbtaskstatus-tab-'+comment_count+'" data-toggle="tab" href="#wpfbtaskstatus-'+comment_count+'" role="tab" aria-controls="wpfbtaskstatus" aria-selected="false">'+status_icon+'</a></li>';
    }
    if(wpf_tab_permission.screenshot=='yes'){
        tab_nav_html+='<li class="nav-item"><a class="nav-link wpf_scrn_tab" id="wpfbscreenshot-tab-'+comment_count+'" data-toggle="tab" href="#wpfbscreenshot-'+comment_count+'" role="tab" aria-controls="wpfbscreenshot" aria-selected="false">'+screen_short_icon+'</a></li>';
    }
    if(wpf_tab_permission.information=='yes'){
        tab_nav_html+='<li class="nav-item"><a class="nav-link wpf_deta_tab" id="wpfbsysinfo-tab-'+comment_count+'" data-toggle="tab" href="#wpfbsysinfo-'+comment_count+'" role="tab" aria-controls="wpfbsysinfo" aria-selected="false">'+info_icon+'</a></li>';
    }
    tab_nav_html+='<li class="nav-item" title="Share Task link"><a class="nav-link wpf_deta_tab" id="sharetasklink-tab-'+comment_count+'" data-toggle="tab" href="#sharetasklink-'+comment_count+'" role="tab" aria-controls="sharetasklink" aria-selected="false">'+share_icon+'</a></li>';
    tab_nav_html+='</ul>';

    var tabs_html = '<div class="tab-content" id="myTabContent-'+comment_count+'">';

    tabs_html+='<div class="tab-pane" id="wpfbsysinfo-'+comment_count+'" role="tabpanel" aria-labelledby="wpfbsysinfo-tab"><ul><li><b>'+wpf_resolution+'</b> <span id="wpfbsysinfo_resolution-'+comment_count+'">'+wpf_config_resolution+'</span></li><li><b>'+wpf_browser+'</b> <span id="wpfbsysinfo_browser-'+comment_count+'">'+wpf_config_browser+'</span></li><li><b>'+wpf_user_name+'</b> <span id="wpfbsysinfo_user_name-'+comment_count+'">'+wpf_config_author_name+'</span></li><li><b>'+wpf_task_id+'</b> <span id="wpfbsysinfo_task_id-'+comment_count+'">'+wpf_config_task_id+'</span></li>';

    if(wpf_task_type==1 || wpf_task_type==2){
        tabs_html+='<li id="wpf_delete_container_'+comment_count+'"><span class="wpfbsysinfo_temp_delete_btn_task_id_'+comment_count+'"><a href="javascript:void(0)" class="wpf_task_temp_delete_btn" style="color:red;" data-btn_elemid="'+comment_count+'"  ><i class="gg-trash"></i> '+wpf_delete_ticket+'</a></span><p class="wpfbsysinfo_temp_delete_task_id_'+comment_count+' wpf_hide" >'+wpf_delete_conform_text1+' '+wpf_delete_conform_text2+'<a href="javascript:void(0)" class="wpf_task_temp_delete" data-elemid="'+comment_count+'" style="color:red;"> '+wpf_yes+'</a></p></li>';
    }
    else{
        if(wpf_tab_permission.delete_task=='yes'){
            tabs_html+='<li><span class="wpfbsysinfo_delete_btn_task_id_'+comment_count+'"><a href="javascript:void(0)" class="wpf_task_delete_btn" data-btn_taskid="'+wpfb_task_id+'" style="color:red;"><i class="gg-trash"></i>'+wpf_delete_ticket+'</a></span><p class="wpfbsysinfo_delete_task_id_'+wpfb_task_id+' wpf_hide" ><b>'+wpf_delete_conform_text1+'</b><br>'+wpf_delete_conform_text2+' <a href="javascript:void(0)" class="wpf_task_delete" data-taskid="'+wpfb_task_id+'" data-elemid="'+comment_count+'" style="color:red;"><b>'+wpf_yes+'</b></a></p></li>';
        }
        else if(wpf_tab_permission.delete_task=='no'){
            if(wpfb_metas.task_config_author_id==current_user_id){
                tabs_html+='<li><span class="wpfbsysinfo_delete_btn_task_id_'+comment_count+'"><a href="javascript:void(0)" class="wpf_task_delete_btn" data-btn_taskid="'+wpfb_task_id+'" style="color:red;"><i class="gg-trash"></i> '+wpf_delete_ticket+'</a></span><p class="wpfbsysinfo_delete_task_id_'+wpfb_task_id+' wpf_hide" ><b>'+wpf_delete_conform_text1+'</b><br>'+wpf_delete_conform_text2+' <a href="javascript:void(0)" class="wpf_task_delete" data-taskid="'+wpfb_task_id+'" data-elemid="'+comment_count+'" style="color:red;"><b>'+wpf_yes+'</b></a></p></li>';
            }
        }
    }

    const uParams = getParams(window.location.href);
    tabs_html+='</ul></div>';
    if(wpf_task_type==3){
        if(wpfb_task_id != 0){
            var task_link_div = '<div class="wpf_task_link_'+wpfb_task_id+'">';
            var task_link = current_page_url+'?id='+uParams['id']+'&wpf_general_taskid='+comment_count+'&wpf_login=1';
            var wpf_remove_login_checkbox = '<div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link'+wpfb_task_id+'" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard("'+wpfb_task_id+'","general")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link'+wpfb_task_id+'">Remove Login Parameter</label></div>' ;
        }else {
            var task_link_div = '<div class="wpf_task_link_'+comment_count+'">';
            var task_link = current_page_url+'?id='+uParams['id']+'&wpf_general_taskid='+comment_count+'&wpf_login=1';
            var wpf_remove_login_checkbox = '<div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link'+wpfb_task_id+'" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard("'+wpfb_task_id+'","general")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link'+wpfb_task_id+'">Remove Login Parameter</label></div>' ;
        }

    }else{
        var task_link = '';
        if(wpfb_task_id != 0){
            var task_link_div = '<div class="wpf_task_link_'+comment_count+'">';

            if ( task_link.lastIndexOf('?') > 0 ) {
                task_link = current_page_url+'?id='+uParams['id']+'&wpf_taskid='+comment_count+'&wpf_login=1';
            } else {
                task_link = current_page_url+'?id='+uParams['id']+'&wpf_taskid='+comment_count+'&wpf_login=1';
            }

            var wpf_remove_login_checkbox = '<div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link'+comment_count+'" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard("'+comment_count+'","normal")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link'+comment_count+'">Remove Login Parameter</label></div>' ;
        }else {
            var wpf_remove_login_checkbox = '<div class="wpf_remove_login_box"><input type="checkbox" id="wpf_remove_login_task_link'+comment_count+'" class="wpf_remove_login_task_link wpf_checkbox" onclick=\'wpf_remove_login_to_clipboard("'+comment_count+'","normal")\'><label class="wpf_remove_login_label wpf_checkbox_label" for="wpf_remove_login_task_link'+comment_count+'">Remove Login Parameter</label></div>' ;
            var task_link_div = '<div class="wpf_task_link_'+comment_count+'">';
            if ( task_link.lastIndexOf('?') > 0 ) {
                task_link = current_page_url+'?id='+uParams['id']+'&wpf_taskid='+comment_count+'&wpf_login=1';
            } else {
                task_link = current_page_url+'?id='+uParams['id']+'&wpf_taskid='+comment_count+'&wpf_login=1';
            }
        }
    }
    tabs_html+='<div class="tab-pane" id="sharetasklink-'+comment_count+'" role="tabpanel" aria-labelledby="sharetasklink-tab"><div class="wpf_icon_title">Share Task Link : </div><input type="text" id="wpf_share_link_'+wpfb_task_id+'" value="'+task_link+'" style="position: absolute; z-index: -999; opacity: 0;"><span class="wpf_share_task_link">'+task_link_div+task_link+'</div><a href="javascript:void(0);" onclick=\'wpf_copy_to_clipboard("wpf_share_link_'+wpfb_task_id+'")\' class="wpf_copy_task_icon" style="display: inline-block; color: var(--main-wpf-color) !important;"><i class="gg-copy"></i></a><span class="wpf_success_wpf_share_link" id="wpf_success_wpf_share_link_'+wpfb_task_id+'" style="display: none;">The link was copied to your clipboard.</span></span>'+wpf_remove_login_checkbox+'</div>';

    tabs_html+='<div class="tab-pane" id="wpfbuser-'+comment_count+'" role="tabpanel" aria-labelledby="home-tab">'+wpfb_users_html+'</div>';

    var wpf_screenshot = '';
    if (wpfb_metas.wpf_task_screenshot != undefined) {
        wpf_screenshot = '<a target="_blank" href="' + wpfb_metas.wpf_task_screenshot + '" class="wpf_screenshot_img_link"><img src="' + wpfb_metas.wpf_task_screenshot + '" id="screenshot_img_' + comment_count + '" style="margin-top:10px; border:1px solid #dee2e6;padding: 5px;width: 100%;" /></a>';
    } else {
        wpf_screenshot = '<a target="_blank" href="#" class="wpf_screenshot_img_link"><img src="#" id="screenshot_img_' + comment_count + '" style="margin-top:10px; border:1px solid #dee2e6;padding: 5px; display: none;width: 100%;" /></a>';
    }
    if (wpf_task_type == 1 || wpf_task_type == 2) {

        if (wpf_tab_permission.display_stickers == 'yes') {
            tabs_html += '<div class="tab-pane" id="wpfbpriority-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbpriority-tab"><div class="anim-slider"><input id="priority_low-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="low" class="wpfbpriority low_radio" checked><label for="priority_low-' + comment_count + '" class="low_label">' + wpf_priority_low + '</label><input id="priority_medium-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="medium" class="wpfbpriority medium_radio"><label for="priority_medium-' + comment_count + '" class="medium_label">' + wpf_priority_medium + '</label><input id="priority_high-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="high" class="wpfbpriority high_radio"><label for="priority_high-' + comment_count + '" class="high_label">' + wpf_priority_high + '</label><input id="priority_critical-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="critical" class="wpfbpriority critical_radio"><label for="priority_critical-' + comment_count + '" class="critical_label">' + wpf_priority_critical + '</label></div></div>';
            tabs_html += '<div class="tab-pane" id="wpfbtaskstatus-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbtaskstatus-tab"><div class="anim-slider"><input id="status_open-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="open" data-elemid="' + comment_count + '" class="wpfbtaskstatus open_radio" checked><label for="status_open-' + comment_count + '" class="open_label">' + wpf_status_open_task + '</label><input id="status_progress-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="in-progress" data-elemid="' + comment_count + '" class="wpfbtaskstatus in_progress_radio" ><label for="status_progress-' + comment_count + '" class="in_progress_label">' + wpf_status_in_progress + '</label><input id="status_pending-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="pending-review" data-elemid="' + comment_count + '" class="wpfbtaskstatus pending_radio" ><label for="status_pending-' + comment_count + '" class="pending_label">' + wpf_status_pending_review + '</label><input id="status_complete-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="complete" data-elemid="' + comment_count + '" class="wpfbtaskstatus complete_radio" ><label for="status_complete-' + comment_count + '" class="complete_label">' + wpf_status_complete + '</label></div></div>';
        } else {
            tabs_html += '<div class="tab-pane" id="wpfbpriority-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbpriority-tab"><div class="anim-slider"><input id="priority_low-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="low" class="wpfbpriority" checked><label for="priority_low-' + comment_count + '">' + wpf_priority_low + '</label><input id="priority_medium-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="medium" class="wpfbpriority"><label for="priority_medium-' + comment_count + '">' + wpf_priority_medium + '</label><input id="priority_high-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="high" class="wpfbpriority"><label for="priority_high-' + comment_count + '">' + wpf_priority_high + '</label><input id="priority_critical-' + comment_count + '" type="radio" name="wpfbpriority' + comment_count + '" data-elemid="' + comment_count + '" value="critical" class="wpfbpriority"><label for="priority_critical-' + comment_count + '">' + wpf_priority_critical + '</label></div></div>';
            tabs_html += '<div class="tab-pane" id="wpfbtaskstatus-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbtaskstatus-tab"><div class="anim-slider"><input id="status_open-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="open" data-elemid="' + comment_count + '" class="wpfbtaskstatus" checked><label for="status_open-' + comment_count + '">' + wpf_status_open_task + '</label><input id="status_progress-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="in-progress" data-elemid="' + comment_count + '" class="wpfbtaskstatus" ><label for="status_progress-' + comment_count + '">' + wpf_status_in_progress + '</label><input id="status_pending-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="pending-review" data-elemid="' + comment_count + '" class="wpfbtaskstatus" ><label for="status_pending-' + comment_count + '">' + wpf_status_pending_review + '</label><input id="status_complete-' + comment_count + '" type="radio" name="wpfbtaskstatus' + comment_count + '" value="complete" data-elemid="' + comment_count + '" class="wpfbtaskstatus" ><label for="status_complete-' + comment_count + '">' + wpf_status_complete + '</label></div></div>';
        }


        tabs_html += '<div class="tab-pane" id="wpfbscreenshot-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbscreenshot-tab"><a href="javascript:void(0)" onclick="screenshot(' + comment_count + ');"><div class="wpf_screenshot_button">' + wpf_screenshot_view + '</div></a>' + wpf_screenshot + '</div><div  id="task_comments_section_' + comment_count + '"><ul class="wpf_current_chat_box scrollbar-outer" id="task_comments_' + comment_count + '"></ul></div>';
    } else {
        tabs_html += '<div class="tab-pane" id="wpfbpriority-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbpriority-tab"><div class="anim-slider">' + wpfb_task_priority_html + '</div></div>';
        tabs_html += '<div class="tab-pane" id="wpfbtaskstatus-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbtaskstatus-tab"><div class="anim-slider">' + wpfb_task_status_html + '</div></div>';
        tabs_html += '<div class="tab-pane" id="wpfbscreenshot-' + comment_count + '" role="tabpanel" aria-labelledby="wpfbscreenshot-tab"><a href="javascript:void(0)" onclick="screenshot(' + comment_count + ');"><div class="wpf_screenshot_button">' + wpf_screenshot_view + '</div></a>' + wpf_screenshot + '</div><div id="task_comments_section_' + comment_count + '"><ul class="wpf_current_chat_box scrollbar-outer" id="task_comments_' + comment_count + '">' + wpfb_messages_html + '</ul></div>';
    }

    tabs_html+='</div>';

    let mark_as_complete_checkbox = "";
    let dissabled_attr = "";
    if(wpfb_metas.task_status == 'complete'){
        dissabled_attr = 'disabled="disabled"';
    }

    // check the mark complete checkbox if the task has completed
    const task_status_complete_checked = (wpfb_metas['task_status'] === 'complete') ? "checked" : "";

    mark_as_complete_checkbox = "<input type='checkbox' data-id='"+comment_count+"' class='mark_as_complete_checkbox wpf_checkbox' id='mark_complete_"+comment_count+"' "+dissabled_attr+" " + task_status_complete_checked + "><label class='wpf_checkbox_label mark_as_complete_lable' for='mark_complete_"+comment_count+"'>"+ wpf_complete_task +"</label>";

    if(wpfb_metas.is_internal=='1'){
        internal_class=' wpf_is_internal';
    }else{
        internal_class='';
    }

    if(new_task_popover==1){
        internal_tooltip_class=' new_internal_tooltip';
    }else if(wpfb_metas.is_internal=='1'){
        internal_tooltip_class=' unmark_internal_tooltip';
    }else if(wpfb_metas.is_internal!='1'){
        internal_tooltip_class=' mark_internal_tooltip';
    }

    if(wpf_current_role=='advisor'){
        internal_button='<button class="wpf_mark_internal'+internal_class+internal_tooltip_class+'" id="wpf_mark_internal_'+comment_count+'" onclick="new_comment(' + comment_count + ',1)">' + wpf_mark_internal_btn + '</button>';
    }else{
        internal_button='';
    }

    var comment_html = '<div class="form-group"><div class="wpf-editor" data-id="comment-' + comment_count + '"></div><textarea class="form-control" id="comment-'+comment_count+'" placeholder="'+wpf_comment_box_placeholder+'"></textarea><span id="wpf_error_'+comment_count+'" class="wpf_hide">'+wpf_task_text_error_msg+'</span><span id="wpf_push_to_media_error_' + comment_count + '" class="wpf_hide">' + wpf_push_to_media_error_msg + '</span><span id="wpf_task_error_'+comment_count+'" class="wpf_hide">'+wpf_task_upload_error_msg+'</span><div class="bottom_buttons">'+mark_as_complete_checkbox+internal_button+'<button class="wpf_green_btn wpf_comment_btn" onclick="new_comment('+comment_count+')">'+wpf_add_comment_btn+'</button></div><button class="wpf_upload_button"><input type="file" name="wpf_uploadfile_'+comment_count+'" id="wpf_uploadfile_'+comment_count+'" data-elemid="'+comment_count+'" class="wpf_uploadfile"> <i class="gg-attachment"></i></button><p id="wpf_upload_error_'+comment_count+'" class="wpf_hide">'+wpf_upload_invalid_file_msg+'</p></div></div></div>';

    var response_html = button_html+popover_container+tab_nav_html+tabs_html+comment_html;

    return response_html;
}

function openWPFTab(wpfTab) {

    // remove the no found image if exists
    if ( jQuery_WPF('.wpf_sidebar_content').hasClass('nothingfound') ) {
        jQuery_WPF('.wpf_sidebar_content').removeClass('nothingfound');
    }

    // switching to other tab also reset the filters
    reset_sidebar_filters();
    
    if(wpfTab == 'wpf_allpages'){
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_thispage').hide();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_backend').hide();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_allpages').show();
        jQuery_WPF('button.wpf_tab_sidebar').removeClass('wpf_active');
        jQuery_WPF('button.wpf_tab_sidebar.'+wpfTab).addClass('wpf_active');

        jQuery_WPF('.wpf_sidebar_content .wpf_container').removeClass('wpf_active_filter');
        jQuery_WPF('#'+wpfTab).addClass('wpf_active_filter');

        jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]:checked').prop('checked',false);
        jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]:checked').prop('checked',false);
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();

        if(all_page_tasks_loaded==0){
            all_page_tasks_loaded=1;
            load_all_page_tasks();
        }
    }else if(wpfTab == 'wpf_backend'){
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_allpages').hide();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_backend').show();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_thispage').hide();
        jQuery_WPF('button.wpf_tab_sidebar').removeClass('wpf_active');
        jQuery_WPF('button.wpf_tab_sidebar.'+wpfTab).addClass('wpf_active');

        jQuery_WPF('.wpf_sidebar_content .wpf_container').removeClass('wpf_active_filter');
        jQuery_WPF('#'+wpfTab).addClass('wpf_active_filter');

        jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]:checked').prop('checked',false);
        jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]:checked').prop('checked',false);
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
        if(all_backend_tasks_loaded==0){
            all_backend_tasks_loaded=1;
            load_all_backend_tasks_admin();
        }
    }
    else{
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_allpages').hide();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_backend').hide();
        jQuery_WPF('.wpf_sidebar_content').find('#wpf_thispage').show();
        jQuery_WPF('button.wpf_tab_sidebar').removeClass('wpf_active');
        jQuery_WPF('button.wpf_tab_sidebar.'+wpfTab).addClass('wpf_active');

        jQuery_WPF('.wpf_sidebar_content .wpf_container').removeClass('wpf_active_filter');
        jQuery_WPF('#'+wpfTab).addClass('wpf_active_filter');

        jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]:checked').prop('checked',false);
        jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]:checked').prop('checked',false);
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
    }
}

function load_all_page_tasks(){
    jQuery_WPF.ajax({
        method:"POST",
        url: ajaxurl,
        data: {action:'load_wpfb_tasks',wpf_nonce:wpf_nonce},
        beforeSend: function(){
            jQuery_WPF('.wpf_sidebar_loader').show();
        },
        success: function(data){
            jQuery_WPF('.wpf_sidebar_loader').hide();
            var wpfb_users_arr = JSON.parse(wpfb_users);
            var onload_wpfb_tasks = JSON.parse(data);
            var wpfb_all_page_task_list_html = '';
            if(onload_wpfb_tasks != null && onload_wpfb_tasks != "null") {

            const k = Object.keys(onload_wpfb_tasks).sort(timeSort);

            comment_count_initial = Object.keys(onload_wpfb_tasks).length;

            jQuery_WPF.each(k,function (index, value) {
                var general_tag = '';
                var wpf_graphics_tag_name = '';
                tasks_on_page[comment_count_initial]=value;

                let display_check_mark = '';
                if(wpf_tab_permission.display_task_id != 'yes'){
                    display_check_mark = '<i class="gg-check"></i>';
                }else{
                    display_check_mark = onload_wpfb_tasks[value].task_comment_id;
                }

                if(onload_wpfb_tasks[value].task_status=='complete'){
                    var bubble_label = display_check_mark;
                }
                else{
                    var bubble_label = onload_wpfb_tasks[value].task_comment_id;
                }

                if(onload_wpfb_tasks[value].task_page_title == null){
                    onload_wpfb_tasks[value].task_page_title = "";
                }

                for (var usr in wpfb_users_arr) {
                    if (wpfb_users_arr.hasOwnProperty(usr)) {
                        if(wpfb_users_arr[usr]['username']==onload_wpfb_tasks[value].task_config_author_name){
                            onload_wpfb_tasks[value].task_config_author_name = wpfb_users_arr[usr]['displayname'];
                            break;
                        }
                    }
                }

                if(onload_wpfb_tasks[value].task_type=='general'){
                    general_tag = '<span>'+wpf_general_tag+'</span>';
                    wpfb_all_page_task_list_html += '<li class="current_page_task ' +onload_wpfb_tasks[value].task_status+ " " + onload_wpfb_tasks[value].task_priority+'" data-taskid="'+onload_wpfb_tasks[value].task_comment_id+'" data-postid="'+value+'" data-task_url="'+onload_wpfb_tasks[value].task_page_url+'?wpf_general_taskid='+value+'"><div class="wpf_task_number" data-disp-id="'+wpfb_metas.site_task_id+'">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+onload_wpfb_tasks[value].task_config_author_name+'<span>'+onload_wpfb_tasks[value].task_time+'</span>'+general_tag+'</level><div class="wpf_task_pagename">'+onload_wpfb_tasks[value].task_page_title+'</div><div class="current_page_task_list">'+onload_wpfb_tasks[value].task_title+'</div></div></li>';
                }else if(onload_wpfb_tasks[value].task_type=='graphics'){
                    wpf_graphics_tag_name = '<span>'+wpf_graphics_tag+'</span>';
                    wpfb_all_page_task_list_html += '<li class="current_page_task ' +onload_wpfb_tasks[value].task_status+ " " + onload_wpfb_tasks[value].task_priority+'" data-taskid="'+onload_wpfb_tasks[value].task_comment_id+'" data-postid="'+value+'" data-task_url="'+onload_wpfb_tasks[value].task_page_url+'&wpf_taskid='+onload_wpfb_tasks[value].task_comment_id+'"><div class="wpf_task_number" data-disp-id="'+wpfb_metas.site_task_id+'">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+onload_wpfb_tasks[value].task_config_author_name+'<span>'+onload_wpfb_tasks[value].task_time+' '+wpf_graphics_tag_name+'</span></level><div class="wpf_task_pagename">'+onload_wpfb_tasks[value].task_page_title+'</div><div class="current_page_task_list">'+onload_wpfb_tasks[value].task_title+'</div></div></li>';
                }else{
                    wpfb_all_page_task_list_html += '<li class="current_page_task ' +onload_wpfb_tasks[value].task_status+ " " + onload_wpfb_tasks[value].task_priority+'" data-taskid="'+onload_wpfb_tasks[value].task_comment_id+'" data-task_url="'+onload_wpfb_tasks[value].task_page_url+'?wpf_taskid='+onload_wpfb_tasks[value].task_comment_id+'"><div class="wpf_task_number" data-disp-id="'+wpfb_metas.site_task_id+'">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+onload_wpfb_tasks[value].task_config_author_name+'<span>'+onload_wpfb_tasks[value].task_time+'</span>'+general_tag+'</level><div class="wpf_task_pagename">'+onload_wpfb_tasks[value].task_page_title+'</div><div class="current_page_task_list">'+onload_wpfb_tasks[value].task_title+'</div></div></li>';
                }

                comment_count_initial--;
            });
        }

            jQuery_WPF("#wpf_allpages_container").html(wpfb_all_page_task_list_html);
        }
    });
}

function getDomPath(el) {
    var stack = [];
    while ( el.parentNode != null ) {
        var sibCount = 0;
        var sibIndex = 0;
        for ( var i = 0; i < el.parentNode.childNodes.length; i++ ) {
            var sib = el.parentNode.childNodes[i];
            if ( sib.nodeName == el.nodeName ) {
                if ( sib === el ) {
                    sibIndex = sibCount;
                }
                sibCount++;
            }
        }
        if ( el.hasAttribute('id') && el.id != '' ) {
            stack.unshift(el.nodeName.toLowerCase() + '#' + el.id);
        } else if ( sibCount > 1 ) {
            stack.unshift(el.nodeName.toLowerCase() + ':eq(' + sibIndex + ')');
        } else {
            stack.unshift(el.nodeName.toLowerCase());
        }
        el = el.parentNode;
    }

    return stack.slice(1); /*removes the html element*/
}

jQuery_WPF.fn.onPositionChanged = function (trigger, millis) {
    if (millis == null) millis = 100;
    var o = jQuery_WPF(this[0]); /*our jquery object*/
    if (o.length < 1) return o;

    var lastPos = null;
    var lastOff = null;
    setInterval(function () {
        if (o == null || o.length < 1) return o; /*abort if element is non existend eny more*/
        if (lastPos == null) lastPos = o.position();
        if (lastOff == null) lastOff = o.offset();
        var newPos = o.position();
        var newOff = o.offset();
        if (lastPos.top != newPos.top || lastPos.left != newPos.left) {
            jQuery_WPF(this).trigger('onPositionChanged', { lastPos: lastPos, newPos: newPos });
            if (typeof (trigger) == "function") trigger(lastPos, newPos);
            lastPos = o.position();
        }
        if (lastOff.top != newOff.top || lastOff.left != newOff.left) {
            jQuery_WPF(this).trigger('onOffsetChanged', { lastOff: lastOff, newOff: newOff});
            if (typeof (trigger) == "function") trigger(lastOff, newOff);
            lastOff= o.offset();
        }
    }, millis);

    return o;
};

function wpf_bubble_tracker(comment_count,task_clean_dom_elem_path) {
    jQuery_WPF(task_clean_dom_elem_path).onPositionChanged(function(){
        setTimeout(function() {
            var element_center = getelementcenter(task_clean_dom_elem_path);
            element_center['left']=element_center['left']-25;
            element_center['top']=element_center['top']-25;
            jQuery_WPF('#bubble-'+comment_count).attr('style','top:'+element_center['top']+'px; left:'+element_center['left']+'px;')
        }, 0);

    });
}

function trigger_bubble_label(){
    var wpf_page_value = getParameterByName('wpf_taskid');
    if(wpf_page_value != ''){
        jQuery_WPF('#wpfb_display_completed_tasks').trigger('click');
        var wpf_tmp_show_task_checkbox_obj = jQuery_WPF("#wpfb_display_tasks");
        if(wpf_tmp_show_task_checkbox_obj.prop('checked')==false){
            wpf_tmp_show_task_checkbox_obj.prop('checked',true);
        }
        wpf_display_tasks(wpf_tmp_show_task_checkbox_obj);

        setTimeout(function() { jQuery_WPF('body').find('#bubble-'+wpf_page_value).trigger('click')},20);

        if(jQuery_WPF("#bubble-"+wpf_page_value).length > 0) {
        jQuery_WPF('html, body').animate({
                    scrollTop: jQuery_WPF("#bubble-"+wpf_page_value).offset().top - 200
                }, 1000);
        }
    }

    var wpf_general_taskid_value = getParameterByName('wpf_general_taskid');
    if(wpf_general_taskid_value != ''){
        wpf_load_general_task(wpf_general_taskid_value);
    }
}

function getParameterByName( name ){
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

function wpf_delete_task(id,task_id){
    var task_info = [];
    task_info['task_id'] = task_id;
    task_info['task_no']=id;
    var task_info_obj = jQuery_WPF.extend({}, task_info);
    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpfb_delete_task",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        beforeSend: function(){
            jQuery_WPF('.wpf_loader_'+id).show();
        },
        success : function(data){
            jQuery_WPF('#bubble-'+id).trigger('click');
            jQuery_WPF('.wpf_general_task_close').trigger('click');
            jQuery_WPF('#bubble-'+id).remove();
            jQuery_WPF('.wpf_loader_'+id).hide();
            jQuery_WPF('#wpf_thispage_container_today [data-taskid="'+id+'"],#wpf_thispage_container_yesterday [data-taskid="'+id+'"],#wpf_thispage_container_this_week [data-taskid="'+id+'"]').remove();
            jQuery_WPF('#wpf_allpages_container_today [data-taskid="'+id+'"],#wpf_allpages_container_yesterday [data-taskid="'+id+'"],#wpf_allpages_container_this_week [data-taskid="'+id+'"]').remove();
            if(onload_wpfb_tasks[task_id] != null){
                jQuery_WPF(onload_wpfb_tasks[task_id].task_element_path).removeClass('wpfb_task_bubble');
            }
        }
    });
}

function wpf_send_report(type) {
    jQuery_WPF.ajax({
        method: "POST",
        url: ajaxurl,
        data: {action: "wpf_send_email_report", wpf_nonce: wpf_nonce, type:type, forced: "yes"},
        beforeSend: function(){
            jQuery_WPF('.wpf_sidebar_loader').show();
        },
        success: function (data) {
            jQuery_WPF('.wpf_sidebar_loader').hide();
            jQuery_WPF('#wpf_front_report_sent_span').css("display", "block");
            setTimeout(function() {
                jQuery_WPF('#wpf_front_report_sent_span').hide();
            }, 3000);
        }
    });
}

function wpf_upload_file(obj){
    var elemid = jQuery_WPF(obj).attr('data-elemid'), task_info=[];
    var wpf_file = jQuery_WPF('#wpf_uploadfile_'+elemid)[0].files[0];
    var wpf_taskid = tasks_on_page[elemid];
    var wpf_comment = '';
    var wpf_upload_form = new FormData();
    wpf_upload_form.append('action', 'wpf_upload_file');
    wpf_upload_form.append('wpf_nonce',wpf_nonce);
    wpf_upload_form.append("wpf_taskid", wpf_taskid);
    wpf_upload_form.append("wpf_upload_file", wpf_file);
    wpf_upload_form.append('task_config_author_name', current_user_name);
    if(wpf_taskid > 0){
        jQuery_WPF.ajax({
            type: 'POST',
            url: ajaxurl,
            data: wpf_upload_form,
            contentType: false,
            processData: false,
            beforeSend: function(){
                jQuery_WPF('.wpf_loader_'+elemid).show();
            },
            success: function (data) {
                var response = JSON.parse(data);
                jQuery_WPF('.wpf_loader_'+elemid).hide();

                if(response.status==1){
                    jQuery_WPF('#wpf_error_'+elemid).hide();
                    jQuery_WPF('#wpf_task_error_'+elemid).hide();
                    jQuery_WPF("input[name=wpf_uploadfile_"+elemid+"]").val('');

                    if(response.type != 1){
                        img_dwn_icon = "";
                    }
                    
                    if(response.type==1){
                        img_dwn_icon  = "<span id='wpf_push_media' class='wpf_push_media wpf_image_download'>"+push_to_media_icon+"</span><span id='wpf_image_download' class='wpf_image_download' onclick='wpf_image_download_action(this)'>"+image_download_icon+"</span><span id='' class='wpf_image_open' onclick='wpf_image_open_new_tab(this)'>"+image_open_icon+"</span><span class='wpf_image_delete' onclick='wpf_task_image_delete(this)'>"+image_close_icon+"</span>";
                        var comment_html = '<li class="wpf_author is_image" data-comment_id="'+response.comment_id+'"><level class="task-author">'+current_user_name+'</level><div class="meassage_area_main">'+img_dwn_icon+'<a target="_blank" href="'+response.message+'"><img src="'+response.message+'"></a></div></li>';
                    }
                    else{
                        var wpf_download_file  = response.message.split("/").pop();
                        var comment_html = '<li class="wpf_author"><level class="task-author">'+current_user_name+' '+wpf_just_now+'</level><div class="meassage_area_main">'+img_dwn_icon+'<a href="'+response.message+'" download="'+wpf_download_file+'" target="_blank"><i class="gg-software-download"></i> '+ wpf_download_file+'</a></div></li>';
                    }

                    jQuery_WPF('#task_comments_'+elemid).append(comment_html);
                    jQuery_WPF('#task_comments_'+elemid).animate({scrollTop: jQuery_WPF('#task_comments_'+elemid).prop("scrollHeight")}, 2000);
                }
                else{
                    jQuery_WPF('#wpf_upload_error_'+elemid).show();
                    jQuery_WPF("input[name=wpf_uploadfile_"+elemid+"]").val('');
                    setTimeout(function() {
                        jQuery_WPF('#wpf_upload_error_'+elemid).hide();
                    }, 5000);
                }
            }
        });
    }else{
        jQuery_WPF('#wpf_error_'+elemid).hide();
        jQuery_WPF('#wpf_task_error_'+elemid).show();
        jQuery_WPF("input[name=wpf_uploadfile_"+elemid+"]").val('');
    }
}

/*FRONTEND ACTICATION WIZARD / ONBOARDING*/
jQuery_WPF(document).ready(function () {
    /*STEP 1 BUTTONS*/
    jQuery_WPF('input[name="wpf_user_type"]').on('click',function () {
        var wpf_user_type = jQuery_WPF('input[name="wpf_user_type"]:checked').val();
        jQuery_WPF.ajax({
            method: 'POST',
            url:ajaxurl,
            data:{action: 'wpf_update_current_user_first_step',wpf_nonce:wpf_nonce,current_user_id:current_user_id,wpf_user_type:wpf_user_type},
            beforeSend: function(){
                jQuery_WPF('.wpf_loader_wizard').show();
            },
            success: function (data) {
                jQuery_WPF('.wpf_loader_wizard').hide();
                if(data==1){
                    jQuery_WPF('#wpf_wizard_notifications').show();
                    jQuery_WPF('#wpf_wizard_role').hide();
                }
            }
        });
        return false;
    });
    /*STEP 2 BUTTONS*/
    jQuery_WPF('#wpf_wizard_notifications_button').on('click',function (e) {
        var wpf_every_new_task = jQuery_WPF('#wpf_every_new_task:checkbox:checked').val();
        var wpf_every_new_comment = jQuery_WPF('#wpf_every_new_comment:checkbox:checked').val();
        var wpf_every_new_complete = jQuery_WPF('#wpf_every_new_complete:checkbox:checked').val();
        var wpf_every_status_change = jQuery_WPF('#wpf_every_status_change:checkbox:checked').val();
        var wpf_daily_report = jQuery_WPF('#wpf_auto_daily_report:checkbox:checked').val();
        var wpf_weekly_report = jQuery_WPF('#wpf_auto_weekly_report:checkbox:checked').val();
        jQuery_WPF.ajax({
            url:ajaxurl,
            method: 'POST',
            data:{action: 'wpf_update_current_user_first_step',wpf_nonce:wpf_nonce,current_user_id:current_user_id,wpf_every_new_task:wpf_every_new_task,wpf_every_new_comment:wpf_every_new_comment,wpf_every_new_complete:wpf_every_new_complete,wpf_every_status_change:wpf_every_status_change,wpf_daily_report:wpf_daily_report,wpf_weekly_report:wpf_weekly_report},
            beforeSend: function(){
                jQuery_WPF('.wpf_loader_wizard').show();
            },
            success: function (data) {
                jQuery_WPF('#wpf_wizard_role,#wpf_wizard_notifications').hide();
                jQuery_WPF('#wpf_wizard_final').show();
                jQuery_WPF('.wpf_loader_wizard').hide();
                if(data==1){

                }
            }
        });
        return false;
    });
    /*STEP 2 BUTTONS*/
    jQuery_WPF('#wpf_wizard_done_button').on('click',function (e) {
        lets_start = '';
        jQuery_WPF.ajax({
            url:ajaxurl,
            method: 'POST',
            data:{action: 'wpf_update_current_user_sec_step',current_user_id:current_user_id,lets_start:1},
            beforeSend: function(){
                jQuery_WPF('.wpf_loader_wizard').show();
            },
            success: function (data) {
                location.reload(true);
            }
        });
        return false;
    });
});

function wpf_new_general_task(id) {
    if(restrict_plugin==1){
        jQuery(".wpf_locked_modal_container").show();
        return;
    }
    disable_comment();
    if(id==0){
        tasks_on_page[comment_count]=0;
        var wpfb_users_arr = JSON.parse(wpfb_users);
        var wpfb_users_html = '<ul class="wp_feedback_filter_checkbox user">';

        for (var key in wpfb_users_arr) {
            if (wpfb_users_arr.hasOwnProperty(key)) {
                var user_name = wpfb_users_arr[key]['displayname'];
                if(current_user_id==key || wpf_website_builder==key){
                    wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'" checked><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                }
                else{
                    wpfb_users_html+='<li><input type="checkbox" name="author_list_'+comment_count+'" value="'+key+'" class="wp_feedback_task wpfbtasknotifyusers wpf_checkbox" data-elemid="'+comment_count+'" id="author_list_'+comment_count+'_'+key+'" data-wp-usrn="'+wpfb_users_arr[key]['username']+'"><label for="author_list_'+comment_count+'_'+key+'" class="wpf_checkbox_label">'+user_name+'</label></li>';
                }
            }
        }
        wpfb_users_html+='</ul>';
        var wpf_popover_html = wpf_task_popover_html(2,comment_count,0,'',wpfb_users_html,'','','',1);
        jQuery_WPF('#wpf_general_comment_tabs').html(wpf_popover_html);
        jQuery_WPF('#wpf_general_comment').show();
        jQuery_WPF("#wpf_uploadfile_"+comment_count).change(function () {
            wpf_upload_file(this);
        });

        // add rich text editor for Task center by Pratap
        jQuery(document).find('.wpf-editor').each(function() {
            if ( ! jQuery_WPF(this).hasClass('activee') ) {
                var $this = jQuery_WPF(this);
                jQuery_WPF(this).addClass('activee');
                var quill = new Quill(this, {
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['link', 'code-block'],
                        ]
                    },
                    placeholder: wpf_comment_box_placeholder,
                    theme: 'bubble'   // Specify theme in configuration
                });
                quill.on('text-change', function(delta, oldDelta, source) {
                    $this.parent().find('textarea').val(quill.root.innerHTML);
                });
            }
        });

        jQuery_WPF('.wpf_task_temp_delete_btn').click(function(){
            var btn_taskid = jQuery_WPF(this).data('btn_elemid');
            jQuery_WPF('.wpfbsysinfo_temp_delete_task_id_'+btn_taskid).show();
        });

        jQuery_WPF('.wpf_task_temp_delete').click(function(){
            var elemid = jQuery_WPF(this).data('elemid');
            jQuery_WPF('#wpf_general_comment .wpf_general_task_close').trigger('click');
        });

    }

    // => v2.1.0
    if (temp_tasks) {
        temp_tasks = [];
    }
}

function wpf_load_general_task(id) {
    jQuery_WPF.ajax({
        url:ajaxurl,
        method:'POST',
        data:{action:'load_wpfb_tasks',wpf_nonce:wpf_nonce,task_id:id},
        beforeSend: function(){
            jQuery_WPF('.wpf_sidebar_loader').show();
        },
        success: function (data) {
            jQuery_WPF('.wpf_sidebar_loader').hide();
            var task_info = JSON.parse(data);
            var task_id = Object.keys(task_info)[0];
            var task_meta = task_info[task_id];
            wpf_generate_general_task_html(task_id,task_meta);
            jQuery_WPF('#wpf_general_comment').show();
            jQuery_WPF("#wpf_uploadfile_"+task_meta.task_comment_id).change(function () {
                wpf_upload_file(this);
            });
            wpf_initiate_task_features();

            // add rich text editor for Task center by Pratap
            jQuery(document).find('.wpf-tc-editor, .wpf-editor').each(function() {
                if ( ! jQuery_WPF(this).hasClass('activee') ) {
                    var $this = jQuery_WPF(this);
                    jQuery_WPF(this).addClass('activee');
                    var quill = new Quill(this, {
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['link', 'code-block'],
                            ]
                        },
                        placeholder: wpf_comment_box_placeholder,
                        theme: 'bubble'   // Specify theme in configuration
                    });
                    quill.on('text-change', function(delta, oldDelta, source) {
                        $this.parent().find('textarea').val(quill.root.innerHTML);
                    });
                }
            });
        }
    });
}

function wpf_initiate_task_features(){
    jQuery_WPF('.wpfbpriority').click(function(e){
        var elemid = jQuery_WPF(this).attr('data-elemid');
        set_task_prioirty(elemid);
        e.stopImmediatePropagation();
    });
    jQuery_WPF('.wpfbtaskstatus').click(function(e){
        var elemid = jQuery_WPF(this).attr('data-elemid');
        set_task_status(elemid);
        e.stopImmediatePropagation();
    });
    jQuery_WPF('.wpfbtasknotifyusers').click(function(){
        var elemid = jQuery_WPF(this).attr('data-elemid');
        set_task_notify_users(elemid);
    });
    jQuery_WPF('.wpf_task_delete_btn').click(function(){
        var btn_taskid = jQuery_WPF(this).data('btn_taskid');
        jQuery_WPF('.wpfbsysinfo_delete_task_id_'+btn_taskid).show();
    });
    jQuery_WPF('.wpf_task_delete').click(function(){
        var elemid = jQuery_WPF(this).data('elemid');
        var task_id = jQuery_WPF(this).data('taskid');
        wpf_delete_task(elemid,task_id);
    });
}


function load_all_backend_tasks_admin(){
    var wpf_current_screen = '';
    var wpfb_users_arr = JSON.parse(wpfb_users);
    jQuery_WPF.ajax({
        method:"POST",
        url: ajaxurl,
        data: {action:'load_wpfb_tasks_admin',wpf_nonce:wpf_nonce,wpf_current_screen:wpf_current_screen,all_page:1},
        beforeSend: function(){
            jQuery_WPF('.wpf_sidebar_loader').show();
        },
        success: function(data){
            jQuery_WPF('.wpf_sidebar_loader').hide();
            var onload_wpfb_tasks = JSON.parse(data);

            var wpfb_all_page_task_list_html = '';
if(onload_wpfb_tasks != null && onload_wpfb_tasks != "null") {

            const k = Object.keys(onload_wpfb_tasks).sort(timeSort);

            comment_count_initial = Object.keys(onload_wpfb_tasks).length;

            var wpfb_all_page_task_list_html = '';
            jQuery_WPF.each(k,function (index, value) {
                var general_tag = '';
                tasks_on_page[comment_count_initial]=value;
                
                let display_check_mark = '';
                if(wpf_tab_permission.display_task_id !== true){
                    display_check_mark = '<i class="gg-check"></i>';
                }else{
                    display_check_mark = onload_wpfb_tasks[value].task_comment_id;
                }

                if(onload_wpfb_tasks[value].task_status=='complete'){
                    var bubble_label = display_check_mark;
                }
                else{
                    var bubble_label = onload_wpfb_tasks[value].task_comment_id;

                }

                var wpf_page_url=onload_wpfb_tasks[value].task_page_url;
                if(wpf_page_url ){
                    var wpf_page_url_with_and=wpf_page_url.split('&')[1];
                    var wpf_page_url_question=wpf_page_url.split('?')[1];
                    if(wpf_page_url_with_and){
                        var saperater = '&';
                    }
                    if(wpf_page_url_question){
                        var saperater = '&';
                    }

                    else{
                        var saperater = '?';
                    }
                }
                
                for (var usr in wpfb_users_arr) {
                    if (wpfb_users_arr.hasOwnProperty(usr)) {
                        if(wpfb_users_arr[usr]['username']==onload_wpfb_tasks[value].task_config_author_name){
                            onload_wpfb_tasks[value].task_config_author_name = wpfb_users_arr[usr]['displayname'];
                            break;
                        }
                    }
                }

                if(onload_wpfb_tasks[value].task_page_title == null){
                    onload_wpfb_tasks[value].task_page_title = "";
                }
                
                if(onload_wpfb_tasks[value].task_type=='general'){
                    general_tag = '<span>'+wpf_general_tag+'</span>';
                    wpfb_all_page_task_list_html += '<li class="current_page_task ' +onload_wpfb_tasks[value].task_status + " " + onload_wpfb_tasks[value].task_priority+'" data-taskid="'+onload_wpfb_tasks[value].task_comment_id+'" data-postid="'+value+'" data-task_url="'+onload_wpfb_tasks[value].task_page_url+saperater+'wpf_general_taskid='+value+'"><div class="wpf_task_number">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+onload_wpfb_tasks[value].task_config_author_name+'<span>'+onload_wpfb_tasks[value].task_time+'</span>'+general_tag+'</level><div class="wpf_task_pagename">'+onload_wpfb_tasks[value].task_page_title+'</div><div class="current_page_task_list">'+onload_wpfb_tasks[value].task_title+'</div></div></li>';
                }
                else{
                    wpfb_all_page_task_list_html += '<li class="current_page_task ' +onload_wpfb_tasks[value].task_status+ " " + onload_wpfb_tasks[value].task_priority+'" data-taskid="'+onload_wpfb_tasks[value].task_comment_id+'" data-task_url="'+onload_wpfb_tasks[value].task_page_url+saperater+'wpf_taskid='+onload_wpfb_tasks[value].task_comment_id+'"><div class="wpf_task_number">'+bubble_label+'</div><div class="wpf_task_sum"><level class="task-author">'+onload_wpfb_tasks[value].task_config_author_name+'<span>'+onload_wpfb_tasks[value].task_time+'</span> '+general_tag+'</level><div class="wpf_task_pagename">'+onload_wpfb_tasks[value].task_page_title+'</div><div class="current_page_task_list">'+onload_wpfb_tasks[value].task_title+'</div></div></li>';
                }

                comment_count_initial--;
            });
        }

            jQuery_WPF("#wpf_backend_container").html(wpfb_all_page_task_list_html);
        }
    });
}

/*Start Code for Fiter task in sidebar*/
function wpf_show(filter_type){
    jQuery_WPF('.wpf_sidebar_content input[name="wpf_filter_task_status"]:checked').prop('checked',false);
    jQuery_WPF('.wpf_sidebar_content input[name="wpf_filter_task_priority"]:checked').prop('checked',false);
    jQuery_WPF('.wpf_sidebar_filter').find('.wpf_filter').removeClass("wpf_active");

    if(filter_type == 'wpf_filter_taskstatus'){
        jQuery_WPF('.wpf_filter_taskstatus').addClass("wpf_active");
        jQuery_WPF('ul#wpf_backend_container li').show();
        jQuery_WPF('#wpf_filter_taskstatus').show();
        jQuery_WPF('#wpf_filter_taskpriority').hide();
    }

    if(filter_type == 'wpf_filter_taskpriority'){
        jQuery_WPF('.wpf_filter_taskpriority').addClass("wpf_active");
        jQuery_WPF('ul#wpf_backend_container li').show();
        jQuery_WPF('#wpf_filter_taskpriority').show();
        jQuery_WPF('#wpf_filter_taskstatus').hide();
    }


}

jQuery_WPF(document).ready(function(){
    jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]').click(function(){
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').hide();
        var wpf_task_status = [];
        jQuery_WPF.each(jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]:checked'), function(){
            jQuery_WPF('.wpf_container.wpf_active_filter .'+jQuery_WPF(this). val()).show();
            wpf_task_status.push(jQuery_WPF(this).val());
        });
        if (wpf_task_status.length === 0) {
            jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
        }
    });

    jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]').click(function(){
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').hide();
        var wpf_task_priority = [];
        jQuery_WPF.each(jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]:checked'), function(){
            jQuery_WPF('.wpf_container.wpf_active_filter .'+jQuery_WPF(this). val()).show();
            wpf_task_priority.push(jQuery_WPF(this).val());
        });
        if (wpf_task_priority.length === 0) {
            jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
        }
    });


    jQuery_WPF('#wpf_filter_taskstatus .wpf_sidebar_filter_reset_task_status').click(function(){
        jQuery_WPF('#wpf_sidebar_filter_task_status input[name="wpf_filter_task_status"]:checked').prop('checked',false);
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
    });
    jQuery_WPF('#wpf_filter_taskpriority .wpf_sidebar_filter_reset_task_priority').click(function(){
        jQuery_WPF('#wpf_sidebar_filter_task_priority input[name="wpf_filter_task_priority"]:checked').prop('checked',false);
        jQuery_WPF('.wpf_container.wpf_active_filter ul li').show();
    });

});
/*END Code for Fiter task in sidebar*/

function wpf_is_valid_url(string) {
    regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    if (regexp.test(string))
    {
        return true;
    }
    else
    {
        return false;
    }
};

function wpf_is_valid_video_url(string) {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
    var match = string.match(regExp);
    if (match && match[2].length == 11) {
        return '<iframe width="100%" height="120" src="https://www.youtube.com/embed/' + match[2] + '" type="text/html" width="500" height="265" frameborder="0" allowfullscreen></iframe>';
    }else{
        return string;
    }

}

function wpf_relative_position(old_coordinates,old_size,new_size) {
    var percent_left = (old_coordinates['left']*100)/old_size['width'];
    var percent_top = (old_coordinates['top']*100)/old_size['height'];

    var new_coordinates = [];
    new_coordinates['left'] = (percent_left*new_size['width'])/100;
    new_coordinates['top'] = (percent_top*new_size['height'])/100;

    return new_coordinates;
}

function wpf_update_bubble_position(task_id,task,left,top) {

    var height = jQuery_WPF('#wpf_graphics_img').height();
    var width = jQuery_WPF('#wpf_graphics_img').width();

    var task_info = [];
    task_info['task_id'] = task_id;
    task_info['task_top']=top;
    task_info['task_left']=left;
    task_info['task_element_height']=height;
    task_info['task_element_width']=width;

    var task_info_obj = jQuery_WPF.extend({}, task_info);

    jQuery_WPF.ajax({
        method : "POST",
        url : ajaxurl,
        data : {action: "wpf_graphics_update_task_coordinates",wpf_nonce:wpf_nonce,task_info:task_info_obj},
        success : function(data){
        }
    });
}

function wpf_remove_login_to_clipboard(comment_count,type){
    const params = getParams(window.location.href);
    if(document.getElementById('wpf_remove_login_task_link'+comment_count).checked) {
        if(type == 'general'){
            var task_link = current_page_url + '?id=' + params['id'] + '&wpf_general_taskid='+comment_count;
            jQuery_WPF(document).find('#wpf_share_link_'+comment_count).val(task_link);
            jQuery_WPF(document).find('.wpf_task_link_'+comment_count).text(task_link);
        }else{
            var task_link = current_page_url + '?id=' + params['id'] + '&wpf_taskid='+comment_count;
            jQuery_WPF('#sharetasklink-'+comment_count+' input[type="text"]').val(task_link);
            jQuery_WPF(document).find('.wpf_task_link_'+comment_count).text(task_link);
        }

    }else{
        if(type == 'general'){
            var task_link = current_page_url + '?id=' + params['id'] + '&wpf_general_taskid='+comment_count+'&wpf_login=1';
            jQuery_WPF(document).find('#wpf_share_link_'+comment_count).val(task_link);
            jQuery_WPF(document).find('.wpf_task_link_'+comment_count).text(task_link);
        }else{
            var task_link = current_page_url + '?id=' + params['id'] + '&wpf_taskid='+comment_count+'&wpf_login=1';
            jQuery_WPF('#sharetasklink-'+comment_count+' input[type="text"]').val(task_link);
            jQuery_WPF(document).find('.wpf_task_link_'+comment_count).text(task_link);
        }
    }
}