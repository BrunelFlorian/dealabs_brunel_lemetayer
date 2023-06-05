import $ from 'jquery';

$(document).ready(function () {    
    $('.rate-deal').on('click', function () {
        console.log("toto");
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
            alert(response);
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}