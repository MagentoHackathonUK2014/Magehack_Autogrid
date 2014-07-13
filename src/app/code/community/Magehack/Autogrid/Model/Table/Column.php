<?php

class Magehack_Autogrid_Model_Table_Column
    extends Mage_Core_Model_Abstract
    implements Magehack_Autogrid_Model_Table_ColumnInterface
{

    const DEFAULT_COLUMN_WIDTH = '80px';

    protected $name;

    protected $formName;
    protected $formInputType;
    protected $formInfo = array();

    protected $gridColumnId;
    protected $gridInfo = array();

    protected $autogridTableId;

    protected $_config;
    protected $_tableParser;


    /**
     * Setter DI for config model
     *
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     * @return $this
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Setter DI for table parser
     *
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     * @return $this
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser)
    {
        $this->_tableParser = $parser;
        return $this;
    }

    public function isInGrid()
    {
        if (isset($this->autogridTableId) && isset($this->name)) {
            $config     = Mage::getModel('magehack_autogrid/config');
            $gridConfig = $config->getGrid($this->autogridTableId);
            if ($gridConfig && isset($gridConfig['columns'][$this->name]['visiblity']))
                return $gridConfig['columns'][$this->name]['visibility'];
        }
        return true;
    }

    public function isInForm()
    {
        if (isset($this->autogridTableId) && isset($this->name)) {
            $config     = Mage::getModel('magehack_autogrid/config');
            $formConfig = $config->getForm($this->autogridTableId);
            if ($formConfig && isset($formConfig['columns'][$this->name]['visiblity']))
                return $formConfig['columns'][$this->name]['visibility'];
        }
        return true;
    }

    public function setAutogridTableId($id)
    {
        $this->autogridTableId = $id;
    }

    public function getAutogridTableId($id)
    {
        if (isset($this->autogridTableId)) {
            return $this->autogridTableId;
        } else {
            return false;
        }
    }


    /**
     *
     * Returns the id (first parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        } else {
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
        if (isset($this->gridColumnId)) {
            return $this->gridColumnId;
        } else {
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
        if (isset($this->formName)) {
            return $this->formName;
        } else {
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
        if (isset($this->formInputType)) {
            return $this->formInputType;
        } else {
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
        if (isset($this->gridInfo)) {
            return $this->gridInfo;
        } else {
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
        if (isset($this->formInfo)) {
            return $this->formInfo;
        } else {
            return false;
        }
    }

    /**
     * @param string $name the database column name that will get all its data set now
     *
     **/
    public function setColumnName($name)
    {

        $this->setName($name);

        //get the type from the parser
        //the parser is set, yes?
        if (isset($this->_tableParser)) {
            $columnArray = $this->_tableParser->getTableColumnByName($name);
        } else {
            Mage::log("Cannot setColumnName without parser. Please call setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface \$parser).\n", null, 'autogrid.log');
            return false;
        }

        if ($columnArray === null) {
            //then the $name is not in the database
            //but actually $name comes from parser in the first place if you are follwoing the code
            Mage::log("Column name not found in database table.\n", null, 'autogrid.log');
            return false;
        }

        $this->setData($columnArray);
        //use the type to set all the defaults and pull any column info from the config too
        $this->setColumnData($columnArray['type']); //the name is the key to the column datatype
    }


    /**
     * Call this method to set all the column information
     * Column information is set by default and by what is pulled from the autogrid.xml config
     * You must set $this->name before calling this method
     * and if you want to use autogrid.xml you must also set $this->autoGridTableId before calling this method
     *
     * @PARAM string $dataType the SQL datatype of this column (fetched from the parser)
     * @RETURN Magehack_Autogrid_Model_Column ie $this
     *
     */
    public function setColumnData($dataType)
    {

        if (! isset($this->name)) {
            Mage::log("Cannot make default column without name.\n", null, 'autogrid.log');
            return false;
        }
        if (! isset($dataType)) {
            Mage::log("Cannot make default column without data type.\n", null, 'autogrid.log');
            return false;
        }
        //if(! isset($this->autogridTableId) ){
        //    Mage::log("Cannot make default column without autogrid table id.\n", null, 'autogrid.log');
        //    return false;
        //}
        //Well, we can make a column for you without it but it will all be defaults


        //always start by making the default so every data item is populated
        $this->makeDefaultColumn($dataType);

        //then check Magehack_Autogrid_Model_Config to see if there are any specific requests
        //but only if your tableId set:

        if (isset($this->autogridTableId)) {
            //then there might be some configuration beyond the defaults
            $tableId = $this->autogridTableId;

            //Magehack_Autogrid_Model_Config
            //$config = Mage::getModel('magehack_autogrid/config');
            if (! isset($this->_config)) {
                Mage::log("Cannot merge from config with no config. Please call ->setConfig() first.\n", null, 'autogrid.log');
                return false;
            }


            $config = $this->_config; //we assume there must be a config if there is an autogridTableId

            //form config
            $formConfig = $config->getForm($tableId);
            if ($formConfig !== false) {
                if (isset($formConfig['columns'][$this->name]))
                    foreach ($formConfig['columns'][$this->name] as $key => $value) {

                        if ($value != false) {

                            if ($key == "name") {
                                //then set the name
                                $this->formName = $value;
                            } elseif ($key == "type") {
                                //then set the type
                                $this->formInputType = $value;
                            } else {
                                //stick it all in the info array
                                $this->formInfo[$key] = $value;
                            }

                        }
                        //end if value wasn't false

                    }
                //end foreach
            }
            //end if formConfig wasn't false

            //grid config
            $gridConfig = $config->getGrid($tableId);
            if ($gridConfig !== false) {
                foreach ($gridConfig['columns'][$this->name] as $key => $value) {

                    if ($value != false) {

                        if ($key == "name") {
                            //then set the name
                            $this->gridColumnId = $value;
                        } else {
                            //stick it all in the info array
                            $this->gridInfo[$key] = $value;
                        }

                    }
                    //end if value wasn't false

                }
                //end foreach
            }
            //end if gridConfig wasn't false
        }
        //end if there was a tableId

        return $this;
    }

    public function getHelper()
    {
        return Mage::helper('magehack_autogrid');
    }


    /**
     * Sets some column data ready for reading later so that the Admin form or the Admin grid can be created
     * @param $dataType as String
     * @param $m as integer (it means the size of the SQL field eg VARCHAR[M])
     * @return null (nothing)
     *
     */
    public function makeDefaultColumn($dataType, $m = null)
    {

        //we will start with some base defaults
        //then you only need to change one or two things depending on the database type

        $title = $this->getTitle()?$this->getTitle():$this->name; //magic via setData() or use the column id if no title or empty title is set,
        //column grid information
        $this->gridColumnId         = $this->name;
        $this->gridInfo['header']   = $title;
        $this->gridInfo['index']    = $this->name;
        $this->gridInfo['align']    = 'left';
        $this->gridInfo['width']    = self::DEFAULT_COLUMN_WIDTH;
        $this->gridInfo['sortable'] = true;
        //		'type'      => ''//'options',

        //column form information
        $this->setFormName($this->name); //if name is null or not set by parser we are in trouble
        $this->setFormInputType('text'); //'textarea' //editor //radio //select //multiselect //
        $this->formInfo = array(
            'label'    => $title,
            //'class'  => 'color {hash:true,required:false}',
            'required' => false,
            'name'     => $this->name,
        );

        //do you want to consider some special cases
        //such as $this->name = "store_id"
        /*
                $this->addColumn('store_id', array(
                                          'header'     => $this->__('Store View'),
                                          'width'      => '200px',
                                          'index'      => 'store_id',
                                          'header_export'      => 'store_id',
                                          'type'       => 'store',
                                          'store_all'  => false,
                                          'store_view' => true,
                                     ));
               //and similar for the form info                      
         */
        //or $this->name = 'websites'
        /*
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }
        */

        //now we can change some of these for specific data types
        switch (strtoupper($dataType)) {
            //these special cases default to text if M<=255 or null, otherwise textarea
            case "VARCHAR" : //What about M?
            case "VARBINARY" : //What about M?
            case "BLOB" :
            case "TEXT" :
                //column form information
                if ($m) {
                    if ($m > 255) {
                        //TEXTAREA
                        $this->setFormInputType('textarea');
                    } else {
                        //TEXT
                    }
                } else {
                    //A tough choice
                    //LETS SAY TEXT not TEXTAREA
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
                //column form information
                //column grid information
                $this->gridInfo['type'] = 'number';
                break;

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
                $this->setFormInputType('date');
                $this->gridInfo['type'] = 'datetime';
                break;

            case "TIME" :
                $this->setFormInputType('time');
                $this->gridInfo['type'] = 'datetime';
                break;
            case "YEAR" :
                break;

            //these cases all default to textarea input
            case "MEDIUMBLOB" :
            case "MEDIUMTEXT" :
            case "LONGBLOB" :
            case "LONGTEXT" :
                //column form information
                //column grid information
                break;

            //these cases could default to radio buttons or yes/no select or a check box...
            case "BOOL" :
            case "BOOLEAN" :
                //column form information
                //$this->setFormInputType('text');
                $this->setFormInputType('checkbox');

                //column grid information
                $this->gridInfo['type']    = 'options';
                $this->gridInfo['options'] = array(
                    '1' => 'Yes',
                    '0' => 'No',
                );
                $this->gridInfo['align']   = 'center';
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
