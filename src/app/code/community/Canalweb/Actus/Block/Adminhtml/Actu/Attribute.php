<?php
/**
 * Canalweb_Actus extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Canalweb
 * @package        Canalweb_Actus
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Actu admin attribute block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_actu_attribute';
        $this->_blockGroup = 'canalweb_actus';
        $this->_headerText = Mage::helper('canalweb_actus')->__('Manage Actu Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('canalweb_actus')->__('Add New Actu Attribute')
        );
    }
}
