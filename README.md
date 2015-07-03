Inchoo_SocialConnect with xenforo bridge (Beta)
====================

The XenForo connect bridge uses the [\[bd\] api](https://xenforo.com/community/resources/bd-api.1732/) for XenForo to allow users to register and login using their accounts on your XenForo forum.
This requires [\[bd\] api](https://xenforo.com/community/resources/bd-api.1732/) to be installed on your forum.

The bridge will add new users to a Magento customer group called 'xenforo' on registration.

To configure the xenforo bridge edit the file app/code/community/Inchoo/SocialConnect/Model/Xenforo/Oauth2/Client.php

Update the following lines to correspond to your xenforo forum
```
const OAUTH2_REVOKE_URI = 'https://xfrocks.com/api/oauth/revoke';
const OAUTH2_SERVICE_URI = 'https://xfrocks.com/api';
const OAUTH2_AUTH_URI = 'https://xfrocks.com/api/oauth/authorize';
const OAUTH2_TOKEN_URI = 'https://xfrocks.com/api/oauth/token';
```

To customise this for use with your site you will want to replace the word "Xenforo" with your forum name in several places in the code.

You should also create a new login button for your site. I have reused the Twitter one for xenforo as a placeholder.

--------------------

Inchoo_SocialConnect is a Magento extension allowing your customers to login or create an account at your store using their Google, Facebook, Twitter or LinkedIn account.

For usage instructions and more details you can visit my [article at inchoo.net](http://inchoo.net/ecommerce/magento/social-connect-magento-extension/).
