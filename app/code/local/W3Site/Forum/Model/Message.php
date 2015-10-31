<?php
/*******************************************************************************
 * ██╗    ██╗██████╗ ███████╗██╗████████╗███████╗    ██████╗ ██████╗  ██████╗  *
 * ██║    ██║╚════██╗██╔════╝██║╚══██╔══╝██╔════╝   ██╔═══██╗██╔══██╗██╔════╝  *
 * ██║ █╗ ██║ █████╔╝███████╗██║   ██║   █████╗     ██║   ██║██████╔╝██║  ███╗ *
 * ██║███╗██║ ╚═══██╗╚════██║██║   ██║   ██╔══╝     ██║   ██║██╔══██╗██║   ██║ *
 * ╚███╔███╔╝██████╔╝███████║██║   ██║   ███████╗██╗╚██████╔╝██║  ██║╚██████╔╝ *
 *  ╚══╝╚══╝ ╚═════╝ ╚══════╝╚═╝   ╚═╝   ╚══════╝╚═╝ ╚═════╝ ╚═╝  ╚═╝ ╚═════╝  *
 *                                                                             *
 **************************** http://w3site.org/ *******************************
 **************************Author: Tereta Alexander ****************************
 * 
 * W3Site_Forum by Tereta Alexander (w3site.org)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tereta@mail.ua so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade extension to newer
 * versions in the future. If you wish to customize extension for your
 * needs please refer to http://www.w3site.org for more information.
 *
 * @category    W3Site
 * @package     W3Site_Forum
 * @copyright   Copyright (c) 2015-2016 Tereta Alexander. (http://www.w3site.org)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Message model
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Model_Message extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('w3site_forum/message');
    }
    
    protected function _beforeSave() {
        if (!$this->getId()){
            $this->setCreated(date('Y-m-d H:i:s'));
        }
        
        $this->setUpdated(date('Y-m-d H:i:s'));
        
        parent::_beforeSave();
    }
    
    public function isEditable(){
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $customerData->getId();
        $subjectCustomerId = $this->getCustomerId();
        
        if ($subjectCustomerId == $customerId){
            return true;
        }
        
        return false;
    }
    
    static protected $_loadedAuthors = array();
    
    public function getAuthor(){
        $customerId = $this->getCustomerId();
        
        if (isset(static::$_loadedAuthors[$customerId])){
            return $_loadedAuthors[$customerId];
        }
        
        $authorModel = Mage::getModel('customer/customer')->load($customerId);
        return $_loadedAuthors[$customerId] = $authorModel;
    }
    
    public function sendEmailNotification(){
        $helper = Mage::helper('w3site_forum');
        $url = $helper->getUrl('forum/subject/view', array('id' => $this->getSubjectId()));
        
        $storeId = Mage::app()->getStore()->getStoreId();
        
        $vars = array(
                'subject_url' => $url,
                'message'     => $this->getMessage()
                );
        
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('w3site_forum_message_notification');
        $emailTemplate->getProcessedTemplate($vars);
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $storeId));
        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $storeId));
        $emailTemplate->send(
                Mage::getStoreConfig('trans_email/ident_general/email', $storeId),
                Mage::getStoreConfig('trans_email/ident_general/name', $storeId), 
                $vars);
    }
}
?>