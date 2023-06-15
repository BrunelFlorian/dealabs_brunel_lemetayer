$(document).ready(function () {
    $('#report-button').on('click', function () {
        event.preventDefault();
        if ($('#report-form').hasClass('show')) {
            $('#report-form').removeClass('show');
            $('#report-form').hide();
        } else {
            $('#report-form').addClass('show');
            $('#report-form').show();
        }
    });
});