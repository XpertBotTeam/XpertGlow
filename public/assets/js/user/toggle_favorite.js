$(document).ready(function() {
    $('.favorite_button').on('click', function(event) {
        event.preventDefault();
        const button = $(this);
        const productItem = button.closest('.product_item');
        const productId = productItem.data('id');
        const isInFavorites = button.data('in-favorites');
        $.ajax({
            url: '/toggle_favorite',
            method: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id: productId,
                is_favorite: isInFavorites 
            },
            success: function(response) {
                button.data('in-favorites', response.is_favorite);
                const icon = button.find('i');
                if (response.is_favorite) {
                    icon.removeClass('fa-regular').addClass('fa-solid');
                } else {
                    icon.removeClass('fa-solid').addClass('fa-regular');
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to toggle favorite status:', error);
                if (xhr.status === 401) {
                    window.location.href = '/login';
                }
            }
        });
    });

});
