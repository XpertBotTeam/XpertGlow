
    $(document).ready(function() {

        $('.order_cancel button#cancel').click(function() {
            const button = $(this);
            const orderElement = button.closest('.order_cancel');
            cancel_order(orderElement);
        });

        function cancel_order(orderElement) {
            const orderID = orderElement.data('order-id');
            $.ajax({
                url: '/cancel_order',
                method: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    order_id: orderID
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                }
            });
        }

    });

    
    const order = $('.order_wrapper');
    const order_status = order.data('order-status');
    const line_1 = $('.line_1');
    const line_2 = $('.line_2');
    const pending = $('.pending');
    const processing = $('.processing');
    const completed = $('.completed');
    const pending_circle = $('.pending_circle');
    const processing_circle = $('.processing_circle');
    const completed_circle = $('.completed_circle');

    if(order_status==="pending"){
        pending_circle.css("border-color","black");
        pending.css("color","black");
        pending_circle.css("animation","fade_shadow 2s ease-out  infinite");
    }

    if(order_status==="processing"){
        pending_circle.css("border-color","black");
        pending.css("color","black");
        pending_circle.css("background-color","black");
        line_1.css("background-color","black");
        processing_circle.css("border-color","black");
        processing.css("color","black");
        processing_circle.css("animation","fade_shadow 2s ease-out  infinite");
    }

    if(order_status==="completed"){
        pending_circle.css("border-color","black");
        pending.css("color","black");
        pending_circle.css("background-color","black");
        line_1.css("background-color","black");
        processing_circle.css("background-color","black");
        processing_circle.css("border-color","black");
        processing_circle.css("color","black");
        processing.css("color","black");
        line_2.css("background-color","black");
        completed_circle.css("background-color","black");
        completed_circle.css("border-color","black");
        completed.css("color","black");
    }

    if(order_status==="cancelled"){
        pending_circle.css("border-color","black");
        pending_circle.css("background-color","black");
        pending.css("color","black");
        line_1.css("background-color","black");
        line_2.css("background-color","black");
        processing_circle.remove();
        processing.remove();
        completed_circle.css("background-color","black");
        completed_circle.css("border-color","black");
        completed.css("color","black");
        completed.text('Cancelled');
    }

