<?php


class Magehack_Autogrid_Test_Model_GenericEntityTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_GenericEntity';

    /**
     * @return Magehack_Autogrid_Model_GenericEntity
     */
    protected function getInstance()
    {
        $stubResource = $this->getMockBuilder('Magehack_Autogrid_Model_Resource_GenericEntity')
            ->disableOriginalConstructor()
            ->getMock();
        /** @var Magehack_Autogrid_Model_GenericEntity $instance */
        $instance = new $this->class;
        $this->app()->getConfig()
            ->replaceInstanceCreation('resource_model', 'magehack_autogrid/genericEntity', $stubResource);
        
        return $instance;
    }
    
    public function testItExists()
    {
        $this->assertTrue(class_exists($this->class), "Class {$this->class} not found by autoloader");
    }
    
    public function testItsAEntityModel()
    {
        $instance = $this->getInstance();
        $result =  $instance instanceof Mage_Core_Model_Abstract;
        $this->assertTrue($result);
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getModelClassName('magehack_autogrid/table');
        $this->assertEquals($this->class, $result);
    }

    public function testItsResourceModelIsCorrect()
    {
        $this->assertAttributeEquals('magehack_autogrid/table', '_resourceName', $this->getInstance());
    }

    public function testSetAutoGridTableIdInitializesTheResource()
    {
        $instance = $this->getInstance();
        
        /** @var PHPUnit_Framework_MockObject_MockObject $resource */
        $resource = $instance->getResource();
        $resource->expects($this->once())
            ->method('setAutoGridTableId');
        
        $instance->setAutoGridTableId('dummy_table_id');
    }
}