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
 * Forum messages controller
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_MessageController extends Mage_Core_Controller_Front_Action
{
    public function editAction(){
        $helper = Mage::helper('w3site_forum');
        $request = $this->getRequest();
        
        $messageModel = Mage::getModel('w3site_forum/message');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        
        if ($request->getParam('id')){
            $messageModel->load($request->getParam('id'));
        }
        
        if ($messageModel->getId() && $messageModel->getCustomerId() != $customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Wrong permissions to modify that message'));
            $this->_redirectReferer();
            return;
        }
        
        $subjectModel = Mage::getModel('w3site_forum/subject');
        $subjectModel->load($messageModel->getSubjectId());

        $forumModel = Mage::getModel('w3site_forum/forum');
        $forumModel->load($subjectModel->getForumId());
        
        $this->loadLayout();
        
        $this->getLayout()->getBlock('breadcrumbs')
            ->addCrumb('forums', array(
                'label' => Mage::getStoreConfig('w3site_forum/index/title'),
                'link' => Mage::helper('w3site_forum')->getUrl('forum')
            ))
            ->addCrumb('subjects', array(
                'label' => $forumModel->getTitle(),
                'link' => Mage::helper('w3site_forum')->getUrl('forum/subject/list', array('forum_id'=>$forumModel->getId()))
            ))
            ->addCrumb('subject', array(
                'label' => $subjectModel->getTitle(),
                'link' => Mage::helper('w3site_forum')->getUrl('forum/subject/view', array('id'=>$subjectModel->getId()))
            ))
            ->addCrumb('subjectMessage', array(
                'label' => Mage::helper('w3site_forum')->__('Message')
            ));
        
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('w3site_forum')->__('Edit Message'));
        
        $messageBlock = $this->getLayout()->getBlock('w3site_forum_message_edit');
        $messageBlock->addData($messageModel->getData());
        
        $this->renderLayout();
    }
    
    public function editPostAction(){
        $helper = Mage::helper('w3site_forum');
        $request = $this->getRequest();
        
        $messageModel = Mage::getModel('w3site_forum/message');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $session = Mage::getSingleton('customer/session');
        
        if ($session->getEditMessageTime() && $session->getEditMessageTime() > (time() - 10)){
            Mage::getSingleton('core/session')->addError($helper->__('The message you can create or modify in 15 seconds'));
            $this->_redirectReferer();
            return;
        }
        
        if (!$request->getParam('message')){
            Mage::getSingleton('core/session')->addError($helper->__('Message should not be empty'));
            $this->_redirectReferer();
            return;
        }
        
        if (!$customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Only authorised users can create new message'));
            $this->_redirectReferer();
            return;
        }
        
        if ($request->getParam('id')){
            $messageModel->load($request->getParam('id'));
        }
        
        if (!$request->getParam('subject_id') && !$messageModel->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Some error whan you trying to work with message'));
            $this->_redirectReferer();
            return;
        }
        
        if ($messageModel->getId() && $messageModel->getCustomerId() != $customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Wrong permissions to modify that message'));
            $this->_redirectReferer();
            return;
        }

        $messageModel->setData('message', htmlspecialchars($request->getParam('message')));
        if (!$messageModel->getData('subject_id')){
            $messageModel->setData('subject_id', $request->getParam('subject_id'));
        }
        $messageModel->setData('customer_id', $customerData->getId());
        $messageModel->save();
        
        $session->setEditMessageTime(time());
        Mage::getSingleton('core/session')->addSuccess($helper->__('The message sucefully saved'));
        
        // Send Email
        $messageModel->sendEmailNotification();
        
        $this->_redirectReferer();
    }
}
?>