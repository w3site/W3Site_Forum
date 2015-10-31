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
 * Forum subjects controller
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_SubjectController extends Mage_Core_Controller_Front_Action
{
    protected function _redirectRewriteUrl($currentRewrite){
        $parsedCurrentUrl = parse_url(Mage::helper('core/url')->getCurrentUrl());
        $currentUrl = $parsedCurrentUrl['scheme'] . '://' . $parsedCurrentUrl['host'] . $parsedCurrentUrl['path'];
        
        if ($currentRewrite != $currentUrl){
            $this->_redirectUrl($currentRewrite . ($parsedCurrentUrl['query'] ? '?' . $parsedCurrentUrl['query'] : ''));
            return true;
        }
    }
    
    public function listAction(){
        $request = $this->getRequest();
        $this->loadLayout();
        
        $headBlock = $this->getLayout()->getBlock('head');
        $forumId = $request->getParam('id');
        
        $currentRewrite = Mage::helper('w3site_forum')->getUrl('forum/subject/list/id/' . $forumId);
        
        if ($this->_redirectRewriteUrl($currentRewrite)){
            return;
        }
        
        $forumModel = Mage::getModel('w3site_forum/forum');
        $forumModel->load($forumId);
        
        $this->getLayout()->getBlock('breadcrumbs')
            ->addCrumb('forums', array(
                'label' => Mage::getStoreConfig('w3site_forum/index/title'),
                'link' => Mage::helper('w3site_forum')->getUrl('forum')
            ))
            ->addCrumb('subjects', array(
                'label' => $forumModel->getTitle()
            ));
        
        if (!$forumModel->getId()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('forum'));
            return;
        }
        
        $headBlock->setTitle($forumModel->getTitle());
        
        $forumListBlock = $this->getLayout()->getBlock('w3site_forum_subject_list');
        $forumListBlock->setForumId($request->getParam('id'));
        $forumListBlock->setForumModel($forumModel);
        
        if (!Mage::getSingleton('customer/session')->isLoggedIn()){
            Mage::getSingleton('customer/session')->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
        }
        
        try{
            $this->renderLayout();
        }
        catch(Exception $e){
            $pageId = Mage::getStoreConfig('web/default/cms_no_route');
            Mage::app()->getFrontController()->getResponse()->setRedirect(rtrim(Mage::getUrl($pageId),'/'));
            return;
        }
    }
    
    public function viewAction(){
        $subjectId = $this->getRequest()->getParam('id');
        
        $currentRewrite = Mage::helper('w3site_forum')->getUrl('forum/subject/view/id/' . $subjectId);
        if ($this->_redirectRewriteUrl($currentRewrite)){
            return;
        }
        
        $this->loadLayout();
        
        if (!Mage::getSingleton('customer/session')->isLoggedIn()){
            Mage::getSingleton('customer/session')->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
        }
        
        $viewSubjectBlock = $this->getLayout()->getBlock('w3site_forum_subject_view');
        
        try{
            $subjectModel = Mage::getModel('w3site_forum/subject');
            $subjectModel->load($subjectId, 'id');
            
            if (!$subjectModel->getId()){
                throw new Exception('Subject not found', 404);
            }
            
            $viewSubjectBlock->setSubjectModel($subjectModel);
            
            $forumModel = Mage::getModel('w3site_forum/forum');
            $forumModel->load($subjectModel->getForumId());
            
            $this->getLayout()->getBlock('breadcrumbs')
                ->addCrumb('forums', array(
                    'label' => Mage::getStoreConfig('w3site_forum/index/title'),
                    'link' => Mage::helper('w3site_forum')->getUrl('forum')
                ))
                ->addCrumb('subjects', array(
                    'label' => $forumModel->getTitle(),
                    'link' => Mage::helper('w3site_forum')->getUrl('forum/subject/list', array('id'=>$forumModel->getId()))
                ))
                ->addCrumb('subject', array(
                    'label' => $subjectModel->getTitle()
                ));
            
            $this->getLayout()->getBlock('head')->setTitle($subjectModel->getTitle());
            
            $messagesCollection = Mage::getModel('w3site_forum/message')->getCollection();
            $messagesCollection->addFieldToFilter('subject_id', $subjectId)
                    ->setOrder('updated', 'DESC');

            $viewSubjectBlock->setMessagesCollection($messagesCollection);
            
            $this->renderLayout();
        }
        catch(Exception $e){
            $pageId = Mage::getStoreConfig('web/default/cms_no_route');
            Mage::app()->getFrontController()->getResponse()->setRedirect(rtrim(Mage::getUrl($pageId),'/'));
            return;
        }
    }
    
    public function editAction(){
        $helper = Mage::helper('w3site_forum');
        $request = $this->getRequest();
        
        $subjectModel = Mage::getModel('w3site_forum/subject');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        
        if ($request->getParam('id')){
            $subjectModel->load($request->getParam('id'));
        }
        
        if ($subjectModel->getId() && $subjectModel->getCustomerId() != $customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Wrong permissions to modify that message'));
            $this->_redirectReferer();
            return;
        }
        
        $forumModel = Mage::getModel('w3site_forum/forum');
        $forumModel->load($subjectModel->getForumId());
        
        $this->loadLayout();
        
        $subjectBlock = $this->getLayout()->getBlock('w3site_forum_subject_edit');
        $subjectBlock->addData($subjectModel->getData());
        
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
            ->addCrumb('subjectEdit', array(
                'label' => Mage::helper('w3site_forum')->__('Subject')
            ));
        
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('w3site_forum')->__('Edit Subject'));
        
        $this->renderLayout();
    }
    
    public function editPostAction(){
        $helper = Mage::helper('w3site_forum');
        $request = $this->getRequest();
        
        $subjectModel = Mage::getModel('w3site_forum/subject');
        
        $session = Mage::getSingleton('customer/session');
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        
        if ($session->getEditSubjectTime() && $session->getEditSubjectTime() > time() - 15){
            Mage::getSingleton('core/session')->addError($helper->__('The subject you can create in 15 seconds'));
            $this->_redirectReferer();
            return;
        }
        
        if (!$customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Only authorised users can create new subject'));
            $this->_redirectReferer();
            return;
        }
        
        if ($request->getParam('id')){
            $subjectModel->load($request->getParam('id'));
        }
        
        if (!$request->getParam('forum_id') && !$subjectModel->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Some error whan you trying to work with subject'));
            $this->_redirectReferer();
            return;
        }
        
        if ($subjectModel->getId() && $subjectModel->getCustomerId() != $customerData->getId()){
            Mage::getSingleton('core/session')->addError($helper->__('Wrong permissions to modify that subject'));
            $this->_redirectReferer();
            return;
        }
        
        $forumId = $request->getParam('forum_id');
        if ($subjectModel->getForumId()){
            $forumId = $subjectModel->getForumId();
        }
        
        $forumModel = Mage::getModel('w3site_forum/forum');
        $forumModel->load($forumId);
        
        if (!$forumModel->getId()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('forum'));
            return;
        }
        
        $subjectModel->setData('title', htmlspecialchars($request->getParam('title')));
        $subjectModel->setData('description', htmlspecialchars($request->getParam('description')));
        if (!$subjectModel->getData('forum_id')){
            $subjectModel->setData('forum_id', $forumId);
        }
        $subjectModel->setData('customer_id', $customerData->getId());
        
        $subjectModel->save();
        Mage::getSingleton('core/session')->addSuccess($helper->__('The subject was sucefully saved'));
        
        $session->setEditSubjectTime(time());
        
        // Send Email
        $subjectModel->sendEmailNotification();
        
        $this->_redirectReferer();
    }
}
?>