(function ($) {
	'use strict';
	$.fn.UltpSlider = function (options) {

    /*Merge options and default options*/
    let opts = $.extend({}, $.fn.UltpSlider.defaults, options);
		/*Functions Scope*/
		let thisTicker = $(this), intervalID, timeoutID, isPause = false;
    let pauseVal  = true;
    let onPressTime = 0;

    /*Always wrap, used in many place*/
    thisTicker.wrap("<div class='acmeticker-wrap'></div>");
		/*Wrap is always relative*/
		thisTicker.parent().css({
			position: 'relative'
		})
    thisTicker.children().first().addClass("active");   /* ====  ADD active class to first item ===== */


    /* ==================================================
        horizontal , vertical and typewriter Control
    ===================================================== */
    if(opts.type == 'horizontal'|| opts.type == 'vertical' || opts.type == 'typewriter') {
      
      /* ====  typewriter ===== */
      let typeAutoPlay = ""
      if (opts.type == 'typewriter') {
        typeAutoPlay  =  setInterval(function() {
          slidePlay();
        }, opts.speed);
      }
  

      let sliderAutoPlay = "";
        if (opts.type == 'horizontal'|| opts.type == 'vertical' ) {
          sliderAutoPlay =  setInterval(function() {
            slidePlay();
          }, opts.speed);
        }

      /* =====  Prev Func  ===== */
        $(opts.controls.prev).click(function () {
            if (opts.type == 'horizontal'|| opts.type == 'vertical' ) {
                  clearInterval(sliderAutoPlay);
                  handleSlider("prev");
                  if (pauseVal) {
                    sliderAutoPlay =  setInterval(function() {
                      slidePlay();
                    }, opts.speed);
                  }
            } 
            if (opts.type == 'typewriter') {
              clearInterval(typeAutoPlay);
                handleSlider("prev");
                if (pauseVal) {
                  typeAutoPlay =  setInterval(function() {
                    slidePlay();
                  }, opts.speed);
                }
            }
        });

      /* =====  Next Event  ===== */
        $(opts.controls.next).click(function () {
          if (opts.type == 'horizontal'|| opts.type == 'vertical') { 
            clearInterval(sliderAutoPlay);
            handleSlider("next");
            if (pauseVal) {
              sliderAutoPlay =  setInterval(function() {
                slidePlay();
              }, opts.speed);
            }
          }
          if (opts.type == 'typewriter') {
            clearInterval(typeAutoPlay);
            handleSlider("next");
            if (pauseVal) {
              typeAutoPlay =  setInterval(function() {
                slidePlay();
              }, opts.speed);
            }
          }
        });

      /* =====  Pause Event  ===== */
        $(opts.controls.toggle).click(function () {
          if (opts.type == 'horizontal' || opts.type == 'vertical') {
              if (pauseVal) {
                pauseVal = false;
                clearInterval(sliderAutoPlay)
              } else {
                pauseVal = true;
                clearInterval(sliderAutoPlay)
                sliderAutoPlay =  setInterval(function() {
                    slidePlay();
                }, opts.speed);
            }
          }
          if (opts.type == 'typewriter') {
            if (pauseVal) {
              pauseVal = false;
              clearInterval(typeAutoPlay)
            } else {
              pauseVal = true;
              clearInterval(typeAutoPlay)
              typeAutoPlay =  setInterval(function() {
                  slidePlay();
              }, opts.speed);
            }
          }
        });

      /* ===== Hover Pause Event  ===== */
        if(opts.pauseOnHover){

          thisTicker.hover(function() {
            if (opts.type == "typewriter") {
              clearInterval(typeAutoPlay)
            }
            if (opts.type == "horizontal" ||  opts.type == "vertical") {
              clearInterval(sliderAutoPlay)
            }
            }, function() {
              if (opts.type == "typewriter" && pauseVal) {
                typeAutoPlay  =  setInterval(function() {
                  slidePlay();
                }, opts.speed);
              }
              if (opts.type == "horizontal" ||  opts.type == "vertical" && pauseVal) {
                sliderAutoPlay =  setInterval(function() {
                  slidePlay();
                }, opts.speed);
              }
            })
        }
    } 


    /* =========================
        Marque Slide Control
    ============================ */
    if(opts.type == 'marquee') {            
      let marqueeSpeed = opts.speed;
      let i = 0;
      let mainWidth;
      let dir = opts.direction;
      let contentWidth = thisTicker.outerWidth();
      let wrapperWidth = $('.ultp-newsTicker-wrap').outerWidth();
      let rtl = $(document).find("body").hasClass("rtl");

      if(dir == "right"){
        mainWidth = wrapperWidth;
      }
      if (dir == "left") {
        mainWidth = thisTicker.outerWidth();
      }
      let marqueeSlide = setInterval(function(){
          if(mainWidth < i && dir == "left" && !rtl) {
            i = -wrapperWidth;
          }
          // if(-(mainWidth)  > i && dir == "left" && !rtl){
          //     i = wrapperWidth;
          // }
          if(mainWidth < i && dir == "right" && !rtl) {
            i = -contentWidth;
          }

          /* ===== For RTL Support  ===== */
          if(contentWidth < i && dir == "right" && rtl) {
            i = -wrapperWidth;
          }
          if(wrapperWidth < i && dir == "left" && rtl) {
            i = -mainWidth;
          }
          thisTicker.css(dir, -i );
          i++;
      }, marqueeSpeed);


      /* =====================
        Prev Button Control
      ======================== */
      $(opts.controls.prev).click(function () {
        if(!pauseVal) { /* ===== If Slide Pause ===== */ 
          pauseVal = true
          marqueeSlide = setInterval(function(){
            if(mainWidth < i && dir == "left" && !rtl) {
              i = -wrapperWidth;
            }
            if(-(wrapperWidth)  > i && dir == "left" && !rtl){
                i =  mainWidth - 100;
            }
            if(mainWidth < i && dir == "right" && !rtl) {
              i = -contentWidth;
            }

          /* ===== For RTL Support  ===== */
          if( contentWidth < i && dir == "right" && rtl) {
            i = -mainWidth;
          }
          if(wrapperWidth < i && dir == "left" && rtl) {
            i = -mainWidth;
          }
            thisTicker.css(dir, -i )
            i++;
          }, marqueeSpeed);
        } else {
          if(-(thisTicker.outerWidth())  > i && dir == "right" && !rtl){
            // i =  $(window).width(); 
            i =  mainWidth;
          }
          if( i < -($('.ultp-newsTicker-wrap').outerWidth()) && dir == "left" && !rtl){
            i = mainWidth;
          }

          /* ===== For RTL Support  ===== */
          if(-(mainWidth)  > i && dir == "right" && rtl){
            i = thisTicker.outerWidth();
          }
          if(-(mainWidth)  > i && dir == "left" && rtl){
            i = $('.ultp-newsTicker-wrap').outerWidth();
          }
          // let childWidth = thisTicker.outerWidth() / thisTicker.children().length;
          i = i - 250;
        }
      });

          /* ===== Onpress Event  ===== */
            $(opts.controls.prev).on('mousedown touchstart', function(e) {
              onPressTime = setInterval(function(){
                if(!rtl && dir == "right" ){
                if(i > -(contentWidth )) {
                  i = i - 30; 
                } else {
                  i = $('.ultp-newsTicker-wrap').outerWidth()-10;
                }
                } else if(rtl && dir == "left" ){
                  if(i < -(contentWidth)){
                    i = $('.ultp-newsTicker-wrap').outerWidth()-10;
                  }
                  i = i - 30; 
                } else if(!rtl && dir == "left" ) {
                if(i < -($('.ultp-newsTicker-wrap').outerWidth())) {
                  i = contentWidth;
                }
                  i = i - 30; 
                } else {
                  if(i < -($('.ultp-newsTicker-wrap').outerWidth()) && rtl && dir == "right" ) {
                  i = contentWidth;
                }
                  i = i - 30; 
                }
              }, 100);
            }).bind('mouseup mouseleave touchend', function() {
              clearInterval(onPressTime);
            });
          


      /* =====================
          Next Event Control
      ======================== */
      $(opts.controls.next).click(function () {
            if(!pauseVal) { /* ===== If Slide Pause ===== */
              pauseVal = true
              marqueeSlide = setInterval(function(){
                if(mainWidth < i && dir == "left" && !rtl) {
                  i = -wrapperWidth;
                }
                // if(-(mainWidth)  > i && dir == "left" && !rtl){
                //     i = wrapperWidth;
                // }
                if(mainWidth < i && dir == "right" && !rtl) {
                  i = -contentWidth;
                }

                /* ===== RTL Support ===== */
                if(wrapperWidth < i && dir == "left" && rtl) {
                  i = -mainWidth;
                }
                if( contentWidth < i && dir == "right" && rtl) {
                  i = -mainWidth;
                }
                    thisTicker.css(dir, -i )
                      i++;
                  }, marqueeSpeed);
            } else {
                // let ChildWidth = thisTicker.outerWidth() / thisTicker.children().length;
                i = i + 250;
            }
      });

    /* =======  Next Onpress Event  ======= */
        $(opts.controls.next).on('mousedown touchstart', function(e) {
          onPressTime = setInterval(function(){
            i = i + 80; 
          }, 80);
        }).bind('mouseup mouseleave touchend', function() {
          clearInterval(onPressTime);
        });

      /* =====================
          Pause Event Control
      ======================== */
      $(opts.controls.toggle).click(function () {
        if (pauseVal) {
          pauseVal = false;
          clearInterval(marqueeSlide);
        } else {
          pauseVal = true;
          marqueeSlide = setInterval(function(){
            if(mainWidth < i && dir == "left" && !rtl) {
                  i = -wrapperWidth;
              }
            // if(-(mainWidth)  > i && dir == "left" && !rtl){
            //     i = wrapperWidth;
            // }
            if($('.ultp-newsTicker-wrap').outerWidth() < i && dir == "left" && rtl){
                i = -(contentWidth+ 100);
            }
            if(mainWidth < i && dir == "right" && !rtl) {
              i = -contentWidth;
            }
            if(contentWidth < i && dir == "right" && rtl) {
              i = -$('.ultp-newsTicker-wrap').outerWidth();
            }
            thisTicker.css(dir, -i )
              i++;
          }, marqueeSpeed);
        }
      });

    /* =======  Hover Pause Control Button   ======= */
      if(opts.pauseOnHover){
        thisTicker.hover(function() {
          clearInterval(marqueeSlide)
        },function() {
          if(pauseVal){
            marqueeSlide = setInterval(function(){
              if((mainWidth) < i && dir == "left" && !rtl) {
                    i = -($('.ultp-newsTicker-wrap').outerWidth());
              }
              
              if(($('.ultp-newsTicker-wrap').outerWidth()) < i && dir == "left" && rtl) {
                i = -mainWidth;
              }
              // if(-(mainWidth)  > i && dir == "left"){
              //     i = wrapperWidth;
              // }
              if($('.ultp-newsTicker-wrap').outerWidth() < i && dir == "right" && !rtl) {
                i = -contentWidth;
              }

              if(contentWidth < i && dir == "right" && rtl) {
                i = -mainWidth;
              }
                  thisTicker.css(dir, -i )
                    i++;
                }, marqueeSpeed);
          }
        })
      }
    }
    /* =====================================
          Next Previous Auto Play Control
    ===================================== */
    function handleSlider(val) {
      let slideIndex = thisTicker.find(".active").index();
      if (slideIndex < 0) {
        slideIndex = 0;
      }
      let index = 1;
      if (val == "prev") {
        thisTicker.children().eq(slideIndex).removeClass("active");
        thisTicker
          .children()
          .eq(slideIndex - index)
          .addClass("active");
      }
      if (val == "next") {
        thisTicker.children().eq(slideIndex).removeClass("active");
        if (slideIndex == thisTicker.children().length - 1) {
          index = -(thisTicker.children().length - 1);
        }
        thisTicker
          .children()
          .eq(slideIndex + index)
          .addClass("active");
      }
    }

    /* =======  Auto Slide Function   ======= */
    function slidePlay() {
      let index = 1;
      let slideIndex = thisTicker.find(".active").index();
        if (slideIndex < 0) {
            slideIndex = 0;
        }
        thisTicker.children().eq(slideIndex).removeClass("active");
        if (slideIndex == thisTicker.children().length - 1) {
          index = -(thisTicker.children().length - 1);
        }
        thisTicker
          .children()
          .eq(slideIndex + index)
          .addClass("active");
    }
  }

  /* ====================
        Default Value 
    ===================== */
  $.fn.UltpSlider.defaults = {
		/*Note: Marquee only take speed not autoplay*/
		type: 'horizontal',/*vertical/horizontal/marquee/typewriter*/
		autoplay: 2000,/*true/false/number*/ /*For vertical/horizontal 4000*//*For typewriter 2000*/
		speed: 50,/*true/false/number*/ /*For vertical/horizontal 600*//*For marquee 0.05*//*For typewriter 50*/
		direction: 'up',/*up/down/left/right*//*For vertical up/down*//*For horizontal/marquee right/left*//*For typewriter direction doesnot work*/
		pauseOnFocus: true,
		pauseOnHover: true,
		controls: {
			prev: '',/*Can be used for vertical/horizontal/typewriter*//*not work for marquee*/
			next: '',/*Can be used for vertical/horizontal/typewriter*//*not work for marquee*/
			toggle: ''/*Can be used for vertical/horizontal/marquee/typewriter*/
		}
	}; 
})(jQuery);
