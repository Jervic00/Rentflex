//Navigation Slide
const navSlide = () => {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-link');
    //Toggle burger
    burger.addEventListener('click', () => {
        nav.classList.toggle('nav-active');
        navLinks.forEach((link, index) => {
            if (link.style.animation) {
                link.style.animation = '';
            }
            else
            link.style.animation = `navLinkFade 0.5s ease forwards ${index / 5 + 0.6}s`
    });
    });   
}


//Filter JQuery
/* $(document).ready(function(){

    $(".btn").click(function(){
       var attr = $(this).attr("id");

       $(".btn").removeClass("active");
       $(this).addClass("active");

       if(attr =="all")
       { 
        $(".car-container").show().animate({opacity: '1'}, 500);
       }
       else
       {
        $(".car-container").hide().animate({opacity: '0'}, 200);
        $("." + attr).show().animate({opacity: '1'}, 500);
       }
    });
}); */

$(".pickup-date").keydown(function(e){
    e.preventDefault();
});
$(".drop-date").keydown(function(e){
    e.preventDefault();
});

/* DATEPICKER BETA */
 $(function() {
    var dateFormat = "mm/dd/yy",

      from = $( "#datetime" )
        .datepicker({
          minDate: "1",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
            var nextDay = new Date(getDate(this));
			var RentLimit = new Date(getDate(this));
            nextDay.setDate(nextDay.getDate() + 1);
            
			RentLimit.setDate(RentLimit.getDate() + 7);

          to.datepicker( "option", "minDate", nextDay ); /* SETTINGS MIN DATE ACCORDING TO FIRST DATEPICKER */
          to.datepicker( "option", "maxDate", RentLimit );/* SETTING MAXDATE ACCORDING TO FIRST DATEPICKER */
 
          //compute if qualified for Monthly payment method
        var date = $(this).datepicker('getDate');
        var today = new Date();
        var dayDiff = Math.ceil((date.getTime() - today.getTime() ) / (1000 * 60 * 60 * 24));
        console.log(dayDiff);

        if(dayDiff<60)
        {
            
            $("#DIFFER").prop('disabled', true);
            $("#CASH").prop('checked', true);
        }
        else if (dayDiff>=60)
        {
            $('#DIFFER').prop('disabled', false);

        }
        }),

      to = $( "#datetime1" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
});
/* DATEPICKER BETA */


                    /* TIME PICKER  */
        $(document).ready(function(){
            $('#time').timepicker({
                timeFormat: 'h:mm p',
                interval: 60,
                minTime: '6:00am',
                maxTime: '9:00pm',
                defaultTime: '8',
                startTime: '6:00am',
                dynamic: false,
                dropdown: true,
                scrollbar: false
            });
        });       
        $(document).ready(function(){
            $('#time1').timepicker({
                timeFormat: 'h:mm p',
                interval: 60,
                minTime: '6:00am',
                maxTime: '9:00pm',
                defaultTime: '8',
                startTime: '6:00am',
                dynamic: false,
                dropdown: true,
                scrollbar: false
            });
        }); /* TIME PICKER END */

        //Image Slider
const productSlideshow = () => {
    const slideShow = document.querySelector('.slider');
    const slideImages = document.querySelectorAll('.slider img');
    
    //btn
    const prevBtn = document.querySelector('#prvButton');
    const nextBtn = document.querySelector('#nxtButton');
    
    //Counter
    let counter = 0;
    const size = slideImages[0].clientWidth;
    
    slideShow.style.transform = 'translateX(' + (-size * counter) + 'px)';
    nextBtn.addEventListener('click',() => {
        slideShow.style.transition = "transform 0.4s ease-in-out";
        if(counter >= slideImages.length -1) counter=0;
        else
        counter++;
        slideShow.style.transform = 'translateX(' + (-size * counter) + 'px)';
        
    });
    
    prevBtn.addEventListener('click',() => {    
        slideShow.style.transition = "transform 0.4s ease-in-out";
        if(counter <= 0) counter=slideImages.length-1;
        else
        counter--;
        slideShow.style.transform = 'translateX(' + (-size * counter) + 'px)';
    });
}



const run = () => {
    navSlide();
    productSlideshow();
}
run();