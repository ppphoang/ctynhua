<?php  
 
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

	ini_set('memory_limit', '1024M');

	global $wpdb;

	

if ( (is_user_logged_in() && get_option('sp_cu_user_require_login_download') == 1 ) or (get_option('sp_cu_user_require_login_download') == '' or get_option('sp_cu_user_require_login_download') == 0 )){

do_action('wp_cdm_download_before');


$file_decrypt = base64_decode($_GET['fid']);
$file_arr = explode("|",$file_decrypt);

#print_r($file_arr);
$fid = $file_arr[0];
$file_date = $file_arr[1];
$file_name = $file_arr[2];
if(!is_numeric($fid)){header("HTTP/1.0 404 Not Found");exit;}
if(class_exists('cdmProductivityLog')){

$cdm_log = new cdmProductivityLog;	

$cdm_log->add($fid,$current_user->ID);	

}



	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$wpdb->escape($fid)."' AND date = '".$wpdb->escape($file_date)."'  AND file = '".$wpdb->escape($file_name)."' order by date desc", ARRAY_A);
#print_r($r);exit;


	if(count($r) == 0){header("HTTP/1.0 404 Not Found");exit;}

$r_rev_check = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where parent= '".$r[0]['id']."'  order by date desc", ARRAY_A);

if(count($r_rev_check) > 0 && $_GET['original'] == ''){



unset($r);

$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix."sp_cu   where id= '".$r_rev_check[0]['id']."'  order by date desc", ARRAY_A);

}


do_action('sp_download_file_after_query', $r);




if(get_option('sp_cu_js_redirect') == 1){

$file = ''.SP_CDM_UPLOADS_DIR_URL.''.$r[0]['uid'].'/'.$r[0]['file'].'';	

	echo '<script type="text/javascript">

<!--

window.location = "'.$file.'"

//-->

</script>';

exit;

}else{



$file = ''.SP_CDM_UPLOADS_DIR.''.$r[0]['uid'].'/'.$r[0]['file'].'';






// grab the requested file's name

$file_name = $file ;



// make sure it's a file before doing anything!

if(is_file($file_name))

{



  /*

    Do any processing you'd like here:

    1.  Increment a counter

    2.  Do something with the DB

    3.  Check user permissions

    4.  Anything you want!

  */



  // required for IE

  if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');  }



 $mime = mime_content_type($file_name);


 

 if($_GET['thumb'] == 1){

	 header('Content-Type: '.$mime); 

  readfile($file_name,filesize($filename));    // push it out

  exit(); 

 }else{
  set_time_limit(0); 

smartReadFile($file_name,basename($file_name),$mime);

exit(0);

 }

}









}

}else{

	auth_redirect();	

	

}

?>