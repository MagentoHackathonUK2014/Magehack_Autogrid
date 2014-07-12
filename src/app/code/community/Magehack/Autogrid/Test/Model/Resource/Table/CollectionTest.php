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
        return new $this->class;
    }

    public function testItExists()
    {
        $this->assertTrue(class_exists($this->class), "Class {$this->class} not found by autoloader");
    }

    public function testItsAEntityModel()
    {
        $instance = $this->getInstance();
        $result =  $instance instanceof Mage_Core_Model_Resource_Db_Collection_Abstract;
        $this->assertTrue($result);
    }

    public function testItsModelClassIsCorrect()
    {
        $instance = $this->getInstance();
        $this->assertAttributeEquals('magehack_autogrid/table', '_itemObjectClass', $instance);
    }
}