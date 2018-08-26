var interval = null;
function slider(){
  var speed = 10000;
  clearInterval(interval);                                    //to stop previous slideshow if any running
  $("div.active").removeClass("active");
  $("#slideshow > div:gt(0)").fadeOut();
  $('#slideshow > div:first').addClass("active");
  var totalItems = $('#slideshow > div').length;              //find total slides
  $('div.active').fadeIn();                                     //display first slide
  interval = setInterval(function()
  {
   $('div.active').fadeOut();                                 //slideUp(hide) currently displaying slide
    var next = $('div.active').next();                        //find next slide
    $('div.active').removeClass('active');                    
    $(next).fadeIn();                                           //show next slide
    $(next).addClass("active");
    var currentIndex = $('div.active').index() + 1;           //find current slide index
    if(totalItems == 1 || currentIndex == 0)                  //if there is only one slide
    {
      $("#slideshow > div:first").show();
      $("div.active").addClass("active");
    }
    if(currentIndex === totalItems)                           //when last slide display's 
    {
    setTimeout(function(){                                    //display last slide for 'speed' time and then display first slide
      $(next).fadeOut();
      $(next).removeClass("active");
      $('#slideshow > div:first').fadeIn();
      $('#slideshow > div:first').addClass("active");
      }, speed);      
    }
  },speed);
}
slider();                                                   //call slider() function
