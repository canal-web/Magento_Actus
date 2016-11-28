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
 * Actu view block
 *
 * @category    Canalweb
 * @package     Canalweb_Actus
 * @author      Ultimate Module Creator
 */
class Canalweb_Actus_Block_Actu_View extends Mage_Core_Block_Template
{
    /**
     * get the current actu
     *
     * @access public
     * @return mixed (Canalweb_Actus_Model_Actu|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentActu()
    {
        return Mage::registry('current_actu');
    }
}
