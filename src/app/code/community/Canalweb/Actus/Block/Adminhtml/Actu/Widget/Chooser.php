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
 * Actu admin widget chooser
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */

class Canalweb_Actus_Block_Adminhtml_Actu_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @access public
     * @param array $arguments Object data
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author Ultimate Module Creator
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            '*/actus_actu_widget/chooser',
            array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        if ($element->getValue()) {
            $actu = Mage::getModel('canalweb_actus/actu')->load($element->getValue());
            if ($actu->getId()) {
                $chooser->setLabel($actu->getTitle());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var actuId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var actuTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(actuId);
                '.$chooserJsObject.'.setElementLabel(actuTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare a static blocks collection
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Widget_Chooser
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('canalweb_actus/actu')->getCollection();
        $collection->addAttributeToSelect('title');
        $collection->addAttributeToSelect('status');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for the a grid
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Widget_Chooser
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            array(
                'header' => Mage::helper('canalweb_actus')->__('Id'),
                'align'  => 'right',
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => 50
            )
        );

        $this->addColumn(
            'chooser_title',
            array(
                'header' => Mage::helper('canalweb_actus')->__('Title'),
                'align'  => 'left',
                'index'  => 'title',
            )
        );
        $this->addColumn(
            'chooser_status',
            array(
                'header'  => Mage::helper('canalweb_actus')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('canalweb_actus')->__('Disabled'),
                    1 => Mage::helper('canalweb_actus')->__('Enabled')
                ),
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * get url for grid
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/actus_actu_widget/chooser',
            array('_current' => true)
        );
    }
}
