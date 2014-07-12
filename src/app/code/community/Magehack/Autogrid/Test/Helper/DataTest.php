<?php


class Magehack_Autogrid_Test_Helper_DataTest
    extends EcomDev_PHPUnit_Test_Case
{
    protected $class = 'Magehack_Autogrid_Helper_Data';

    /**
     * @return Magehack_Autogrid_Helper_Data
     */
    public function getInstance()
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
        $result = $instance instanceof Mage_Core_Helper_Abstract;
        $this->assertTrue($result);
    }

    public function testItCanBeInstantiatedViaFactory()
    {
        $result = Mage::getConfig()->getHelperClassName('magehack_autogrid');
        $this->assertEquals($this->class, $result);
    }
} 