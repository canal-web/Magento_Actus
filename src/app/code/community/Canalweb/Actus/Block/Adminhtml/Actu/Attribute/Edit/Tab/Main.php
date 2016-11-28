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
 * Adminhtml actu attribute edit page main tab
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Edit_Tab_Main extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Main_Abstract
{
    /**
     * Adding product form elements for editing attribute
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Attribute_Edit_Tab_Main
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $attributeObject = $this->getAttributeObject();
        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $frontendInputElm = $form->getElement('frontend_input');
        $additionalTypes = array(
            array(
                'value' => 'image',
                'label' => Mage::helper('canalweb_actus')->__('Image')
            ),
            array(
                'value' => 'file',
                'label' => Mage::helper('canalweb_actus')->__('File')
            )
        );
        $response = new Varien_Object();
        $response->setTypes(array());
        Mage::dispatchEvent('adminhtml_actu_attribute_types', array('response'=>$response));
        $_disabledTypes = array();
        $_hiddenFields = array();
        foreach ($response->getTypes() as $type) {
            $additionalTypes[] = $type;
            if (isset($type['hide_fields'])) {
                $_hiddenFields[$type['value']] = $type['hide_fields'];
            }
            if (isset($type['disabled_types'])) {
                $_disabledTypes[$type['value']] = $type['disabled_types'];
            }
        }
        Mage::register('attribute_type_hidden_fields', $_hiddenFields);
        Mage::register('attribute_type_disabled_types', $_disabledTypes);

        $frontendInputValues = array_merge($frontendInputElm->getValues(), $additionalTypes);
        $frontendInputElm->setValues($frontendInputValues);

        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $scopes = array(
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                Mage::helper('canalweb_actus')->__('Store View'),
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                Mage::helper('canalweb_actus')->__('Website'),
            Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                Mage::helper('canalweb_actus')->__('Global'),
        );

        $fieldset->addField(
            'is_global',
            'select',
            array(
                'name'  => 'is_global',
                'label' => Mage::helper('canalweb_actus')->__('Scope'),
                'title' => Mage::helper('canalweb_actus')->__('Scope'),
                'note'  => Mage::helper('canalweb_actus')->__('Declare attribute value saving scope'),
                'values'=> $scopes
            ),
            'attribute_code'
        );
        $fieldset->addField(
            'position',
            'text',
            array(
                'name'  => 'position',
                'label' => Mage::helper('canalweb_actus')->__('Position'),
                'title' => Mage::helper('canalweb_actus')->__('Position'),
                'note'  => Mage::helper('canalweb_actus')->__('Position in the admin form'),
            ),
            'is_global'
        );
        $fieldset->addField(
            'note',
            'textarea',
            array(
                'name'  => 'note',
                'label' => Mage::helper('canalweb_actus')->__('Note'),
                'title' => Mage::helper('canalweb_actus')->__('Note'),
                'note'  => Mage::helper('canalweb_actus')->__('Text to appear below the input.'),
            ),
            'position'
        );

        $fieldset->removeField('is_unique');
        // frontend properties fieldset
        $fieldset = $form->addFieldset(
            'front_fieldset',
            array(
                'legend'=>Mage::helper('canalweb_actus')->__('Frontend Properties')
            )
        );
        $fieldset->addField(
            'is_wysiwyg_enabled',
            'select',
            array(
                'name' => 'is_wysiwyg_enabled',
                'label' => Mage::helper('canalweb_actus')->__('Enable WYSIWYG'),
                'title' => Mage::helper('canalweb_actus')->__('Enable WYSIWYG'),
                'values' => $yesnoSource,
            )
        );
        Mage::dispatchEvent(
            'canalweb_actus_adminhtml_actu_attribute_edit_prepare_form',
            array(
                'form'      => $form,
                'attribute' => $attributeObject
            )
        );
        return $this;
    }
}
