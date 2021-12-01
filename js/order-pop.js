
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
        var formattedDate = buildDate(data['order_date']);
        var fontColour = data.options.pop_font_colour;

        console.log(formattedDate);
        popper.attr('class', 'op-popper');

        if (data.options.pop_background_colour) {
            popper.css('background-color', data.options.pop_background_colour);
        }
        
        jQuery(
            `<button type="button" class="close" aria-label="Close" 
                    style="background-color: transparent; padding: 0; color: ${fontColour};"
                    onClick="document.querySelector('.op-popper').style.left = '-999px'">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="op-content-container">
                <div class="op-content" style="color: ${fontColour}">
                    <span class="orderdate meta" style="color: ${fontColour}">${formattedDate}</span>
                    <p class="customer-details pt-0" style="color: ${fontColour}">${data.customer.first_name} ${data.customer.last_name.charAt(0)} from ${data.customer.city}, ${data.customer.state} bought ..</p>
                    <p class="product-name" style="color: ${fontColour}">${data.product.name}</p>
                    <span class="meta" style="color: ${fontColour}">
                        <a href="#" style="color: ${fontColour}">Call to action link</a>
                    </span>
                </div>
                <div class="op-image">${data.product.image}</div>
            </div>`
        ).appendTo(popper);

        popper.appendTo('body');
    }

    function buildDate(date) {

        var diff = moment.duration(moment().diff(date)).asHours();
        console.log(date, diff);
        if (diff > 48) {
            return `${parseInt(diff/24)} days ago`;
        }

        if (diff < 1) {
            return `${parseInt(diff*60)} min ago`;
        }

        return `${parseInt(diff)} hours ago`;

        var day = moment(date, 'YYYY-MM-DD').format('Do');
        var month = moment(date, 'YYYY-MM-DD').format('MMMM');
        var year = moment(date, 'YYYY-MM-DD').format('YYYY');
        return {
            day,
            month,
            year,
        };
    }
 })