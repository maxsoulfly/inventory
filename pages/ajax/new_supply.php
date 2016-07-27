<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');

    if (isset($_POST['location_id'])) {
        $location_id = $_POST['location_id'];
        if ($location_id == "") {
            $location_id = 1;
        }

        $thisSupplyID = add_transaction (0, $location_id);

        echo $thisSupplyID;

    }
 ?>