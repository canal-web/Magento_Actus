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
 * Actu comment form block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_Comment_Form extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $customerSession = Mage::getSingleton('customer/session');
        parent::__construct();
        $data =  Mage::getSingleton('customer/session')->getActuCommentFormData(true);
        $data = new Varien_Object($data);
        // add logged in customer name as nickname
        if (!$data->getName()) {
            $customer = $customerSession->getCustomer();
            if ($customer && $customer->getId()) {
                $data->setName($customer->getFirstname());
                $data->setEmail($customer->getEmail());
            }
        }
        $this->setAllowWriteCommentFlag(
            $customerSession->isLoggedIn() ||
            Mage::getStoreConfigFlag('canalweb_actus/actu/allow_guest_comment')
        );
        if (!$this->getAllowWriteCommentFlag()) {
            $this->setLoginLink(
                Mage::getUrl(
                    'customer/account/login/',
                    array(
                        Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME => Mage::helper('core')->urlEncode(
                            Mage::getUrl('*/*/*', array('_current' => true)) .
                            '#comment-form'
                        )
                    )
                )
            );
        }
        $this->setCommentData($data);
    }

    /**
     * get current actu
     *
     * @access public
     * @return Canalweb_Actus_Model_Actu
     * @author Ultimate Module Creator
     */
    public function getActu()
    {
        return Mage::registry('current_actu');
    }

    /**
     * get form action
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAction()
    {
        return Mage::getUrl(
            'canalweb_actus/actu/commentpost',
            array('id' => $this->getActu()->getId())
        );
    }
}
