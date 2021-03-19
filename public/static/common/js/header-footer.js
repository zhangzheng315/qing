function init(){
    // $('.nav-pro-con').attr('real-height', $('.nav-pro-con').height());
    $('.footer-about-item').attr('real-height', $('.footer-about-item').height());
    $('.footer-service-item').attr('real-height', $('.footer-service-item').height());
    $('.footer-industry-item').attr('real-height', $('.footer-industry-item').height());
    $('.footer-scene-item').attr('real-height', $('.footer-scene-item').height());
    $('.footer-college-item').attr('real-height', $('.footer-college-item').height());
    // $('.nav-pro-con').css({
    //     'height': '0px'
    // })
    $('.footer-item').css({
        'height': '0px'
    })
}

$(document).ready(function() {
   const deviceWidth = $(window).width();
   if(deviceWidth < 820) {
       init();
   }
})
