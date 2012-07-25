<?php 
function custom_adminbar_li_button() {
if (!is_user_logged_in()) {
echo '<li class="bp-login no-arrow"><a href="/">Log In</a></li>
<li class="bp-signup no-arrow"><a href="/register/">Sign up</a></li>';
}
}

function remv_bp_adminbar_li(){

remove_action('bp_adminbar_menus', 'bp_adminbar_login_menu', 2);
add_action('bp_adminbar_menus', 'custom_adminbar_li_button', 2);
}
add_action('wp_footer','remv_bp_adminbar_li',1);


add_filter("xprofile_group_fields","bpdev_filter_profile_fields_by_usertype",10,2);
function bpdev_filter_profile_fields_by_usertype($fields,$group_id){

    //only disable these fields on edit page
    if(!bp_is_profile_edit())
        return $fields;

    //please change it with the name of fields you don't want to allow editing
   $field_to_remove=array("Name");

   $count=count($fields);
   $flds=array();
   for($i=0;$i<$count;$i++){
    if(in_array($fields[$i]->name,$field_to_remove))
            unset($fields[$i]);

    else
        $flds[]=$fields[$i];//doh, I did not remember a way to reset the index, so creating a new array
    }
    return $flds;

}

// Use different functions if you want to modify question and answer editors differently
add_filter( 'qa_question_editor_settings', 'my_modify_editor_function', 10, 2 );
add_filter( 'qa_answer_editor_settings', 'my_modify_editor_function', 10, 2 );
function my_modify_editor_function( $settings, $ID ) {
	// Dont use visual editor for visitors
	if ( !is_user_logged_in() ) {
		$tinymce = false;
	}
	// Add some tinyMCE plugins and buttons for admins. Don't forget to check if they work in your theme.
	else if ( current_user_can( 'manage_options' ) ) {
		$tinymce['plugins']='inlinepopups,tabfocus,paste,media,fullscreen';
		$tinymce['theme_advanced_buttons2']='pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,undo,redo';
	}
	// Use minimal editor for other roles
	else {
		$tinymce['teeny'] = true;
	}
	// Make something special for question #1 and answer #1
	if ( 1 == $ID )
		$tinymce['theme_advanced_buttons1']='formatselect,forecolor,|,bold,italic,underline';

	$settings['tinymce'] = $tinymce;

	return $settings;
}

?>