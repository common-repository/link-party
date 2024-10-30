<?php
require_once(RL_BASE_INC . '/functions.php');

/**
 * The following code creates our admin menu and lets users specify options
 *
 */
add_action('admin_menu', 'rl_add_pages');
function rl_add_pages() {
    add_submenu_page('options-general.php','illi Settings', 'Link Party!', 8, RL_BASE_INC . '/admin.php');
}

/**  
  *  The following sets of functions are the 'new' core to the illi plugin.
  */

define('illi_REWRITERULES', '1');								// flag to determine if plugin can change WP rewrite rules
define('illi_QUERYVAR', 'illi');							// get/post variable name for querying tag/keyword from WP
define('illi_META', 'regpage');									// post meta key used in the wp database
define('illi_TEMPLATE', '');									// template file to use for registration pages - should be NULL

function illi_init() {
    global $wp_rewrite;
    
    /* Shouldn't need to change this - can set to 0 if you want to force permalinks off */
    if (isset($wp_rewrite) && $wp_rewrite->using_permalinks()) {
        define('illi_REWRITEON', '1');							// turn on nice permalinks
        define('illi_LINKBASE', $wp_rewrite->root);				// set to "index.php/" if using that style
    } else {
        define('illi_REWRITEON', '0');							// old school links
        define('illi_LINKBASE', '');							// don't need this
    }

    /* generate rewrite rules for above queries */
    if (illi_REWRITEON && illi_REWRITERULES)
        add_filter('search_rewrite_rules', 'illi_createRewriteRules');
}
add_action('init','illi_init');
add_shortcode('linkparty', 'illi_gallery_string');  

function illi_createRewriteRules($rewrite) {
	global $wp_rewrite;
	
	// add rewrite tokens
	$keytag_token = '%' . illi_QUERYVAR . '%';
	$wp_rewrite->add_rewrite_tag($keytag_token, '(.+)', illi_QUERYVAR . '=');
    
	$illi_structure = $wp_rewrite->root . illi_QUERYVAR . "/$keytag_token";
	$illi_rewrite = $wp_rewrite->generate_rewrite_rules($illi_structure);
	
	return ( $rewrite + $illi_rewrite );
}

function is_regpage() {
    global $wp_version;
    $regpage = ( isset($wp_version) && ($wp_version >= 2.0) ) ? 
                get_query_var(illi_QUERYVAR) : 
                $GLOBALS[illi_QUERYVAR];
	if (!is_null($regpage) && ($regpage != ''))
		return true;
	else
		return false;
}

add_filter('query_vars', 'illi_addQueryVar');
add_action('parse_query', 'illi_parseQuery');

function illi_addQueryVar($wpvar_array) {
	$wpvar_array[] = illi_QUERYVAR;
	return($wpvar_array);
}

function illi_parseQuery() {
	// if this is a keyword query, then reset other is_x flags and add query filters
	if (is_regpage()) {
		global $wp_query;
		$wp_query->is_single = false;
		$wp_query->is_page = false;
		$wp_query->is_archive = false;
		$wp_query->is_search = false;
		$wp_query->is_home = false;
		
//		add_filter('posts_where', 'illi_postsWhere');
//		add_filter('posts_join', 'illi_postsJoin');
		add_action('template_redirect', 'illi_redirect');
	}
}

function illi_postsWhere($where) {
    global $wp_version;
    $regpage = ( isset($wp_version) && ($wp_version >= 2.0) ) ? 
                get_query_var(illi_QUERYVAR) : 
                $GLOBALS[illi_QUERYVAR];

    $where .= " AND wp_meta.meta_key = '" . illi_META . "' ";
	$where .= " AND wp_meta.meta_value LIKE '%" . $regpage . "%' ";

    $where = str_replace(' AND (post_status = "publish"', ' AND ((post_status = \'static\' OR post_status = \'publish\')', $where);
    
	return ($where);
}

function illi_postsJoin($join) {
	global $wpdb;
	$join .= " LEFT JOIN $wpdb->postmeta AS jilli_meta ON ($wpdb->posts.ID = jilli_meta.post_id) ";
	return ($join);
}

function illi_redirect() {
	if (is_regpage()) {
		illi_start_session();		
		require('./wp-load.php');
		wp_redirect('wp-login.php?action=register');
	}
	return;
}

function illi_start_session() {
	global $wpdb;
	$regpage = get_query_var(illi_QUERYVAR);
	$rlquery = "SELECT illidef FROM " . $wpdb->prefix . "illi WHERE pagedef = '" . $regpage . "'";
	$rlevel = $wpdb->get_var($rlquery);
	setcookie('illi',$rlevel,time()+600);	
}

function illi_promote($user_ID) {
	if(isset($_COOKIE['illi'])) {
		$illi_level = $_COOKIE['illi'];
		$update = 'promote';
		$role=$illi_level;
		$user = new WP_User($user_ID);
		$user->set_role($illi_level);
	}
	setcookie('illi','',time()-600);
	return $user_ID;	
}

add_action ( 'user_register', 'illi_promote' );
?>