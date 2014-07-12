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
        $result = Mage::getConfig()->getModelClassName('magehack_autogrid/table');
        $this->assertEquals($this->class, $result);
    }
} 