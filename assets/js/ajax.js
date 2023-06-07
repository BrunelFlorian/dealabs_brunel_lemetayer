$(document).ready(function () {    
    $('.rate-deal').on('click', function () {
        var url = $(this).parent().data('url');
        var type = 'POST';
        var data = { type: $(this).data('type') };
        makeAjaxRequest(url, type, data);
    });
});

function makeAjaxRequest(url, type, data) {
    $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        data: data,
        success: function (response) {
            $('.notation-value-' + response.success.dealId).text(response.success.notation);
        },
        error: function (xhr, status, error) {
            $('.deal-list').prepend('<div class="alert alert-danger" role="alert">' + xhr.responseJSON.error + '</div>');

            setTimeout(function () {
                $('.alert-danger').remove();
            }, 10000);
        }
    });
}