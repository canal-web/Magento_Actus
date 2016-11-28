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
 * Actu admin controller
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Adminhtml_Actus_ActuController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Canalweb_Actus');
    }

    /**
     * init the actu
     *
     * @access protected 
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    protected function _initActu()
    {
        $this->_title($this->__('Actus'))
             ->_title($this->__('Manage Actus'));

        $actuId  = (int) $this->getRequest()->getParam('id');
        $actu    = Mage::getModel('canalweb_actus/actu')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($actuId) {
            $actu->load($actuId);
        }
        Mage::register('current_actu', $actu);
        return $actu;
    }

    /**
     * default action for actu controller
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_title($this->__('Actus'))
             ->_title($this->__('Manage Actus'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new actu action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit actu action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $actuId  = (int) $this->getRequest()->getParam('id');
        $actu    = $this->_initActu();
        if ($actuId && !$actu->getId()) {
            $this->_getSession()->addError(
                Mage::helper('canalweb_actus')->__('This actu no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getActuData(true)) {
            $actu->setData($data);
        }
        $this->_title($actu->getTitle());
        Mage::dispatchEvent(
            'canalweb_actus_actu_edit_action',
            array('actu' => $actu)
        );
        $this->loadLayout();
        if ($actu->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('canalweb_actus')->__('Default Values'))
                    ->setWebsiteIds($actu->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save actu action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $actuId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $actu     = $this->_initActu();
            $actuData = $this->getRequest()->getPost('actu', array());
            $actu->addData($actuData);
            $actu->setAttributeSetId($actu->getDefaultAttributeSetId());
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $actu->setData($attributeCode, false);
                }
            }
            try {
                $actu->save();
                $actuId = $actu->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('canalweb_actus')->__('Actu was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setActuData($actuData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('canalweb_actus')->__('Error saving actu')
                )
                ->setActuData($actuData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $actuId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete actu
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $actu = Mage::getModel('canalweb_actus/actu')->load($id);
            try {
                $actu->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('canalweb_actus')->__('The actus has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete actus
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $actuIds = $this->getRequest()->getParam('actu');
        if (!is_array($actuIds)) {
            $this->_getSession()->addError($this->__('Please select actus.'));
        } else {
            try {
                foreach ($actuIds as $actuId) {
                    $actu = Mage::getSingleton('canalweb_actus/actu')->load($actuId);
                    Mage::dispatchEvent(
                        'canalweb_actus_controller_actu_delete',
                        array('actu' => $actu)
                    );
                    $actu->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('canalweb_actus')->__('Total of %d record(s) have been deleted.', count($actuIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $actuIds = $this->getRequest()->getParam('actu');
        if (!is_array($actuIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('canalweb_actus')->__('Please select actus.')
            );
        } else {
            try {
                foreach ($actuIds as $actuId) {
                $actu = Mage::getSingleton('canalweb_actus/actu')->load($actuId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d actus were successfully updated.', count($actuIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('canalweb_actus')->__('There was an error updating actus.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/canalweb_actus/actu');
    }

    /**
     * Export actus in CSV format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'actus.csv';
        $content    = $this->getLayout()->createBlock('canalweb_actus/adminhtml_actu_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export actus in Excel format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'actu.xls';
        $content    = $this->getLayout()->createBlock('canalweb_actus/adminhtml_actu_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export actus in XML format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'actu.xml';
        $content    = $this->getLayout()->createBlock('canalweb_actus/adminhtml_actu_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'canalweb_actus/adminhtml_actus_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }
}
