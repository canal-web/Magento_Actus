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
 * Actu customer comments list
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_Customer_Comment_View extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * get current comment
     *
     * @access public
     * @return Canalweb_Actus_Model_Actu_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
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
}
