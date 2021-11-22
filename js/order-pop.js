
jQuery(document).ready( function() {

    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {action: "op_get_order"},
    })
    .then(function(data) {
        console.log(data);
        create_pop(data);
    });
 
    function create_pop(data) {
        var popper = jQuery(`<div />`);
        var orderDate = new Date(data['order_date']);
        popper.attr('class', 'op-popper');
        
        jQuery(`<span class="firstname">${data['first_name']}</span>`).appendTo(popper);
        jQuery(`<span class="lastname"> ${data['last_name']}</span>`).appendTo(popper);

        jQuery(`<span> ordered </span>`).appendTo(popper);

        jQuery(`<span class="productname">${data['product_name']}</span>`).appendTo(popper);

        jQuery(`<span> in </span>`).appendTo(popper);

        jQuery(`<span class="orderdate">${orderDate}</span>`).appendTo(popper);
        popper.appendTo('body');
    }
 })