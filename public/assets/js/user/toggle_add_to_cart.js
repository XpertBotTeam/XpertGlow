$(document).ready(function() {
    $('#addToCart').on('click', function(event) {
        event.preventDefault();
        const quantity = $('#quantity').val();
        const productId = $(this).data('id');

        console.log(productId);
        console.log(quantity);
        $.ajax({
            url: '/toggle_add_to_cart', 
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                quantity: quantity,
            },
            success: function(response) {

                const notification_message = $('.notification_message');
                const notification = $('.notification');
                notification_message.text(response.message);
                notification.css("display","flex");

            },
            error: function(xhr, status, error) {

                const notification_message = $('.notification_message');
                const notification = $('.notification');
                notification_message.text(xhr.responseJSON.error);
                notification.css("display","flex");

                if (xhr.status === 401) {
                    window.location.href = '/login';
                }
            }
        });
    });
 
});
