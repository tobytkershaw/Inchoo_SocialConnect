<?php
/**
* Inchoo
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Please do not edit or add to this file if you wish to upgrade
* Magento or this extension to newer versions in the future.
** Inchoo *give their best to conform to
* "non-obtrusive, best Magento practices" style of coding.
* However,* Inchoo *guarantee functional accuracy of
* specific extension behavior. Additionally we take no responsibility
* for any possible issue(s) resulting from extension usage.
* We reserve the full right not to provide any kind of support for our free extensions.
* Thank you for your understanding.
*
* @category Inchoo
* @package SocialConnect
* @author Marko Martinović <marko.martinovic@inchoo.net>
* @copyright Copyright (c) Inchoo (http://inchoo.net/)
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/

class Inchoo_SocialConnect_Block_Checkout extends Mage_Core_Block_Template
{
    protected $clientGoogle = null;
    protected $clientFacebook = null;
    protected $clientTwitter = null;
    protected $clientLinkedin = null;
    protected $clientXenforo = null;

    protected $numEnabled = 0;
    protected $numShown = 0;

    protected function _construct() {
        parent::_construct();

        $this->clientGoogle = Mage::getSingleton('inchoo_socialconnect/google_oauth2_client');
        $this->clientFacebook = Mage::getSingleton('inchoo_socialconnect/facebook_oauth2_client');
        $this->clientTwitter = Mage::getSingleton('inchoo_socialconnect/twitter_oauth_client');
        $this->clientLinkedin = Mage::getSingleton('inchoo_socialconnect/linkedin_oauth2_client');
        $this->clientXenforo = Mage::getSingleton('inchoo_socialconnect/xenforo_oauth2_client');

        if( !$this->_googleEnabled() &&
            !$this->_facebookEnabled() &&
            !$this->_twitterEnabled() &&
            !$this->_xenforoEnabled() &&
            !$this->_linkedinEnabled()) {
            return;
        }

        if($this->_googleEnabled()) {
            $this->numEnabled++;
        }

        if($this->_facebookEnabled()) {
            $this->numEnabled++;
        }

        if($this->_twitterEnabled()) {
            $this->numEnabled++;
        }

        if($this->_linkedinEnabled()) {
            $this->numEnabled++;
        }
        
        if($this->_xenforoEnabled()) {
            $this->numEnabled++;
        }

        Mage::register('inchoo_socialconnect_button_text', $this->__('Continue'));

        $this->setTemplate('inchoo/socialconnect/checkout.phtml');
    }

    protected function _getColSet()
    {
        return 'col'.$this->numEnabled.'-set';
    }

    protected function _getCol()
    {
        return 'col-'.++$this->numShown;
    }

    protected function _googleEnabled()
    {
        return $this->clientGoogle->isEnabled();
    }

    protected function _facebookEnabled()
    {
        return $this->clientFacebook->isEnabled();
    }

    protected function _twitterEnabled()
    {
        return $this->clientTwitter->isEnabled();
    }

    protected function _linkedinEnabled()
    {
        return $this->clientLinkedin->isEnabled();
    }
    
    protected function _xenforoEnabled()
    {
        return $this->clientXenforo->isEnabled();
    }

}