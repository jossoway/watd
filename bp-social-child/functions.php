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

?>

