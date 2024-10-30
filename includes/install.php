<?php
global $wpdb;
$table_name = $wpdb->prefix . "illi";
$table_name_Parties = $wpdb->prefix . "illi_Parties";
$table_name_Submissions = $wpdb->prefix . "illi_Submissions";


/*
$sql3 = "CREATE TABLE tester (
	  ID int(11) NOT NULL AUTO_INCREMENT,
	  pagedef varchar(50) NOT NULL,
	  illidef varchar(55) NOT NULL,
	  UNIQUE KEY ID (ID)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql3);
*/



if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  ID int(11) NOT NULL AUTO_INCREMENT,
	  pagedef varchar(50) NOT NULL,
	  illidef varchar(55) NOT NULL,
	  UNIQUE KEY ID (ID)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      add_option("illi_DB_Version", "1.0");
}


//Create LinkPartyParties Table
if($wpdb->get_var("show tables like '$table_name_Parties'") != $table_name_Parties) {
      
      $sql = "CREATE TABLE " . $table_name_Parties . " (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PartyName` varchar(255) DEFAULT NULL,
 `PostDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `Description` varchar(255) DEFAULT NULL,
 `SubmissionEnd` datetime DEFAULT NULL,
 PRIMARY KEY (`ID`)
   );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

   
}

//Create image directory
$upload_dir = $upload_dir . "/wp-content/plugins/illi3/images/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

//Create LinkPartySubmissions
if($wpdb->get_var("show tables like '$table_name_Submissions'") != $table_name_Submissions) {
      
      $sql = "CREATE TABLE " . $table_name_Submissions . " (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PartyID` int(11) NOT NULL,
 `CreateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `PartyName` varchar(255) DEFAULT NULL,
 `HtmlLink` varchar(255) DEFAULT NULL,
 `ImageLink` varchar(255) DEFAULT NULL,
 `Description` varchar(255) DEFAULT NULL,
 `Approved` int(11) DEFAULT NULL,
 `LinkOrder` int(11) DEFAULT NULL,
 PRIMARY KEY (`ID`)
) ;";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

}


?>