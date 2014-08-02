<?php


class Magehack_Autogrid_Test_Model_ConfigTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Config';

    /**
     * @return Magehack_Autogrid_Model_Config
     */
    protected function getInstance()
    {
        /** @var Magehack_Autogrid_Model_Table $instance */
        $instance = new $this->class;
        
        return $instance;
    }

    public function testItExists()
    {
        $this->assertTrue(class_exists($this->class), "Class {$this->class} not found by autoloader");
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getModelClassName('magehack_autogrid/config');
        $this->assertEquals($this->class, $result);
    }
    
    public function testItReturnsAllTableIdsFromTheXml()
    {
        $tableIds = array('table1', 'table2', 'table3');
        $xml = '';
        foreach ($tableIds as $tableId) {
            $xml .= "<$tableId><table>test/table_alias</table></$tableId>";
        }
        $xml = "<config><tables>$xml</tables></config>";
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $this->assertEquals($tableIds, $instance->getAllTableIds());
    }
    

    public function dataProviderItReturnsATableName()
    {
        return array(
            array('some/table_alias'),
            array('some_table_name')
        );
    }

    /**
     * @parmm string $table
     * @dataProvider dataProviderItReturnsATableName
     */
    public function testItReturnsATableNameForAGivenTableId($table)
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>$table</table>
        </$tableId>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $this->assertEquals($table, $instance->getTableName($tableId));
    }

    /**
     * @parmm string $table
     */
    public function testItReturnsFalseForAnInvalidTableId()
    {
        $tableId = 'test_table_id';
        $xml = "<config><tables><$tableId></$tableId></tables></config>";
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $this->assertSame(false, $instance->getTableName($tableId));
    }
    
    public function testItReturnsTheTableTitleForAGivenTableId()
    {
        $tableId = 'test_table_id';
        $title = 'The Table Title';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <title>$title</title>
        </$tableId>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $this->assertEquals($title, $instance->getTableTitle($tableId));
    }
    
    public function testItReturnsAEmptyStrForAGivenTableId()
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
        </$tableId>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $this->assertSame('', $instance->getTableTitle($tableId));
    }
    
    public function dataProviderItReturnsTheDefaultForATableName()
    {
        return array(
            array('source_model', 'expected/value'),
            array('source_model', 'expected/value'),
            array('frontend_input', 'select'),
            array('frontend_input', 'text'),
        );
    }

    /**
     * @param string $key
     * @param string $expected
     * @dataProvider dataProviderItReturnsTheDefaultForATableName
     */
    public function testItReturnsTheDefaultForATableName($key, $expected)
    {
        $columnName = 'test';
        $path = "adminhtml/autogrid/column_defaults/$columnName/$key";
        $this->app()->getConfig()->setNode($path, $expected);
        
        $instance = $this->getInstance();
        $this->assertSame($expected, $instance->getColumnInfoDefault($columnName, $key));
    }
    
    public function testItReturnsTheGridConfig()
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <grid>
                <column_a>
                    <frontend_label>Frontend Label</frontend_label>
                </column_a>
                <column_b>
                    <source_model>magehack_autogrid/table_column_source_yesno</source_model>
                </column_b>
                <column_c>
                    <frontend_label>Multiple fields to parse</frontend_label>
                    <something_special>A different value</something_special>
                    <backend_type>int</backend_type>
                    <header>The Column Title</header>
                </column_c>
            </grid>
        </$tableId>
    </tables>
</config>
EOX;

        $expected = array(
            'column_a' => array(
                'frontend_label' => 'Frontend Label',
                'type' => 'text',
                'index' => 'column_a',
                'header' => 'Column A',
            ),
            'column_b' => array(
                'source_model' => 'magehack_autogrid/table_column_source_yesno',
                'type' => 'options',
                'options' => array(0 => 'No', 1 => 'Yes'),
                'index' => 'column_b',
                'header' => 'Column B',
            ),
            'column_c' => array(
                'frontend_label' => 'Multiple fields to parse',
                'something_special' => 'A different value',
                'backend_type' => 'int',
                'type' => 'numeric',
                'index' => 'column_c',
                'header' => 'The Column Title',
            )
        );
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getGrid($tableId);
        $this->assertEquals($expected, $result);
    }
    
    public function testItMergesInColumnKeyDefaultValues()
    {
        $sourceModel = 'magehack_autogrid/table_column_source_storeId';
        $options = Mage::getModel($sourceModel)->getGridOptionArray();
        $path = "adminhtml/autogrid/column_defaults/store_id/source_model";
        $this->app()->getConfig()->setNode($path, $sourceModel);
        $path = "adminhtml/autogrid/column_defaults/store_id/backend_type";
        $this->app()->getConfig()->setNode($path, 'int');
        
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <grid>
                <store_id>
                    <frontend_label>The Label</frontend_label>
                </store_id>
            </grid>
        </$tableId>
    </tables>
</config>
EOX;
        $expected = array(
            'store_id' => array(
                'frontend_label' => 'The Label',
                'backend_type' => 'int',
                'source_model' => $sourceModel,
                'type' => 'options',
                'options' => $options,
                'index' => 'store_id',
                'header' => 'Store Id',
            )
        );
        
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getGrid($tableId);
        $this->assertEquals($expected, $result);
    }

    public function testItReturnsTheFormConfig()
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <form>
                <column_a>
                    <frontend_label>Frontend Label</frontend_label>
                </column_a>
                <column_b>
                    <source_model>magehack_autogrid/table_column_source_yesno</source_model>
                </column_b>
                <column_c>
                    <frontend_label>Multiple fields to parse</frontend_label>
                    <something_special>A different value</something_special>
                    <backend_type>int</backend_type>
                    <label>The Label</label>
                </column_c>
            </form>
        </$tableId>
    </tables>
</config>
EOX;
        $expected = array(
            'column_a' => array(
                'frontend_label' => 'Frontend Label',
                'frontend_input' => 'text',
                'name' => 'column_a',
                'label' => 'Column A',
            ),
            'column_b' => array(
                'source_model' => 'magehack_autogrid/table_column_source_yesno',
                'frontend_input' => 'select',
                'values' => array(
                    array('value' => 0, 'label' => 'No'),
                    array('value' => 1, 'label' => 'Yes')
                ),
                'name' => 'column_b',
                'label' => 'Column B',
            ),
            'column_c' => array(
                'frontend_label' => 'Multiple fields to parse',
                'something_special' => 'A different value',
                'backend_type' => 'int',
                'frontend_input' => 'text',
                'name' => 'column_c',
                'label' => 'The Label',
            )
        );
        
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getForm($tableId);
        $this->assertEquals($expected, $result);
    }
    
    public function testItReturnsTheSpecifiedColumnInfo()
    {        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <grid>
                <column_a>
                    <frontend_label>A Label</frontend_label>
                    <something_special>A different value</something_special>
                    <backend_type>int</backend_type>
                </column_a>
                <column_b>
                    <backend_type>varchar</backend_type>
                </column_b>
            </grid>
        </$tableId>
    </tables>
</config>
EOX;
        $expected = array(
            'frontend_label' => 'A Label',
            'something_special' => 'A different value',
            'backend_type' => 'int',
            'type' => 'numeric',
            'index' => 'column_a',
            'header' => 'Column A',
        );
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getColumnInfo($tableId, 'column_a');
        $this->assertEquals($expected, $result);
    }

    public function testItReturnsTheSpecifiedFieldInfo()
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <table>tablename</table>
            <form>
                <column_a>
                    <frontend_label>A Label</frontend_label>
                    <something_special>A different value</something_special>
                    <backend_type>int</backend_type>
                </column_a>
                <column_b>
                    <backend_type>varchar</backend_type>
                </column_b>
            </form>
        </$tableId>
    </tables>
</config>
EOX;
        $expected = array(
            'frontend_label' => 'A Label',
            'something_special' => 'A different value',
            'backend_type' => 'int',
            'frontend_input' => 'text',
            'name' => 'column_a',
            'label' => 'Column A'
        );
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getFieldInfo($tableId, 'column_a');
        $this->assertEquals($expected, $result);
    }
    
    public function testItReturnsTheTableIdForAGivenUri()
    {
        $tableId = 'test_table_id';
        $tableUri = 'the_table_uri';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <uri>$tableUri</uri>
        </$tableId>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        $result = $instance->getTableIdFromController($tableUri);
        $this->assertEquals($tableId, $result);
    }
    
    public function testItNotReturnsTheTableIdForAGivenTableIdIfTheTableHasAnUri()
    {
        $tableId = 'test_table_id';
        $tableUri = 'the_table_uri';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId>
            <uri>$tableUri</uri>
        </$tableId>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        
        // fetching a table by table Id should be impossible when an uri is specified
        $result = $instance->getTableIdFromController($tableId);
        $this->assertFalse($result);
    }
    
    public function testItReturnsTheTableIdForAGivenTableIdIfTheTableNotHasAnUri()
    {
        $tableId = 'test_table_id';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId/>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);
        
        // fetching a table by table Id should work when no uri is specified
        $result = $instance->getTableIdFromController($tableId);
        $this->assertEquals($tableId, $result);
    }
    
    public function invalidTableUriProvider()
    {
        return array(
            array('.test'),
            array('/test'),
            array('../test'),
            array('test..'),
            array('//test'),
            array('te/*/st'),
        );
    }

    /**
     * @dataProvider invalidTableUriProvider
     */
    public function testItDoesNotAcceptAUriWithInvalidCharacters($tableUri)
    {
        $tableId = 'test';
        $xml = <<<EOX
<config>
    <tables>
        <$tableId/>
    </tables>
</config>
EOX;
        $config = new Mage_Core_Model_Config_Base($xml);
        $instance = $this->getInstance();
        $instance->setConfig($config);

        // fetching a table by table Id should work when no uri is specified
        $result = $instance->getTableIdFromController($tableUri);
        $this->assertFalse($result);
    }
}