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
 * Forum model
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Model_Forum extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('w3site_forum/forum');
    }
    
    public function delete($deleteSubData = false)
    {
        $idPath = 'forum/subject/list/id/' . $this->getId();
        $rewrite = Mage::getModel("core/url_rewrite")->loadByIdPath($idPath);
        $rewrite->delete();
        
        if (!$deleteSubData){
            return parent::delete();
        }
        
        $messageModel = Mage::getModel('w3site_forum/subject');
        $messageCollaction = $messageModel->getCollection()->addFieldToFilter('forum_id', $this->getId());
        
        foreach($messageCollaction as $item){
            $item->delete(true);
        }
        
        return parent::delete();
    }
    
    protected function _afterSave() {
        $rewriteForum = Mage::getModel("core/url_rewrite")->loadByIdPath('forum');
        $forumUrl = $rewriteForum->getRequestPath();
        if (!$forumUrl){
            $forumUrl = 'forum';
        }
        
        $idPath = 'forum/subject/list/id/' . $this->getId();
        $rewrite = Mage::getModel("core/url_rewrite")->loadByIdPath($idPath);
        
        if ($rewrite->getUrlRewriteId()){
            return parent::_afterSave();
        }
        
        $rewrite->setStoreId(1)
            ->setIsSystem(0)
            ->setIdPath($idPath)
            ->setTargetPath($idPath)
            ->setOptions();
        
        $url = $forumUrl . '/' . Mage::helper('w3site_forum')->prepareUrl($this->getTitle());
        
        $reservedRewrite = Mage::getModel("core/url_rewrite")->setStoreId(1)->loadByRequestPath($url);
        if ($reservedRewrite->getUrlRewriteId() && $reservedRewrite->getUrlRewriteId() != $rewrite->getUrlRewriteId()){
            $url = $url . '_' . uniqId();
        }
        
        $rewrite->setRequestPath($url);
        $rewrite->save();
        
        return parent::_afterSave();
    }
}
?>