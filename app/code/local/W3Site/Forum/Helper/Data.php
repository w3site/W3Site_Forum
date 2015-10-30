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
 * Forum helper data
 *
 * @category   W3Site
 * @package    W3Site_Forum
 * @author     Teretea Alexander <tereta@mail.ua>
 */
class W3Site_Forum_Helper_Data extends Mage_Core_Helper_Abstract {
    public function prepareUrl($url){
        $url = trim(mb_strToLower($url));
        
        $search = array(
            chr(208) . chr(176), chr(208) . chr(177),
            chr(208) . chr(178), chr(208) . chr(179),
            chr(208) . chr(180), chr(208) . chr(181),
            chr(208) . chr(182), chr(208) . chr(183),
            chr(208) . chr(184), chr(208) . chr(185),
            chr(208) . chr(186), chr(208) . chr(187),
            chr(208) . chr(188), chr(208) . chr(189),
            chr(208) . chr(190), chr(208) . chr(191),
            chr(209) . chr(128), chr(209) . chr(129),
            chr(209) . chr(130), chr(209) . chr(131),
            chr(209) . chr(132), chr(209) . chr(133),
            chr(209) . chr(134), chr(209) . chr(135),
            chr(209) . chr(136), chr(209) . chr(137),
            chr(209) . chr(138), chr(209) . chr(139),
            chr(209) . chr(140), chr(209) . chr(141),
            chr(209) . chr(142), chr(209) . chr(143)
            );
        
        $replace = array(
            'a', 'b', 'v', 'g', 'd', 'e', 'j', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'shch', '', 'y', '', 'e', 'yu', 'ya'
        );
        
        $url = str_replace($search, $replace, $url);
        
        return trim(preg_replace('/[^a-z0-9\-]/Usi', '_', $url), '_');
    }
    
    public function getUrl($path, $params){
        $params['_nosid'] = true;
        
        $paramsString = '';
        foreach($params as $key=>$item){
            if (subStr($key, 0, 1) == '_') continue;
            $paramsString .= '/' . $key . '/' . $item;
        }
        
        $rewrite = Mage::getModel('core/url_rewrite')->loadByIdPath($path . $paramsString);
        if (!$rewrite->getUrlRewriteId()){
            return Mage::getUrl($path, $params);
        }
        
        return Mage::getUrl($rewrite->getRequestPath());
    }
    
    public function prepareText($text, $limit = null){
        if ($limit){
            $text = substr($text, 0, $limit);
        }
        
        $text = str_replace("\n", "<br/>", $text);
        //$text = str_replace(" ", "&nbsp;", $text);
        
        return $text;
    }
}