  // 解决方案吸顶操作
 let serviceWidth = $(document).width();
 let solutionnav = $('#solution-nav');
  let height = solutionnav.offset().top;
  let winheight = $(window).height();
 //设置监听事件
 if(serviceWidth > 768) {
 $(window).scroll(function() {
     let scrollHeight = $(document).scrollTop();
     if( scrollHeight > $('#section-1').offset().top && scrollHeight < $('#section-1').height() + $('#section-1').offset().top ) {
         $('.nav-content li').eq(0).siblings().removeClass('active');
         $('.nav-content li').eq(0).addClass('active');
     }
     if( scrollHeight > $('#section-2').offset().top && scrollHeight < $('#section-2').height() + $('#section-2').offset().top ) {
         $('.nav-content li').eq(1).siblings().removeClass('active');
         $('.nav-content li').eq(1).addClass('active');
     }
     if( scrollHeight > $('#section-3').offset().top && scrollHeight < $('#section-3').height() + $('#section-3').offset().top ) {
         $('.nav-content li').eq(2).siblings().removeClass('active');
         $('.nav-content li').eq(2).addClass('active');
     }
     if( scrollHeight > $('#section-4').offset().top && scrollHeight < $('#section-4').height() + $('#section-4').offset().top ) {
         $('.nav-content li').eq(3).siblings().removeClass('active');
         $('.nav-content li').eq(3).addClass('active');
     }
    //  if( scrollHeight > $('#section-5').offset().top && scrollHeight < $('#section-5').height() + $('#section-5').offset().top ) {
    //      $('.nav-content li').eq(4).siblings().removeClass('active');
    //      $('.nav-content li').eq(4).addClass('active');
    //  }
     if(scrollHeight >= (height - 65) ) {
         solutionnav.addClass('solution-nav-fixed');
         $('.header-nav').css({
             'display': 'none'
         })
     }else if (scrollHeight < (height - 65)) {
         solutionnav.removeClass('solution-nav-fixed');
         $('.header-nav').css({
             'display': 'block'
         })
     }
 })
}
 $('.nav-content li').on('click', function() {
     if(!$(this).hasClass('active')) {
     $(this).siblings().removeClass('active');
     $(this).addClass('active');
     }
 })