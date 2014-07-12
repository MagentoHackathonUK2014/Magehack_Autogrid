<?php

class Magehack_Autogrid_Model_Resource_Table_Cell
	extends Mage_Core_Model_Abstract
		implements Magehack_Autogrid_Model_Resource_Table_CellInterface{

			
//class Micodigital_Helloworld_Model_Cell extends  Mage_Core_Model_Abstract{
			
		const DEFAULT_COLUMN_WIDTH = '80px';	
		protected $gridInfo = array();
		protected $formInfo = array();

		
		

public function getGridInfo(){
	return $this->gridInfo;
}
public function getGFormInfo(){
	return $this->formInfo;
}
		
		
/**
	$dataType as String
	Returns (nothing) but sets some cell data ready for reading later
	so that the Admin for or the Admin grid can be created
*/

public function setType($dataType){

	if(!$this->name){
	  Mage::log("Cannot make default cell without name.\n",null,'autogrid.log');
	  return false;
	}
	if(!$dataType){
	  Mage::log("Cannot make default cell without data type.\n",null,'autogrid.log');
	  return false;
	}

  //always start by making the default so every data item is populated
  
  $this->makeDefaultCell($dataType);
  
  //then check some autogrid config to see if there are any specific requests
  //and update the deafult values
  
  
  //LATER
  

}

public function getHelper(){

  return Mage::helper('magehack_autogrid');
  //return Mage::helper('helloworld');
  
}


/**
	$recognisedDataType as String
	$m as integer
	Returns (nothing) but sets some cell data ready for reading later
	so that the Admin for or the Admin grid can be created
*/
public function makeDefaultCell($recognisedDataType, $m=null){
	
	
	//we will start with some base defaults
	//then you only need to change one or two things depending on the database type
	
		//cell grid information		
		$this->gridColumnId = $this->name;
		$this->gridInfo['header'] = ($this->getHelper()->__($this->name));
		$this->gridInfo['index'] = ($this->name);
		$this->gridInfo['align'] = ('left');
		$this->gridInfo['width'] = (self::DEFAULT_COLUMN_WIDTH);
//		'type'      => ''//'options',
	
		//cell form information
		$this->setFormName($this->name); //if name is null or not set by parser we are in trouble  
    $this->setFormInputType('text'); //'textbox' //editor //radio //select //selectmulti
    $this->formInfo = array(
    				'label'     => $this->getHelper()->__($this->name),
		      //'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => $this->name,
    	);
		
		
	//now we can change some of these for specific data types
	switch($recognisedDataType){
		
		//these special cases default to text if M<=255, otherwise textbox	
		case "VARCHAR" : //What about M?
		case "VARBINARY" : //What about M?
		case "BLOB" : 
		case "TEXT" : 
			
			//cell form information
			
			if($m){
				if($m > 255){
					 //TEXTBOX
				}else{
					 //TEXT
				}
			}else{
				//A tough choice
				//LETS SAY TEXT not TEXTBOX
			}
			
			
			//cell grid information	
			
			break;
			
		//these cases all default to text input	
		case "BIT" :
		case "TINYINT" :
		case "SMALLINT" :
		case "MEDIUMINT" :
		case "INT" :
		case "INTEGER" :
		case "BIGINT" :
		case "SERIAL" :
		case "DECIMAL" :
		case "DEC" :
		case "FLOAT" :
		case "DOUBLE" :
		case "DOUBLE PRECISION" :
	
		case "CHAR" :
		case "BINARY" :
		case "TINYBLOB" :
		case "TINYTEXT" :
			//cell form information
			//cell grid information	
			
			break;
	
		//these cases are text input but they might have validation
		//they might have flags for date pickers	
		case "DATE" :
		case "DATETIME" :
		case "TIMESTAMP" :
		case "TIME" :
		case "YEAR" :
			//cell form information
			//cell grid information	
		
			break;
		
	
		//these cases all default to textbox input		
		case "MEDIUMBLOB" :
		case "MEDIUMTEXT" :
		case "LONGBLOB" :
		case "LONGTEXT" :
			//cell form information
			//cell grid information	
	
			break;
			
		//these cases default to yes/no input
		case "BOOL" :
		case "BOOLEAN" :
			//cell form information
			//cell grid information	
	
			break;
			
		//these are edge cases, but can be select boxes; what does $info have in it?
		case "SET" :
		case "ENUM" :
			//cell form information
			//cell grid information	
	
			break;
			
		default:
			//the default will be a text box
			//it was already set before we entered this switch
		  Mage::log("Cell type not recognised. Used base defaults instead.\n",null,'autogrid.log');
		
	}//end switch
				
	
}//end function makeDefaultCell



} //end class


/*** DEV NOTES AND CODE GRAVEYARD ***/
  

/*
mysql data types that this module will recognise

INPUT TYPE		DATATYPE			TYPE
Text					Numeric			BIT[(M)]
Text					Numeric			TINYINT[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			BOOL, BOOLEAN
Text					Numeric			SMALLINT[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			MEDIUMINT[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			INT[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			INTEGER[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			BIGINT[(M)] [UNSIGNED] [ZEROFILL]
Text					Numeric			SERIAL is an alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE.
Text					Numeric			DECIMAL[(M[,D])] [UNSIGNED] [ZEROFILL]
Text					Numeric			DEC[(M[,D])] [UNSIGNED] [ZEROFILL], NUMERIC[(M[,D])] [UNSIGNED] [ZEROFILL], FIXED[(M[,D])] [UNSIGNED] [ZEROFILL]
Text					Numeric			FLOAT[(M,D)] [UNSIGNED] [ZEROFILL]
Text					Numeric			DOUBLE[(M,D)] [UNSIGNED] [ZEROFILL]
Text					Numeric			DOUBLE PRECISION[(M,D)] [UNSIGNED] [ZEROFILL], REAL[(M,D)] [UNSIGNED] [ZEROFILL]
Text					Numeric			FLOAT(p) [UNSIGNED] [ZEROFILL]
		
Text with datepicker JS and HTML5 thing	Date and time	DATE
Text with datepicker JS and HTML5 thing	Date and time	DATETIME[(fsp)]
Text with datepicker JS and HTML5 thing	Date and time	TIMESTAMP[(fsp)]
Text with datepicker JS and HTML5 thing	Date and time	TIME[(fsp)]
Text with datepicker JS and HTML5 thing	Date and time	YEAR[(2|4)]
		
M<255, text. M>254, textbox		String			[NATIONAL] VARCHAR(M) [CHARACTER SET charset_name] [COLLATE collation_name]
Text													String			[NATIONAL] CHAR[(M)] [CHARACTER SET charset_name] [COLLATE collation_name]
Text													String			BINARY(M)
M<255, text. M>254, textbox		String			VARBINARY(M)
Text													String			TINYBLOB
Text													String			TINYTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
Text box											String			BLOB[(M)]
Text box											String			TEXT[(M)] [CHARACTER SET charset_name] [COLLATE collation_name]
Text box											String			MEDIUMBLOB
Text box											String			MEDIUMTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
Text box											String			LONGBLOB
Text box											String			LONGTEXT [CHARACTER SET charset_name] [COLLATE collation_name]
Text box											String			ENUM('value1','value2',...) [CHARACTER SET charset_name] [COLLATE collation_name]
Select (but propose unsupported here)		SET('value1','value2',...) [CHARACTER SET charset_name] [COLLATE collation_name]
		
List of possible HTML(5) input types		
text		
textbox		
radiobutton		
select		
multiselect		
		
HTML5 input types		
color		
date		
datetime		
datetime-local		
email		
month		
number		
range		
search		
tel		
time		
url		
week		

*/

//usage : 

//$object->makeAdminForm();

//assume :
//--only one tab unless specified in XML config
//--only one groups unless specified in XML config


//function makeAdminForm(){

/*
	//get the table PATH from the config somehow:
	$tablePath = ;
	
  //get the table name from the table path
  $tableName = Mage::getSingleton('core/resource')->getTableName($tableName);
  $tableName = $this->getTable('core/config_data');
  $tableName = 
  //ask the database for the column types
  
  $adapter  Mage_Core_Model_Resource_Helper_Mysql4::  ->_getReadAdapter();
  $adapter->showTableStatus($tableName);
  
  //see also
  class Mage_Backup_Model_Resource_Db
  public function getTableStatus($tableName)
  //it returns teh status as a varien object
  //so we borrow from that model
  $usefulModel = Mage::getModel("backup/resource_db");
  $tableStatusObject = $usefulModel->getTableStatus($tableName)
  
  //Hidden under '$adapter' - don't worry about it.
  Magento 1.X
  Magento 2.0
*/ //-- from Jaques  
  
  //loop the database types
  //   -- is there a XML config for the form input type
  //   -- OR match the SQL type to a defualt form input type (see table above)
  
  //NOW
  
// $cell = Mage::getModel('magehack_autogrid/cell');
// $cell->setName($name)  //MAGIC SETTERS
// ->setType($info['DATA_TYPE']);
// $table->addCell($cell);

//}


/*
$struct
  $name
  $info
  
  (returned from describeTable() )
  
  AND CREATE a 'CELL' object

GetForm  (get form)
  name
  id
  Type
  inputType
  configArray
  
  
GetGrid things
  somethings
  column width
  
  
  path
  
  
  //LATER
  
  //Prototype 'output':

    	  $fieldset->addField('corporate_address', 'editor', array(
          'label'     => Mage::helper('wholesalertracker')->__('Corporate address'),
          'title'     => Mage::helper('wholesalertracker')->__('Address'),
          'style'     => 'width:700px; height:70px;',
		      'wysiwyg'   => false,
          'required'  => false,
          'name'      => 'corporate_address',
      ));
	
	  $fieldset->addField('corporate_telephone_number', 'text', array(
          'label'     => Mage::helper('wholesalertracker')->__('Corporate phone number'),
		      //'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => 'corporate_telephone_number',
      ));  
  
  	  $fieldset->addField('kiosk_type', 'select', array(
          'label'     => Mage::helper('wholesalertracker')->__('Kiosk type'),
		      //'class'  => 'color {hash:true,required:false}',
          'required'  => false,
          'name'      => 'kiosk_type',
          'values'    => array(
              array(
                  'value'     => 'kiosk',
                  'label'     => Mage::helper('wholesalertracker')->__('Retail kiosk'),
              ),

              array(
                  'value'     => 'embedded',
                  'label'     => Mage::helper('wholesalertracker')->__('Embedded in website'),
              )
              ),
          
      ));  
    
    
  //list of inputs to the ->addField() method  
  
  
  
  
  */
  
  
 

?>
