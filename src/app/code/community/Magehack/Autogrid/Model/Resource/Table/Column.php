<?php

class Magehack_Autogrid_Model_Resource_Table_Column
    extends Mage_Core_Model_Abstract
    implements Magehack_Autogrid_Model_Resource_Table_ColumnInterface
{

    const DEFAULT_COLUMN_WIDTH = '80px';
    
    protected $name;
    protected $type;

    protected $formName;
    protected $formInputType;
    protected $formInfo = array();

    protected $gridColumnId;
    protected $gridInfo = array();


    /**
     *
     * Returns the id (first parameter of addField) for setting up a form field
     *
     * @return array
     */
     public function getName()
     {
     	 if (isset($this->name)){
     	   return $this->name;
     	 }else{
     	 	 return false;
     	 }
     }

     /**
     * @param $columnName string - column name from mysql
     */
     public function setName($columnName)
     {
     	 $this->name = $columnName;
     }

    /**
     *
     * Returns the id (first parameter of addColumn() for setting up an admin grid column
     *
     * @return array
     */
     public function getGridColumnId()
     {
     	 if (isset($this->gridColumnId)){
     	   return $this->gridColumnId;
     	 }else{
     	 	 return false;
     	 }
     }

     /**
     * @param $gridColumnId string - sets the id (first parameter of addColumn() for setting up an admin grid column
     */
     public function setGridColumnId($gridColumnId)
     {
     	 $this->gridColumnId = $gridColumnId;
     }
     
     
    /**
     *
     * Returns the form id for pasing to addfield()
     *
     * @return array
     */
     public function getFormName()
     {
     	 if (isset($this->formName)){
     	   return $this->formName;
     	 }else{
     	 	 return false;
     	 }
     }

     /**
     * @param $formName string - form id for pasing to addfield()
     */
     public function setFormName($formName)
     {
     	 $this->formName = $formName;
     }
     
     
    /**
     *
     * Returns the input type (second parameter of addField) for setting up a form field
     *
     * @return string
     */
     public function getFormInputType()
     {
     	 if (isset($this->formInputType)){
     	   return $this->formInputType;
     	 }else{
     	 	 return false;
     	 }
     }

     public function setFormInputType($formInputType)
     {
     	 $this->formInputType = $formInputType;
     }
     
     
    /**
     *
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo()
    {
     	 if (isset($this->gridInfo)){
     	   return $this->gridInfo;
     	 }else{
     	 	 return false;
     	 }        
    }
    
    /**
     *
     * Returns the info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormInfo()
    {
     	 if (isset($this->formInfo)){
     	   return $this->formInfo;
     	 }else{
     	 	 return false;
     	 }        
    }


    /**
     * $dataType as String
     * Returns (nothing) but sets some column data ready for reading later
     * so that the Admin for or the Admin grid can be created
     */

    public function setType($dataType)
    {

        if (! $this->name) {
            Mage::log("Cannot make default column without name.\n", null, 'autogrid.log');
            return false;
        }
        if (! $dataType) {
            Mage::log("Cannot make default column without data type.\n", null, 'autogrid.log');
            return false;
        }

        //always start by making the default so every data item is populated

        $this->makeDefaultColumn($dataType);

        //then check some autogrid config to see if there are any specific requests
        //and update the deafult values


        //LATER


    }

    public function getHelper()
    {
        return Mage::helper('magehack_autogrid');
    }


    /**
     * $recognisedDataType as String
     * $m as integer (it means the size of the SQL field eg VARCHAR[M])
     * Returns (nothing) but sets some column data ready for reading later
     * so that the Admin for or the Admin grid can be created
     */
    public function makeDefaultColumn($dataType, $m = null)
    {

        //we will start with some base defaults
        //then you only need to change one or two things depending on the database type

        //column grid information
        $this->gridColumnId       = $this->name;
        $this->gridInfo['header'] = ($this->getHelper()->__($this->name));
        $this->gridInfo['index']  = ($this->name);
        $this->gridInfo['align']  = ('left');
        $this->gridInfo['width']  = (self::DEFAULT_COLUMN_WIDTH);
        //		'type'      => ''//'options',

        //column form information
        $this->setFormName($this->name); //if name is null or not set by parser we are in trouble
        $this->setFormInputType('text'); //'textbox' //editor //radio //select //selectmulti
        $this->formInfo = array(
            'label'    => $this->getHelper()->__($this->name),
            //'class'  => 'color {hash:true,required:false}',
            'required' => false,
            'name'     => $this->name,
        );

        //now we can change some of these for specific data types
        switch (strtoupper($dataType)) {

            //these special cases default to text if M<=255 or null, otherwise textbox
            case "VARCHAR" : //What about M?
            case "VARBINARY" : //What about M?
            case "BLOB" :
            case "TEXT" :

                //column form information

                if ($m) {
                    if ($m > 255) {
                        //TEXTBOX
                        $this->setFormInputType('textbox');
                    } else {
                        //TEXT
                    }
                } else {
                    //A tough choice
                    //LETS SAY TEXT not TEXTBOX
                }

                //column grid information

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
                //column form information
                //column grid information

                break;

            //these cases are text input but they might have validation
            //they might have flags for date pickers
            case "DATE" :
            case "DATETIME" :
            case "TIMESTAMP" :
            case "TIME" :
            case "YEAR" :
                //column form information
                //column grid information

                break;

            //these cases all default to textbox input
            case "MEDIUMBLOB" :
            case "MEDIUMTEXT" :
            case "LONGBLOB" :
            case "LONGTEXT" :
                //column form information
                //column grid information

                break;

            //these cases default to yes/no input
            case "BOOL" :
            case "BOOLEAN" :
                //column form information
                //column grid information
                $this->setFormInputType('textbox');

                break;

            //these are edge cases, but can be select boxes; what does $info have in it?
            case "SET" :
            case "ENUM" :
                //column form information
                //column grid information

                break;

            default:
                //the default will be a text box
                //it was already set before we entered this switch
                Mage::log("Column type not recognised. Used base defaults instead.\n", null, 'autogrid.log');

        }
        //end switch


    }
    //end function makeDefaultColumn


} //end class
