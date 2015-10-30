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
 * Forum installation
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
$this->startSetup();

// Add forum
$table = $this->getConnection()
        ->newTable($this->getTable('w3site_forum/forum'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'ID')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
            'nullable'  => false,
        ), 'Title')
        ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'Description');

try{
    $this->getConnection()->createTable($table);
}
catch(Exception $e){
    Mage::log("Table '" . $this->getTable('w3site_forum/forum') . "' already created.");
}

// Add subject
$table = $this->getConnection()
        ->newTable($this->getTable('w3site_forum/subject'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'ID')
        ->addColumn('forum_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'Forum ID')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
            'nullable'  => false,
        ), 'Title')
        ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'Description');

try{
    $this->getConnection()->createTable($table);
}
catch(Exception $e){
    Mage::log("Table '" . $this->getTable('w3site_forum/subject') . "' already created.");
}

// Add message
$table = $this->getConnection()
        ->newTable($this->getTable('w3site_forum/message'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Id')
        ->addColumn('subject_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Subject ID')
        ->addColumn('message', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => false,
        ), 'Description');

try{
    $this->getConnection()->createTable($table);
}
catch(Exception $e){
    Mage::log("Table '" . $this->getTable('w3site_forum/message') . "' already created.");
}

$this->endSetup();