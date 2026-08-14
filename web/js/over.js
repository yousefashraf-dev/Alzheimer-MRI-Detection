
$("h2").click(function(){

})



$(window).scroll(function(){
    let scrollTop = $(window).scrollTop()

    if(scrollTop > 200)
    {
        $(".btn1").fadeIn(500)
    }
    else{
        $(".btn1").fadeOut(500)
    }
  $(".btn1").click(function(){
    $("body,html").animate({scrollTop:0} ,2000)
   })
})
