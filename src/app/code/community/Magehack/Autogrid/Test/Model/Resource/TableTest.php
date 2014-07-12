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
        return new $this->class;
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

    public function testItsTableNameIsCorrect()
    {
        $instance = $this->getInstance();
        $result = $instance->getMainTable();
        $this->assertEquals('magehack_autogrid_dummy', $result);
    }
    
    public function testItsIdFieldIsCorrect()
    {
        $instance = $this->getInstance();
        $result = $instance->getIdFieldName();
        $this->assertEquals('id', $result);
    }
}