<?php
/*
Plugin Name: iDonate.ie Donate Now
Description: Donate Now lets you add an online donation facility to you website. Powered by iDonate.ie. No SSL required. All donations processed securely by iDonate.ie. Available to iDonate members only.
Version: 2.0
Author: iDonate.ie
License: GPLv2 or later
*/

function dnp_donatenw_activation() {
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	//require_once( ABSPATH . 'wp-content/plugins/donate-now/donateData.js');
    // require_once( ABSPATH . 'wp-content/plugins/donate-now/donate.css');
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'donate_form';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		
		 image_url	varchar(255),
		 charityId	varchar(255),
		 url_vat	 varchar(255),
	 fixed_amount  varchar(255),
	 fmgy  varchar(255),
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		
		UNIQUE KEY id (id)
	) $charset_collate;";


	dbDelta( $sql );

}
register_activation_hook(__FILE__, 'dnp_donatenw_activation');

 
function dnp_add_div(){
	
	//echo '<div><a href = "javascript:void(0)" onclick = "closeAll">vikashere</a><div id="light_donate" class="white_content_donate"></div></div><div id="fade_donate" class="black_overlay_donate"></div>';
	echo '
<div id="popup1" class="overlay">
	<div class="popup" >
		<a class="close" href="#">×</a>	
		<div class="content" id="light_donate">
			Please wait popup is loading...
		</div>
	</div>
</div>';
	

}
add_action('wp_head', 'dnp_add_div');

add_shortcode("donate-now","dnp_my_n_forms_handler");

function dnp_my_n_forms_handler($atts,$content=null){
	global $wpdb;
    extract( shortcode_atts( array( 
        'id_page_key' => '',
         ), $atts ) );
	
			
		 $lastid2 = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix ."donate_form WHERE id='$id_page_key'");
	
	
	$image_url = $lastid2->image_url; 	
	$charityId = $lastid2->charityId; 
	$vatInfo = $lastid2->url_vat; 
	$fixed_amount = $lastid2->fixed_amount; 
	$fmgy = $lastid2->fmgy;
	$url = plugins_url("donateData.php?charityId=".$charityId."&tax=".$vatInfo."&amount=".$fixed_amount."&type=".$fmgy, __FILE__ );
	//echo get_remote_data($url); 
	
	
	
	/************ Donate link open on new tab **********************/
	$donateFinalURL = 'https://www.idonate.ie/donation_widget/register-donor-anonymous.php?pid=';
	
	$donateFinalURL .= $charityId;
	if($vatInfo == 'no'){
		$donateFinalURL .= "&tax=".$vatInfo;
	}
	if(!empty($fixed_amount)){
		$donateFinalURL .= "&amount=".$fixed_amount;
	}
	if($fmgy=='yes'){
		$donateFinalURL .= "&type=monthly";
	}
	
	$result = '<p><span class="dnpiframelink" style="cursor:pointer;" id="'.$url.'" ><a href="'.$donateFinalURL.'" target="_blank"><img src="'.$image_url.'" width="185px" height="55px"></a></span></p>';
	/************ Donate link open on new tab **********************/
	return $result;
	
	
	
	
	
	

    //return do_shortcode($fmgy);
}

define('WD_FM_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_FM_URL', plugins_url(plugin_basename(dirname(__FILE__))));

require_once( ABSPATH . 'wp-includes/shortcodes.php' );
require_once( ABSPATH . 'wp-includes/pluggable.php' );

function dnp_pagination_donate($query,$per_page=10,$page=1,$url='?'){   
    global $wpdb;

    $row = $wpdb->get_results("SELECT * FROM {$query}");
    $total = $wpdb->num_rows;
    $adjacents = "2"; 
      
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $lastlabel = "Last &rsaquo;&rsaquo;";
      
    $page = ($page == 0 ? 1 : $page);  
    $start = ($page - 1) * $per_page;                               
      
    $prev = $page - 1;                          
    $next = $page + 1;
      
    $lastpage = ceil($total/$per_page);
      
    $lpm1 = $lastpage - 1; // //last page minus 1
      
    $pagination = "";
    if($lastpage > 1){   
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";
              
            if ($page > 1) $pagination.= "<li><a href='{$url}pgid={$prev}&page=manage_fm'>{$prevlabel}</a></li>";
              
        if ($lastpage < 7 + ($adjacents * 2)){   
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if ($counter == $page)
                    $pagination.= "<li><a class='current'>{$counter}</a></li>";
                else
                    $pagination.= "<li><a href='{$url}pgid={$counter}&page=manage_fm'>{$counter}</a></li>";                    
            }
          
        } elseif($lastpage > 5 + ($adjacents * 2)){
              
            if($page < 1 + ($adjacents * 2)) {
                  
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}pgid={$counter}&page=manage_fm'>{$counter}</a></li>";                    
                }
                $pagination.= "<li class='dot'>...</li>";
                $pagination.= "<li><a href='{$url}pgid={$lpm1}&page=manage_fm'>{$lpm1}</a></li>";
                $pagination.= "<li><a href='{$url}pgid={$lastpage}&page=manage_fm'>{$lastpage}</a></li>";  
                      
            } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                  
                $pagination.= "<li><a href='{$url}pgid=1&page=manage_fm'>1</a></li>";
                $pagination.= "<li><a href='{$url}pgid=2&page=manage_fm'>2</a></li>";
                $pagination.= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}pgid={$counter}&page=manage_fm'>{$counter}</a></li>";                    
                }
                $pagination.= "<li class='dot'>..</li>";
                $pagination.= "<li><a href='{$url}pgid={$lpm1}&page=manage_fm'>{$lpm1}</a></li>";
                $pagination.= "<li><a href='{$url}pgid={$lastpage}&page=manage_fm'>{$lastpage}</a></li>";      
                  
            } else {
                  
                $pagination.= "<li><a href='{$url}pgid=1&page=manage_fm'>1</a></li>";
                $pagination.= "<li><a href='{$url}pgid=2&page=manage_fm'>2</a></li>";
                $pagination.= "<li class='dot'>..</li>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
                    else
                        $pagination.= "<li><a href='{$url}pgid={$counter}&page=manage_fm'>{$counter}</a></li>";                    
                }
            }
        }
          
            if ($page < $counter - 1) {
                $pagination.= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
                $pagination.= "<li><a href='{$url}pgid=$lastpage&page=manage_fm'>{$lastlabel}</a></li>";
            }
          
        $pagination.= "</ul>";        
    }
      
    return $pagination;
}

function dnp_options_panel() {
  add_menu_page('Donate-now', 'Donate-now', 'manage_options', 'manage_fm', 'dnp_manage_fm', WD_FM_URL . '/images/donate-16x16.png');

}

add_action('admin_menu', 'dnp_options_panel');


/*===Post Params ====*/

global $msg;
$msg = "";
if(isset($_REQUEST['delete'])) {
    global $wpdb;
	$table_directory_name = $wpdb->prefix .'donate_form';
	$wpdb->delete($table_directory_name, array('id' => $_GET['delete']), array('%d'));
	$msg = "Deleted successfully";
}

if(isset($_REQUEST['wp-submit']))
{
	

	$error='';
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	
	$uploadedfile = $_FILES['pic'];
	
 if(empty($_REQUEST['charityId'])){
	
	 $error = 'Please fill charity ID field.';
	 
 }
 else if(empty($_REQUEST['image_url']) && $uploadedfile['name'] == '' )
 {
	 $error = 'Please Select the Button Image.';
 }
 
 else{
	

	$table_name2 = $wpdb->prefix . 'donate_form';
	$charityId = $_REQUEST['charityId'];
	$url_vat = $_REQUEST['displayvy'];
	$fixed_amount = $_REQUEST['fixed_amount']; 
	$fmgy = $_REQUEST['fmgy']; 
	$image_url = $_REQUEST['image_url'];
	if($image_url != "")
	{
		$img_url = $image_url;
		//goto HERE;
	}
	
	
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	
	$uploadedfile = $_FILES['pic'];
	
	
		
	/*===Save data to table=====*/		
			if($uploadedfile['name'] != ''){
				
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );				
				$img_url = $movefile['url'];			
				$sql_insert ="INSERT INTO $table_name2(charityId, url_vat, fixed_amount, image_url, fmgy) VALUES ('$charityId', '$url_vat', '$fixed_amount', '$img_url', '$fmgy')";

				$new=$wpdb->query($sql_insert);
				$msg = "Created successfully. Check codes below";

			}
	/*===Save data to table=====*/	
	/*===Send domain to iDonate=====*/	
                  $to = 'alan@idonate.ie'; 
                  $subject = 'Widget Created'; 
                  $message = 'Wordpress : ' .ABSPATH; 
                  $headers = 'From: alan@idonate.ie' . "\r\n" . 'Reply-To: alan@idonate.ie' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); 
                  mail($to, $subject, $message, $headers, '-alan@idonate.ie'); 
	/*===Send domain to iDonate=====*/	
}	
 

}

function dnp_manage_fm()

{
global $error;	
global $msg;
 ?>
 
 
 <br/>
        <div class="login">
         <h1> Create a Shortcode / Code Snippet</h1>
<p>This plugin allows you to create various shortcodes and snippets for your wordpress site. 
<br>
<br>
Using the options below, you can fixed the amount shown on donation form, force monthly only option, use different images on a donate button. If you are not eligible for Tax benefits, you may wish to hide this info.
<br>
<br>

<strong>Charity ID</strong> : To find your charity ID , check the URL of your pofile page on iDonate.ie e.g. https://www.idonate.ie/<strong style="color:#02DB00; font-size:16px;">171</strong>_sample-charitys.html -> ID=171</p>
         <form  method="post" action="" enctype="multipart/form-data" name="myForm" onsubmit="return validateForm()">
	<p style="color:red;"><?php echo $msg; ?></p>
	<p>
		<label for="user_login">Charity ID<?php if(!empty($error)) echo "<div style='color:red;'>".$error."</div>"?><br>
		<input type="text"  name="charityId" id="charityId" ></label>
	</p>
	<p>

		<label for="user_pass">Select an option for your donate button<br>
		<?php /*?><input type="radio" name="url_button" id="url_button" checked>
		 Donate Image URL / <?php */?> <input type="radio" name="url_image" id="url_image" >Upload Image<br>
		
		<input type="text" name="image_url" id="image_url" value="" >
		
		<input type="file" name="pic" id="pic" value="" style="display:none;">
		
		
		
		
		</label>
	</p>
	
	
	<p>
		<label for="user_pass">Display TAX info <br>
		
		<input name="displayvy" type="radio" value="yes" checked> Yes 
		<input name="displayvy" type="radio" value="no" > No

		</label>
		
	</p>
	
	<p>
		<label for="user_pass">Force Fixed Amount on Donation Form<br>

		<input type="text" name="fixed_amount" name="fixed_amount" id="fixed_amount"></label>
	</p>
	<p>
		<label for="user_pass">Force Monthly Gift  <br>
		<input name="fmgy" type="radio" value="yes"> Yes 
		<input name="fmgy" type="radio" value="no" checked> No</label>
		
	</p>
	
		
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="buttonp" value="Create Shortcode / Code Snippet">
		
	</p>

</form>
</p>
<div>
</div>
</div>
<br />
<h1>Shortcode / Code Snippets</h1>
<p>Copy Shortcode into Pages created in Wordpress or use Code Snippets in your templates</p>
<table width="100%">
<tr><td colspan="3"></td></tr>
<tr style="background-color:darkseagreen;height: 50px;font-weight: bold;"><td width="15%">Charity ID</td><td width="25%">Short Code</td><td width="50%">Code Snippet</td><td width="10%">Delete</td></tr>  
<?php
global $wpdb;
$page = (int)(!isset($_GET["pgid"]) ? 1 : $_GET["pgid"]);
if ($page <= 0) $page = 1;
$per_page = 10;
$startpoint = ($page * $per_page) - $per_page;
$statement = "`".$wpdb->prefix ."donate_form` ORDER BY `id` ASC";
         /* Get total number of records */
$allresults = $wpdb->get_results("SELECT * FROM  {$statement} LIMIT {$startpoint} , {$per_page}");
         
         $rec_count = 	 $wpdb->num_rows;
         
        

             $i=0;
		foreach ($allresults  as $rows){
			    if($i%2==0){
					$class = 'background-color: aliceblue';
				}else{
					$class = 'background-color: white';
				}
				$image_url = $rows->image_url; 
				$charityId = $rows->charityId; 
				 $vatInfo = $rows->url_vat; 
				 $fixed_amount = $rows->fixed_amount; 
				 $fmgy = $rows->fmgy; 
			$url = plugins_url("donateData.php?charityId=".$charityId."&tax=".$vatInfo."&amount=".$fixed_amount."&type=".$fmgy, __FILE__ );
			
				?>

				<tr style="<?php echo $class;?>">
				<td width="15%"><?php echo $charityId;?></td>
				<td width="25%"><?php echo "[donate-now id_page_key = ".$rows->id."]";?></td>
				<td width="50%">
				<textarea style="margin: 0px; width: 500px; height: 100px;"><span class="iframe" style="cursor:pointer;" id="<?php echo $url;?>" ><img src="<?php echo $image_url; ?>" width="185px" height="55px"></span></textarea></td>
				<td width="10%"><a href="<?php echo admin_url('admin.php'); ?>?page=manage_fm&delete=<?php echo $rows->id; ?>" class="button button-primary button-small">Delete</a></td>
			</tr> 

				<?php
				$i++;
		 }
		 ?>
		 <tr><td colspan="3"></td></tr>
		 <tr><td colspan="3"></td></tr>
		 			<tr><td colspan="3">
		 <?php echo dnp_pagination_donate($statement,$per_page,$page,$url='?');  ?>
</td></tr>
</table>
 
<script type="text/javascript">

jQuery(document).ready(function(){
	
    jQuery("#url_image").click(function(){
		jQuery("#image_url").hide();
	   jQuery("#image_url").val("");  //hide give image url
		 jQuery("#pic").show();
	   });
	/*
	 jQuery("#url_image").click(function(){
       jQuery("#pic").show();  //show to select  image Button
	 
    });
	*/
	 jQuery("#url_button").click(function(){
       jQuery("#image_url").show();//show image Url
	   jQuery("#image_url").val("<?php echo plugins_url('images/Button_DonateNow.png', __FILE__ ); ?>");
	   jQuery("#pic").val(""); // blank a image
		jQuery("#pic").hide();
    });
	/*
	 jQuery("#url_button").click(function(){
       jQuery("#pic").hide();  //hide image Select
	 
    });
	*/
	
	// check uncheck values of radio images 
	
	
	jQuery("#url_image").click(function(){
          jQuery('#url_image').attr('checked', true)
    jQuery('#url_button').attr('checked', false)
	
    });  
	
	jQuery("#url_button").click(function(){
          jQuery('#url_button').attr('checked', true)
    jQuery('#url_image').attr('checked', false)
	
    });
	

		
		// check uncheck values of Vat
	
	jQuery("#fmgy").click(function(){
          jQuery('#fmgy').attr('checked', true)
    jQuery('#fmgn').attr('checked', false)
	
    });  
	
	jQuery("#fmgn").click(function(){
          jQuery('#fmgn').attr('checked', true)
    jQuery('#fmgy').attr('checked', false)
	
    });
	
	// check uncheck values of Vat  End here
	
	
});
</script>
<?php 
  
 }
 function dnp_scripts() {	

	wp_enqueue_script( 'jquery-donate', plugin_dir_url( __FILE__ ) . 'js/jquery-donate.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'jquery-colorbox', plugin_dir_url( __FILE__ ) . 'js/jquery.colorbox-min.js', array('jquery'), '1.0.0', true );
	wp_localize_script('jquery-donate', 'donatevalues', array('wsiteurl' => site_url()));

	wp_register_style( 'donate-now-css', plugins_url('donate.css', __FILE__) );
	wp_enqueue_style( 'donate-now-css' );
	wp_register_style( 'donate-now-colobox', plugins_url('colorbox.css', __FILE__) );
	wp_enqueue_style( 'donate-now-colobox' );
	wp_register_style( 'donate-style-colobox', plugins_url('css/style.css', __FILE__) );
	wp_enqueue_style( 'donate-style-colobox' );
}

add_action( 'wp_enqueue_scripts', 'dnp_scripts','999' );


add_action('admin_enqueue_scripts', 'dnp_styles');
function dnp_styles() {
	wp_register_style('slidesjs_fontss', plugins_url('donate.css', __FILE__));
	wp_enqueue_style('slidesjs_fontss');
	wp_register_style('colorbox_css', plugins_url('colorbox.css', __FILE__));
	wp_enqueue_style('colorbox_css');
	wp_register_style('donate_css', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('donate_css');
}
?>