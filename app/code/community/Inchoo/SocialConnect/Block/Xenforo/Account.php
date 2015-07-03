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

class Inchoo_SocialConnect_Block_Xenforo_Account extends Mage_Core_Block_Template
{
    protected $client = null;
    protected $userInfo = null;

    protected function _construct() {
        parent::_construct();

        $this->client = Mage::getSingleton('inchoo_socialconnect/xenforo_client');
        if(!($this->client->isEnabled())) {
            return;
        }

        $this->userInfo = Mage::registry('inchoo_socialconnect_xenforo_userinfo');

        $this->setTemplate('inchoo/socialconnect/xenforo/account.phtml');
    }

    protected function _hasData()
    {
        return $this->userInfo->hasData();
    }

    protected function _getXenforoId()
    {  
        return $this->userInfo->user_id;
    }

    protected function _getStatus()
    {
        if(!empty($this->userInfo->links->permalink)) {
            $link = '<a href="'.$this->userInfo->links->permalink.'" target="_blank">'.
                    $this->htmlEscape($this->userInfo->username).'</a>';
        } else {
            $link = $this->userInfo->username;
        }

        return $link;
    }

    protected function _getEmail()
    {
        return $this->userInfo->user_email;
    }

    protected function _getPicture()
    {
        if(!empty($this->userInfo->links->avatar)) {
            return Mage::helper('inchoo_socialconnect/xenforo')
                    ->getProperDimensionsPictureUrl($this->userInfo->user_id,
                            $this->userInfo->links->avatar);
        }

        return null;
    }

    protected function _getName()
    {
        return $this->userInfo->username;
    }

    protected function _getGender()
    {
        if(!empty($this->userInfo->gender)) {
            return ucfirst($this->userInfo->gender);
        }

        return null;
    }

    protected function _getBirthday()
    {   
        $user_dob_day = $this->userInfo->user_dob_day;
        $user_dob_month = $this->userInfo->user_dob_month;
        $user_dob_year = $this->userInfo->user_dob_year;
        if(!empty($user_dob_day) && !empty($user_dob_month) && !empty($user_dob_year)) {
            $birthday = date('F j, Y', strtotime($user_dob_month . "/" . $user_dob_day . "/" . $user_dob_year));
            return $birthday;
        }
        return null;
    }

}