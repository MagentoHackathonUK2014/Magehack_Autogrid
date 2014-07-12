<?php


class Magehack_Autogrid_Test_Model_Resource_TableTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Resource_Table';

    /**
     * @return Magehack_Autogrid_Model_Resource_Table
     */
    protected function getInstance()
    {
        /** @var Magehack_Autogrid_Model_Resource_Table $instance */
        $instance = new $this->class;
        
        $stubHelper = $this->getMock('Magehack_Autogrid_Helper_Data');
        
        $stubConfig = $this->getMock('Magehack_Autogrid_Model_Config');
        
        $stubTableParser = $this->getMock('Magehack_Autogrid_Model_Resource_Table_Parser');
        
        $instance->setHelper($stubHelper);
        
        $instance->setConfig($stubConfig);
        
        $instance->setTableParser($stubTableParser);
        
        return $instance;
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
}