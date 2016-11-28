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
 * Actu admin grid block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Adminhtml_Actu_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('actuGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('canalweb_actus/actu')
            ->getCollection()
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'title', 
            'canalweb_actus_actu/title', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'canalweb_actus_actu_title', 
                'canalweb_actus_actu/title', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('canalweb_actus')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('canalweb_actus')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'canalweb_actus_actu_title', 
                array(
                    'header'    => Mage::helper('canalweb_actus')->__('Title in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'canalweb_actus_actu_title',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('canalweb_actus')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('canalweb_actus')->__('Enabled'),
                    '0' => Mage::helper('canalweb_actus')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('canalweb_actus')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('canalweb_actus')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('canalweb_actus')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('canalweb_actus')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('canalweb_actus')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('canalweb_actus')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('canalweb_actus')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('canalweb_actus')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Ultimate Module Creator
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Canalweb_Actus_Block_Adminhtml_Actu_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('actu');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('canalweb_actus')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('canalweb_actus')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('canalweb_actus')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('canalweb_actus')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('canalweb_actus')->__('Enabled'),
                            '0' => Mage::helper('canalweb_actus')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Canalweb_Actus_Model_Actu
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
