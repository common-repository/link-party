<?php

if( $_GET['action'] == 'delete_submission' ) {
	illi_delete_submission($_GET['id']);
	
}

function illi_redirect_list() {
		$redirects = get_illi_redirects();
		$total_redirects = count($redirects);
                
		$style = '';

		foreach($redirects as $redirect) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
	
         		$redirect_data=get_single_redirect($redirect);
                        $partyID=get_partyID($redirect);
                        $SubmissionCount=illi_count_submissions($partyID['ID']);

			echo "\n\t";



			echo "<tr id='redirect-" . $redirect_data['ID'] . "'" . $style . ">";

//echo "<td class='column-role'>" . ucwords($partyID['ID']) . "</td>";


//Shows the ID, then it places a delete button below that
echo "<td class='column-role'>" . ucwords($partyID['ID']);
echo '<div class="row-actions">';
			echo '<span class="delete">';

//echo "<td class='column-role'>" . ucwords($partyID['ID']) . "</td>";


			echo "<a class=\"submitdelete\" onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this Link Party '%s'\n  'Cancel' to stop, 'OK' to delete."), $redirect_data['pagedef'] )) . "') ) { return true;}return false;\" href=\"?page=illi3/includes/admin.php&amp;action=delete&amp;rl_id=" . $partyID["ID"] . "\">Delete</a>"; 
			echo '</span>';


echo "</td>";


//Show the Name in BOLD, then the description below it

$idd=$partyID['ID'];
echo "<td class='column-redirect'><b>" . ucwords($partyID['PartyName']) . "</b><br />" . ucwords($partyID['Description']) . ' [linkparty id="' . $idd . '"] </td>';



			echo "</div>";
			echo "</td>";
			
//add new cell
            echo "<td class='column-role'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $SubmissionCount['Total'] . "</td>";
echo "<td class='column-role'>" . ucwords($partyID['PostDate']) . "</td>";
            echo "<td class='column-role'>" . ucwords($partyID['SubmissionEnd']) . "</td>";


			echo "</tr>";
		}
}

function get_illi_redirects() {
     global $wpdb;
     $links = $wpdb->get_col( "SELECT ID FROM " . $wpdb->prefix . "illi_Parties" );
     return $links;
}

function delete_single_submission($id) {
     global $wpdb;
     $query = 'Delete From ' . $wpdb->prefix . 'illi_Submissions WHERE ID=' . $id;
     $wpdb->query( $wpdb->prepare( $query));

	//return $sredirect;
}

function get_single_redirect($rlid) {
	global $wpdb;
	$query = 'SELECT * FROM ' . $wpdb->prefix . 'illi_Parties WHERE ID=' . $rlid;
	$sredirect = $wpdb->get_row($query, ARRAY_A);

	return $sredirect;
}

function get_PartyID($rlid) {
	global $wpdb;
	$query = 'SELECT * FROM ' . $wpdb->prefix . 'illi_Parties WHERE ID=' . $rlid;
	$sredirect = $wpdb->get_row($query, ARRAY_A);
	return $sredirect;
}

function illi_create_new_redirect($page,$role) {
	global $wpdb;
	$query = 'INSERT INTO ' .
			$wpdb->prefix . 'illi_Parties
			( pagedef, illidef )
			VALUES (%s, %s)';
	$wpdb->query( $wpdb->prepare( $query,$page,$role));
}

function illi_delete_redirect($rlid) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'illi_Parties WHERE ID=%s';
	$wpdb->query( $wpdb->prepare( $query, $rlid ) );
}

function illi_delete_submission($id) {
	global $wpdb;
	$query = 'DELETE FROM ' . $wpdb->prefix . 'illi_Submissions WHERE ID=%s';
	$wpdb->query( $wpdb->prepare( $query, $id ) );
}

function illi_count_submissions($rlid) {
	global $wpdb;
	$query = 'SELECT COUNT( ID ) AS Total
FROM ' . $wpdb->prefix . 'illi_Submissions WHERE PartyID=' . $rlid;
	$total = $wpdb->get_row($query, ARRAY_A);
//echo "counting " . $rlid;
	return $total;
}

function illi_submission_end($id) {
	global $wpdb;
        $enddate =  $wpdb->get_var('SELECT SubmissionEnd FROM ' . $wpdb->prefix . 'illi_Parties WHERE ID=' . $id );
        return $enddate;
}

function illi_party_name($id) {
	global $wpdb;
        $partyname =  $wpdb->get_var('SELECT PartyName FROM ' . $wpdb->prefix . 'illi_Parties WHERE ID=' . $id );
        return $partyname;
}


function illi_create_new_party($illiname,$illidescription,$illienddate) {
	global $wpdb;

$query = "SELECT PartyName FROM " . $wpdb->prefix . "illi_Parties WHERE PartyName='" . $illiname . "'";
$select_result = mysql_query($query);
if (!mysql_num_rows($select_result)) {
$query = 'INSERT INTO ' .
			$wpdb->prefix . 'illi_Parties
			( PartyName, PostDate, Description, SubmissionEnd )
			VALUES (%s, CURRENT_TIMESTAMP, %s, %s)';
$wpdb->query( $wpdb->prepare( $query,$illiname, $illidescription,$illienddate));
//$insert_result = mysql_query($query);
$id = mysql_insert_id();
}
else {
$row = mysql_fetch_assoc($select_result);
$id = $row['id'];
}
return $id;
}


function illi_create_new_submission($id, $SubmissionName,$SubmissionDescription,$htmllink, $imagelink) {
	global $wpdb;

$query = "SELECT PartyName FROM " . $wpdb->prefix . "illi_Parties WHERE PartyName='Sweet'";
$select_result = mysql_query($query);
if (!mysql_num_rows($select_result)) {
$query = 'INSERT INTO ' .
			$wpdb->prefix . 'illi_Submissions
			( PartyID, CreateDate, PartyName, htmllink, imagelink, Description )
			VALUES (%s, CURRENT_TIMESTAMP, %s, %s, %s, %s)';
$wpdb->query( $wpdb->prepare( $query,$id, $SubmissionName, $htmllink, $imagelink, $SubmissionDescription));
//$insert_result = mysql_query($query);
$id = mysql_insert_id();
}
else {
$row = mysql_fetch_assoc($select_result);
$id = $row['id'];
}
 echo '<script>parent.window.location.reload(true);</script>';
return $id;
}


//Full code copied from dreamweaver

//This function runs the actually gallery function but returns a return and not echos.
function illi_gallery_string($atts){

  ob_start();
  illi_gallery($atts);
  $outputstring= ob_get_clean();

  return $outputstring;
}

function illi_gallery($atts) {

// Attributes
	extract( shortcode_atts(
		array(
			'id' => '1',
		), $atts )
	);

	// Code
	//return '';

//echo "atts: " . $id;
//echo "<br />";

/*
global $wpdb;
$query = "SELECT PartyName FROM " . $wpdb->prefix . "illi_Parties WHERE ID='" . $id . "'";
$party_name = mysql_query($query);
*/



$submissionlist= get_illi_submissionlist($id);
$end_date = illi_submission_end($id);
$party_name = illi_party_name($id);

$subcount = count($submissionlist);
//echo 'Here is my great link party';
echo '<h2>' . $party_name . '</h2>';
//echo '<br />';
if ($subcount == 0) {
echo 'There are no submissions yet. Be the first and add yours below!';


}



//echo count($submissionlist);
        $new_row=0;
  //makes the first tr code  
         
        echo '<table width="400" border="0" cellpadding="4" summary="sss">'; 
        echo "<tr>";

 foreach($submissionlist as $submissionlist2) {
    $new_row=$new_row + 1;
    
    //if the row is one more than a 4 then a new row begins
 if ($new_row %4 == 1)
  {
 // echo "<tr>";
  
  }
        
$ream= $new_row %4;	
    
    //get the info for a single submission
			$full_submission=get_single_submission($submissionlist2);
     $sub_id=ucwords(ucwords($full_submission['ID']));
     $sub_name=ucwords(ucwords($full_submission['PartyName']));
     $sub_html=ucwords(ucwords($full_submission['HtmlLink']));
     $sub_image=ucwords(ucwords($full_submission['ImageLink']));
     $sub_desc=ucwords(ucwords($full_submission['Description']));



echo '<td><center><a href="' . $sub_html . '"><img class="size-full wp-image-18 aligncenter" title="' . $sub_desc . '" alt="1" src="' . $sub_image . '" height="125" /></a>';


if ( current_user_can('moderate_comments') ) {

  echo '<span class="delete">';

  echo "<a class=\"submitdelete\" onclick=\"if ( confirm('" . esc_js(sprintf( __("You are about to delete this Link Party '%s'\n  'Cancel' to stop, 'OK' to delete."), $sub_name )) . "') ) { return true;}return false;\" href=\"?page=illi3/includes/functions.php&amp;action=delete_submission&amp;id=" . $sub_id . "\">X </a>"; 
			echo '</span>';


} 


echo $new_row . '. ' . $sub_name . '</center></td>';


 //Every 4 cells is a new row.  If the cell is divisible by 4
 //it ends the row, if it isnt, it increasese the number   
    
       
   if ($new_row %4 == 0)
      {
        echo "</tr>";
      }
     
}
echo '</tr>';
echo '</table>';


//echo 'Yo Dawg!';
//echo time() . "  " . strtotime($end_date);

//}

//start show hide

if ($new_row > 21){
if (get_option('users_can_register') == '1') {
echo '<center>Link Party Powered by <a href="http://www.illistyle.com"> illi Style</a></center>';
}
}
//date check
if (time() > strtotime($end_date)) {
echo 'Submissions have ended.';
}
else{
?>

<script language="javascript"> 
function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "Add a Link to the Party!";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
} 
</script>
<a id="displayText" href="javascript:toggle();">add your link!</a>

<?php
/*
if ($new_row > 21){
echo '<center>Link Party Powered by <a href="http://www.illistyle.com"> illi Style</a></center>';
}
*/
?>


<div id="toggleText" style="display: none">



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

illi_create_new_submission($id, $product_title, $product_url, $product_btn_text, $path);


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
        <td><input type="text" name="title" value="Name" /></td>
    </tr>
    <tr>
        <td>Submission Description</td>
        <td><input type="text" name="url" value="Description" /></td>
    </tr>
    <tr>
        <td>Html Link</td>
        <td><input type="url" name="btn_text" value="http://" /></td>
    </tr>

</table>
    <input type="file" name="image_file" />



    <input type="submit" align="right" value="Submit" />
</form>

 <?php }?>



</div>

<?php

//end show hide

}

}



//Code to get data from submissions


function get_single_submission($rlid) {
	global $wpdb;
	$query = 'SELECT * FROM ' . $wpdb->prefix . 'illi_Submissions WHERE ID=' . $rlid;
	$submission1 = $wpdb->get_row($query, ARRAY_A);
	return $submission1;
}

function get_illi_submissionlist($partyID) {
     global $wpdb;
     $sublist = $wpdb->get_col( 'SELECT ID FROM ' . $wpdb->prefix . 'illi_Submissions Where partyID=' . $partyID . ' order by id' );
     return $sublist;
}



?>