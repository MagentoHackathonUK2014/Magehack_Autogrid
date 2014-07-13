<?php

/**
 * Magento Hackathon 2014 UK
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Magehack
 * @package    Magehack_Autogrid
 * @copyright  Copyright (c) 2014 Magento community
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magehack_Autogrid_Model_Table_Column extends Mage_Core_Model_Abstract implements Magehack_Autogrid_Model_Table_ColumnInterface
{

    const DEFAULT_COLUMN_WIDTH = '80px';

    /**
     * Setter DI for config model
     *
     * @param Magehack_Autogrid_Model_ConfigInterface $config
     * @return $this
     */
    public function setConfig(Magehack_Autogrid_Model_ConfigInterface $config)
    {
        return $this->setData('config', $config);
    }

    /**
     * Setter DI for table parser
     *
     * @param Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser
     * @return $this
     */
    public function setTableParser(Magehack_Autogrid_Model_Resource_Table_ParserInterface $parser)
    {
        return $this->setData('table_parser', $parser);
    }

    /**
     * Return whether the column should be visible in a grid
     *
     * @return bool
     */
    public function isInGrid()
    {
        if ($this->hasAutogridTableId() && $this->hasColumnName() && $this->hasConfig()) {
            $config     = $this->getConfig();
            $gridConfig = $config->getGrid($this->getAutogridTableId());
            if ($gridConfig && isset($gridConfig['columns'][$this->getColumnName()]['visiblity'])) {
                return $gridConfig['columns'][$this->getColumnName()]['visibility'];
            }
        }

        return true;
    }

    /**
     * Return whether the column should be visible as a field in a form
     *
     * @return bool
     */
    public function isInForm()
    {
        if ($this->hasAutogridTableId() && $this->hasColumnName() && $this->hasConfig()) {
            $config     = $this->getConfig(); //Mage::getModel('magehack_autogrid/config');
            $formConfig = $config->getForm($this->getAutogridTableId());
            if ($formConfig && isset($formConfig['columns'][$this->getColumnName()]['visiblity'])) {
                return $formConfig['columns'][$this->getColumnName()]['visibility'];
            }
        }

        return true;
    }

    /**
     * Set the autogrid table id the column is associated with
     *
     * @param string $tableId
     * @return $this
     */
    public function setAutogridTableId($tableId)
    {
        return $this->setData('auto_grid_table_id', $id);
    }

    /**
     * Return the autogrid table id
     *
     * @return string
     */
    public function getAutogridTableId()
    {
        return $this->getData('auto_grid_table_id');
    }

    /**
     * Returns the name ot the associated database column
     *
     * @return string
     */
    public function getColumnName()
    {
        return $this->getData('column_name');
    }

    /**
     * Set the database table column name the autogrid table column is associated with
     *
     * @param string $columnName
     * @return $this
     */
    public function setColumnName($columnName)
    {

        $this->setData('column_name', $columnName);

        //get the type from the parser
        //the parser is set, yes?
        if ($this->hasTableParser()) {
            $columnArray = $this->getTableParser()->getTableColumnByName($columnName);
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

        $this->setTableColumn($columnArray);
        //use the type to set all the defaults and pull any column info from the config too
        $this->_setColumnData($columnArray['type']); //the name is the key to the column datatype
    }

    /**
     * Returns the id (first parameter of addColumn() for setting up an admin grid column
     *
     * @return string
     */
    public function getGridColumnId()
    {
        return $this->getData('grid_column_id');
    }

    /**
     * Return the form field element id (forst paramet
     *
     * @return string
     */
    public function getFormFieldId()
    {
        return $this->getData('form_field_id');
    }

    /**
     * Returns the form element input type (e.g. text or select)
     *
     * @return string
     */
    public function getFieldInputType()
    {
        return $this->getData('field_input_type');
    }

    /**
     * Returns the info array (second parameter of addColumn) for setting up a grid column
     *
     * @return array
     */
    public function getGridInfo()
    {
        return $this->getData('grid_info');
    }

    /**
     * Returns the field info array (third parameter of addField) for setting up a form field
     *
     * @return array
     */
    public function getFormFieldInfo()
    {
        return $this->getData('form_field_info');
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
    protected function _setColumnData($dataType)
    {

        if (!isset($dataType)) {
            Mage::log("Cannot make default column without data type.\n", null, 'autogrid.log');
            return false;
        }

        if (!$this->hasColumnName()) {
            Mage::log("Cannot make default column without name.\n", null, 'autogrid.log');
            return false;
        }

        //Well, we can make a column for you without it but it will all be defaults
        //always start by making the default so every data item is populated
        $this->makeDefaultColumn($dataType);

        //then check Magehack_Autogrid_Model_Config to see if there are any specific requests
        //but only if your tableId set:

        if ($this->hasAutogridTableId()) {
            //then there might be some configuration beyond the defaults
            $tableId = $this->getAutogridTableId();

            //Magehack_Autogrid_Model_Config
            //$config = Mage::getModel('magehack_autogrid/config');
            if (!$this->hasConfig()) {
                Mage::log("Cannot merge from config with no config. Please call ->setConfig() first.\n", null, 'autogrid.log');
                return false;
            }


            $config = $this->getConfig(); //we assume there must be a config if there is an autogridTableId
            //form config
            $formConfig = $config->getForm($tableId);
            if ($formConfig !== false) {
                if (isset($formConfig['columns'][$this->getColumnName()])) {
                    foreach ($formConfig['columns'][$this->getColumnName()] as $key => $value) {

                        if ($value != false) {

                            if ($key == "name") {
                                //then set the name
                                $this->setFormFieldId($value);
                            } elseif ($key == "type") {
                                //then set the type
                                $this->setFieldInputType($value);
                            } else {
                                //stick it all in the info array
                                if (!$this->hasFormFieldInfo()) {
                                    $this->setFormFieldInfo(array());
                                }
                                $formFieldInfo = $this->getFormFieldInfo();
                                $fieldFieldInfo[$key] = $value;
                                $this->setFormFieldInfo($formFieldInfo);
                            }
                        }
                        //end if value wasn't false
                    }
                    //end foreach
                }
            }
            //end if formConfig wasn't false
            //grid config
            $gridConfig = $config->getGrid($tableId);
            if ($gridConfig !== false) {
                foreach ($gridConfig['columns'][$this->getColumnName()] as $key => $value) {

                    if ($value != false) {

                        if ($key == "name") {
                            //then set the name
                            $this->setGridColumnId($value);
                        } else {
                            //stick it all in the info array
                            if (!$this->hasGridInfo()) {
                                $this->setGridInfo(array());
                            }
                            $gridInfo = $this->getGridInfo();
                            $gridInfo[$key] = $value;
                            $this->setGridInfo($gridInfo);
                        }
                    }
                    //end if value wasn't false
                }
                //end foreach
            }
            //end if gridConfig wasn't false
            //now the config can change the title and the type
            //so we update the class members anyway just incase they were set from the config
            $fieldInfo = $this->getFormFieldInfo();
            $this->setFormInputType($fieldInfo['type']);
            //LATER $this->setTitle(); //magic
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

        $title                      = $this->getTitle() ? $this->getTitle() : $this->name; //magic via setData() or use the column id if no title or empty title is set,
        //column grid information
        $gridInfo = $this->getGridInfo();
        $this->setGridColumnId($this->getColumnName());
        $gridInfo['header']   = $title;
        $gridInfo['index']    = $this->getColumnName();
        $gridInfo['align']    = 'left';
        $gridInfo['width']    = self::DEFAULT_COLUMN_WIDTH;
        $gridInfo['sortable'] = true;
        //		'type'      => ''//'options',
        //column form information
        $this->setFormName($this->getColumnName()); //if name is null or not set by parser we are in trouble
        $this->setFormInputType('text'); //'textarea' //editor //radio //select //multiselect //
        $this->setFormInfo(array(
            'label'    => $title,
            //'class'  => 'color {hash:true,required:false}',
            'required' => false,
            'name'     => $this->getColumnName(),
        ));

        //now set some defaults based on the SQL data type
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
                $gridInfo = $this->getGridInfo();
                $gridInfo['type'] = 'number';
                $this->setGridInfo($gridInfo);
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
                $gridInfo = $this->getGridInfo();
                $gridInfo['type'] = 'datetime';
                $this->setGridInfo($gridInfo);
                $formInfo = $this->getFormInfo();
                $formInfo['format'] = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                $this->setFormInfo($formInfo);
                break;

            case "TIME" :
                $this->setFormInputType('time');
                $gridInfo = $this->getGridInfo();
                $gridInfo['type'] = 'datetime';
                $this->setGridInfo($gridInfo);
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
                $gridInfo = $this->getGridInfo();
                $gridInfo['type']    = 'options';
                $gridInfo['options'] = array(
                    '1' => 'Yes',
                    '0' => 'No',
                );
                $gridInfo['align']   = 'center';
                $this->setGridInfo($gridInfo);
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
        //now we treat some special cases
        //do you want to consider some special cases
        //such as $this->name = "store_id"
        //or $this->name = 'websites'
        //now there are some defaults in the config too,
        //but I don;t understand them yet
        //$this->config is autogrid.xml but is Vinai proposing config.xml?

        if ($columnSourceModel = $this->getConfig()->getDefaultSourceModel($this->getColumnName())) {

            //column grid information
            $gridInfo = $this->getGridInfo();
            $gridInfo['type']    = 'options';
            $gridInfo['options'] = Mage::getModel($columnSourceModel)->getFlatOptionArray();

            $formInfo = $this->getFormInfo();
            $formInfo['type']   = 'select';
            $formInfo['values'] = Mage::getModel($columnSourceModel)->getSourceOptionArray();
            $this->setGridInfo($gridInfo);
        }

        /*
          Are there special cases?

          switch(strtolower($this->name)){

          case 'websites' :
          //column form information

          //column grid information
          $this->gridInfo['header']    = Mage::helper('catalog')->__('Websites'),
          $this->gridInfo['width']     = '100px',
          $this->gridInfo['sortable']  = false,
          $this->gridInfo['index']     = 'websites',
          $this->gridInfo['type']      = 'options',
          $this->gridInfo['options']   = Mage::getModel('core/website')->getCollection()->toOptionHash(),
          break;

          case 'store_id' :
          //column form information
          $this->setFormInputType('multiselect');

          $this->formInfo['name']      = 'stores[]';
          $this->formInfo['label']     = 'Store View';
          $this->formInfo['title']     = 'Store View';
          $this->formInfo['required']  = true;
          $this->formInfo['values']    = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);

          //column grid information
          $this->gridInfo['header']     = Mage::helper('catalog')->__('Store View');  //is this to restrictive?
          $this->gridInfo['width']      = '200px';									//is this too restrictive?
          $this->gridInfo['index']      = 'store_id';
          $this->gridInfo['header_export'] = 'store_id';
          $this->gridInfo['type']      = 'store';
          $this->gridInfo['store_all']  = false;  //what is this?
          $this->gridInfo['store_view'] = true;

          break;

          default:
          //no default
          }

         */
    }

    //end function makeDefaultColumn
}

//end class
