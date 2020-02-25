<?php    
if(isset($_POST['submit'])){ 

$target_dir_blog = "blog/";
$target_path_blog = $target_dir_blog . basename( $_FILES['blogfileToUpload']['name']); 

$target_dir_tutorial = "tutorial/";
$target_path_tutorial = $target_dir_tutorial . basename( $_FILES['tutorialfileToUpload']['name']); 


if(move_uploaded_file($_FILES['blogfileToUpload']['tmp_name'], $target_path_blog) && 
    move_uploaded_file($_FILES['tutorialfileToUpload']['tmp_name'], $target_path_tutorial)) {
    
    $message = "The file ".  basename( $_FILES['blogfileToUpload']['name'])." and ". basename( $_FILES['tutorialfileToUpload']['name']).
    " has been uploaded successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
} else{
    echo "There was an error uploading the file, please try again!";
}
}    
?>