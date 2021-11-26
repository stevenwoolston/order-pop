
jQuery(document).ready( function() {

    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {action: "op_get_order"},
    })
    .then(function(data) {
        console.log(data);
        if (!data) {
            throw Error;
        }
        return preparePopData(data);
    })
    .then(function(data) {
        // console.log(data);
        if (!data) return;

        create_pop(data);
    });
 
    function preparePopData(data) {
        var orderPop = localStorage.getItem('order_pop');
        // console.log(JSON.parse(orderPop));
        if (orderPop) {
            var orderPopData = JSON.parse(orderPop);
            var minutesElapsed = Math.floor((Math.abs(new Date(orderPopData.last_notification) - new Date()) / 1000)/60);
            // console.log(minutesElapsed);
            orderPopData.interval = data.options.interval;
            if (minutesElapsed >= data.options.interval) {
                data.last_notification = new Date();
                localStorage.setItem('order_pop', JSON.stringify(data));
                return data;
            }
        } else {
            //  never run before
            data.last_notification = new Date();
            localStorage.setItem('order_pop', JSON.stringify(data));
            return data;
        }

        return null;
    }

    function create_pop(data) {
        var popper = jQuery(`<div />`);
        var orderDate = new Date(data['order_date']);
        var formattedDate = buildDate(orderDate);
        popper.attr('class', 'op-popper');

        if (data.options.pop_background_colour) {
            popper.css('background-color', data.options.pop_background_colour);
        }
        
        jQuery(
            `<button type="button" class="close" aria-label="Close" 
                    style="background-color: transparent; padding: 0;"
                    onClick="document.querySelector('.op-popper').style.left = '-999px'">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="op-content-container">
                <div class="op-image">${data.product.image}</div>
                <div class="op-content">
                    <p><span class="firstname">${data.customer.first_name}</span>
                    <span class="lastname"> ${data.customer.last_name}</span>
                    <span> ordered </span>
                    <a class="product_url" href="${data.product.url}">${data.product.name}</a> (${data.product.category})
                    <span> on </span>
                    <span class="orderdate">${formattedDate.day} ${formattedDate.month} ${formattedDate.year}.</span></p>
                    <p>${data.options.sale_message}</p>
                </div>
            </div>`
        ).appendTo(popper);

        popper.appendTo('body');
    }

    function buildDate(date) {
        var day = date.getDate().toString();
        var year = date.getFullYear().toString();
        var month = date.toLocaleString('default', { month: 'long' });
        return {
            day,
            month,
            year,
        };
    }
 })