<?php
	class Record{
		
		protected $fields = array();
		protected $types = array();
		protected $TableName;
		protected $Database;
		protected $QueryDatabase;
		protected $Server;
		protected $Username;
		protected $Password;
		protected $LastSQL;
		protected $IDColumn;
		protected $KeyList;
		protected $QuotedTypes = array('varchar','text','datetime');
		protected $mysqli;
		
		function __construct($TableName,$Database,$Server,$Username,$Password){
			$this->TableName = $TableName;
			$this->Database = $Database;
			$this->Server = $Server;
			$this->Username = $Username;
			$this->Password = $Password;
			$this->QueryDatabase = "Information_Schema";
			
			$columns = $this->DoQuery("SELECT Column_Name,Column_Type,Column_Key
						   			  FROM Columns
						   			  WHERE Table_Schema = '$this->Database' AND Table_Name = '$this->TableName';");
			
			
			$this->QueryDatabase = $Database;
			
			//print_r($columns->fetch_array(MYSQLI_ASSOC));
			//exit;
			
			while($row = $columns->fetch_array(MYSQLI_ASSOC)){
				
				$fulltype = $row['COLUMN_TYPE'];
				
				$type = explode('(',$fulltype);
				$columnkey = $row['COLUMN_KEY'];
				$column = $row['COLUMN_NAME'];
				if($columnkey == 'PRI'){
					$this->IDColumn = $column;
				}
				$this->fields[$column] = '';
				$this->types[$column] = $type[0];
			}
			$this->KeyList = $this->IDColumn;
			foreach($this->fields as $key => $value){
				if($key != $this->IDColumn){
					$this->KeyList = $this->KeyList . ',' . $key;
				}
			}
			
		}
		function debug(){
			$typelist = '';
			foreach($this->types as $type)
			{
				$typelist .=$type;
			}
			return $typelist;
		}
		function updateAttributes($newAtts){
			foreach($this->fields as $field => $value){
				if(array_key_exists($field, $newAtts)){
					$this->set($field,$newAtts[$field]);
				}
			}
		}
		function load($ID){
			$ID = intval($ID);
			$record = $this->DoQuery("SELECT $this->KeyList
						   FROM $this->TableName
						   WHERE $this->IDColumn = $ID");
			
			
			print_r($record->num_rows());
			exit;
			
			if($record->num_rows() == 0){
				throw new Exception('No record was found in ' . $this->TableName .  ' for ID ' . $ID);
			}
			
			while($row = $record->fetch_array()){
				foreach($this->fields as $key => $value){
					$this->fields[$key] = $row[$key];
				}
			}
		}
		
		function save(){
			$this->beforeSave();
			if(strlen($this->fields[$this->IDColumn]) == 0){
				$valuelist = '';
				$keylist = '';
				$count = 0;
				foreach($this->fields as $key => $thisvalue){
					if($key != $this->IDColumn){
						$count++;
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$value = "'" . $thisvalue ."'";
						}else{
							$value = $thisvalue;
						}
						if(strlen($thisvalue) == 0){
							$value = 'NULL';
						}
						if($count > 1){
							$keylist = $keylist . ',' . $key;
							$valuelist = $valuelist . ',' . $value;
						}else{
							$keylist = $key;
							$valuelist = $value;
						}
					}
				}
				//die('keys:' . $keylist . '<br>values:' . $valuelist);
				$result = $this->DoQuery("INSERT INTO $this->TableName
								($keylist)
								VALUES
								($valuelist);");
				$this->fields[$this->IDColumn] = mysql_insert_id();
			}else{
				$keyvalues = '';
				$count = 0;
				foreach($this->fields as $key => $thisvalue){
					if($key != $this->IDColumn){
						$count++;
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$value = "'" . $thisvalue ."'";
						}else{
							$value = $thisvalue;
						}
						if(strlen($thisvalue) == 0){
							$value = 'NULL';
						}
						if($count > 1){
							$keyvalues = $keyvalues . ',' . $key . '=' . $value;
						}else{
							$keyvalues = $key . '=' . $value;
						}
					}
				}
				//die($keyvalues);
				$IDValue = $this->fields[$this->IDColumn];
				//die("UPDATE $this->TableName SET $keyvalues WHERE $this->IDColumn = $IDValue");
				$QueryString = "UPDATE $this->TableName SET $keyvalues WHERE $this->IDColumn = $IDValue;";
				//$LogDate = date("Y-m-d H:i:s", time());
				//$this->DoQuery("INSERT INTO QueryLogs (QueryString, DateEntered) VALUES ('$QueryString','$LogDate')");
				//die($QueryString);
				$this->DoQuery($QueryString);
			}
			$this->afterSave();
		}
		
		function delete(){
			$IDValue = $this->fields[$this->IDColumn];
			$result = $this->DoQuery("DELETE FROM $this->TableName
									 WHERE $this->IDColumn = $IDValue;");
			return $result;
		}
		
		function get( $key )
		{
		  return $this->fields[ $key ];
		}
	  
		function set( $key, $value )
		{
		  if ( array_key_exists( $key, $this->fields ) )
		  {
			$this->fields[ $key ] = $value;
			return 'true';
		  }
		  die('Column not found');
		  //return 'false';
		}
		
		function beforeSave(){
		}
		
		function copyrecord(){
			$this->fields[$this->IDColumn] = '';
		}
		
		function afterSave(){
		}
		
		function IsNewRecord(){
			if(strlen($this->fields[$this->IDColumn]) == 0){
				return true;
			}
			else{
				return false;
			}
		}
		
		function SQLDate($datevalue){
			$DateValues = getdate($datevalue);
			$DisplayDate = $DateValues['year'] . '-' . $DateValues['mon'] . '-' . $DateValues['mday'] . ' ' . $DateValues['hours'] . ':' . $DateValues['minutes'] . ':' . $DateValues['seconds'];
			return $DisplayDate;
		}
		
		function FormatDate($datevalue){
			$SplitDateTime = explode(' ', $datevalue);
			$DateString = $SplitDateTime[0];
			$TimeString = $SplitDateTime[1];
			
			$SplitDate = explode('-',$DateString);
			$Month = $SplitDate[1];
			$Day = $SplitDate[2];
			$Year = $SplitDate[0];
			$DisplayDate = $Month . '/' . $Day . '/' . $Year . ' ' . $TimeString;
			return $DisplayDate;
		}
		
		protected function DoQuery($SQL){
			// Make a MySQL Connection
			/*mysqli_connect($this->Server, $this->Username, $this->Password);
			mysqli_select_db($this->QueryDatabase);
			
			$result = mysqli_query($SQL); 
			
			$this->LastSQL = $SQL;
			
			return $result;*/
			
			$this->mysqli = new mysqli($this->Server, $this->Username, $this->Password);
			$this->mysqli->select_db($this->QueryDatabase);
			
			$result = $this->mysqli->query($SQL); 
			
			$this->LastSQL = $SQL;
			
			return $result;
			
		}
	}
?>