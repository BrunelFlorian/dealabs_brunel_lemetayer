import $ from 'jquery';

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
            alert(error);
        }
    });
}