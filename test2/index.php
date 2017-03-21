 <!DOCTYPE html>
<html lang="en">
 <head>
     <meta charset="UTF-8">
     <title>Test</title>
	 <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet' type='text/css'>

 </head>
 <body style="max-width: 90%">



<?php

if ($_SERVER['REQUEST_METHOD']=='POST'){

	showData();
	}else{
	showData();
}

function showData($user_name='input user name',$name='input name',$email='input email',$text=''){?>
<table class="table table-condensed col-lg-10 col-lg-offset-1" >
    <tr>
		<th>ID</th>
		<th>USER_NAME</th>
		<th>NAME</th>
		<th>EMAIL</th>
		<th>TEXT</th>
		<th>FILE</th>
		<th>IP</th>
		<th>DATE
			<form method='POST'>
				<div class="form-group">
					<label for="DATE">order</label>
					<select name='DATE'>
						<option value='ASC'>ASC</option>
						<option value='DESC'>DESC</option>
					</select>
				</div>
				<div class="form-group">
					<label for="date_from">from</label>
					<input type="date" name='date_from' value='0'>
				</div>
				<div class="form-group">
					<label for="date_to">to</label>
					<input type="date" name='date_to' value='0'>
				</div>
			<input type='submit' value='sort'>
			</form>
		</th>
    </tr>
    <?php
	$connect = mysqli_connect('localhost','root','','test');

    $query='SELECT id,user_name,name,email,text,file,ip,date
		   FROM users';
	if ((isset($_POST['date_from']) and ($_POST['date_from'] > 0)) and (isset($_POST['date_to']) and ($_POST['date_to'] > 0))){
		$date_to = $_POST['date_to'];
		$date_from = $_POST['date_from'];
		$query.= ' WHERE date <= '."STR_TO_DATE('$date_to', '%Y-%m-%d')".' and date >= '."STR_TO_DATE('$date_from', '%Y-%m-%d')";
	}
	elseif (isset($_POST['date_to']) and ($_POST['date_to'] > 0)){
		$date_to = $_POST['date_to'];
		$query.= ' WHERE date <= '."STR_TO_DATE('$date_to', '%Y-%m-%d')";
	}
	elseif (isset($_POST['date_from']) and ($_POST['date_from'] > 0)){
		$date_from = $_POST['date_from'];
		$query.= ' WHERE date >= '."STR_TO_DATE('$date_from', '%Y-%m-%d')";
	}
	if (isset($_POST['DATE'])){
	$query.= ' ORDER BY date '.$_POST['DATE'];
	}
	$result = mysqli_query($connect, $query);
	if(!$result){
		echo mysqli_error($connect);
	}else{
		foreach ($result as $user){
			extract($user);
			?>
			    <tr>
		<th><?=$id;?></th>
		<th><?=$user_name;?></th>
		<th><?=$name;?></th>
		<th><?=$email;?></th>
		<th><?=$text;?></th>
		<th><?=$file;?></th>
		<th><?=$ip;?></th>
		<th><?=$date;?></th>
    </tr>
			<?php
		}
	}
	mysqli_close($connect);
	   
   ?>
</table>
<?php
}


?>

</body>
</html>