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
 * Edit subject block
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Block_Admin_Subject_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $subjectModel = Mage::registry('w3site_forum_subject');
        
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/subjectEdit'),
                'method' => 'post',
            )
        );
        
        $form->setUseContainer(true);
        $this->setForm($form);
        
        $helper = Mage::helper('w3site_forum');
        
        $fieldset = $form->addFieldset('forum', array(
            'legend' => $helper->__('Edit Subject'),
            'class' => 'fieldset-wide'
        ));
        
        $fieldset->addField('id', 'hidden', array(
            'name' => 'id'
        ));
        
        $fieldset->addField('id_note', 'note', array(
            'label' => $helper->__('ID'),
            'text'  => '# ' . $subjectModel->getId()
        ));
        
        $fieldset->addField('forum_id', 'text', array(
            'name' => 'forum_id',
            'label' => $helper->__('Forum ID'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        
        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => $helper->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
        ));
        
        $fieldset->addField('description', 'textarea', array(
            'class'     => 'required-entry',
            'required'  => true,
            'name' => 'description',
            'label' => $helper->__('Description'),
        ));
        
        $form->setValues($subjectModel->getData());
        
        return parent::_prepareForm();
    }
}
?>