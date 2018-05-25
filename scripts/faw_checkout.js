/* global CB_PHPVAR */
//CB_PHPVAR => siteurl ajaxurl
//gets url last part
function getLastPart(url) {
    var parts = url.split("/");
    return (url.lastIndexOf('/') !== url.length - 1
            ? parts[parts.length - 1]
            : parts[parts.length - 2]);
}

(function ($) {
    'use strict';
    $(function () {
$("#faw_checkout").data()
        var merchant = "U93V9MYgl/M=";
        var locale = "ar-eg";
     //   var merchantRefNum = "1234567"; //internal order nu
//        var productsJSON = JSON.stringify(
//                [{
//                        "productSKU": "11112",
//                        "description": "Nokia.",
//                        "price": "50",
//                        "quantity": "2"
//                    }, {
//                        "productSKU": "87456",
//                        "description": "Lenovo",
//                        "price": "20",
//                        "quantity": "1"
//                    }]
//                );
        var customerName = null; //"safkaonline";
        var mobile = "01002662707";
        var email = "eng.shassan@gmail.com";
        var mode = null
        var customerId = "1016058"; //internal customer id
        var orderDesc = "Some Description";
        var orderExpiry = 48; //hours


        $("#faw_checkout").click(function () {
         //   console.log(merchantRefNum);
            loadFawryPluginPopup(merchant, locale, merchantRefNum,
                    productsJSON, customerName, mobile, email, mode, customerId, orderDesc,
                    orderExpiry);

        });

    }); //end $(function() {
})(jQuery);


//user closed after completion
function fawryCallbackFunction() {
    // Your optional implementation which can be empty or you can remove the method at all
}
//user cancelled
function requestCanceldCallBack(merchantRefNum) {
    // Your implementation to handle the cancelbutton
}
