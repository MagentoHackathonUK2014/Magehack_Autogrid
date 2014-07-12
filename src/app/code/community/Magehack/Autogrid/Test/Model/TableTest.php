<?php


class Magehack_Autogrid_Test_Model_TableTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Model_Table';

    /**
     * @return Magehack_Autogrid_Model_Table
     */
    protected function getInstance()
    {
        return new $this->class;
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

    public function testItsResourceModelIsCorrect()
    {
        $this->assertAttributeEquals('magehack_autogrid/table', '_resourceName', $this->getInstance());
    }
}