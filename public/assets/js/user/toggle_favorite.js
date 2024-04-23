$(document).ready(function() {
    $('.favorite_button').on('click', function(event) {
        event.preventDefault();
        const button = $(this);
        const addToFavorite = button.closest('.add_to_favorite');
        const productId = addToFavorite.data('id');
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
                const notification_message = $('.notification_message');
                const notification = $('.notification');
                if (response.is_favorite) {
                    icon.removeClass('fa-regular').addClass('fa-solid');
                    notification_message.text("Product added from Favorites");
                } else {
                    icon.removeClass('fa-solid').addClass('fa-regular');
                    notification_message.text("Product removed from Favorites");
                }

                notification.css("display","flex");
            },
            error: function(xhr, status, error) {

                if (xhr.status === 401) {
                    window.location.href = '/login';
                }
            }
        });
    });

});
