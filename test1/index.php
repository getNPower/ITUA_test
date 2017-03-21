 <!DOCTYPE html>
<html lang="en">
 <head>
     <meta charset="UTF-8">
     <title>Test</title>
	 <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' type='text/css'>

 </head>
 <body>



<?php

if ($_SERVER['REQUEST_METHOD']=='POST'){

	$errorList = validateData();
	if ($errorList){
		showForm($errorList['user_name'],$errorList['name'],$errorList['email'],$errorList['text']);
	}else{
		writeUser();
	}
}else{
	showForm();
}

function showForm($user_name='input user name',$name='input name',$email='input email',$text=''){?>
<form method='POST' enctype='multipart/form-data' class="col-lg-6 col-lg-offset-3" >
  <div class="form-group">
    <label for="user_name">USER_NAME</label>
    <input type="text" class="form-control" name='user_name' placeholder='<?=$user_name;?>'>
  </div>
  <div class="form-group">
    <label for="name">NAME</label>
    <input type="text" class="form-control" name='name' placeholder='<?=$name;?>'>
  </div>
  <div class="form-group">
    <label for="EMAIL">EMAIL</label>
    <input type="email" class="form-control" name='email' placeholder='<?=$email;?>'>
  </div>
    <div class="form-group">
    <label for="text">TEXT</label>
	<textarea class="form-control" rows="3" name="text"><?=$text;?></textarea>
  </div>

  <div class="form-group">
    <label for="file">File input</label>
    <input type="file" name='file'>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>  
<?php
}


function validateData(){
	$errorList = null;
	$patern_name = "/^[A-Z][-a-zA-Z]+$/";
	$patern_email = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
	if(empty($_POST['user_name'])){
		$errorList['user_name']='USER_NAME is empty';
	}elseif (!preg_match($patern_name, $_POST['user_name'])){
		$errorList['user_name']='Enter valid USER_NAME';
	}
	if(empty($_POST['name'])){
		$errorList['name']='Name is empty';
	}elseif (!preg_match($patern_name, $_POST['name'])){
		$errorList['name']='Enter valid NAME';
	}
	if(empty($_POST['email'])){
		$errorList['email']='EMAIL is empty';
	}elseif (!preg_match($patern_email, $_POST['email'])){
		$errorList['email']='Enter valid EMAIL';
	}
	if(empty($_POST['text'])){
		$errorList['text']='TEXT is empty';
	}elseif (strlen($_POST['text'])<20){
		$errorList['text']='Enter more then 20 char';
	}

	return $errorList;
}


function writeUser(){
	$file='';
	if($_FILES['file']['tmp_name']){
		if($_FILES['file']['error']==UPLOAD_ERR_OK){
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$tmp_name=$_FILES['file']['tmp_name'];
		$new_name='upload/'.basename(date('d-m-Y-H-i-s')).'.'.$ext;
			if (move_uploaded_file($tmp_name,$new_name)){
				$file=$new_name;
			}
		
		}
	}
	$user_name = $_POST['user_name'];
	$name=$_POST['name'];
	$email=$_POST['email'];
	$text=$_POST['text'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date('d-m-Y');
	//print_r ($date);

	$connect = mysqli_connect('localhost','root','','test');
	$query="INSERT into users (user_name, name, email, text, file, ip, date)
			VALUE('$user_name', '$name', '$email', '$text', '$file', '$ip',  STR_TO_DATE('$date', '%d-%m-%Y'))";

	$result = mysqli_query($connect, $query);

	if(!$result){
		echo mysqli_error($connect);
	}



mysqli_close($connect);
echo "user data inserted";
header("Refresh:0");
}

?>

</body>
</html>