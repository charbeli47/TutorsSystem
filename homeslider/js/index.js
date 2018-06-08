$(document).ready(function(){
    var slide = $(".slide");
    var pointer = $(".slidepointer");
	var viewWidth = $(window).width();
	var sliderInner = $(".slider-inner");
	var childrenNo = sliderInner.children().length;
	setActive($(".active"));
	sliderInner.width( viewWidth * childrenNo );
	
	$(window).resize(function(){
		viewWidth = $(window).width();
	});
	
	function setWidth(){
		slide.each(function(){
			$(this).width(viewWidth);
			//$(this).css("left", viewWidth * $(this).index());
		});	
	}
	
	function setActive(element){
		var clickedIndex = element.index();
		
		$(".slider-nav .active").removeClass("active");
		element.addClass("active");
		
		sliderInner.css("transform", "translateX(-" + clickedIndex * viewWidth + "px) translateZ(0)");
		
		$(".slider-inner .active").removeClass("active");
		$(".slider-inner .slide").eq(clickedIndex).addClass("active");
	}
	
	setWidth();
	
	$(".slider-nav > div").on("click", function(){
		setActive($(this));
	});
	
	$(window).resize(function(){
		setWidth();
	});
	
	setTimeout(function(){
		$(".slider").fadeIn(500);
	}, 3000);
	var index = 0;
	setInterval(function () {
	    pointer.each(function () {
	        var clickedIndex = $(this).index();
	        if(clickedIndex == index)
	        {
	            $(".slider-nav .active").removeClass("active");
	            $(this).addClass("active");

	            sliderInner.css("transform", "translateX(-" + clickedIndex * viewWidth + "px) translateZ(0)");

	            $(".slider-inner .active").removeClass("active");
	            $(".slider-inner .slide").eq(clickedIndex).addClass("active");
	        }
	        
	    });
	    index++;
	    if (index > pointer.length - 1)
	        index = 0;
	}, 5000);
});