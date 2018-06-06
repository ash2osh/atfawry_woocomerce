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
        var mode = null
        var orderDesc = null;

        $("#faw_checkout").click(function () {
            //   console.log(merchantRefNum);
            loadFawryPluginPopup(merchant, locale, merchantRefNum,
                    productsJSON, customerName, mobile, email, mode, customerId, orderDesc,
                    orderExpiry);

        });

//auto click
            $("#faw_checkout").trigger('click');



    }); //end $(function() {
})(jQuery);


//user closed after completion
function fawryCallbackFunction() {

    //change message
    console.log('success');
//reload
    location.reload();
}
//user cancelled
function requestCanceldCallBack(merchantRefNum) {
    //TODO handle in html
    //add payment failed under message
    console.log('faild');
}
