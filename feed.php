<?php
//include the Facebook PHP SDK
include_once 'facebook.php';
include_once 'csv_writter.php';
include_once 'config.inc.php';

if(isset($_POST['load'])){
//instantiate the Facebook library with the APP ID and APP SECRET
$facebook = new Facebook(array(
	'appId' => FACEBOOK_API,
	'secret' => FACEBOOK_SECRET,
	'cookie' => true
));

//get the news feed of the active page using the page's access token
$page_feed = $facebook->api(
	'/'.$_POST['page_id'].'/feed',
	'GET',
	array(
		'access_token' => $_SESSION['active']['access_token'],
		'limit'=>10000000
	)
);	
$filename = $_POST['page_id'].'.csv';
$filename_comments = $_POST['page_id'].'_comments.csv';
$file_post = new CSV($filename);
$file_post_comments = new CSV($filename_comments);
}
?>


<html>
  <body>
    <form action="feed.php" method="post">
      <input type="text" name="page_id"/>
      <input type="submit" value="Load" name="load"/>
    </form>
  </body>
  <div>
   <?php
   
   
   if(is_array($page_feed))
   {
	  $file_post->open();
	  $file_post_comments->open();
	  
	  $file_post->save_header(array('post_id','from_name','from_id','message','post_type','created_time','created_updated'));
	  $file_post_comments->save_header(array('post_id','comment_id','comment_from_name','message','comment_created'));
     //print_r($page_feed);
	 foreach($page_feed['data'] as $post){
		
		
		
		echo "ID:".$post['id']."<br>";
		echo "FROM NAME:".$post['from']['name']."<br>";
		echo "FROM ID:".$post['from']['id']."<br>";
		echo "MESSAGE:".$post['message']."<br>";
		echo "TYPE:".$post['type']."<br>";
		echo "CREATED_TIME:".$post['created_time']."<br>";
		echo "UPDATED_TIME:".$post['updated_time']."<br>";
		$file_post->save_line(array($post['id'],$post['from']['name'],$post['from']['id'],$post['message'],$post['type'],$post['created_time'],$post['updated_time']));
		
		if(is_array($post['comments']['data'])){
		foreach ($post['comments']['data'] as $comment){
			$file_post_comments->save_line(array($post['id'],$comment['id'],$comment['from']['name'],$comment['message'],$comment['created_time']));
		    echo "------------------------------------------------<br>";
			echo " COMMENT ID:".$comment['id']."<br>";
			echo " COMMENT FROM:".$comment['from']['name']."<br>";
			echo " COMMENT:".$comment['message']."<br>";
			echo " COMMENT CREATED:".$comment['created_time']."<br>";
		}}
		
		echo '-------------------------------------------<br>';
		 
	 }
	 $file_post->close();
   $file_post_comments->close();
   }
   
   
   ?>
  </div>
</html>

