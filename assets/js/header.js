$(document).ready(function() {
    $(window).scroll(function() {
        var currentScroll = $(this).scrollTop();
        console.log("currentscroll = " + currentScroll);

        if (currentScroll > 100) {
            console.log("toto");
            $('.navbar').fadeOut();
        } else {
            console.log("tata");
            $('.navbar').fadeIn();
        }
    });
});