jQuery(document).ready(function() {

    //If Local Vars dont exists, show popup every time, else, only when cookies arent set or expired.
    if(typeof acf_popup_object === 'undefined'){
        //If local vars undefined
        setTimeout(function() {
            jQuery(".page_popup_btn").trigger("click");
        },10);
    }else{
        //If local vars defined, and cookies set
        console.log(use_cookies);
        var use_cookies = acf_popup_object.acf_popup_use_cookies;
        var cookie_timeout = acf_popup_object.acf_popup_cookie_timeout
        var cookie_name = acf_popup_object.acf_popup_cookie_name;
        if(use_cookies){
            var cookie_check = checkCookie(cookie_name);
            if(cookie_check == false){
                setTimeout(function() {
                    jQuery(".page_popup_btn").trigger("click");
                    setCookie(cookie_name,1,cookie_timeout);
                },10);
            }
        }
    }
    
    //Page Popup
    jQuery('.page_popup_btn').magnificPopup({
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function() {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });
    
    //Hing animation currently disabled, caused issue with html template, will revisit
    // Hinge effect popup
    // $('a.hinge').magnificPopup({
    //     mainClass: 'mfp-with-fade',
    //     removalDelay: 1000, //delay removal by X to allow out-animation
    //     callbacks: {
    //         beforeClose: function() {
    //             this.content.addClass('hinge');
    //         }, 
    //         close: function() {
    //             this.content.removeClass('hinge'); 
    //         }
    //     },
    //     midClick: true
    // });

});

function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(cookieName) {
    var cookie_val=getCookie(cookieName);
    if(cookie_val != ''){
        return true;
    }else{
        return false;
    }
}