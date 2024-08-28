(function ($) {

    // Receiving object
    var wsdsPublicObject, product_wrapper, countdownStatus, variationdetailsWrapper, variationCountdownhtml;
    
    wsdsPublicObject = wsds_public_object;
    
    if (wsdsPublicObject.wrapperClass !== "" && wsdsPublicObject.wrapperClass !== undefined) {
        product_wrapper = wsdsPublicObject.wrapperClass;
    }
    else {
        product_wrapper = '.product.product-type-variable .woocommerce-variation';
    }

    $(document).ready(function(){

        // Triggering the `show_variation` event
        $('body').on("show_variation", function (event, variation) {
            
            // Getting variation sale countdown
            if (!variation) {
                console.info('WSDS Info: Variation data not found!');
            }
            else {
                countdownStatus = variation.wsds_countdown_status;
                variationCountdownhtml = variation.wsds_countdown_html;
                // Checking Empty string, undefined, null or not
                if (countdownStatus) {
                    // Passing variation html to the function
                    $(document.body).trigger('wsds_show_sale_countdown', [$(this), countdownStatus, variationCountdownhtml, 'show_variation']);
                }
            }
        });
    
        // Triggering `hide_variation` event
        $('body').on("hide_variation", function (event, variation) {
            $(document.body).trigger('wsds_show_sale_countdown', [$(this), countdownStatus, variationCountdownhtml, 'hide_variation']);
        });
    
        // Init the wsds_show_sale_countdown trigger
        $(document.body).on("wsds_show_sale_countdown", function (event, mainWrapper, countdownStatus, variationCountdownhtml, displayCondition) {
            changeCountdown(mainWrapper, countdownStatus, variationCountdownhtml, displayCondition);
        });

        wsds_init_start_countdown();
        wsds_init_end_countdown();
        
        /** Countdown script for scheduled product sale countdown */
        function wsds_init_start_countdown() {
            $(".wsds_countdown_start").each(function() {
                var start_time = $(this).attr('data-start');
                var product_id = $(this).attr('data-product');
                var interval1 = setInterval(function() {
                    var today = new Date();
                    var str = today.toGMTString();
                    var now_timestamp = Date.parse(str) / 1000;
                    var remain_start_time = start_time - now_timestamp;
                    if (remain_start_time > 0) {
                        $('#wsds_countdown_start_' + product_id + ' ul').html(wsds_convertMS(remain_start_time + '000'));
                    } else {
                        clearInterval(interval1);
                        setTimeout(() => {
                            document.location.reload();
                        }, 1000);
                    }
                }, 1000);
                
            });
        }
        
        function wsds_init_end_countdown() {
            $(".wsds_countdown_end").each(function() {
                var end_time = $(this).attr('data-end');
                var product_id = $(this).attr('data-product');
                var interval2 = setInterval(function() {
                    var today = new Date();
                    var str = today.toGMTString();
                    var now_timestamp = Date.parse(str) / 1000;
                    var remain_end_time = end_time - now_timestamp;
                    if (remain_end_time > 0) {
                        $('#wsds_countdown_end_' + product_id + ' ul').html(wsds_convertMS(remain_end_time + '000'));
                    } else {
                        clearInterval(interval2);
                        setTimeout(() => {
                            document.location.reload();
                        }, 1000);
                        
                    }
                }, 1000);
                
            });
        }

        // Function to run- on changing the variation dropdown
        function changeCountdown(mainWrapper, countdownStatus, variationCountdownhtml, displayCondition) {
            var countdownContainer2;
            countdownContainer2 = mainWrapper.find(product_wrapper);
            
            if(displayCondition == 'show_variation') {
                if (countdownStatus == 1) {                   
                    if(countdownContainer2.find('.wsds_coundown_single').length > 0) {
                        countdownContainer2.find('.wsds_coundown_single').replace(variationCountdownhtml).show(0,function(){
                            wsds_init_start_countdown();
                            wsds_init_end_countdown();
                        });
                    }else{                    
                        countdownContainer2.append(variationCountdownhtml).show(0,function(){
                            wsds_init_start_countdown();
                            wsds_init_end_countdown();
                        });
                    }
                }
            }else{
                if(countdownContainer2.find('.wsds_coundown_single').length > 0) {
                    countdownContainer2.find('.wsds_coundown_single').remove();
                }
            }
        }

        function wsds_convertMS(ms) {
            var d, h, m, s;
            s = Math.floor(ms / 1000);
            m = Math.floor(s / 60);
            s = s % 60;
            h = Math.floor(m / 60);
            m = m % 60;
            d = Math.floor(h / 24);
            h = h % 24;

            var html = '<li><div><span class="wsds_count_digit">' + d + '</span><span class="wsds_count_lable">Days</span></div></li><li><div><span class="wsds_count_digit">' + h + '</span><span class="wsds_count_lable">Hours</span></div></li><li><div><span class="wsds_count_digit">' + m + '</span><span class="wsds_count_lable">Min</span></div></li><li><div><span class="wsds_count_digit">' + s + '</span><span class="wsds_count_lable">Sec</span></div></li>'
            return html;
        };
    
    });

})(jQuery);