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
 * View subject block
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Block_Subject_View extends Mage_Core_Block_Template{
    protected $_subject = null;
    protected $_messages = null;
    
    public function _construct(){
        $this->setTemplate('w3site/forum/message.phtml');
    }
    
    public function setSubjectModel($subjectModel){
        $this->_subject = $subjectModel;
        return $this;
    }
    
    public function setMessagesCollection($messagesCollection){
        $this->_messages = $messagesCollection;
    }
    
    public function getMessagesCollection(){
        return $this->_messages;
    }
    
    public function getSubjectModel(){
        if (!$this->_subject){
            $this->prepareModels();
        }
        
        return $this->_subject;
    }
    
    public function getForumId(){
        return $this->getSubjectModel()->getForumId();
    }
    
    public function getSubjectId(){
        return $this->getSubjectModel()->getId();
    }
    
    public function isEditable(){
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $customerData->getId();
        $subjectCustomerId = $this->getSubjectModel()->getCustomerId();
        
        if ($subjectCustomerId == $customerId){
            return true;
        }
        
        return false;
    }
    
    public function getAuthor(){
        return $this->getSubjectModel()->getAuthor();
    }
    
    protected function _toHtml(){
        $subjectModel = $this->getSubjectModel();
        $this->addData($subjectModel->getData());
        
        if ($toolbarPagerBlock = $this->getChild('toolbar_pager')){
            $toolbarPagerBlock->setCollection($this->getMessagesCollection());
        }
        
        return parent::_toHtml();
    }
}
?>