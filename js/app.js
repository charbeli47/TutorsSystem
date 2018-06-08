
// replace these values with those generated in your TokBox Account
var apiKey = "45989702";
var sessionId = "2_MX40NTk4OTcwMn5-MTUwOTEyMzkyNDg4MH5nbENyMzdwZFVRbDVYQ2ErQUI3WGY4ajZ-fg";
var token = "T1==cGFydG5lcl9pZD00NTk4OTcwMiZzaWc9NzM5NTIzMmRkMzliMDVhYjU3ODg0MTU5NzQxNWY3NTg3MDQwYmQxYTpzZXNzaW9uX2lkPTJfTVg0ME5UazRPVGN3TW41LU1UVXdPVEV5TXpreU5EZzRNSDVuYkVOeU16ZHdaRlZSYkRWWVEyRXJRVUkzV0dZNGFqWi1mZyZjcmVhdGVfdGltZT0xNTA5MTIzOTU3Jm5vbmNlPTAuMzM4OTU2NDY1MTc2MDU4NiZyb2xlPXB1Ymxpc2hlciZleHBpcmVfdGltZT0xNTExNjcwMzk5JmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9";

initializeSession();
// Handling all of our errors here by alerting them
function handleError(error) {
    if (error) {
        alert(error.message);
    }
}

function initializeSession() {
    var session = OT.initSession(apiKey, sessionId);

    // Subscribe to a newly created stream
    session.on('streamCreated', function (event) {
        session.subscribe(event.stream, 'subscriber', {
            insertMode: 'append',
            width: '100%',
            height: '100%'
        }, handleError);
    });

    // Create a publisher
    var publisher = OT.initPublisher('publisher', {
        insertMode: 'append',
        width: '100%',
        height: '100%'
    }, handleError);

    // Connect to the session
    session.connect(token, function (error) {
        // If the connection is successful, publish to the session
        if (error) {
            handleError(error);
        } else {
            session.publish(publisher, handleError);
        }
    });
}

"use strict";

;(function() {

    //revolution
    var revolutionReady = function() {
        if($(".rev_slider").length > 0) {
            var windWidth = window.innerWidth,
                widthSetting = (windWidth > 1024) ? 'fullscreen' : 'auto';

            $('.rev_slider_1').bind("revolution.slide.onloaded",function (e) {
                var preview = $(this).find('.preview'),
                    bulletsWrapp = $(this).find('.tp-bullets');

                $(bulletsWrapp).append(preview);
                $(bulletsWrapp).find('.tp-bullet').on('mouseover', function() {
                    var windWidth = $(window).innerWidth(),
                        bullets = $(bulletsWrapp).find('.tp-bullet'),
                        i = 0;

                    if(windWidth < 1025) return;

                    $(preview).find('img').hide();

                    for(; i < bullets.length; i++) {
                        if($(bullets).get(i) === this) {
                            break;
                        }
                    }
                    
                    $(preview).find('img').eq(i).show();
                    $(preview).css({left: -33 + 18*i + 'px'});

                    $(preview).stop();
                    $(preview).fadeIn();
                });

                $(bulletsWrapp).find('.tp-bullet').on('mouseleave', function() {
                    $(preview).stop();
                    $(preview).fadeOut();
                });
            });

            var revapi4 = $(".rev_slider_1").show().revolution({
                sliderType: "standard",
                jsFileLocation:"vendor/revolution/js/",
                sliderLayout:widthSetting,
                dottedOverlay:"none",
                lazyType:"smart",
                disableProgressBar: 'on',
                delay:4000,
                navigation : {
                    bullets:{
                        style:"class",
                        enable:true,
                        container:"slider",
                        direction:"horizontal",
                        hide_onleave: false,
                        space:6,       
                        h_align:"center",
                        v_align:"bottom",
                        h_offset: 0,
                        v_offset: 60
                    },
                    keyboardNavigation:"on",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation:"off",
                    onHoverStop:"on",
                    touch:{
                        touchenabled:"on",
                        swipe_threshold: 75,
                        swipe_min_touches: 1,
                        swipe_direction: "horizontal",
                        drag_block_vertical: false
                    }

                },
                parallax: {
                    type:"mouse",
                    origo:"slidercenter",
                    speed:2000,
                    levels:[2,3,4,5,6,7,12,16,10,50],
                    disable_onmobile:"on",
                    bgparallax:"on"
                },
                shadow:0,
                spinner:"on",
                stopLoop:"off",
                shuffle:"on",
                autoHeight:"off",
                fullScreenAlignForce:"on",
                fullScreenOffsetContainer: '.top-spacer',
                fullScreenOffset:"",
                hideThumbsOnMobile:"off",
                hideSliderAtLimit:0,
                hideCaptionAtLimit:0,
                hideAllCaptionAtLilmit:0,
                fallbacks: {
                    simplifyAll:"off",
                    nextSlideOnWindowFocus:"off",
                    disableFocusListener:false,
                },
                gridwidth:1920,
                gridheight:890 
            });
        }
    };

    //owl
    var baner2 = $('.baner-2');

    function centerBanerBlock() {
        var baner2 = $('.baner-2'),
            docWidth = window.innerWidth,
            banerBlock = $(baner2).find('.baner-block'),
            i = 0;

            $(banerBlock).css({maxHeight: 'inherit'});

            if(docWidth > 414) {
                for (; i < banerBlock.length; i++) {
                    var block = $(banerBlock).eq(i); 

                    $(block).removeClass('full-height full-width');
                    $(block).css({ marginTop: '0px' });
                }
                return;
            }

            for (; i < banerBlock.length; i++) {
                var block = $(banerBlock).eq(i),
                    img = $(block).find('img'),
                    wrappWidth = $(baner2).find('.owl-wrapper-outer').width();

                $(block).removeClass('full-height full-width');
                
                $(block).addClass('full-height');

                $(block).css({ marginTop: '0px' });

                var imgWidth = img.width();

                if(imgWidth > wrappWidth) {

                    $(block).removeClass('full-height full-width');

                    $(block).addClass('full-width');

                    var wrappHeight = $(baner2).find('.owl-wrapper-outer').innerHeight(),
                        imgHeight = img.height(),
                        mTop = (wrappHeight - imgHeight) / 2;

                    $(block).css({ marginTop: mTop + 'px' });

                    $(block).css({maxHeight: imgHeight + 'px'});
                }
            } 
    };

    function addCarouselBaner2() {
        var baner2 = $('.baner-2'),
            docWidth = window.innerWidth,
            baner2carousel = $(baner2).find('.baner-2-carousel'),
            owl;

        if(docWidth < 415) {
            if($(baner2carousel).data('owlCarousel')) return;

            $(baner2carousel).owlCarousel({
                items: 1,
                navigation: true,
                navigationText: [
   "<i class='icon icon-arrow-left2'></i>",
   "<i class='icon icon-arrow-right2'></i>"
                ],
                paginationSpeed : 1000,
                goToFirstSpeed : 2000,
                singleItem : true
            });

            $(baner2).addClass('slider');
            $(baner2carousel).addClass('owl-carousel');

        } else {
            $(baner2).removeClass('slider');
            $(baner2carousel).removeClass('owl-carousel');

            owl = $(baner2carousel).data('owlCarousel');
            if(typeof owl == 'object') owl.destroy();
        };

    };

    function addCarouselBaner3() {
        var baner3 = $('.baner-3'),
            docWidth = window.innerWidth,
            baner3carousel = $(baner3).find('.baner-3-carousel'),
            owl;

        if(docWidth < 768) {
            if($(baner3carousel).data('owlCarousel')) return;

            $(baner3carousel).owlCarousel({
                items : 4,
                pagination: true,
                itemsCustom : false,
                itemsDesktop : false,
                itemsDesktopSmall : false,
                itemsTablet: [751,2],
                itemsTabletSmall: [397,1],
                itemsMobile : false
            });

            $(baner3carousel).addClass('owl-carousel');
        } else {
            $(baner3carousel).removeClass('owl-carousel');

            owl = $(baner3carousel).data('owlCarousel');
            if(typeof owl == 'object') owl.destroy();
        };
    };

    function endResize(func) {
        var windWidth = window.innerWidth,
            interval;

        interval = setInterval(function() {
            var windWidthInterval = window.innerWidth;
            if(windWidth === windWidthInterval) {
                setTimeout(function() {
                    func();
                }, 200);
            } 
            clearInterval(interval);
        }, 100); 
    };

    $('.blog').owlCarousel({
        items: 3,
        pagination: true,
        itemsDesktop: [1007,2],
        itemsDesktopSmall : [900,2],
        itemsTablet: [607,1]
    });
    $('.blog-posts .owl-item').css({ width:  $(window).innerWidth()/3 + 'px' });

    $('.brand-carousel').owlCarousel({
        items : 6,
        pagination: true,
        itemsCustom : false,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: [768,3],
        itemsTabletSmall: false,
        itemsMobile: false,
        navigation: false,
        navigationText: [
          "<i class='icon-chevron-left icon-white'></i>",
          "<i class='icon-chevron-right icon-white'></i>"
        ]
    });
    $('.offers .owl-carousel').owlCarousel({
        items : 4,
        pagination: false,
        navigation: true,
        navigationText: [
            "<span class='icon-arrow-left2 icon'></span>",
            "<span class='icon-arrow-right2 icon'></span>"
        ],
        itemsCustom : false,
        itemsDesktop : false,
        itemsDesktopSmall : [1007,3],
        itemsTablet: [750,2],
        itemsTabletSmall: false,
        itemsMobile : false,
        afterMove: function() {
            $('.offers .product').removeClass('right-hover-always');
            $('.offers .product').eq(this.currentItem).addClass('right-hover-always');
        }
    });

    var carouselReady = function() {
        addCarouselBaner2();
        centerBanerBlock();
        addCarouselBaner3();
    };

    var carouselResize = function() { 
        addCarouselBaner2();
        endResize(centerBanerBlock);
        addCarouselBaner3();
    };

    //zoom
    var sync1 = $(".product-block .image-block .main-block");
    var sync2 = $(".product-block .image-block .preview-block");
     
    sync1.owlCarousel({
        singleItem : true,
        slideSpeed : 1000,
        navigation: true,
        navigationText: [
            "<span class='icon-arrow-left2 icon'></span>",
            "<span class='icon-arrow-right2 icon'></span>"
        ],
        pagination:false,
        afterAction : syncPosition,
        responsiveRefreshRate : 200,
        afterMove: function() {
          changeZoomContainer(this.currentItem);
        }
    });
     
    sync2.owlCarousel({
        items : 6,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet : false,
        itemsMobile : false,
        pagination:false,
        navigation: true,
        navigationText: [
            "<span class='icon-arrow-left2 icon'></span>",
            "<span class='icon-arrow-right2 icon'></span>"
        ],
        responsiveRefreshRate : 100,
        afterInit : function(el){
          el.find(".owl-item").eq(0).addClass("synced");
        }
    });
     
    function syncPosition(el){
        var current = this.currentItem;
        $(".product-block .image-block .preview-block")
            .find(".owl-item")
            .removeClass("synced")
            .eq(current)
            .addClass("synced");
        if($(".product-block .image-block .preview-block").data("owlCarousel") !== undefined){
            center(current)
        }
    }
     
    $(".product-block .image-block .preview-block").on('click', '.owl-item', function(e){
        e.preventDefault();

        if($(e.target).hasClass('fancybox-media') || $(e.target).parent().hasClass('fancybox-media'))
            return;
        
        var number = $(this).data("owlItem");
        sync1.trigger("owl.goTo",number);
    });
     
    function center(number){
        var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
        var num = number;
        var found = false;
        for(var i in sync2visible){
            if(num === sync2visible[i]){
                var found = true;
            }
        }
         
        if(found===false){
            if(num>sync2visible[sync2visible.length-1]){
                sync2.trigger("owl.goTo", num - sync2visible.length+2)
            }else{
                if(num - 1 === -1){
                    num = 0;
                }
                sync2.trigger("owl.goTo", num);
            }
        } else if(num === sync2visible[sync2visible.length-1]){
            sync2.trigger("owl.goTo", sync2visible[1])
        } else if(num === sync2visible[0]){
            sync2.trigger("owl.goTo", num-1)
        }
    }

    var productImg = $('.product-block .image-block .main-block .owl-wrapper .owl-item img'),
        zoomConfig = {
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 500,
            lensFadeIn: 500,
            lensFadeOut: 500,
            scrollZoom: true,
            zoomWindowWidth: 473,
            zoomWindowHeight: 336,
            borderSize: 1,
            //easing : true,
            zoomLevel: 0.6,
            zoomWindowPosition: 1,
            cursor: 'crosshair',
            //tint: true,
            //tintColour: '#282530',
            enabled: true
        };

    $(productImg).eq(0).ezPlus(zoomConfig);

    function changeZoomContainer(currentItem){
        $('.zoomContainer').remove();
        setTimeout(function() {
            $(productImg).eq(currentItem).ezPlus(zoomConfig);
        }, 1000);
    }


    //isotope
    
    var isotopeLoad =  function() {
        (function productIsotope() {
            var $productWrapp = $('.products-wrapper'),
                $productIsotope = $productWrapp.find('.isotope'),
                $prodNav = $('.product-navigation');

             $('.product-navigation .button').on('click', function(){
                $prodNav.find('.selected-item li').html($(this).html());
                $(this).parent().find('a').removeClass('is-checked');
                $(this).find('a').addClass('is-checked');

                var selector = $(this).attr('data-filter'),
                    rightProdNum,
                    selectedLi,
                    i = 0;

                $($productIsotope).isotope({ 
                    itemSelector: '.grid-item', 
                    layoutMode: 'fitRows',
                    filter: selector
                });

                $(document).trigger('changeIsotope');

                $productWrapp.find('.product.right-hover').removeClass('right-hover');

                if($productWrapp.hasClass('product-lg-1')) rightProdNum = 1;
                else if($productWrapp.hasClass('product-lg-2')) rightProdNum = 2;
                else if($productWrapp.hasClass('product-lg-3')) rightProdNum = 3;
                else if($productWrapp.hasClass('product-lg-4')) rightProdNum = 4;
                else if($productWrapp.hasClass('product-lg-5')) rightProdNum = 5;
                else if($productWrapp.hasClass('product-lg-6')) rightProdNum = 6;
                else rightProdNum = 4;

                if(selector === '*') selector = '.grid-item'

                selectedLi = $productWrapp.find(selector);

                for(; i < selectedLi.length; i++) {
                    if(i % rightProdNum === 0)
                        $(selectedLi).eq(i).find('.product').addClass('right-hover');
                }

                return false;
            });
        })();
        $('.product-navigation ul:last-child li .is-checked').parent().trigger('click');

        (function galleryIsotope() {
            var $gelleryWrapp = $('.gallery'),
                $galleryNav = $('.gallery-navigation');

            $('.gallery-navigation .button').on('click', function(){
                var $this = $(this);

                $galleryNav.find('.selected-item li').html($(this).html());
                $this.parent().find('a').removeClass('is-checked');
                $this.find('a').addClass('is-checked');

                var selector = $this.attr('data-filter');

                $($gelleryWrapp).find('.gallery-content').isotope({ 
                    itemSelector: '.grid-item',
                    layoutMode: 'fitRows',
                    filter: selector
                });

                //add fancybox
                $('.fancybox-overlay').remove();

                $('.gallery-content').find('.grid-item > a').removeAttr('rel');
                $('.gallery-content').find('.grid-item > a').attr('href', '#');

                if(selector === '*') selector = '.grid-item';

                $('.gallery-content').find(selector).find('> a').attr('rel', 'fancy-gallery');
                
                var links = $('.gallery-content').find(selector).find('> a'),
                    src,
                    i = 0;

                for(; i < links.length; i++) {
                    src = $(links).eq(i).find('img').attr('data-full');
                    $(links).eq(i).attr('href', src);
                }

                $("a[rel='fancy-gallery']").fancybox({
                    showCloseButton: true,
                    showNavArrows: true,
                    margin: 140,
                    openEffect  : 'none',
                    closeEffect : 'none'
                });

                return false;
            });

        })();
        $('.gallery-navigation ul:last-child li .is-checked').parent().trigger('click');
    };

    //fancybox
    var productItems = $('.product-block').find('.image-block .main-block .item'),
        src,
        i = 0;

    $(productItems).attr('rel', 'fancy-product');

    for(; i < productItems.length; i++) {
        src = $(productItems).eq(i).find('img').attr('data-fancybox');
        $(productItems).eq(i).attr('href', src);
    }

    if($("a[rel='fancy-product']").length > 0) {
        $("a[rel='fancy-product']").fancybox({
            showCloseButton: true,
            showNavArrows: true,
            margin: 140,
            openEffect  : 'none',
            closeEffect : 'none'
        });
    }

    if($('.fancybox-media').length > 0) {
        $('.fancybox-media').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',
            width       : 1280,
            height      : 720,
            maxWidth    : '100%',
            maxHeight   : '100%',  
            padding     : 0,
            margin      : 0,           
            helpers : {
                media : {
                    youtube : {
                         params : {
                             theme : 'light',
                             vq    : 'hd720',
                             css   : {
                                'body' : 'color: #fff'
                             } 
                         }
                    } 
                }
            }
        });
    }

    //rangeSlider
    var range = $('.range');

    if($(range).length > 0) {
        $(range).ionRangeSlider({
            type: "double",
            min: 0,
            max: 3000,
            from: 0,
            to: 2300,
            step: 10,
            prefix: "$",
            prettify_enabled: true
        });
    }

    //Common js
    var MYAPP = {

        initialize: function() {
            this.topbar.setUpListeners();
            this.main.setUpListeners();
            this.footer.setUpListeners();
        },

        topbar: {
            setUpListeners: function() {
                var topbar = $('.topbar'),
                    topbarMobile = $('.topbar-mobile'),
                    mobileMenu = $('.mobile-menu'),
                    closeMobileMenu = $('.close-mobile-menu'),
                    btnToogleMenu = $(topbarMobile).find('.toogle-menu'),
                    phoneMenu = $('.phone-menu'),
                    navBlock = $(topbar).find('.topbar-menu .navigation-block'),
                    megaMenu = $(topbar).find('.megamenu'),
                    simpleMenu = $(topbar).find('.simplemenu'),
                    megaMenuItems = $(navBlock).find('.megamenu-item'),
                    simpleMenuItems = $(navBlock).find('.simplemenu-item'),
                    sidebar = $(topbar).find('.topbar-sidebar'),
                    basketBlock = $('.basket'),
                    moreOptBlock = $(sidebar).find('.sidebar-more-opt-bl'),
                    siteNav = $(moreOptBlock).find('.site-navigation'),
                    btnShowMoreOpt = $(sidebar).find('.show-more-opt-btn'),
                    btnHideMoreOpt = $(moreOptBlock).find('.option-block-3 .hide-more-opt-btn'),
                    btnShowBasket = $(sidebar).find('.show-basket-btn'),
                    btnHideBasket = $(basketBlock).find('.close-basket'),
                    square1 = $(sidebar).find('.square-1'),
                    square2 = $(sidebar).find('.square-2'),
                    square3 = $(sidebar).find('.square-3'),
                    searchBlock = $(sidebar).find('.search-block '),
                    searchInput = $(searchBlock).find('input'),
                    topbarMobile = $('.topbar-mobile'),
                    mobileSearch = $(topbarMobile).find('.search'),
                    optionsMobileBtn = $('.options-mobile-head .open-block'),
                    optionsMenu = $('.options-menu'),
                    closeOptMobileBtn = $(optionsMenu).find('.close-options-menu'),
                    slideTime = 240;

                $("[rel='tooltip']").tooltip();

                function fadeUotElems() {
                    for(var i = 0; i < arguments.length; i++) {
                        $(arguments[i]).fadeOut(MYAPP.topbar.sideBarFadeTime);
                    }
                };

                //SHOW/HIDE MORE OPT    
                $(btnShowMoreOpt).on('click', function() { 
                    $(square2).addClass('active');
                    MYAPP.topbar.hideSearch();
                    MYAPP.topbar.hideBasket();
                    fadeUotElems(megaMenu);
                    $(this).find('.content-wrapp').css({height: $(topbar).height() + 'px'});
                    $(moreOptBlock).animate({width: '818px'}, MYAPP.topbar.sideBarFadeTime*2);
                });

                $(btnHideMoreOpt).on('click', function() { 
                    MYAPP.topbar.hideMoreOpt();
                    return false; 
                });

                $(moreOptBlock).find('.option-block-3 .square-opt-1').on('mouseenter', function() {
                    $(siteNav).stop();
                    $(siteNav).show();
                });

                $(moreOptBlock, siteNav).on('mouseleave', function() {
                    $(siteNav).stop();
                    $(siteNav).hide();
                });

                //SHOW/HIDE BASKET 
                $(btnShowBasket).on('click', function() { 
                    $(square3).addClass('active');
                    MYAPP.topbar.hideSearch();
                    MYAPP.topbar.hideMoreOpt();
                    fadeUotElems(megaMenu);
                    $(basketBlock).fadeIn(MYAPP.topbar.sideBarFadeTime);  
                });

                $(btnHideBasket).on('click', function() { 
                    MYAPP.topbar.hideBasket(); 
                });

                //ОТМЕНА ВЫДЕЛЕНИЯ ТЕСТА
                $(topbar).on('mousedown', function(e) {
                    if(e.target.tagName !== 'INPUT') {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });

                //МЕГА МЕНЮ
                $(megaMenuItems).find('> a').on('mouseenter', function(e) {
                    MYAPP.topbar.hideSearch();
                    MYAPP.topbar.hideMoreOpt();
                    MYAPP.topbar.hideBasket(); 
                    fadeUotElems(simpleMenu);
                    MYAPP.topbar.megaMenu(e, topbar);
                });


                //SIMPLE МЕНЮ
                $(simpleMenuItems).find('> a').on('mouseenter', function(e) { 
                    MYAPP.topbar.hideSearch();
                    MYAPP.topbar.hideMoreOpt();
                    MYAPP.topbar.hideBasket();
                    fadeUotElems(megaMenu);
                    MYAPP.topbar.toggleSimpleMenu(e, topbar);
                });

                $(simpleMenu).find('li').on('mouseenter', function(e) {
                    MYAPP.topbar.listSimpleMenu.call(this, e, simpleMenu);
                });


                $(square1).on('click', function(e) {
                    var target = e.target;

                    if($(target).hasClass('close-search')) {
                        $(square1).toggleClass('active');
                        searchBlock.animate({width: '0px'}, MYAPP.topbar.sideBarFadeTime*2);
                    }

                    if(!$(target).hasClass('square-1') && !$(target).hasClass('icon-search')) return false;
                    
                    $(square1).toggleClass('active');
                    if($(square1).delay(300).hasClass('active')) {
                        $(megaMenu).fadeOut(MYAPP.topbar.sideBarFadeTime);

                        var marginTop = ($(topbar).height() - $(searchBlock).find('.search-wrapp').height())/2;
        
                        $(searchBlock).find('.search-wrapp').css({marginTop: marginTop + 'px'});
                        $(searchBlock).animate({width: '510px'}, MYAPP.topbar.sideBarFadeTime*2);
                        $(searchInput).focus();
                    } else {
                        $(searchBlock).animate({width: '0px'}, MYAPP.topbar.sideBarFadeTime*2);
                    }

                    $(searchInput).on('keydown', function(e) {
                        if(e.keyCode === 27) {
                            MYAPP.topbar.hideSearch();
                        }
                    });
                    $(searchInput).blur(MYAPP.topbar.hideSearch);
                });

                //MOBILE
                //search
                $(mobileSearch).on('click keydown', function(e) {
                    MYAPP.topbar.toggleModileSearch(e, mobileSearch);
                });

                //open menu
                $(btnToogleMenu).on('click', function() {
                    $('html').toggleClass('mobile-menu-open');
                    if($('html').hasClass('mobile-menu-open')) {
                        $(mobileMenu).slideDown(slideTime);
                    } else {
                        $(mobileMenu).slideUp(slideTime);
                    }
                });

                $(closeMobileMenu).on('click', function(e) {
                    $(mobileMenu).slideUp(slideTime);
                    setTimeout(function() {
                        $('html').removeClass('mobile-menu-open');
                    }, slideTime);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                //open list
                $(mobileMenu).on('click', function(e) {
                    MYAPP.topbar.mobileMenuList(e, mobileMenu);
                });

                //MOBILE OPTIONS
                $(optionsMobileBtn).on('click', function() {
                    if($(this).hasClass('open-lng')) {
                        $(optionsMenu).find('.languages').show();
                    } else if($(this).hasClass('open-valute')) {
                        $(optionsMenu).find('.valute').show();
                    } else if($(this).hasClass('open-user')) {
                        $(optionsMenu).find('.user').show();
                    }

                    $(optionsMenu).slideDown(MYAPP.topbar.sideBarFadeTime*2);

                });

                $(closeOptMobileBtn).on('click', function() {
                    $(optionsMenu).slideUp(MYAPP.topbar.sideBarFadeTime*2);
                    setTimeout(function() {
                        $(optionsMenu).find('.languages').hide();
                        $(optionsMenu).find('.valute').hide();
                        $(optionsMenu).find('.user').hide();
                    }, MYAPP.topbar.sideBarFadeTime);
                });
            },

            sideBarFadeTime: 140,

            resizeTopSpacer: function() {
                var topSpacer = $('.top-spacer'),
                    windWidth = window.innerWidth,
                    topHeight = (windWidth > 1024) ? $('.topbar').height() :
                                                     $('header').height()  ;

                    $(topSpacer).css({height: topHeight + 'px'});
            },

            hideMoreOpt: function() {
                var topbar = $('.topbar'),
                    sidebar = $(topbar).find('.topbar-sidebar'),
                    square2 = $(sidebar).find('.square-2'),
                    moreOptBlock = $(sidebar).find('.sidebar-more-opt-bl'),
                    siteNav = $(moreOptBlock).find('.site-navigation');

                if(!$(square2).hasClass('active')) return;
                $(square2).removeClass('active');
                $(siteNav).hide();
                $(moreOptBlock).stop();
                $(moreOptBlock).animate({width: '0px'}, MYAPP.topbar.sideBarFadeTime*2);
            },

            hideSearch: function() {
                var topbar = $('.topbar'),
                    sidebar = $(topbar).find('.topbar-sidebar'),
                    square1 = $(sidebar).find('.square-1'),
                    searchBlock = $(sidebar).find('.search-block');

                if(!$(square1).hasClass('active')) return;
                $(square1).removeClass('active');
                $(searchBlock).stop();
                $(searchBlock).animate({width: '0px'}, MYAPP.topbar.sideBarFadeTime*2);
            },

            hideBasket: function() {
                var topbar = $('.topbar'),
                    sidebar = $(topbar).find('.topbar-sidebar'),
                    square3 = $(sidebar).find('.square-3'),
                    basketBlock = $('.basket');

                if(!$(square3).hasClass('active')) return;
                $(square3).removeClass('active');
                $(basketBlock).stop();
                $(basketBlock).fadeOut(MYAPP.topbar.sideBarFadeTime);
            },

            hideMegaMenu: function() {
                var topbar = $('.topbar'),
                    megaMenu = $(topbar).find('.megamenu');

                $(megaMenu).hide();
            },

            hideSimpleMenu: function() {
                var topbar = $('.topbar'),
                    simpleMenu = $(topbar).find('.simplemenu');

                $(simpleMenu).hide();
            },

            hideOptionsMenu: function() {
                var topbar = $('.topbar'),
                    optionsMenu = $('.options-menu');

                $(optionsMenu).hide();
            },

            hideMobileMenu: function() {
                var mobileMenu = $('.mobile-menu');

                if(window.innerWidth > 1024) {
                    $('html').removeClass('mobile-menu-open');
                    $(mobileMenu).hide();
                }
            },

            megaMenu: function(e, topbar) {
                var target = e.target,
                    li = $(target).parent(),
                    targetWidth = $(target).width(),
                    megaMenu = $(target).parent().find('.megamenu'),
                    arrow = $(megaMenu).find('.arrow'),
                    transitionBlock = $(megaMenu).find('.transition-block'),
                    topbarPosBottom = $(topbar).height(),
                    fadeTime = 200;

                $(megaMenu).stop();
                $(megaMenu).fadeIn(fadeTime);
                
                //ПОЛОЖЕНИЕ СТРЕЛКИ
                var domMM = $(megaMenu).get(),
                    targetLeft = target.getBoundingClientRect().left,
                    targetBottom = target.getBoundingClientRect().bottom,
                    MMLeft = domMM[0].getBoundingClientRect().left,
                    transitLeftPos = targetLeft - MMLeft + pageXOffset;

                $(transitionBlock).css({
                    width: targetWidth + 40 + 'px',
                    left: transitLeftPos - 20 + 'px'
                });

                $(megaMenu).css({
                    top: targetBottom + 30 + 'px'
                });

                $(li).one('mouseleave', function() {
                    $(megaMenu).stop();
                    $(megaMenu).fadeOut(fadeTime);
                });

            },

            toggleSimpleMenu: function(e, topbar) {
                var target = e.target,
                    li = $(target).parent(),
                    targetLeft = $(target).position().left,
                    simpleTop = $(target).position().top + $(target).height(),
                    targetWidth = $(target).width(),
                    simpleMenu = $(target).parent().find('.simplemenu'),
                    fadeTime = 240;

                $(simpleMenu).css({
                    left: targetLeft + targetWidth/2 + 'px',
                    top: simpleTop + 20 + 'px'
                });

                $(simpleMenu).stop();
                $(simpleMenu).fadeIn(fadeTime);
                
                $(li).one('mouseleave', function() {
                    $(simpleMenu).stop();
                    $(simpleMenu).fadeOut(fadeTime);
                });

            },

            listSimpleMenu: function(e, simpleMenu) {
                var simpleInside = $(this).find('.simplemenu-inside').eq(0);

                if(simpleInside.length === 0) return;
                
                $(simpleInside).css({right: '-100%'});
                $(simpleInside).removeClass('right-arrow');
                $(simpleInside).show();

                var right = $(simpleInside).get(0).getBoundingClientRect().right,
                    windWidth = $(window).width();

                if (windWidth - right < 0) {
                    $(simpleInside).css({right: '100%'});
                    $(simpleInside).addClass('right-arrow');
                }

                var left = $(simpleInside).get(0).getBoundingClientRect().left;

                if (left < 0) {
                    $(simpleInside).css({left: 'auto', right: 100 + left/376*100 + '%'});
                    $(simpleInside).addClass('right-arrow');
                }

                $(this).on('mouseleave', function() {
                    $(simpleInside).hide();
                });
            },

            toggleModileSearch: function(e,mobileSearch) {
                var target = e.target,
                    mobileBtnSearch = $(mobileSearch).find('.icon-search'),
                    mobileSearchBlock = $(mobileSearch).find('.search-block'),
                    mobileCloseSearch = $(mobileSearchBlock).find('.close-search');

                if(target === $(mobileBtnSearch).get(0)) {
                    $(mobileSearchBlock).stop();

                    if(!$(mobileSearch).hasClass('active')) {

                        $(mobileSearch).css({
                            zIndex: 500
                        });
                        $('.options-mobile-head .curtain-for-search').fadeIn();  

                        $(mobileSearch).toggleClass('active');
                        $(mobileSearchBlock).animate({right: '0%'}, {
                            duration: 200,
                            complete: function() {
                                $(mobileSearch).find('input').focus();
                            }
                        });
                    } else {
                        $('.options-mobile-head .curtain-for-search').fadeOut();
                        $(mobileSearchBlock).animate({right: '-100%'}, {
                            duration: 200,
                            complete: function() {
                                $(mobileSearch).toggleClass('active');
                                $(mobileSearch).css({
                                    zIndex: 'inherit'
                                });
                            }
                        });
                    }
                } else if(target === $(mobileCloseSearch).get(0) || e.keyCode === 27) {
                    $('.options-mobile-head .curtain-for-search').fadeOut();
                    $(mobileSearchBlock).animate({right: '-100%'}, {
                        duration: 200,
                        complete: function() {
                            $(mobileSearch).toggleClass('active');
                            $(mobileSearch).css({
                                zIndex: 'inherit'
                            });
                        }
                    });
                }
            },


            mobileMenuList: function(e, mobileMenu) {
                var target = e.target,
                    wrappBlock,
                    ul,
                    timeList = 400;

                (target.tagName === "LI") ? wrappBlock = target:
                                                      wrappBlock = $(target).parents('li');
                    
                if(wrappBlock.length === 0) return;
                
                if(!$(wrappBlock).hasClass('head-btn')) {
                    ul = $(wrappBlock).find('> ul');

                    if(ul.length === 0 || target.tagName === 'A') return;

                    $(ul).animate({right: 0}, timeList);
                } else if($(wrappBlock).hasClass('back-category')) {
                    ul = $(wrappBlock).parents('ul').eq(0);

                    $(ul).animate({right: '-100%'}, {
                        duration: timeList,
                        specialEasing: {
                            right: 'linear'
                        }
                    });

                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }

        },

        main: {
            setUpListeners: function() { 
                var product = $('.product'),
                    previewImage = $(product).find('.preview-images-wrapp li img'),
                    baner2 = $('.baner-2'),
                    productWrapp = $('.products-wrapper'),
                    tabs = $('.tabs'),
                    count = $('.count-product'),
                    listing = $('.listing'),
                    listingAccordion = $(listing).find('.listing-sidebar .listing-accordion'),
                    listToggle = $('.list-toggle'),
                    btnListView = $(listing).find('.control .btn-list-block a'),
                    checkoutBasket = $('.checkout .checkout-basket'),
                    productMenuNav = $('.tt-menu-type1');

                $(product).on('mouseenter mouseup', function(e) {
                    var that = this,
                        windWidth = window.innerWidth;

                    if(windWidth < 1025) return;

                    if(e.type === 'mouseup') {
                        setTimeout(function() {
                            MYAPP.main.product.hover.call(that);
                        }, 0);
                    } else if(e.type === 'mouseenter') {
                        MYAPP.main.product.hover.call(that);
                    }
                });

                $(previewImage).on('click', function(e) {
                    MYAPP.main.product.changePhoto(e);
                });

                $(tabs).find('.navigation ul li').on('click', function(e) {
                    var target = e.target;

                    if(!$(target).parent().hasClass('tab-btn') || target.tagName !== 'A') 
                        return;

                    MYAPP.main.tabs.changeTab.call(this, 'user');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                //count
                $(document).on('keydown', '.count input[name="qty"]', function(e) {
                    var keyCode = e.keyCode;

                    if(keyCode !== 8 && (keyCode < 48 || keyCode > 57)) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });

                $(document).on('click', '.count .tt-btn', function(e) {
                     MYAPP.main.count.changeCount.call(this, e);
                });

                $(document).on('keyup', '.count input[name="qty"]', function(e) {
                    var value = $(this).prop('value');

                    MYAPP.main.count.changeCount.call(this, e, value);
                });

                $(document).on('blur', '.count input[name="qty"]', function(e) {
                    var value = $(this).prop('value');

                    if(+value === 0)
                        MYAPP.main.count.changeCount.call(this, e, 1);
                });

                $('.count input[name="qty"]').keyup().parents('.count').mouseleave();
                //end count

                $(listingAccordion).find('> ul > li > a').on('click', function(e) { 
                    MYAPP.main.listing.accordion.call(this, e);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $(listingAccordion).find('> ul > li.open a').click();

                $(listToggle).find('a').on('click', function(e) {
                    MYAPP.main.listing.listToggle.call(this, e);
                });

                $(listToggle).find('> li.open a').click();

                $(btnListView).on('click', function(e) {
                    MYAPP.main.listing.changeProductGridView.call(this, e);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $(product).find('.button-open').on('click', function(e) {
                    MYAPP.main.product.quickView.show.call(this);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $(checkoutBasket).find('.content').slideDown(600);

                $(productMenuNav).find('.selected-item').on('click', function(e) {
                    $(this).parents('.tt-menu-type1').toggleClass('active');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $('.modal').on('show.bs.modal', function() {
                    var windHeight = $(window).innerHeight();
                    $(this).find('.modal-dialog').css({marginTop: windHeight/100*14 + 'px'});
                });

                $('.modal').on('shown.bs.modal', function() {
                    MYAPP.main.modalPositionCorrect(this);
                });

                $(document).on('click', '.color li, .product-color li', function(e) {
                    $(this).parent().find('li').removeClass('active');
                    $(this).addClass('active');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $(document).on('click', '.size li', function(e) {
                    if ($(this).hasClass('disabled')) return;
                    $(this).parent().find('li').removeClass('active');
                    $(this).addClass('active');
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });                

                $(document).on('changeIsotope', function() {
                    MYAPP.main.product.resizeText(productWrapp);
                });
            },

            modalPositionCorrect: function(modal) {
                if($(modal).length === 0) return;
                var scrollWidth = $(modal).get(0).offsetWidth - $(modal).get(0).clientWidth;

                $(modal).eq(0).css({marginRight: '-' + scrollWidth + 'px'});
            },

            baners: {
                resizeBlock: function() {
                    var baner = $('.baner-2'),
                        windWidth = window.innerWidth,
                        sizeDef = 10,
                        banerBtnTransition = 300;
                    
                    if(windWidth > 414) {
                        var banerWidth = $(baner).width(),
                            CONST_WIDTH = 1170,
                            quotient = banerWidth/CONST_WIDTH;

                        $(baner).find('.baner-block').css({ fontSize: 'inherit'});
                        $(baner).css({ fontSize: sizeDef * quotient + 'px'});
                    } else {
                        setTimeout(function() {
                            var banerBlock = $(baner).find('.baner-block'),
                                CONST_WIDTH_BLOCKS = [470, 270, 370, 370, 370, 370],
                                i = 0;

                            for(; i < banerBlock.length; i++) {
                                var block = $(banerBlock).eq(i),
                                    blockWidth = $(block).width(),
                                    CONST_WIDTH_THIS = CONST_WIDTH_BLOCKS[i],
                                    quotient = blockWidth/CONST_WIDTH_THIS;

                                $(block).css({ fontSize: sizeDef * quotient + 'px'});
                            }
                        }, 100);
                    }

                },

                addLabels: function() {
                    var product = $('.baner-3 .product'),
                        i = 0;

                    for(; i < product.length; i++) {
                        if(i === 0) {
                            var label = $('<div>').addClass('label'),
                                span = $('<span>').addClass('icon icon-chevron-right');
                            
                            $(label).append(span);
                            
                        } else if (i < product.length - 1) {
                            var label = $('<div>').addClass('label'),
                                span = $('<span>').addClass('icon icon-add');
                                
                                $(label).append(span);
                        }

                        if (i < product.length - 1) {
                            $(product).find('.product-image-block').eq(i).append(label);
                        }
                    }
                }
            },

            product: {
                hover: function() {
                    var windWidth = window.innerWidth,
                        substrate = $(this).find('.substrate'),
                        mainInside = $(this).find('.product-main-inside'),
                        previewWrapp = $(this).find('.product-hidden-block-2'),
                        previewWrappWidth = ($(previewWrapp).length > 0) ? $(previewWrapp).innerWidth() : 10,
                        hiddenInfo = $(this).find('.product-hidden-block-1'),
                        hiddenInfoHeight = ($(hiddenInfo).length > 0) ? $(hiddenInfo).innerHeight() : 0,
                        substrateWidth = $(mainInside).innerWidth() + previewWrappWidth,
                        substrateHeight = $(mainInside).innerHeight() + hiddenInfoHeight,
                        left,
                        right;

                    if($(this).hasClass('right-hover') && windWidth < 1363 || $(this).hasClass('right-hover-always')) {
                        left = '-10px';
                        right = 'auto';
                    } else {
                        left = 'auto';
                        right = '-10px';
                    }

                    $(substrate).css({
                        width: substrateWidth + 10 + 'px',
                        height: substrateHeight + 20 + 'px',
                        left: left,
                        right: right
                    });
                },

                changePhoto: function(e) {
                    var target = e.target,
                        imageBlock = $(target).parents('.product').find('.product-image-block'),
                        mainImage = $(imageBlock).find('img'),
                        src = $(target).attr('data-preview'),
                        imageBlockHeight = $(imageBlock).innerHeight(),
                        timeChange = 260;

                    $(imageBlock).css({ height: imageBlockHeight + 'px' });

                    $(mainImage).fadeOut(timeChange/2);
                    setTimeout(function() { $(mainImage).attr('src', src); }, timeChange/2);
                    $(mainImage).on('load', function() {
                        $(imageBlock).css({ height: 'auto' });
                        $(mainImage).fadeIn(timeChange/2);
                    });
                },

                resizeText: function(productWrapp) {
                    var productWrapp = productWrapp || $('.products-wrapper').get(0),
                        product = $(productWrapp).find('.product'),
                        CONST_WIDTH = 270.5,
                        sizeDef = 7.4,
                        i = 0;

                    for(; i < product.length; i++) {
                        var productThis = $(product).eq(i),
                            thisWidth = $(productThis).width(),
                            quotient = thisWidth/CONST_WIDTH;

                        var curtains = $(productThis).find('.product-image-block .curtain');

                        curtains.css({ fontSize: sizeDef * quotient + 'px' });
                    }
                },

                changeTimer: function(product) {
                    setInterval(function() {
                        var i = 0;

                        for(; i < product.length; i++) {
                            var curtain = $(product).eq(i).find('.curtain-2');

                            if(curtain.length === 0) continue;

                            var day = $(curtain).find('.day .number'),
                                hrs = $(curtain).find('.hrs .number'),
                                min = $(curtain).find('.min .number'),
                                sec = $(curtain).find('.sec .number'),
                                numbDay = +$(day).html(),
                                numbHrs = +$(hrs).html(),
                                numbMin = +$(min).html(),
                                numbSec = +$(sec).html();

                            if(numbSec === 0 && numbMin === 0 && numbHrs === 0 && numbDay === 0) return;

                            numbSec--;
                            if(numbSec < 0) {
                                numbSec = 59;
                                numbMin--;
                            }

                            if(numbMin < 0) {
                                numbMin = 59;
                                numbHrs--;
                            }

                            if(numbHrs < 0) {
                                numbHrs = 23;
                                numbDay--;
                            }

                            if(numbDay < 0) {
                                numbSec = 0;
                                numbMin = 0;
                                numbHrs = 0;
                                numbDay = 0;
                            }

                            var addZero = function(number) {
                                if(+number < 10) number = '0' + number;
                                return number;
                            };

                            numbSec = addZero(numbSec);
                            numbMin = addZero(numbMin);
                            numbHrs = addZero(numbHrs);
                            numbDay = addZero(numbDay);

                            $(sec).html(numbSec);
                            $(min).html(numbMin);
                            $(hrs).html(numbHrs);
                            $(day).html(numbDay);
                        }

                    }, 1000);
                },

                quickView: {
                    show: function() {
                        var productNumber = $(this).parents('.product').attr('data-product-id') || 'default',
                            str = 'number=' + productNumber,
                            type = 'POST';

                        function addModal(html, plug) {
                            var modal = $('.modal.quick-view-modal'),
                                windHeight = $(window).innerHeight();

                            if(plug === true) {
                                var mainImg = $(modal).find('.code-plug .image-block .main-image img'),
                                    previewImg = $(modal).find('.code-plug .image-block .preview-image img'),
                                    i = 0;
                                
                                $(mainImg).attr('src', 'images/product/product_images/men/' + productNumber + '_1.jpg');
                                for(; i < $(previewImg).length; i++) {
                                    $(previewImg).eq(i).attr('src', 'images/product/product_images/men/' + productNumber + '_'+ (i + 1) +'.jpg')
                                }
                                $(modal).find('.code-plug').show();
                            } else {
                                $(modal).find('.modal-dialog .modal-content .modal-body').html(html);
                            }

                            $(modal).find('.modal-dialog').css({
                                marginTop: windHeight/100*14 + 'px'
                            });
                            $(modal).modal();

                            $(modal).find('.count input[name="qty"]').keyup().parents('.count').mouseleave();

                            $('.modal.quick-view-modal .preview-image img').on('click.changePhoto', function(e) {
                                MYAPP.main.product.quickView.changePhoto.call(this);
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            });

                            $(modal).on('hidden.bs.modal', function() {
                                $('.modal.quick-view-modal .preview-image a').unbind('click.changePhoto');
                            });
                        };

                        $.ajax({
                            data: str,
                            type: type,
                            cache: false,
                            url: 'ajax/quick_view.ashx',
                            success: function(data) {
                                if(data === 'Error') {
                                    alert('Error sending data to the server');
                                    return;
                                }
                                
                                addModal(data);
                            },
                            error: function(jqXHR, exception) {
                                if (jqXHR.status === 0) {
                                    console.log('Not connect.\n Verify Network.');

                                    addModal('', true);

                                } else if (jqXHR.status == 404) {
                                    console.log('Requested page not found. [404]');
                                } else if (jqXHR.status == 500) {
                                    console.log('Internal Server Error [500].');
                                } else if (exception === 'parsererror') {
                                    console.log(jqXHR.responseText);
                                } else if (exception === 'timeout') {
                                    console.log('Time out error.');
                                } else if (exception === 'abort') {
                                    console.log('Ajax request aborted.');
                                } else {
                                    console.log('Uncaught Error.\n' + jqXHR.responseText);
                                }
                            }
                        });
                    },

                    changePhoto: function() {
                        var mainImageBlock = $(this).parents('.image-block').find('.main-image'),
                            mainImage = $(mainImageBlock).find('img'),
                            src = $(this).attr('data-preview'),
                            imageBlockHeight = $(mainImageBlock).innerHeight(),
                            timeChange = 260;

                        $(mainImageBlock).css({ height: imageBlockHeight + 'px' });

                        $(mainImage).fadeOut(timeChange/2);
                        setTimeout(function() { $(mainImage).attr('src', src); }, timeChange/2);
                        $(mainImage).on('load', function() {
                            $(mainImageBlock).css({ height: 'auto' });
                            $(mainImage).fadeIn(timeChange/2);
                        });
                    }
                }
            },

            tabs: {
                changeTab: function(use) {
                    var use = use || 'program',
                        windWidth = window.innerWidth,
                        dataTab = $(this).attr('data-tab'),
                        tabsWrapp = $(this).parents('.tabs'),
                        tabs = $(tabsWrapp).find('.content > div'),
                        hasActive = $(this).hasClass('active'),        
                        tabsDel = $(this).parent().find('li .tab'),
                        i = 0;
                    
                    $(this).parent().find('> li').removeClass('active');

                    $(this).parent().find('> li').stop();
                    $(this).parent().find('> li > a').stop();

                    $(this).parent().find('> li').animate({
                        height: '42px'
                    },{
                        complete: function() {
                            $(tabsDel).remove();
                        }
                    });
                    $(this).parent().find('> li > a').animate({
                        paddingTop: '8px'
                    });

                    if(use === 'user')
                        if(hasActive) return;

                    $(this).addClass('active');

                    if(windWidth > 768) {
                        $(tabs).hide();

                        for(; i < tabs.length; i++) {
                            if($(tabs).eq(i).hasClass(dataTab)) {
                                $(tabs).eq(i).show();
                                break;
                            }
                        }

                        MYAPP.main.tabs.moveBorder('slide');

                    } else {
                        
                        for(; i < tabs.length; i++) {
                            if($(tabs).eq(i).hasClass(dataTab)) {
                                var innerTab = $(tabs).eq(i).clone();

                                $(this).append(innerTab);
                                $(innerTab).show();


                                var tabHeight = $(this).find('.tab').innerHeight(),
                                    thisHeight = 42,
                                    paddingTop = 30,
                                    setHeight = thisHeight + paddingTop + tabHeight;

                                $(this).stop();
                                $(this).find('> a').stop();

                                $(this).animate({
                                    height: setHeight + 'px'
                                });
                                $(this).find('> a').animate({
                                    paddingTop: paddingTop + 'px'
                                });
                                break;
                            }
                        }
                    }
                },

                moveBorder: function (transition) {
                    var border = $('.tabs .navigation .border');

                        if(border.length === 0) return;

                    var borderWidth = $(border).width(),
                        li = ($('.tabs .navigation ul li.active').length > 0) ? 
                            $('.tabs .navigation ul li.active') : 
                            $('.tabs .navigation ul li:first-child'),
                        liPos = $(li).position().left,
                        liWidth = $(li).width();

                    if(transition === 'slide') {
                        $(border).animate({marginLeft: liPos - 15 - (borderWidth - liWidth)/2 + 'px'}, 300);
                    } else if (transition === 'no-slide') {
                        $(border).css({marginLeft: liPos - 15 - (borderWidth - liWidth)/2 + 'px'});
                    }
                }
            },

            listing: {
                accordion: function(e) {
                    var li = $(this).parent();

                    $(li).toggleClass('active');
                    $(li).stop();

                    if($(li).hasClass('active')) {
                        var tabHeight = $(li).find('.content').innerHeight(),
                            thisHeight = 58,
                            setHeight = thisHeight + tabHeight;

                        $(li).animate({
                            height: setHeight + 'px'
                        }, {
                            complete: function() {
                                $(li).css({height: 'auto'});
                            }
                        });
                    } else {
                        $(li).animate({
                            height: '58px'
                        });
                    }
                },

                listToggle: function(e) {
                    var li = $(this).parent();

                    if($(li).find('ul').get(0)) {
                        $(li).toggleClass('active');
                        if($(li).hasClass('active')) {
                            var height = $(li).find('ul').innerHeight() + 31;
                            $(li).animate({height: height + 'px'}, {
                                complete: function() {
                                    $(li).css({height: 'auto'});
                                }
                            });
                        } else
                            $(li).animate({height: '31px'});

                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }

                },

                changeProductGridView: function(e) {
                    var productWrapp = $('.products-wrapper');

                    $(this).parent().children().removeClass('active');
                    $(this).addClass('active');

                    if($(this).hasClass('list')) {
                        $(productWrapp).removeClass('product-grid').addClass('product-list');
                    } else if($(this).hasClass('grid')) {
                        $(productWrapp).removeClass('product-list').addClass('product-grid');
                        MYAPP.main.product.resizeText(productWrapp);
                    }
                }
            },

            count: {
                changeCount: function(e, number) {
                    var count = $(this).parents('.count'),
                        btns = $(count).find('.tt-btn'),
                        output = $(count).find('.output'),
                        numb = +number || +$(output).prop('value'),
                        max = +$(output).attr('data-max'),
                        setNumb;

                    if($(this).hasClass('btn-less')) {
                        if(numb <= 1) return;
                        setNumb = numb - 1;
                    } else if ($(this).hasClass('btn-more')) {
                        if(numb >= max) return;
                        setNumb = numb + 1;
                    } else if (number) {
                        setNumb = numb;
                    }

                    if(setNumb != undefined && isNaN(setNumb)) setNumb = 1;

                    $(btns).removeClass('disabled');
                    $(count).tooltip('destroy');

                    if (setNumb <= 1) {
                        $(output).prop('value', '1');
                        $(count).find('.btn-less').addClass('disabled');
                            $(count).tooltip({
                                title: 'This minimum value',
                                animation: false,
                                placement: 'bottom',
                                trigger: 'hover'
                            });
                            $(count).tooltip('show');
                    } else if (setNumb >= max) {
                        $(output).prop('value', max); 
                        $(count).find('.btn-more').addClass('disabled');
                            $(count).tooltip({
                                title: 'This maximum value',
                                animation: false,
                                placement: 'bottom',
                                trigger: 'hover'
                            });
                            $(count).tooltip('show');
                    } else {
                        $(output).prop('value', setNumb);
                    }
                }
            },

            compare: {
                alignBlocks: function(event) {
                    if($('.compare').length > 0) {
                        var productRow = $('.compare > .fixed-wrapp > .row'),
                            event = event || false,
                            div,
                            thisHeight,
                            maxHeight,
                            i = 0,
                            j = 0;

                        for(; i < productRow.length; i++) {
                            div = $(productRow).eq(i).children(),
                            maxHeight = 0;

                            $(div).css({'min-height': 'auto'});

                            for(j = 0; j < div.length; j++) {
                                thisHeight = $(div).eq(j).innerHeight();

                                if(thisHeight > maxHeight) maxHeight = thisHeight;
                            }

                            div.css({'min-height': maxHeight + 'px'});
                        }
                        if(event === 'ready') {
                            $('.compare').addClass('border');
                        }
                    }
                }
            }
        },

        footer: {
            setUpListeners: function() {
                var toTop = $('.footer .to-top'),
                    popups = $('.footer .popups-btn .tt-btn');

                $(toTop).on('click', function(e) {
                    $('html, body').animate({scrollTop: 0}, 500);
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });

                $(popups).find('.curtain').on('click', function() {
                    var hasActive = $(this).parent().hasClass('active');

                    $(this).parent().parent().find('.tt-btn').removeClass('active');

                    if(hasActive) $(this).parent().removeClass('active');
                    else  $(this).parent().addClass('active');
                });
            }
        }
    };

    MYAPP.initialize();

    function endResizeMain(func) {
        var windWidth = window.innerWidth,
            interval;

        $(baner2).removeClass('transition');
        
        interval = setInterval(function() {
            var windWidthInterval = window.innerWidth;
            if(windWidth === windWidthInterval) {
                setTimeout(function() {
                    func();
                    $(baner2).addClass('transition');
                }, 100);
            } 
            clearInterval(interval);
        }, 100);  
    };

    $(function() {
        var productWrapp = $('.products-wrapper'),
            product = $('.product'),
            swiperSlideText = $('.swiper-container .swiper-slide span'),
            listing = $('.listing'),
            btnListView = $(listing).find('.control .btn-list-block a'),
            modalsActive = $('.modal.active'),
            baner2 = $('.baner-2'),
            tabs = $('.tabs');

        carouselReady();
        revolutionReady();
        MYAPP.topbar.resizeTopSpacer();
        MYAPP.main.product.resizeText();
        $(productWrapp).find('.product .curtain').show();
        MYAPP.main.product.changeTimer(product);
        MYAPP.main.baners.addLabels();
        $(swiperSlideText).fadeIn(200);
        MYAPP.main.compare.alignBlocks('ready');
        $(btnListView).parent().find('.active').click();
        $(modalsActive).modal();
        MYAPP.main.baners.resizeBlock();
        $(baner2).css({visibility: 'visible'});
        MYAPP.main.tabs.changeTab.call($(tabs).find('.navigation ul li.active'));
    });

    $(window).on('load', function() {
        isotopeLoad();
    });

    $(window).on('resize', function() {
        var baner2 = $('.baner-2'),
            productWrapp = $('.products-wrapper'),
            tabs = $('.tabs');

        carouselResize();
        MYAPP.topbar.hideMoreOpt();
        MYAPP.topbar.hideBasket();
        MYAPP.topbar.hideSearch();
        MYAPP.topbar.hideMegaMenu();
        MYAPP.topbar.hideSimpleMenu();
        MYAPP.topbar.hideOptionsMenu();
        MYAPP.topbar.resizeTopSpacer();
        MYAPP.topbar.hideMobileMenu();
        MYAPP.main.baners.resizeBlock(baner2);
        endResizeMain(MYAPP.main.baners.resizeBlock);
        MYAPP.main.product.resizeText(productWrapp);
        MYAPP.main.tabs.moveBorder('no-slide');
        MYAPP.main.compare.alignBlocks();
        MYAPP.main.modalPositionCorrect($('.modal.in'));
        endResize(function() {
            var windWidth = window.innerWidth,
                activeLi = ($(tabs).find('.navigation ul li.active').length > 0) ?
                            $(tabs).find('.navigation ul li.active') :
                            $(tabs).find('.navigation ul li:first-child');

            if(windWidth < 769 && $(tabs).find('.navigation ul li.active').length === 0)
                return; 

            MYAPP.main.tabs.changeTab.call($(activeLi).get(0));
        });
    });

    $(window).on('scroll', function() {
        MYAPP.topbar.hideMoreOpt();
        MYAPP.topbar.hideBasket();
        MYAPP.topbar.hideSearch();
        MYAPP.topbar.hideMegaMenu();
        MYAPP.topbar.hideSimpleMenu();
    });

})();

