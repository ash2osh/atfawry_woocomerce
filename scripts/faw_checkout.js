/* global CB_PHPVAR */
//FAW_PHPVAR => siteurl ajaxurl
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

console.log(FAW_PHPVAR.ajaxurl);

    }); //end $(function() {
})(jQuery);


//user closed after completion
function fawryCallbackFunction() {

    //change message
    console.log(merchantRefNum);
    jQuery.post(FAW_PHPVAR.ajaxurl, {"action": "ash2osh_faw_payment_recieved", "merchantRefNum": merchantRefNum}, function (response) {
        console.log('Got this from the server: ' + response);
        //reload
    location.reload();
    });

}
//user cancelled
function requestCanceldCallBack(merchantRefNum) {
    //TODO handle in html
    //add payment failed under message
    console.log('faild');
}
