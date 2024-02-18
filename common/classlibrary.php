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
			
			while($row = mysql_fetch_array($columns)){
				$fulltype = $row['Column_Type'];
				list($type,$length) = explode('(',$fulltype);
				$columnkey = $row['Column_Key'];
				$column = $row['Column_Name'];
				if($columnkey == 'PRI'){
					$this->IDColumn = $column;
				}
				$this->fields[$column] = '';
				$this->types[$column] = $type;
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
		function load($ID){
			$record = $this->DoQuery("SELECT $this->KeyList
						   FROM $this->TableName
						   WHERE $this->IDColumn = $ID");
			
			
			while($row = mysql_fetch_array($record)){
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
				$this->DoQuery("UPDATE $this->TableName SET $keyvalues WHERE $this->IDColumn = $IDValue;");
			}
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
		
		protected function DoQuery($SQL){
			// Make a MySQL Connection
			mysql_connect($this->Server, $this->Username, $this->Password) or die(mysql_error());
			mysql_select_db($this->QueryDatabase) or die(mysql_error());
			
			$result = mysql_query($SQL)
			or die(mysql_error()); 
			
			$this->LastSQL = $SQL;
			
			return $result;
		}
	}
	
	class Site extends Record{
		function __construct(){
			record::__construct('Sites','mandjscreations','localhost','root','//att1');
		}
		
		function GetCategories(){
			$Categories = $this->DoQuery("SELECT CategoryID FROM Categories WHERE ParentID IS NULL");
			
			$CategoryArray = array();
			
			while($row = mysql_fetch_array($Categories)){
				$category = new Category();
				$category->load($row['CategoryID']);
				$CategoryArray[] = $category;
			}
			
			return $CategoryArray;
		}
	}
	
	class Article extends Record{
		function __construct(){
			record::__construct('Articles','mandjscreations','localhost','root','//att1');
		}
		
		function beforeSave(){
			if (strlen($this->fields[$this->IDColumn]) == 0){
				$this->set('DateEntered',date("Y-m-d H:i:s", time()) );
			}
		}
	}
	
	class Category extends Record{
		function __construct(){
			record::__construct('Categories','mandjscreations','localhost','root','//att1');
		}
		
		function GetCategories(){
			$Categories = $this->DoQuery("SELECT CategoryID FROM Categories WHERE ParentID = $this->get('CategoryID');");
			
			$CategoryArray = array();
			
			while($row = mysql_fetch_array($Categories)){
				$category = new Category();
				$category->load($row['CategoryID']);
				$CategoryArray[] = $category;
			}
			
			return $CategoryArray;
		}
		
		function GetArticles(){
			$CategoryID = $this->get('CategoryID');
			$Articles = $this->DoQuery("SELECT ArticleID FROM Articles WHERE CategoryID = $CategoryID ORDER BY DateEntered DESC");
			
			$ArticleArray = array();
			
			while($row = mysql_fetch_array($Articles)){
				$article = new Article();
				$article->load($row['ArticleID']);
				$ArticleArray[] = $article;
			}
			
			return $ArticleArray;
		}
	}
?>