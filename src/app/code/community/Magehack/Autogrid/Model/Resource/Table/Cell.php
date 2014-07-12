<?php

class Magehack_Autogrid_Model_Resource_Table_Cell
    extends Mage_Core_Model_Abstract
    implements Magehack_Autogrid_Model_Resource_Table_CellInterface
{

    const DEFAULT_COLUMN_WIDTH = '80px';
    protected $gridInfo = array();
    protected $formInfo = array();


    public function getGridInfo()
    {
        return $this->gridInfo;
    }

    public function getGFormInfo()
    {
        return $this->formInfo;
    }


    /**
     * $dataType as String
     * Returns (nothing) but sets some cell data ready for reading later
     * so that the Admin for or the Admin grid can be created
     */

    public function setType($dataType)
    {

        if (! $this->name) {
            Mage::log("Cannot make default cell without name.\n", null, 'autogrid.log');
            return false;
        }
        if (! $dataType) {
            Mage::log("Cannot make default cell without data type.\n", null, 'autogrid.log');
            return false;
        }

        //always start by making the default so every data item is populated

        $this->makeDefaultCell($dataType);

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
     * $m as integer
     * Returns (nothing) but sets some cell data ready for reading later
     * so that the Admin for or the Admin grid can be created
     */
    public function makeDefaultCell($recognisedDataType, $m = null)
    {

        //we will start with some base defaults
        //then you only need to change one or two things depending on the database type

        //cell grid information
        $this->gridColumnId       = $this->name;
        $this->gridInfo['header'] = ($this->getHelper()->__($this->name));
        $this->gridInfo['index']  = ($this->name);
        $this->gridInfo['align']  = ('left');
        $this->gridInfo['width']  = (self::DEFAULT_COLUMN_WIDTH);
        //		'type'      => ''//'options',

        //cell form information
        $this->setFormName($this->name); //if name is null or not set by parser we are in trouble
        $this->setFormInputType('text'); //'textbox' //editor //radio //select //selectmulti
        $this->formInfo = array(
            'label'    => $this->getHelper()->__($this->name),
            //'class'  => 'color {hash:true,required:false}',
            'required' => false,
            'name'     => $this->name,
        );

        //now we can change some of these for specific data types
        switch (strtoupper($recognisedDataType)) {

            //these special cases default to text if M<=255, otherwise textbox
            case "VARCHAR" : //What about M?
            case "VARBINARY" : //What about M?
            case "BLOB" :
            case "TEXT" :

                //cell form information

                if ($m) {
                    if ($m > 255) {
                        //TEXTBOX
                    } else {
                        //TEXT
                    }
                } else {
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
                Mage::log("Cell type not recognised. Used base defaults instead.\n", null, 'autogrid.log');

        }
        //end switch


    }
    //end function makeDefaultCell


} //end class
