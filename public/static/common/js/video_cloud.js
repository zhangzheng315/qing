  // 视频云服务吸顶操作
  let serviceWidth = $(document).width();
  let solutionnav = $('.navbar');
   let height = solutionnav.offset().top;
   let winheight = $(window).height();
  //设置监听事件
  if(serviceWidth > 768) {
  $(window).scroll(function() {
      let scrollHeight = $(document).scrollTop();
      if( scrollHeight > $('#section-1').offset().top && scrollHeight < $('#section-1').height() + $('#section-1').offset().top ) {
          $('.navbar-content li').eq(0).siblings().removeClass('active');
          $('.navbar-content li').eq(0).addClass('active');
      }
      if( scrollHeight > $('#section-2').offset().top && scrollHeight < $('#section-2').height() + $('#section-2').offset().top ) {
          $('.navbar-content li').eq(1).siblings().removeClass('active');
          $('.navbar-content li').eq(1).addClass('active');
      }
      if( scrollHeight > $('#section-3').offset().top && scrollHeight < $('#section-3').height() + $('#section-3').offset().top ) {
          $('.navbar-content li').eq(2).siblings().removeClass('active');
          $('.navbar-content li').eq(2).addClass('active');
      }
      if( scrollHeight > $('#section-4').offset().top && scrollHeight < $('#section-4').height() + $('#section-4').offset().top ) {
          $('.navbar-content li').eq(3).siblings().removeClass('active');
          $('.navbar-content li').eq(3).addClass('active');
      }
      if(scrollHeight >= (height - 65) ) {
          solutionnav.addClass('video-nav-fixed');
          $('.header-nav').css({
              'display': 'none'
          })
      }else if (scrollHeight < (height - 65)) {
          solutionnav.removeClass('video-nav-fixed');
          $('.header-nav').css({
              'display': 'block'
          })
      }
  })
 }
  $('.navbar-content li').on('click', function() {
      if(!$(this).hasClass('active')) {
      $(this).siblings().removeClass('active');
      $(this).addClass('active');
      }
  })