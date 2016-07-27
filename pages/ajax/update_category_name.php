<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['id'])) { $id = $_POST['id']; if($id=="") {unset($id);}}
	if (isset($_POST['name'])) { $name = $_POST['name']; if($name=="") {unset($name);}}

	if (isset($name)&&isset($id)) {
		// all ok -> sqlquery 
		
		$result = mysqli_query ($db, "UPDATE `categories`
                                SET `name`='$name'
                                WHERE `id`='$id'");
		
		if ($result) {
			//echo"the name was updated to ".$name;
            echo 1;
		}
		else {
			//echo "wasn't updated";
            echo 0;
		}
	}
	else {
			//echo "no id and no name";
        echo 0;
	}
?>