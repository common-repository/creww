<?php

if (! defined('ABSPATH'))
{
  die;
}
if(!current_user_can("administrator"))
{

  echo "only admin can access";
  die;
}
$edit = 0;
$key = get_option('api_key_creww');

$url = 'https://creww.io/api/wordpress-check-user.php';  

$post_data = array(
     'key_a' => urlencode($key));

$result = wp_remote_request( $url, array( 'body' => $post_data ) );

$res = json_decode( wp_remote_retrieve_body( $result ), true );


if ( @$_REQUEST['edit'] != '')
 
 {

  if(! wp_verify_nonce( @$_REQUEST['nonce_check_name'], 'nonce_check' ) )
  {
    
     print 'Sorry, your nonce did not verify.';
     exit;
  }

else {

  $edit = @$_REQUEST['edit'];
   // process form data
}
}




?>









<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Creww.io - Putting Your Team's Influence To Use</title>
    
</head>
<body style="background-color:#f1f1f1;">
<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4" style="padding-top:121px;">
    
        <div>
            
            
            <div class="text-center mb-2" style="padding-bottom: 10px;">
                <a href="#">
                    <img src="<?php echo plugins_url('Creww/assets/creww_logo_1.png') ?>" alt="" style="height: 70px; padding-left: 19px;" class="img-fluid">
                </a>
                <h4 class="title mb-2" style="padding-left: 22px;" >API Key</h4>                
            </div>

            <div style="padding-left: 0px; padding-right: 0px" id="status">
            
            </div>
            
                    <div class="" id="content-div">
                        <form method="post" action="options.php">
                        <?php settings_fields( 'settings-group' ); ?>
                        <?php do_settings_sections( 'settings-group' ); ?>
                        	<input type="hidden" name="edit" value=0>
                            <div class="form-group">
                            <input type="text" name="api_key_creww" class="form-control" value="<?php echo get_option('api_key_creww'); ?>">
                            </div>

                            <div class="form-group">
                            	<button type="submit" id="l_button" style="width: 100%; background-color: #3ECF8E; color:#ffffff;" class="btn d-block w-100 " value="save">Submit</button>
                        	</div>
                        </form>
                    </div>
                    
              
            <div style="padding-bottom: 40px" class="login text-center" >
            <form action="#" method="post" id="key"> 
            	<!-- <input type="hidden" name="page" value=creww> -->
                <input type="hidden" name="edit" value=1>
                <?php wp_nonce_field( 'nonce_check', 'nonce_check_name' ); ?>
                <a style="color:blue;cursor:pointer;" id="edit-key" name="edit1"  onclick="form_post()">Edit Key</a> | <a href="https://creww.io/login.php">Login in on Creww to get your API key</a>
           	</form>
            </div>
        </div>
    <!-- </section> -->
</div>
    



<!--jQuery, tether &  Bootstrap JS.-->







 
<script type="text/javascript">
function form_post($)
{
  jQuery("#key").submit();
}

   
   var edit_data = '<?php echo $edit ?>';
   if(edit_data == 0)
   {

	   jQuery(document).ready(function($){
	   		var response = <?php echo json_encode($res[0]) ?>;
	   		var data = <?php echo json_encode($res[1]) ?>;
	   		if(response == "success")
	   		{
	   			$("#content-div").html('<div class="alert alert-success">'+
				  '<center><strong>Connected!</strong> '+data+
				  '</div></center>');

	   		}


	   });
   	
   }



</script>
</body>
</html>