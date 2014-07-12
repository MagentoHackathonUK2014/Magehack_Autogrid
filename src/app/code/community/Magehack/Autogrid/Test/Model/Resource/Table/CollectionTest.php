<?php


class Magehack_Autogrid_Test_Model_Resource_Table_CollectionTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Resource_Table_Collection';

    /**
     * @return Magehack_Autogrid_Model_Resource_Table_Collection
     */
    protected function getInstance()
    {
        $stubZendDbAdapter = $this->getMockForAbstractClass(
            'Zend_Db_Adapter_Abstract', // original class name
            array(), // arguments
            '', // mock class name
            false // call original constructor
        );
        
        $stubResource = $this->getMock('Magehack_Autogrid_Model_Resource_Table');
        $stubResource->expects($this->any())
            ->method('getReadConnection')
            ->withAnyParameters()
            ->will($this->returnValue($stubZendDbAdapter));
        $this->app()->getConfig()->replaceInstanceCreation('resource_model', 'magehack_autogrid/table', $stubResource);
        
        /** @var Magehack_Autogrid_Model_Resource_Table_Collection $instance */
        $instance = new $this->class;
        
        return $instance;
    }

    public function testItExists()
    {
        $this->assertTrue(class_exists($this->class), "Class {$this->class} not found by autoloader");
    }

    public function testItsAEntityCollection()
    {
        $instance = $this->getInstance();
        $result =  $instance instanceof Mage_Core_Model_Resource_Db_Collection_Abstract;
        $this->assertTrue($result);
    }

    public function testItsModelClassIsCorrect()
    {
        $instance = $this->getInstance();
        $this->assertAttributeEquals('Magehack_Autogrid_Model_Table', '_itemObjectClass', $instance);
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getResourceModelClassName('magehack_autogrid/table_collection');
        $this->assertEquals($this->class, $result);
    }
    
    public function testInitInitializesTheSelect()
    {
        $instance = $this->getInstance();
        $this->assertAttributeEmpty('_select', $instance);
        $instance->init('dummy_table_id');
        $this->assertAttributeNotEmpty('_select', $instance);
    }
}