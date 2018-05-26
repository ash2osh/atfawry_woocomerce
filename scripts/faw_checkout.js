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
        var locale = "ar-eg";
        var mode = null
   //     var orderExpiry = 48; //hours
          var orderDesc = null;

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
        //   var customerName = null; //"safkaonline";
        //   var mobile = "01000000";
        //   var email = "aaa@gmail.com";

        //   var customerId = "1016058"; //internal customer id




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
    //TODO handle in html
    //hide button
    //change message
    console.log('success');
}
//user cancelled
function requestCanceldCallBack(merchantRefNum) {
    //TODO handle in html
    //add payment failed under message
    console.log('faild');
}
