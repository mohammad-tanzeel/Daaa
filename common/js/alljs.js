
$(function(){
    
    if($('#fullpage').length!=0){
    $('#fullpage').fullpage({
				anchors: ['section1', 'section2','section3', 'section4', 'section5'],
				menu: '#menu',
                scrollBar:true,
                css3: true,
            // scrollOverflow: true,
                scrollingSpeed: 900,
               'navigation': true,
               'navigationPosition': 'right',
              // 'navigationTooltips': ['fullPage.js', 'Powerful', 'Amazing', 'Simple'],
             
        
         afterLoad: function(anchorLink, index, slideAnchor, slideIndex){
             
				//$('.shad').removeAttr('style');
                 var thissect = $(this)
                    thissect.addClass("costom_act");
             
			    }
        
			});
    
    }
    
    if($('.fullpage_inr').length!=0){
     $('.fullpage_inr').fullpage({
				anchors: ['section1', 'section2','section3', 'section4', 'section5'],
				menu: '#menu',
                scrollBar:true,
                css3: true,
                scrollingSpeed: 900,
               'navigation': true,
               'navigationPosition': 'right',
    
              afterLoad: function(anchorLink, index, slideAnchor, slideIndex){
             
				//$('.shad').removeAttr('style');
                 var thissect = $(this)
                    thissect.addClass("costom_act");
             
			    }
        
			});
   
    }
    
   var num_of_slid =  $('.section').length;
    
    if(num_of_slid<2){
        $('#fp-nav').hide();
    }
   

    var open = false;  
     $('.menubtn').click(function(e){
        e.preventDefault();
        if(!open){
            $('.nav').addClass('js-nav-active')
            
            $(this).addClass('open')
          open = true;
        }
        else{
            $('.nav').removeClass('js-nav-active')
            $(this).removeClass('open')
          open = false;
        } 
         
     });
     
     

    
    $('.circle_nav li span').click(function(){
        var data = $(this).attr('data-cont');
        $('.circle_nav li span').removeClass('active');
        $(this).addClass('active');
        $('.circle_nav').attr('data-active', data);
        
        $('.hom_sec3_tabcont .tab_cont').removeClass('active');
        $('.hom_sec3_tabcont2 .tab_cont').removeClass('active');
        $('.img_cont .img').removeClass('active');
        
        var acttab = 'tab_'+data;
        var act_img = 'img_'+data;
         $('.'+acttab).addClass('active');
         $('.'+act_img).addClass('active');
    })
    
    $('.inr_circl_nav li .piont').click(function(){
        var data = $(this).attr('data-cont');
        $('.inr_circl_nav').attr('data-active', data);
        
        $('.inr_circl_nav li .piont').removeClass('active');
        $(this).addClass('active');
        
        $('.tabcont .tab').removeClass('active');
        $('.circle_slides .circle_slide_img').removeClass('active');
        
        var acttab = 'tab_'+data;
        var act_img = 'img_'+data;
        
         $('.'+acttab).addClass('active');
         $('.'+act_img).addClass('active');
    
    });
    
    $('.inr_circl_nav2 li .piont').click(function(){
        var data = $(this).attr('data-cont');
        $('.inr_circl_nav2').attr('data-active', data);
        
        $('.inr_circl_nav2 li .piont').removeClass('active');
        $(this).addClass('active');
        
        $('.tab_cont .tab').fadeOut();
        
        
        var acttab = 'tab_'+data;
        
        
         $('.'+acttab).fadeIn();
        
    
    });
    
    
    
    
  $('.scroll_pane').jScrollPane();
   
    
    
  $("#testimonials_slider").bxSlider({
        slideWidth: 134,
        maxSlides: 4,
        minSlides: 1,
        moveSlides: 1,
        slideMargin: 30,
		speed:400,
        pager: false,
		oneToOneTouch: false,
        infiniteLoop: false
  });
        
   $(".testimonials_details_cont").hide(); 
    $(".testimonials_slider li a:first").addClass("active").show(); 
    $(".testimonials_details_cont:first").show();
    $(".testimonials_slider li a").click(function() {
    $(".testimonials_slider li a").removeClass("active");
    $(this).addClass("active");
    $(".testimonials_details_cont").hide();
    var activeTab = $(this).attr("href");
            $(activeTab).show(); 
            return false;
    });	
        
  $(".city_dr_list li a").fancybox({
        minWidth  : 100,
        minHeight : 460,
        maxWidth	: 440,
        maxHeight	: 460,
        width		: '95%',
        height		: '95%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none',
        padding : 5,
        autoHeight : true,
        autoWidth  : true,
        openMethod: 'stickyupIn',
        closeMethod: 'stickyupOut',
        type: 'iframe'

   });
    
    
     $(".sca_cont").hide(); 
    $(".sca_tab li a:first").addClass("active").show(); 
    $(".sca_cont:first").show();
    $(".sca_tab li a").click(function() {
    $(".sca_tab li a").removeClass("active");
    $(this).addClass("active");
    $(".sca_cont").hide();
    var activeTab = $(this).attr("href");
            $(activeTab).show(); 
            return false;
    });	        

    $(".faq_cont h3").click(function(){
    $(this).next(".faq_acc").slideToggle("slow")
    .siblings(".faq_acc:visible").slideUp("slow");
    $(this).toggleClass("active");
    $(this).siblings("h3").removeClass("active");
    });

  
//    
});


  
    
