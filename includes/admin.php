<?php
require_once('functions.php');
if( $_POST[ 'legacy-action' ] == 'update' ) {
    // Read their posted value
	$opt_user = $_POST[ $data_field_name ];
    // Save the posted value in the database
	update_option( 'plugin_illi_default', $opt_val );
    // Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_POST['action'] == 'add' ) {
	// Read posted values
	$update_rlpage = $_POST['redirect'];
	$update_rlrole = $_POST['role'];
	// Save the posted value
	illi_create_new_redirect($update_rlpage,$update_rlrole);
	// Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Redirect created.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_POST['action'] == 'update' ) {
	$update_rlregister = $_POST['users_can_register'];
	$update_rldefault = $_POST['default_role'];
	$update_legacy = $_POST['use_legacy'];
	if($update_legacy == '1')
		$update_legacy = "true";
	update_option('users_can_register', $update_rlregister);
	update_option('default_role', $update_rldefault);
	update_option('illi_Use_Legacy', $update_legacy);
	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}
if( $_GET['action'] == 'delete' ) {
	illi_delete_redirect($_GET['rl_id']);
	?>
	<div class="updated"><p><strong><?php _e('Party deleted.', 'rl_trans_domain'); ?></strong></p></div>
	<?php
}

if( $_GET['action'] == 'delete_submission' ) {
	illi_delete_submission($_GET['id']);
	
}

if( $_POST['action'] == 'newparty' ) {
	// Read posted values
	$add_partyname = $_POST['PartyName'];
	$add_partydescription = $_POST['PartyDescription'];
        $add_partyend = $_POST['SubmissionEnd'];
	// Save the posted value
	illi_create_new_party($add_partyname, $add_partydescription, $add_partyend);
	// Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e(' New Party created.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}


if( $_POST['action'] == 'newsubmissionX' ) {
	// Read posted values
	$add_submissionname = $_POST['SubmissionName'];
	$add_submissiondescription = $_POST['SubmissionDescription'];
        $add_submissionhmtl = $_POST['htmllink'];
        $add_submissionimage = $_POST['imagelink'];
	// Save the posted value
 	illi_create_new_submission($add_submissionname, $add_submissiondescription, $add_submissionhmtl, $add_submissionimage);
echo "shouldnt be here";
	// Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e(' New Submission created.', 'rl_trans_domain' ); ?></strong></p></div>
	<?php
}


// Display the options screen
echo '<div class="wrap">';
echo '<h2>Link Party! Settings</h2>';
?>

<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col" id="redirect" class="manage-column column-posts" style="">Party ID</th>
        <th scope="col" id="middle" class="manage-column column-redirect" style="">Party Name and Description</th>
        <th scope="col" id="count" class="manage-column column-role" style="">Submissions</th>
        <th scope="col" id="start" class="manage-column column-role" style="">Submission Start</th>
	<th scope="col" id="role" class="manage-column column-role" style="">Submission End</th>
</tr>
</thead>

<tfoot>
<tr class="thead">
	<th scope="col" id="redirect" class="manage-column column-posts" style="">Party ID</th>
        <th scope="col" id="middle" class="manage-column column-redirect" style="">Party Name and Description</th>
        <th scope="col" id="count" class="manage-column column-role" style="">Submissions</th>
        <th scope="col" id="start" class="manage-column column-role" style="">Submission Start</th>
	<th scope="col" id="role" class="manage-column column-role" style="">Submission End</th>
</tr>
</tfoot>
<tbody id="illi" class="list">
<?php
illi_redirect_list();
?>
</tbody>
</table>


<hr />
<h2>Add New Party</h2>
<form method="post">
<input type="hidden" name="action" value="newparty" />
<table class="form-table" cellspacing="0">
<tbody class="list">
<tr>
<td>Party Name:  <input type="text" name="PartyName" value="" /></td>
</tr>
<tr>
<td>Party Description:     <input type="text" name="PartyDescription" value="" style="width:400px;"/></td>
</tr>
<tr>
<td>Submission End Date:  <input type="datetime-local" name="SubmissionEnd" value="" /></td>
</tr>

<td class="submit" style="width:10%;"><input type="submit" value="Create New Party" /></td>
</tr>
</tbody>
</table>
</form>
<!--
<form method="post">
<input type="hidden" name="action" value="update" />
-->

<!--
<h3>Add New Submission</h3>
<form method="post">
<input type="hidden" name="action" value="newsubmissionX" />
<table class="form-table" cellspacing="0">
<tbody class="list">
<tr>
<td>Submission Name:<input type="text" name="SubmissionName" value="" /></td>
</tr>
<tr>
<td>Submission Description:<input type="text" name="SubmissionDescription" value="" style="width:400px;"/></td>
</tr>
<tr>
<td>Submission Link:<input type="url" name="htmllink" value="" style="width:400px;"/></td>
</tr>
<tr>
<td>Submission Link:<input type="url" name="imagelink" value="" style="width:400px;"/></td>
</tr>


<td class="submit" style="width:10%;"><input type="submit" value="Create New Party" /></td>
</tr>
</tbody>
</table>
</form>
-->

<!--
****New Submission Content****
 <?
if ('POST' == $_SERVER['REQUEST_METHOD'])
 {
global $wpdb;

$product_title = $_POST['title'];
$product_url = $_POST['url'];
$product_btn_text = $_POST['btn_text'];

// CREATE UNIQUE NAME FOR IMAGE
$remote_addr = $_SERVER['REMOTE_ADDR'];
$time = time();
$new_name = $remote_addr;
$new_name .= $time;
//echo $new_name;

// IMAGE UPLOAD

$upload_dir = ABSPATH;

$upload_dir = $upload_dir . "/wp-content/plugins/illi3/images/";


echo "<br />";


if (file_exists($upload_dir) && is_writable($upload_dir)) {
//        echo "<br /> Directory exists and is fine.... <br />";


$image_info = getimagesize($_FILES["image_file"]["tmp_name"]);
$image_width = $image_info[0];
$image_height = $image_info[1];

echo "<br /> width: " . $image_width . " height: " . $image_height . "<br />";

if(($image_width / $image_height != 1) || ($image_width > 1000)) {
echo "Images must be square and less than 1000x1000.  e.g. 125x125, 200x200";
}
else{
echo "good image";


$uploadedfile = $_FILES['image_file']['name'];
$extension = explode(".", $uploadedfile);
$extensiontype = $extension['1'];
$finallocation = $path . $newname;


$path = site_url() . "/wp-content/plugins/illi3/images/" . $new_name . "." . $extensiontype;
//echo "<br /> path: " . $path;


$target_path = $upload_dir;
$target_path = $target_path . $new_name . "." . $extensiontype; 


if(move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
      //  echo "The file ".  basename( $_FILES['image_file']['name']). 
        " has been uploaded <br />";
} else{
    echo "There was an error uploading the file, please try again! <br />";

}

echo '<img src="' . $path . '" alt="' . $target_path . '">';
$product_img = $new_name.'.'.$extensiontype;


//ADD TO DATABASE

illi_create_new_submission($product_title, $product_url, $product_btn_text, $path);


echo "<br />You have successfully added "  .$product_title.   " to the link party";

}
}
//above
else {
        echo "Upload directory is not writable, or does not exist... <br />" . $upload_dir;
}


} else { ?> 

 <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
<table border="0" bordercolor="none" width="50%" cellpadding="0" cellspacing="0">
    <tr>
        <td>Submission Name:</td>
        <td><input type="text" name="title" value="product title" /></td>
    </tr>
    <tr>
        <td>Submission Description</td>
        <td><input type="text" name="url" value="product url" /></td>
    </tr>
    <tr>
        <td>Html Link</td>
        <td><input type="url" name="btn_text" value="Get your copy" /></td>
    </tr>

</table>
    <input type="file" name="image_file" />



    <input type="submit" value="Submit" />
</form>

 <?php }?>

-->
<form method="post">
<input type="hidden" name="action" value="update" />

<hr /><h2>General Options</h2>
<p class="description">This plugin is supported by donations.  Please support so we can make some sweet updates.  We also place a "powered by illi Style" link on parties over 20 submissions. If you would prefer not to have this, uncheck the box below. </p>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('I support Link Party!') ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php _e('Membership') ?></span></legend><label for="users_can_register">
<input name="users_can_register" type="checkbox" id="users_can_register" value="1" <?php checked('1', get_option('users_can_register')); ?> />
<?php _e('Supporter!') ?></label>
</fieldset></td>
</tr>
<!--
<tr valign="top">

<th scope="row"><label for="default_role"><?php _e('New User Default Role') ?></label></th>
<td>
<select name="default_role" id="default_role"><?php wp_dropdown_roles( get_option('default_role') ); ?></select>
</td>
</tr>
-->

</table>
<p class="submit"><input type="submit" value="Save" /></p>
</form>


