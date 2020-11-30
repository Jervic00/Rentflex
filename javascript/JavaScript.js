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
$(document).ready(function(){
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
});

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
/* 
slideShow.addEventListener('transitionend', () => {
    if(slideImages[counter].id === 'lastClone')
    {
        slideShow.style.transition = "none";
        counter = slideImages.length - 2;
        slideShow.style.transform = 'translateX(' + (-size * counter) + 'px)';
    }

});
slideShow.addEventListener('transitionend', () => {
    if(slideImages[counter].id === 'firstClone')
    {
        slideShow.style.transition = "none";
        counter = slideImages.length - counter;
        slideShow.style.transform = 'translateX(' + (-size * counter) + 'px)';
    } 

});//Image slider END
*/


