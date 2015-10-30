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
 * Messages grid
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Block_Admin_Message_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('w3site_forum_message_list_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('w3site_forum/message')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
    
    protected function _prepareColumns()
    {
        $helper = Mage::helper('w3site_forum');
        
        $this->addColumn('id', array(
            'header' => $helper->__('#'),
            'index'  => 'id',
            'width'  => '5%'
        ));
        
        $this->addColumn('subject_id', array(
            'header' => $helper->__('Subject'),
            'index'  => 'subject_id',
            'width'  => '5%'
        ));
        
        $this->addColumn('message', array(
            'header' => $helper->__('Message'),
            'index'  => 'message'
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/message', array('_current'=>true));
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/messageEdit', array('id' => $row->getId()));
    }
}