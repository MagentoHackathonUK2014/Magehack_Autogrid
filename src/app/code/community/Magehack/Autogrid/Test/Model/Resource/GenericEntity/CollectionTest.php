<?php
/**
 * Magento Hackathon 2014 UK
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Magehack
 * @package    Magehack_Autogrid
 * @copyright  Copyright (c) 2014 Magento community
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

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