<?php
class SQL_Logic {
	private $db;
	function __construct($db){
		$this->db = $db;
	}
    function getAllData($tableName){
    	$response = array();
		$myQuery = "show columns from $tableName"; 
		$result = mysqli_query($this->db,$myQuery); 
		$row = mysqli_fetch_row($result); 
		$columnCount = count($row);
    	$response["items"] = array();
    	$result = mysqli_query($this->db,"SELECT * FROM $tableName");

		if (mysqli_num_rows($result) > 0) {
			// looping through all results
			// products node
			while ($row = mysqli_fetch_array($result)) {
			// temp user array
				$product = array();
				for($i = 0; $i < $columnCount; $i++){
					$product[$i] = $row[$i];
				}

				// push single product into final response array
				array_push($response["items"], $product);
			}
			// success
			$response["success"] = 1;
		} else {
			// no products found
			$response["success"] = 0;
			$response["message"] = "No products found";
		}
		return $response;
    }
    function insertData($table, $data){
    	$fields = array_keys($data);
    	$response = array();
    	$sql = "INSERT INTO ".$table."(`".implode('`,`', $fields)."`) VALUES ('".implode("','", $data)."')";
    	if(mysqli_query($this->db,$sql) == 1){
    		$response["success"] = 1;
    	}
    	else{
    		$response["success"] = 0;
    		$response["message"] = "something went wrong";
    	}
    	return $response;
    }
    function removeData($table, $whereColumn, $whereClause){
    	$response = array();
    	$query = "DELETE from $table where $whereColumn = $whereClause";
    	if(mysqli_query($this->db,$query) == 1){
    		$response["success"] = 1;
    	}
    	else{
    		$response["success"] = 0;
    		$response["message"] = "something went wrong";
    	}
    	return $response;
    }
}
?>