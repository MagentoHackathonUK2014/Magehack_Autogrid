<?php


class Magehack_Autogrid_Test_Model_Resource_GenericEntityTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Resource_GenericEntity';

    /**
     * @param string $tableName
     * @return Magehack_Autogrid_Model_Resource_GenericEntity
     */
    protected function getInstance($tableName = null)
    {
        /** @var Magehack_Autogrid_Model_Resource_GenericEntity $instance */
        $instance = new $this->class;
        
        $stubHelper = $this->getMock('Magehack_Autogrid_Helper_Data');
        
        $stubConfig = $this->getMock('Magehack_Autogrid_Model_Config');
        if ($tableName) {
            $stubConfig->expects($this->any())
                ->method('getTableName')
                ->withAnyParameters()
                ->will($this->returnValue('dummy_table'));
        }
        
        $stubTableParser = $this->getMock('Magehack_Autogrid_Model_Resource_Table_Parser');
        
        $instance->setHelper($stubHelper);
        
        $instance->setConfig($stubConfig);
        
        $instance->setTableParser($stubTableParser);
        
        return $instance;
    }

    /**
     * Return a property value, regardless if it is public or not.
     * 
     * @param object|string $instance Class name or object instance
     * @param string $property
     * @return mixed
     */
    protected function getPropertyValue($instance, $property)
    {
        $property = new ReflectionProperty($instance, $property);
        $isPublic = $property->isPublic();
        if (! $isPublic) {
            $property->setAccessible(true);
        }
        $value = $property->getValue($instance);
        if (! $isPublic) {
            $property->setAccessible(false);
        }
        
        return $value;
    }

    public function testItExists()
    {
        $this->assertTrue(class_exists($this->class), "Class {$this->class} not found by autoloader");
    }

    public function testItsAEntityResourceModel()
    {
        $instance = $this->getInstance();
        $result =  $instance instanceof Mage_Core_Model_Resource_Abstract;
        $this->assertTrue($result);
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getResourceModelClassName('magehack_autogrid/table');
        $this->assertEquals($this->class, $result);
    }

    /**
     * @expectedException Mage_Core_Exception
     * @expectedExceptionMessage Empty main table name
     */
    public function testItIsInstantiatedWithoutATable()
    {
        $instance = $this->getInstance('dummy_table');

        $this->app()->getConfig()->setNode(
            'global/models/magehack_autogrid_resource/entities/dummy_table/table', 'dummy_table_name'
        );
        
        $instance->getMainTable();
    }
    
    public function testItIsInitializedBySetAutoGridTableId()
    {
        $instance = $this->getInstance('dummy_table');
        
        $this->app()->getConfig()->setNode(
            'global/models/magehack_autogrid_resource/entities/dummy_table/table', 'dummy_table_name'
        );
        
        $instance->setAutoGridTableId('dummy_table_id');

        $this->assertNotEmpty($instance->getMainTable());
    }
}