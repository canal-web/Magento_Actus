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
 * Actu admin edit tab attributes block
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
*/
class Canalweb_Actus_Block_Adminhtml_Actu_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the attributes for the form
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setDataObject(Mage::registry('current_actu'));
        $fieldset = $form->addFieldset(
            'info',
            array(
                'legend' => Mage::helper('canalweb_actus')->__('Actu Information'),
                'class' => 'fieldset-wide',
            )
        );
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->setEntity(Mage::getResourceModel('canalweb_actus/actu'));
        }
        $this->_setFieldset($attributes, $fieldset, array());
        $formValues = Mage::registry('current_actu')->getData();
        if (!Mage::registry('current_actu')->getId()) {
            foreach ($attributes as $attribute) {
                if (!isset($formValues[$attribute->getAttributeCode()])) {
                    $formValues[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                }
            }
        }
        $form->addValues($formValues);
        $form->setFieldNameSuffix('actu');
        $this->setForm($form);
    }

    /**
     * prepare layout
     *
     * @access protected
     * @return void
     * @see Mage_Adminhtml_Block_Widget_Form::_prepareLayout()
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('canalweb_actus/adminhtml_actus_renderer_fieldset_element')
        );
    }

    /**
     * get the additional element types for form
     *
     * @access protected
     * @return array()
     * @see Mage_Adminhtml_Block_Widget_Form::_getAdditionalElementTypes()
     * @author Ultimate Module Creator
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'     => Mage::getConfig()->getBlockClassName(
                'canalweb_actus/adminhtml_actu_helper_file'
            ),
            'image'    => Mage::getConfig()->getBlockClassName(
                'canalweb_actus/adminhtml_actu_helper_image'
            ),
            'textarea' => Mage::getConfig()->getBlockClassName(
                'adminhtml/catalog_helper_form_wysiwyg'
            )
        );
    }

    /**
     * get current entity
     *
     * @access protected
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    public function getActu()
    {
        return Mage::registry('current_actu');
    }
}
