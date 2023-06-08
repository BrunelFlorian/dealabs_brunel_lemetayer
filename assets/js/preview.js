$(document).ready(function () {
    $('.post-deal .post-el').on('keyup change', function () {
        var value = $(this).val();
        console.log(value);
        var id = $(this).attr('id');
        console.log(id);

        // Format value if needed
        if (id == 'post_form_price') {
            value = value + ' €';
        } else if (id == 'post_form_expirationdate') {
            var date = new Date(value);
            value = date.toLocaleString();
        }
        console.log('#' + id + '_preview');
        $('#' + id + '_preview').text(value);
    });
});
