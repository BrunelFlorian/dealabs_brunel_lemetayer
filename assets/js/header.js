$(document).ready(function() {
    $(window).scroll(function() {
        var currentScroll = $(this).scrollTop();

        if (currentScroll > 100) {
            $('.navbar').fadeOut();
        } else {
            $('.navbar').fadeIn();
        }
    });
});