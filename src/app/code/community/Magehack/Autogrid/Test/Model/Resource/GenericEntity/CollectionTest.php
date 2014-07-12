<?php


class Magehack_Autogrid_Test_Model_Resource_GenericEntity_CollectionTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Resource_GenericEntity_Collection';

    /**
     * @return Magehack_Autogrid_Model_Resource_GenericEntity_Collection
     */
    protected function getInstance()
    {
        $stubZendDbAdapter = $this->getMockForAbstractClass(
            'Zend_Db_Adapter_Abstract', // original class name
            array(), // arguments
            '', // mock class name
            false // call original constructor
        );
        
        $stubResource = $this->getMock('Magehack_Autogrid_Model_Resource_GenericEntity');
        $stubResource->expects($this->any())
            ->method('getReadConnection')
            ->withAnyParameters()
            ->will($this->returnValue($stubZendDbAdapter));
        $this->app()->getConfig()->replaceInstanceCreation('resource_model', 'magehack_autogrid/genericEntity', $stubResource);
        
        /** @var Magehack_Autogrid_Model_Resource_GenericEntity_Collection $instance */
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
        $this->assertAttributeEquals('Magehack_Autogrid_Model_GenericEntity', '_itemObjectClass', $instance);
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getResourceModelClassName('magehack_autogrid/genericEntity_collection');
        $this->assertEquals($this->class, $result);
    }
    
    public function testSetAutoGridTableIdInitializesTheSelect()
    {
        $instance = $this->getInstance();
        $this->assertAttributeEmpty('_select', $instance);
        $instance->setAutoGridTableId('dummy_table_id');
        $this->assertAttributeNotEmpty('_select', $instance);
    }
}