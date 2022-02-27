jQuery(document).ready( function() {

	var debug_active = false;

    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {action: "op_get_order"},
    })
    .then(function(data) {
        // console.log(data);
        if (!data) {
            throw Error;
        }
        return preparePopData(data);
    })
    .then(function(data) {
        // console.log(data);
        if (!data) return;

        createPop(data);
    });
 
    function preparePopData(data) {
        var orderPop = localStorage.getItem('order_pop'),
					orderPopData = {},
					dismissInterval = data.options.pop_interval_between_pops_after_dismissed_minutes;

        if (orderPop) {
					orderPopData = JSON.parse(orderPop);
				}

				orderPopData.dismissInterval = dismissInterval;
				localStorage.setItem('order_pop', JSON.stringify(orderPopData));
				debug_active = data.options.debug_active;
				if (debug_active) {
					console.log('set local storage', orderPopData);
				}
        return data;
    }

		function queuePop(data) {
			var orderPop = localStorage.getItem('order_pop'),
				orderPopData = JSON.parse(orderPop);

			if (orderPopData.dismissedUntil && moment.duration(moment().utc().diff(orderPopData.dismissedUntil)).asMinutes() < 0) {
				if (debug_active) {
					console.log(moment.duration(moment().utc().diff(orderPopData.dismissedUntil)).asMinutes());
				}
				//	just keep looping until dismiss date reached - not hitting the db so minimal perf
				setTimeout(function() {
					jQuery('.op-popper').remove();
					createPop(data);
				}, 10000)
				return false;
			}

			return true;
		}

    function createPop(data) {
			if (!queuePop(data)) {
				return;
			}

			var popper = jQuery(`<div />`),
				fontColour = data.options.pop_font_colour,
				backgroundColour = data.options.pop_background_colour,
				dismissInterval = data.options.pop_interval_between_pops_after_dismissed_minutes;
				orderPop = localStorage.getItem('order_pop'),
				orderPopData = JSON.parse(orderPop);

			popper.attr('class', 'op-popper');

			if (backgroundColour) {
					popper.css('background-color', backgroundColour);
			}
			
			jQuery(
					`<div class="op-content-container">
							<button type="button" class="close" aria-label="Close" 
											style="background-color: transparent; padding: 0; color: ${fontColour};">
									<span id="order-pop-dismiss-button" data-dismissinterval="${dismissInterval}" aria-hidden="true">&times;</span>
							</button>
							<div class="op-content-wrapper"></div>
					</div>`
			).appendTo(popper);

			popper.appendTo('body');

			refreshPop(data, 0);

			if (debug_active) {
					console.log(data);
			}
    }

    function refreshPop(data, productIndex) {
			if (!queuePop(data)) {
				return;
			}

			var fontColour = data.options.pop_font_colour,
				refreshInterval = parseInt(data.options.pop_interval_between_pop_refresh_seconds)*1000,
				utmCode = data.options.utm_code,
				formattedDate = buildDate(data.products[productIndex]['order_date']),
				customerFirstName = data.products[productIndex].order_first_name,
				customerLastName = data.products[productIndex].order_last_name.charAt(0),
				customerCity = data.products[productIndex].order_city,
				customerState = data.products[productIndex].order_state,
				productName = data.products[productIndex].name,
				productUrl = data.products[productIndex].url,
				productImage = data.products[productIndex].image;
				
			if (debug_active) {
				console.log(`Refreshing....${new Date()}`, productName);
			}

			var html = `
				<div class="op-content" style="color: ${fontColour}">
						<span class="orderdate meta" style="color: ${fontColour}">${formattedDate}</span>
						<p class="customer-details pt-0" style="color: ${fontColour}">${customerFirstName} ${customerLastName} 
								from ${customerCity}, ${customerState} bought ...</p>
						<p class="product-name" style="color: ${fontColour}">
							<a href="${productUrl}${utmCode}">${productName}</a>
						</p>
						<span class="meta" style="color: ${fontColour}">
								<a href="${productUrl}${utmCode}" style="color: ${fontColour}">Click here to view</a>
						</span>
				</div>
				<div class="op-image">${productImage}</div>
			`;

			// console.log('product', data.products[productIndex]);
			jQuery('.op-content-wrapper').toggleClass('refreshing');
			setTimeout(function() {
				jQuery('.op-content-wrapper').html(html).toggleClass('refreshing');
			}, 500)

			if (productIndex == data.products.length-1) {
				productIndex = 0;
			}

			setTimeout(function() {
				productIndex++;
				refreshPop(data, productIndex);
			}, refreshInterval)

    }

    function buildDate(date) {

        var diff = moment.duration(moment().diff(date)).asHours();
        // console.log(date, diff);
        if (diff > 48) {
            return `${parseInt(diff/24)} days ago`;
        }

        if (diff < 1) {
            return `${parseInt(diff*60)} min ago`;
        }

        return `${parseInt(diff)} hours ago`;
    }

		window.addEventListener('click', function(e) {
			if (e.target.id != 'order-pop-dismiss-button') {
				return;
			}

			var button = document.getElementById(e.target.id),
				dismissInterval = button.getAttribute('data-dismissinterval');

			var orderPop = localStorage.getItem('order_pop');
			var orderPopData = {};
			if (orderPop) {
				var orderPopData = JSON.parse(orderPop);
			}

			orderPopData.dismissedUntil = moment().add(dismissInterval, "minutes");
			localStorage.setItem('order_pop', JSON.stringify(orderPopData));

			if (debug_active) {
				console.log('dismissed', JSON.parse(orderPop));
			}
			document.querySelector('.op-popper').style.left = '-999px';
		});
 })