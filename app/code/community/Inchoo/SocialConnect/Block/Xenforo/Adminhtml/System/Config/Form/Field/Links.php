<?php

class Inchoo_SocialConnect_Block_Xenforo_Adminhtml_System_Config_Form_Field_Links
    extends Inchoo_SocialConnect_Block_Adminhtml_System_Config_Form_Field_Links
{

    protected function getAuthProviderLink()
    {
        return 'Xenforo Developers';
    }

    protected function getAuthProviderLinkHref()
    {
        return 'https://xenforo.com/';
    }
    
}