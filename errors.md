File: (179) vendor\paypal\rest-api-sdk-php\lib\PayPal\Common\PayPalModel.php

Change

} else if (sizeof($v) <= 0 && is_array($v) ) {

to

} else if (is_array($v) && sizeof($v) <= 0) {
