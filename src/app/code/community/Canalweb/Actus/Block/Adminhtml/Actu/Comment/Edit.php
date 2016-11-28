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
 * Actu comment admin edit form
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'canalweb_actus';
        $this->_controller = 'adminhtml_actu_comment';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('canalweb_actus')->__('Save Actu comment')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('canalweb_actus')->__('Delete Actu comment')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'        => Mage::helper('canalweb_actus')->__('Save And Continue Edit'),
                'onclick'    => 'saveAndContinueEdit()',
                'class'        => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('comment_data') && Mage::registry('comment_data')->getId()) {
            return Mage::helper('canalweb_actus')->__(
                "Edit Actu comment '%s'",
                $this->escapeHtml(Mage::registry('comment_data')->getTitle())
            );
        }
        return '';
    }
}
