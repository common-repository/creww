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

$data = get_option('api_key_creww');


$url = 'https://creww.io/api/rock_test';  
$foo = 'bar';
$post_data = array(
     'key' => urlencode($data));

$result = wp_remote_request( $url, array( 'body' => $post_data ) );

$res = json_decode( wp_remote_retrieve_body( $result ), true );


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  

  <style type="text/css">
    .statistic{
      width: 100%;

    }
  </style>
</head>
<body style="background-color:#f1f1f1;">
<div class="ui container" id="con">
 <div class="ui stackable grid">
      <div class="three column row">
        <div class="column" id="basic-stat-1" style="width: 100%;">
          
        </div>
        <div class="column" id="basic-stat-2" style="width: 100%;">
          
        </div>
        <div class="column" id="basic-stat-3" style="width: 100%;">
          
        </div>
      </div>
      <div class="row">
       <div class="column" id="chart-container" style="width: 100%;margin-bottom: 10px;">
          <h4 class="ui header">Posts per day</h4>
         <div id="post-chart"></div>
       </div>
       <div class="column" id="chart-container-two" style="width: 100%;margin-top: 10px;">
       <h4 class="ui header">Reach per day</h4>
         <div id="reach-chart"></div>
       </div>
      </div>
      <div class="two column row">
        <div class="column" id="content">
        <h4 class="ui header">Content Stats</h4>
          <table class="ui very basic collapsing celled table content-table">
      <thead>
        <tr><th>Post</th>
        <th>Shares</th>
        <th>Reach</th>
        
      </tr></thead>
      <tbody id="content-tbody">
        

      </tbody>
    </table>
        </div>
        <div class="column" id="recent">
        <h4 class="ui header">Recent Posts</h4>
          <table class="ui very basic collapsing celled table recent-table">
      <thead>
        <tr><th style="width: 60%">Post</th>
        <th style="width: 20%">Platform</th>
        <th style="width: 20%">Reach</th>
       
      </tr></thead>
      <tbody id="recent-tbody">
        

      </tbody>
    </table>
        </div>
      </div>

</div>
</div>


<script type="text/javascript">

	 

	if("<?php echo $res[0];?>" =="failed")
       {
       	jQuery("#con").html('<h1>Please check your <a href="options-general.php?page=creww">Api key!</a></h1>');
       }

	else if("<?php echo $res[0];?>" =="success"){
		var one = <?php echo json_encode($res[1]);?>;
		var two = <?php echo json_encode($res[2]);?>;
		var six = <?php echo json_encode($res[6]);?>;
    var content_data = <?php echo json_encode($res[7]);?>;
    var recent_data = <?php echo json_encode($res[8]);?>;
    console.log(content_data);
        area_chart(one,two,six);  
          jQuery("#basic-stat-1").html('<div class="ui huge statistics">'+
      '<div class="red statistic">'+
        '<div class="value">'+
          <?php echo $res[3];?>+
        '</div>'+
        '<div class="label">'+
          'Total Posts'+
        '</div>'+
      '</div>'+
      '</div>');
       jQuery("#basic-stat-2").html('<div class="ui huge statistics">'+'<div class="orange statistic">'+
        '<div class="value">'+
          <?php echo $res[4];?>+
        '</div>'+
        '<div class="label">'+
          'Total Reach'+
        '</div>'+
      '</div>'+
      '</div>');
       jQuery("#basic-stat-3").html('<div class="ui huge statistics">'+
      '<div class="yellow statistic">'+
        '<div class="value">'+
          <?php echo $res[5];?>+
        '</div>'+
        '<div class="label">'+
          'Connected accounts'+
        '</div>'+
      '</div>');


       var content_row = '';
                 if(content_data.length>0){
                   for(var i=0;i<content_data.length;i++){
                     content_row +='<tr>'+
                 '<td>'+
                   '<h4 class="ui image header">'+
                     '<img src="'+content_data[i]['image']+'" class="ui mini rounded image">'+
                     '<div class="content" style="width:90%">'+
                       
                       '<div class="sub header">'+content_data[i]['title']+
                     '</div>'+
                   '</div>'+
                 '</h4></td>'+
                 '<td>'+
                   +content_data[i]['count']+
                 '</td>'+
                 '<td>'+
                   +content_data[i]['sum']+
                 '</td>'+
          
               '</tr>';
                   }
                   jQuery("#content-tbody").html(content_row);
                 }else{
                   jQuery("#content-table").remove();
                   //show message here no content uploaded yet 
                 }
                 content_row='';
                  if(recent_data.length>0){
                   for(var i=0;i<recent_data.length;i++){
                     content_row +='<tr>'+
                 '<td>'+
                   '<h4 class="ui image header">'+
                     '<img src="'+recent_data[i]['image']+'" class="ui mini rounded image">'+
                     '<div class="content" style="width:90%">'+
                       
                       '<div class="sub header">'+recent_data[i]['title']+
                     '</div>'+
                   '</div>'+
                 '</h4></td>'+
                 '<td class="ui header">'+'<div class="content" style="width:90%;font-size:11px;">On '+
                   recent_data[i]['type']+', <div class="sub header">'+recent_data[i]['timeis']+'</div></div>'+
                 '</td>'+
                 '<td>'+
                   +recent_data[i]['reach']+
                 '</td>'+
               '</tr>';
                   }
                   jQuery("#recent-tbody").html(content_row);
                 }else{
                   jQuery("#recent-table").remove();


        }
      }
       
     
    

    function area_chart(datais,maxpost, maxreach){
      var color1 = "#98b7ea";
      var color2 = "#5bb773";
      var color3 = "#e5c15a";


      Morris.Area({
        element: 'post-chart',
        data: datais,
        xkey: 'date',
        ykeys: ['facebook', 'twitter', 'linkedin'],
        labels: ['Facebook', 'Twitter', 'Linkedin'],
        lineColors: [color1, color2, color3],
        pointSize: 2,
        hideHover: 'auto',
         ymax: maxpost,
          parseTime:false,
      });

      Morris.Area({
        element: 'reach-chart',
        data: datais,
        xkey: 'date',
        ykeys: ['reach'],
        labels: ['Reach'],
        lineColors: [color1 ],
        pointSize: 2,
        hideHover: 'auto',
         ymax: maxreach,
          parseTime:false,
      });
    }
</script>

</body>
</html>