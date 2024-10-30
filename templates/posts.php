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

	$post_data1 = get_posts();
	$data = get_option('api_key_creww');
	$author_name = array();

	foreach ($post_data1 as $k) {

	
		$temp = get_the_author_meta('display_name', $k->post_author);
		array_push($author_name, $temp);
	}



	

	//post the content to creww
	$key_a = @$_REQUEST['url_some'];
	$key_id = @$_REQUEST['url_id'];
	if($key_a != '' )
	{
		$url = 'https://creww.io/api/addurl.php';  
		
		$post_data = array(
		     'key' => urlencode($data),
		     'url' => $key_a,
		     'key_id' => $key_id);

		$result = wp_remote_request( $url, array( 'body' => $post_data ) );

		$res = json_decode( wp_remote_retrieve_body( $result ), true );
		
	}

	//check the posts that are already on creww
	$url1 = 'https://creww.io/api/check-wordpress-post.php';  
	
	$post_data2 = array(
	     'api_key' => urlencode($data));

	$result1 = wp_remote_request( $url1, array( 'body' => $post_data2 ) );

	$res1 = json_decode( wp_remote_retrieve_body( $result1 ), true );
	
	
?>
<html>
<head>

	<meta charset="utf-8">
	

</head>
<body style="background-color:#f1f1f1; ">
<div class="ui container" id="con">
	<center><h1>Creww</h1></center>
	<table class="ui celled padded table" style="width: 98%">
  <thead>
    <tr>
    <th>Id</th>
    <th class="single line">Title</th>
    <th>Author</th>
    <th>Date</th>
    <th>Action</th>
    <th>Reach</th>
  </tr></thead>
  <tbody>
    
  </tbody>
</table>
<form class="ui hidden" method="POST" action="#">
	<input type="hidden" name="url_some" id="se_val" value="">
	<input type="hidden" name="url_id" id="se_val1" value="">
	<input type="submit" name="submit" id="sub">
</form>

	</div>


<script type="text/javascript">
	// alert("hello");
	jQuery(document).ready(function(){

		var data = <?php echo json_encode($post_data1);?>;
		var data1 = <?php echo json_encode($res1[1]);?>;
		var reach = <?php echo json_encode($res1[2]);?>;
		var author_name = <?php echo json_encode($author_name);?>;
		var check_key = "<?php echo $res1[0];?>";
		if(check_key == "failed")
		{
			jQuery("#con").html('<h1>Please check your <a href="options-general.php?page=creww">Api key!</a></h1>');
		}

		console.log(data1);
		console.log(reach);
		console.log(jQuery.inArray('22', data1));
		for(var i=0;i<data.length;i++)
		{
			var elem ='';
			elem = '<tr>'+
				'<td>'+(i+1)+'</td>'+
				'<td>'+data[i]['post_title']+'</td>'+
				'<td>'+author_name[i]+'</td>'+
				'<td>'+data[i]['post_date']+'</td>';
				if(jQuery.inArray(''+data[i]['ID']+'', data1) !== -1)
				{
					elem += '<td>Added</td>'+
					'<td>'+reach[jQuery.inArray(''+data[i]['ID']+'', data1)]+'</td>'+
					'</tr>';
				}
				else
				{
					
					elem += '<td><button class="ui primary mini button" data-url="'+data[i]['guid']+'" data-id="'+data[i]['ID']+'" value="130" onclick="shareUrl(this)">Add to Creww</button></td>'+
					'<td>---</td>'+
					'</tr>';

				}
				jQuery("tbody").append(elem);
		}

	});

	console.log("<?php echo $key_a; ?>");
	function shareUrl(button)
	{

		var key_api = "<?php echo $data; ?>";
		console.log(key_api);
		var url_val = jQuery(button).attr("data-url");
		var url_id = jQuery(button).attr("data-id");
		jQuery("#se_val").val(url_val);
		jQuery("#se_val1").val(url_id);
		jQuery("#sub").click();

		
		
	}
</script>
</body>
</html>



