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
		protected $QuotedTypes = array('varchar','text','datetime','date');
		protected $mysqli;
		
		function __construct($TableName,$Database,$Server,$Username,$Password){
			$this->TableName = $TableName;
			$this->Database = $Database;
			$this->Server = $Server;
			$this->Username = $Username;
			$this->Password = $Password;
			
			$this->loadColumns();
			
		}
		
		
		function loadColumns(){
			$this->QueryDatabase = "Information_Schema";
			
			$columns = $this->DoQuery("SELECT Column_Name,Column_Type,Column_Key
						   			  FROM Columns
						   			  WHERE Table_Schema = ? AND Table_Name = ?;", [$this->Database, $this->TableName], 'ss');
			
			$this->QueryDatabase = $this->Database;
			
			while($row = $columns->fetch_array()){
				
				$fulltype = $row['COLUMN_TYPE'];
				
				$type = explode('(',$fulltype);
				$columnkey = $row['COLUMN_KEY'];
				$column = $row['COLUMN_NAME'];
				if($columnkey == 'PRI'){
					$this->IDColumn = $column;
				}
				$this->fields[$column] = '';
				$this->types[$column] = $type[0];

				$this->mysqliTypes[$column] = 's';

				if($type[0] == 'int' or $type[0] == 'bit'){
					$this->mysqliTypes[$column] = 'i';
				}

			}

			//var_dump($this->types);

			$this->KeyList = $this->IDColumn;
			foreach($this->fields as $key => $value){
				if($key != $this->IDColumn){
					$this->KeyList = $this->KeyList . ',' . $key;
				}
			}
		}
		
		
		function reset(){
			foreach($this->fields as $key => $value){
				$this->set($key, '');
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
		
		
		function getFields(){
			return $this->fields;
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
						   WHERE $this->IDColumn = ?", [$ID], 'i');
			
			
			if($record->num_rows == 0){
				throw new \Exception('No record was found in ' . $this->TableName .  ' for ID ' . $ID);
			}
			
			while($row = $record->fetch_array()){
				foreach($this->fields as $key => $value){
					$this->fields[$key] = $row[$key];
				}
			}
		}
		
		
		function loadBy($values){
			
			$records = $this->findBy($values);
			
			if(sizeof($records) != 1){
				throw new \Exception(sizeof($records) . " records found in ' . $this->TableName .  ' for provided values. Expected 1." . json_encode($values));
			}
			
			$row = $records[0];
			
			$this->load($row[$this->IDColumn]);
			
		}
		
		
		function findBy($values){
			
			$findSQL = "SELECT $this->KeyList
						   FROM $this->TableName
						   WHERE ";
			
			$params = [];
			$types = '';

			if(isset($values['equalsValues'])){
				foreach($values['equalsValues'] as $key => $value){
					if(isset($this->fields[$key])){
						array_push($params, $value);
						$types = $types . $this->mysqliTypes[$key];
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$findSQL = $findSQL . "`$key` = ? AND";
						}else{
							$findSQL = $findSQL . "`$key` = ? AND";
						}
					}
				}
				
				$findSQL = $findSQL . " 1 = 1";
			}
			
			
			if(isset($values['inListValues'])){
				foreach($values['inListValues'] as $key => $value){
					if(isset($this->fields[$key]) AND sizeof($value)){
						
						$questionMarks = array_map(function(){
							return '?';
						}, $value);

						array_push($params, ...$value);
						$types = $types . str_repeat($this->mysqliTypes[$key], sizeof($value));
						
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$findSQL = $findSQL . "`$key` IN (" . join("','", $questionMarks) . ") AND";
						}else{
							$findSQL = $findSQL . "`$key` IN (" . join(",", $questionMarks) . ") AND";
						}
					}else{
						$findSQL = $findSQL . "1 = 0 AND";
					}
				}
				
				$findSQL = $findSQL . " 1 = 1";
			}
			
			if(isset($values['sort']) AND sizeof($values['sort']) > 0){
				
				$findSQL = $findSQL . " ORDER BY";
				
				foreach($values['sort'] as $index => $item){
					$findSQL = $findSQL . " {$item['column']} {$item['direction']}";
					if($index < sizeof($values['sort']) - 1){
						$findSQL = $findSQL . ",";
					}
				}
			}
			
			//var_dump($findSQL);
			//die('dfs');
			
			$records = $this->DoQuery($findSQL, $params, $types);
			
			$results = [];
			
			while($row = $records->fetch_array()){
				array_push($results, $row);
			}
			
			return $results;
		}
		
		
		function save(){
			$this->beforeSave();
			if(strlen($this->fields[$this->IDColumn]) == 0){
				$valuelist = '';
				$keylist = '';
				$count = 0;

				$params = [];
				$types = '';

				foreach($this->fields as $key => $thisvalue){
					if($key != $this->IDColumn){
						$count++;
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$value = "?";
						}else{
							$value = "?";
						}
						if(strlen($thisvalue) == 0){
							$value = 'NULL';
							//array_push($params, 'NULL');
						}else{
							array_push($params, $thisvalue);
							$types = $types . $this->mysqliTypes[$key];
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
								($valuelist);", $params, $types);
				$this->fields[$this->IDColumn] = $this->mysqli->insert_id;
			}else{
				$keyvalues = '';
				$count = 0;

				$params = [];
				$types = '';

				foreach($this->fields as $key => $thisvalue){
					if($key != $this->IDColumn){
						$count++;
						if(in_array($this->types[$key],$this->QuotedTypes)){
							$value = "?";
						}else{
							$value = "?";
						}
						if(strlen($thisvalue) == 0){
							$value = 'NULL';
						}else{
							array_push($params, $thisvalue);
							$types = $types . $this->mysqliTypes[$key];
						}

						if($count > 1){
							$keyvalues = $keyvalues . ',' . $key . '=' . $value;
						}else{
							$keyvalues = $key . '=' . $value;
						}
					}
				}
				
				$IDValue = $this->fields[$this->IDColumn];
				
				$QueryString = "UPDATE $this->TableName SET $keyvalues WHERE $this->IDColumn = $IDValue;";
				
				$this->DoQuery($QueryString, $params, $types);
			}
			$this->afterSave();
		}
		
		function delete(){
			$IDValue = $this->fields[$this->IDColumn];
			$result = $this->DoQuery("DELETE FROM $this->TableName
									 WHERE $this->IDColumn = ?;", [$IDValue], 'i');
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
		  die("Column not found $key in table {$this->TableName}");
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
		
		protected function DoQuery($SQL, $params, $types){
			
			$this->mysqli = new \mysqli($this->Server, $this->Username, $this->Password);
			$this->mysqli->select_db($this->QueryDatabase);
			
			$stmt = $this->mysqli->prepare($SQL);

			$this->LastSQL = $SQL;

			//var_dump($SQL);
			//var_dump($stmt);
			
			if(sizeof($params)){
				$stmt->bind_param($types, ...$params);
			}
			$stmt->execute();
			$result = $stmt->get_result();
			
			if(!$result){
				//var_dump($this->mysqli->error);
				//var_dump($result);
			}
			
			/*$result = $this->mysqli->query($SQL); 
			
			$this->LastSQL = $SQL;
			
			if(!$result){
				var_dump($this->mysqli->error);
				var_dump($result);
			}*/
			
			//$this->mysqli->close();
			
			
			return $result;
			
		}
		
		protected function createTable($tableName, $columns){
			
			$dropSQL = "DROP TABLE IF EXISTS $tableName;";
			
			$this->DoQuery($dropSQL, [], '');
			
			$createSQL = "CREATE TABLE IF NOT EXISTS $tableName(";
			
			$primaryKey = [];
			
			foreach($columns as $key => $column){
				
				$columnSQL = '';
				
				if($column['primaryKey']){
					array_push($primaryKey, "`{$column['name']}`");
				}
				
				//$columnSQL = "{$column['name']} NOT NULL";
				$columnSQL = "`{$column['name']}` {$column['type']}";
				
				if($column['allowNull']){
					$columnSQL = "$columnSQL NULL";
				}else{
					$columnSQL = "$columnSQL NOT NULL";
				}
				
				$columnSQL = "$columnSQL {$column['extra']}";
				
				$createSQL = $createSQL . ' ' . $columnSQL;
				
				if($key + 1 < sizeof($columns)){
					$createSQL = $createSQL . ',';
				}
			}
			
			if(sizeof($primaryKey)){
				$createSQL = $createSQL . ",PRIMARY KEY (" . join(',', $primaryKey) . ")";
			}
			
			$createSQL = $createSQL . ') ENGINE=InnoDB AUTO_INCREMENT=1;';
			
			//var_dump($createSQL);
			
			$this->DoQuery($createSQL, [], '');
			
		}
		
		
		function addRecords($records){
			
			$this->loadColumns();
			
			foreach($records as $key => $record){
				$this->reset();
				$this->updateAttributes($record);
				$this->save();
			}
			
		}
		
	}
?>