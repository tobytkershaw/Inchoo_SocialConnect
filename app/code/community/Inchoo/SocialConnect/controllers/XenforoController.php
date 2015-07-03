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

class Inchoo_SocialConnect_XenforoController extends Inchoo_SocialConnect_Controller_Abstract
{

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {
        Mage::helper('inchoo_socialconnect/xenforo')->disconnect($customer);

        Mage::getSingleton('core/session')
            ->addSuccess(
                $this->__('You have successfully disconnected your Xenforo account from our store account.')
            );
    }

    protected function _connectCallback() {
        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        if(!($errorCode || $code) && !$state) {
            // Direct route access - deny
            return $this;
        }

        if(!$state || $state != Mage::getSingleton('core/session')->getXenforoCsrf()) {
            return $this;
        }

        if($errorCode) {
            // Xenforo API read light - abort
            if($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')
                    ->addNotice(
                        $this->__('Xenforo Connect process aborted.')
                    );

                return $this;
            }

            throw new Exception(
                sprintf(
                    $this->__('Sorry, "%s" error occured. Please try again.'),
                    $errorCode
                )
            );
        }

        if ($code) {
            // Xenforo API green light - proceed

            $info = Mage::getModel('inchoo_socialconnect/xenforo_info')->load();
            /* @var $info Inchoo_SocialConnect_Model_Xenforo_Info */

            $token = $info->getClient()->getAccessToken();

            $customersByXenforoId = Mage::helper('inchoo_socialconnect/xenforo')
                ->getCustomersByXenforoId($info->getId());

            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                // Logged in user
                if($customersByXenforoId->getSize()) {
                    // Xenforo account already connected to other account - deny
                    Mage::getSingleton('core/session')
                        ->addNotice(
                            $this->__('Your Xenforo account is already connected to one of our store accounts.')
                        );

                    return $this;
                }

                // Connect from account dashboard - attach
                $customer = Mage::getSingleton('customer/session')->getCustomer();

                Mage::helper('inchoo_socialconnect/xenforo')->connectByXenforoId(
                    $customer,
                    $info->getUser_id(),
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Xenforo account is now connected to your store account. You can now login using our Xenforo Login button or using store account credentials you will receive to your email address.')
                );

                return $this;
            }

            if($customersByXenforoId->getSize()) {
                // Existing connected user - login
                $customer = $customersByXenforoId->getFirstItem();

                Mage::helper('inchoo_socialconnect/xenforo')->loginByCustomer($customer);

                Mage::getSingleton('core/session')
                    ->addSuccess(
                        $this->__('You have successfully logged in using your Xenforo account.')
                    );

                return $this;
            }

            $customersByEmail = Mage::helper('inchoo_socialconnect/facebook')
                ->getCustomersByEmail($info->getUser_email());

            if($customersByEmail->getSize())  {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();

                Mage::helper('inchoo_socialconnect/xenforo')->connectByXenforoId(
                    $customer,
                    $info->getUser_id(),
                    $token
                );

                Mage::getSingleton('core/session')->addSuccess(
                    $this->__('We have discovered you already have an account at our store. Your Xenforo account is now connected to your store account.')
                );

                return $this;
            }

            $userame = $info->getUserame();
            if(empty($userame)) {
                throw new Exception(
                    $this->__('Sorry, could not retrieve your Xenforo username. Please try again.')
                );
            }

            Mage::helper('inchoo_socialconnect/xenforo')->connectByCreatingAccount(
                $info->getUser_email(),
                $info->getUsername(),
                $info->getUser_id(),
                $token
            );

            Mage::getSingleton('core/session')->addSuccess(
                $this->__('Your Xenforo account is now connected to your new user account at our store. Now you can login using our Xenforo Login button or using store account credentials you will receive to your email address.')
            );
        }
    }

}