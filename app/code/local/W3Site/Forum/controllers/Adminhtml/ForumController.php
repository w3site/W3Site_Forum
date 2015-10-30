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
 * Admin forum controller
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Adminhtml_ForumController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch(){
        $this->_title($this->__('Forum'));
        
        return parent::preDispatch();
    }
    
    public function listAction(){
        $this->_title($this->__('List'));
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('w3site_forum/admin_forum_list'));
        $this->renderLayout();
    }
    
    public function editAction(){
        $request = $this->getRequest();
        
        $forumModel = Mage::getModel('w3site_forum/forum')->load($request->getParam('id'));
        
        if ($request->getPost()){
            $forumModel->setData('title', $request->getPost('title'));
            $forumModel->setData('description', $request->getPost('description'));
            $forumModel->save();
            
            Mage::getSingleton('core/session')->addSuccess("Forum has been saved"); 
        }

        $this->_title($this->__('Edit'));
        $this->loadLayout();
        
        Mage::register('w3site_forum_form', $forumModel);
        
        $forumEdit = $this->getLayout()->createBlock('w3site_forum/admin_forum_edit');
        
        if ($forumModel->getId()){
            $forumEdit->addSubjectAddButton($forumModel->getId());
        }
        
        $this->_addContent($forumEdit);
        $this->renderLayout();
    }
    
    public function deleteAction(){
        $request = $this->getRequest();
        $messageModel = Mage::getModel('w3site_forum/forum')->load($this->getRequest()->getParam('id'));
        $messageModel->delete(true);
        
        $this->_redirect('*/*/list');
    }
    
    public function subjectAction(){
        $this->_title($this->__('Subjects'));
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('w3site_forum/admin_subject_list'));
        $this->renderLayout();
    }
    
    public function subjectEditAction(){
        $request = $this->getRequest();
        $subjectModel = Mage::getModel('w3site_forum/subject')->load($this->getRequest()->getParam('id'));
        
        if ($forumId = $this->getRequest()->getParam('forum_id')){
            $subjectModel->setData('forum_id', $forumId);
        }
        
        if ($request->getPost()){
            $subjectModel->setData('forum_id', $request->getPost('forum_id'));
            $subjectModel->setData('title', $request->getPost('title'));
            $subjectModel->setData('description', $request->getPost('description'));
            $subjectModel->save();
            
            Mage::getSingleton('core/session')->addSuccess("Subject has been saved"); 
        }
        
        $subjectEditBlock = $this->getLayout()->createBlock('w3site_forum/admin_subject_edit');
        
        if ($subjectModel->getId()){
            $subjectEditBlock->addMessageAddButton($subjectModel->getId());
        }
        
        $this->_title($this->__('Edit'));
        $this->loadLayout();
        
        Mage::register('w3site_forum_subject', $subjectModel);
        
        $this->_addContent($subjectEditBlock);
        $this->renderLayout();
    }
    
    public function subjectDeleteAction(){
        $request = $this->getRequest();
        $messageModel = Mage::getModel('w3site_forum/subject')->load($this->getRequest()->getParam('id'));
        $messageModel->delete(true);
        
        $this->_redirect('*/*/subject');
    }
    
    public function messageAction(){
        $this->_title($this->__('Messages'));
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('w3site_forum/admin_message_list'));
        $this->renderLayout();
    }
    
    public function messageEditAction(){
        $request = $this->getRequest();
        $messageModel = Mage::getModel('w3site_forum/message')->load($this->getRequest()->getParam('id'));
        
        if ($subjectId = $request->getParam('subject_id')){
            $messageModel->setData('subject_id', $subjectId);
        }
        
        if ($request->getPost()){
            $messageModel->setData('subject_id', $request->getPost('subject_id'));
            $messageModel->setData('message', $request->getPost('message'));
            $messageModel->save();
            
            Mage::getSingleton('core/session')->addSuccess("Message has been saved"); 
        }
        
        $this->_title($this->__('Edit'));
        $this->loadLayout();
        
        Mage::register('w3site_forum_message', $messageModel);
        
        $this->_addContent($this->getLayout()->createBlock('w3site_forum/admin_message_edit'));
        $this->renderLayout();
    }
    
    public function messageDeleteAction(){
        $request = $this->getRequest();
        $messageModel = Mage::getModel('w3site_forum/message')->load($this->getRequest()->getParam('id'));
        $messageModel->delete();
        
        $this->_redirect('*/*/message');
    }
}