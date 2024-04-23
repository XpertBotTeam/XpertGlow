$(document).ready(function() {

    function update_item(itemElement, quantity) {
        const itemId = itemElement.data('item-id');
        $.ajax({
            url: '/update_cart_item',
            method: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                item_id: itemId,
                quantity: quantity,
            },
            success: function(response) {

                updateCartUI(response.cart);
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
            }
        });
    }
    
    function delete_item(itemElement) {
        const itemId = itemElement.data('item-id');
        $.ajax({
            url: '/delete_cart_item',
            method: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                item_id: itemId 
            },
            success: function(response) {
                if (response.success) {
                    updateCartUI(response.cart);
                    const notification_message = $('.notification_message');
                    const notification = $('.notification');
                    notification_message.text(response.message);
                    notification.css("display","flex");
                }
            },
            error: function() {
            }
        });
    }

    function delete_cart(cartElement) {

        const cartId = cartElement.data('cart-id');
        $.ajax({
            url: '/delete_cart',
            method: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                cart_id: cartId 
            },
            success: function(response) {
                if (response.success) {
                    $('.cart_wrapper').remove();
                    const emptyCartHtml = `
                    <div class="no_results">
                        <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
                        <div class="no_results_text">Your Cart is Empty</div>
                    </div>
                    `;
                    $('body').append(emptyCartHtml);
                }
                    const notification_message = $('.notification_message');
                    const notification = $('.notification');
                    notification_message.text(response.message);
                    notification.css("display","flex");
            },
            error: function() {
            }
        });
    }

    function place_order(addressId) {
        $.ajax({
            url: '/place_order',
            method: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                address_id: addressId
            },
            success: function(response) {
                
                if (response.success) {
                    $('.cart_wrapper').remove();
                    const emptyCartHtml = `
                    <div class="no_results">
                        <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
                        <div class="no_results_text">Your Cart is Empty</div>
                    </div>
                    `;
                    $('body').append(emptyCartHtml);
                    const notification_message = $('.notification_message');
                    const notification = $('.notification');
                    notification_message.text(response.message);
                    notification.css("display","flex");
                }
                
            },
            error: function() {
            }
        });

    }

    $('.input_container button#increase').click(function() {
        const button = $(this);
        const itemElement = button.closest('.item');
        const input = itemElement.find('input[type="number"]');
        const newQuantity = parseInt(input.val()) + 1;
        update_item(itemElement, newQuantity);
    });

    $('.input_container button#decrease').click(function() {
        const button = $(this);
        const itemElement = button.closest('.item');
        const input = itemElement.find('input[type="number"]');
        let quantity = parseInt(input.val());
        if (quantity > 1) {
            const newQuantity = quantity - 1;
            update_item(itemElement, newQuantity);    
        }
    });

    $('.item_delete button#remove').click(function() {
        const button = $(this);
        const itemElement = button.closest('.item');
        delete_item(itemElement);
    });

    $('.item_delete button#remove_all').click(function() {
        const button = $(this);
        const cartElement = button.closest('.cart_wrapper');
        delete_cart(cartElement);
    });

    $('.check_place button#place_order').click(function() {
        const button = $(this);
        const selectedAddress = $('#address-select').val();

        if (selectedAddress) {
            place_order(selectedAddress);
        }
        
    });

    function updateCartUI(cart) {

        if (cart.cart_items.length > 0) {
            const allItemsContainer = $('.all_items');
            const checkItems = $('.check_items span');
            const checkPrice = $('.check_price span');
            allItemsContainer.empty();
            const emptyItemHtml = `
                    <div class="item empty-item">
                        <div class="item_image"></div>
                        <div class="item_name"></div>
                        <div class="item_quantity"></div>
                        <div class="item_subtotal"></div>
                        <div class="item_delete"><button id="remove_all">Remove All</button></div>
                    </div>
                `;
            allItemsContainer.append(emptyItemHtml);

            let totalItems = 0;
            let totalPrice = 0;

            cart.cart_items.forEach(function(cart_item) {

                const baseUrl = 'storage/images/products/';
                const imagePath = baseUrl + cart_item.product.images[0].path;
                const subtotal = (cart_item.product.price * cart_item.quantity).toFixed(2);
                totalItems += cart_item.quantity;
                totalPrice += cart_item.product.price * cart_item.quantity;

                const itemHtml = `
                <div class="item" data-item-id="${cart_item.id}">
                    <div class="item_image">
                        <div class="image_container"><img src="${imagePath}" alt="${cart_item.product.name}"></div>
                    </div>
                    <div class="item_name">
                    <a href="./product/${cart_item.product.id}">${cart_item.product.name}</a>
                    </div>
                    <div class="item_quantity">
                        <div class="input_container">
                            <button id="decrease">-</button>
                            <input type="number" id="quantity" value="${cart_item.quantity}" min="1" readonly>
                            <button id="increase">+</button>
                        </div>
                    </div>
                    <div class="item_subtotal">
                        $${subtotal}
                    </div>
                    <div class="item_delete"><button id="remove">Remove</button></div>
                </div>
            `;
                allItemsContainer.append(itemHtml);
            });

            checkItems.text(totalItems);
            checkPrice.text(`$${totalPrice.toFixed(2)}`);
            bindEventListeners();

        }
        else {
            $('.cart_wrapper').remove();
            const emptyCartHtml = `
                    <div class="no_results">
                        <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
                        <div class="no_results_text">Your Cart is Empty</div>
                    </div>
            `;
            $('body').append(emptyCartHtml);
        }
    }


    function bindEventListeners() {

        $('.input_container button#increase').off('click').on('click', function() {
            const button = $(this);
            const itemElement = button.closest('.item');
            const input = itemElement.find('input[type="number"]');
            const newQuantity = parseInt(input.val()) + 1;
            update_item(itemElement, newQuantity);
        });
    
        $('.input_container button#decrease').off('click').on('click', function() {
            const button = $(this);
            const itemElement = button.closest('.item');
            const input = itemElement.find('input[type="number"]');
            let quantity = parseInt(input.val());
            if (quantity > 1) {
                const newQuantity = quantity - 1;
                update_item(itemElement, newQuantity);
            }
        });

        $('.item_delete button#remove').click(function() {
            const button = $(this);
            const itemElement = button.closest('.item');
            delete_item(itemElement);
        });

        $('.item_delete button#remove_all').click(function() {
            const button = $(this);
            const cartElement = button.closest('.cart_wrapper');
            delete_cart(cartElement);
        });
    }

});
