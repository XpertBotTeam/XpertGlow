$(document).ready(function() {
    $('#addToCart').on('click', function(event) {
        event.preventDefault();
        const quantity = $('#quantity').val();
        const productId = $(this).data('id');
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
                console.log("added");
            },
            error: function(xhr, status, error) {
                console.error('Failed to toggle Add to Cart status:', error);
                if (xhr.status === 401) {
                    window.location.href = '/login';
                }
            }
        });
    });
 
});
