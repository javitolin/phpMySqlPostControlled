
<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	include 'sqlLogic.php';
	$data = null;
	if(isset($_POST['dbName']) && isset($_POST['dbUser']) && isset($_POST['dbPass'])){
		define('DB_USER', $_POST['dbUser']);
		define('DB_PASSWORD', $_POST['dbPass']);
		define('DB_DATABASE', $_POST['dbName']);
		define('DB_SERVER', "localhost");
	}
	else{
		$response = array();
		$response["success"] = 0;
		$response["message"] = "Must specify dbUser, dbPass, dbName";
		echo json_encode($response);
	}
	$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
	$sqlLogic = new SQL_Logic($db);
	if(!isset($_POST['tableName'])){
		$response = array();
		$response["success"] = 0;
		$response["message"] = "Must specify table name: tableName";
		echo json_encode($response);
	}
	else{
		$tableName = $_POST['tableName'];
		if(!isset($_POST['Action'])){
			$response = array();
			$response["success"] = 0;
			$response["message"] = "Must specify Action: Action: select, insert, remove";
			echo json_encode($response);
		}
		else{
			switch($_POST['Action']){
			case 'select':
				$response = $sqlLogic->getAllData($tableName);
				echo json_encode($response);
				break;
			case 'insert':
				$response = array();
				if(!isset($_POST['insertData'])){
					$response["success"] = 0;
					$response["message"] = "Must insert data: insertData";
					echo json_encode($response);
				}
				else{
					$data = json_decode($_POST['insertData'],true);
					$error = getJsonError(json_last_error());
					if($error != null){
						$response["success"] = 0;
						$response["message"] = "Wrong json: ".$error." received: ".$_POST['insertData'];
						echo json_encode($response);
					}
					else{
						$response = $sqlLogic->insertData($tableName, $data);
						echo json_encode($response);
					}
				}
				break;
			case 'remove':
				$response = array();
				if(!isset($_POST['rColumn']) || !isset($_POST['rClause'])){
					$response["success"] = 0;
					$response["message"] = "Must add rColumn and rClause";
					echo json_encode($response);
				}
				else{
					$whereClause = $_POST['rClause'];
					$whereColumn = $_POST['rColumn'];
					$response = $sqlLogic->removeData($tableName, $whereColumn,$whereClause);
					echo json_encode($response);
				}
			break;
		}
		}
	}

	function getJsonError($err){
		switch ($err) {
		    case JSON_ERROR_NONE:
		    	return null;
		    break;
		    case JSON_ERROR_DEPTH:
		        return ' - Maximum stack depth exceeded';
		    break;
		    case JSON_ERROR_STATE_MISMATCH:
		        return ' - Underflow or the modes mismatch';
		    break;
		    case JSON_ERROR_CTRL_CHAR:
		        return ' - Unexpected control character found';
		    break;
		    case JSON_ERROR_SYNTAX:
		        return ' - Syntax error, malformed JSON';
		    break;
		    case JSON_ERROR_UTF8:
		        return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		    break;
		    default:
		        return ' - Unknown error';
		    break;
		}
	}
?>