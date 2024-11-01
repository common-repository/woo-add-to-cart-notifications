//Global var to avoid any conflicts
var PENGUINARTS = {};
(function($){
	
'use strict';

	//Predefined variables
	var $_body = jQuery('body'),
		$_base_website_url  = window.location.protocol+'//'+window.location.hostname,
		$_template_html = null,
		$_has_sale_price = null;
	
	PENGUINARTS.template1 = function($product_name, $cart_url){
		$_template_html = '<div class="alert_add_cart" tabindex="-1" role="alert">';
		$_template_html += '<div class="aside_inner">';
	    $_template_html += '<div class="aside_left">';
		$_template_html += '<p>"'+$product_name+'" was added to your cart.</p>';
	    $_template_html += '</div>';
	    $_template_html += '<div class="aside_right">';
	    $_template_html += '<a href="'+$cart_url+'">View cart</a>';
	    $_template_html += '</div>';
		$_template_html += '</div>';
		$_template_html += '</div>';
		return $_template_html;
	}
	
	PENGUINARTS.template2 = function($product_name, $product_image, $price, $sale_price, $currency, $cart_url){
		$_has_sale_price = 'regular_price';
		if($sale_price){
			$_has_sale_price = 'has_sale_price';
		}
		$_template_html = '<div class="alert_add_cart_template2 custom_popup_template" tabindex="-1" role="alert">';
		$_template_html += '<div class="title">';
		$_template_html += '<h2>You have added a new product to your cart!</h2>';
		$_template_html += '</div>';
		$_template_html += '<div class="table_row">';
		$_template_html += '<div class="column left"><div class="background" style="background-image:url('+$product_image+')"></div></div>';
		$_template_html += '<div class="column right">';
		$_template_html += '<p class="product_name">'+$product_name+'</p>';
		$_template_html += '<div class="price_container '+$_has_sale_price+'">';
		$_template_html += '<p class="regular_price">'+$currency +' '+$price+'</p>';
		$_template_html += '<p class="sale_price">'+$currency +' '+$sale_price+'</p>';
		$_template_html += '</div>';
		$_template_html += '<div class="cart_button_template">';
		$_template_html += '<ul>';
		$_template_html += '<li>';
		$_template_html += '<a href="" class="close_popup" class="blue_button">Continue Shopping</a>';
		$_template_html += '</li>';
		$_template_html += '<li>';
		$_template_html += '<a href="'+$cart_url+'" class="green_button">View Cart</a>';
		$_template_html += '</li>';
		$_template_html += '</ul>';
		$_template_html += '</div>';
		$_template_html += '</div>';
		$_template_html += '</div>';
		
		$_template_html += '</div>';
		return $_template_html;
	}
	
	PENGUINARTS.template3 = function($product_name, $product_image, $price, $sale_price, $currency, $more_products_template, $cart_url){
		$_has_sale_price = 'regular_price';
		if($sale_price){
			$_has_sale_price = 'has_sale_price';
		}
		$_template_html = '<div class="alert_add_cart_template2 custom_popup_template" tabindex="-1" role="alert">';
		$_template_html += '<div class="title">';
		$_template_html += '<h2>You have added a new product to your cart!</h2>';
		$_template_html += '</div>';
		$_template_html += '<div class="table_row">';
		$_template_html += '<div class="column left"><div class="background" style="background-image:url('+$product_image+')"></div></div>';
		$_template_html += '<div class="column right">';
		$_template_html += '<p class="product_name">'+$product_name+'</p>';
		$_template_html += '<div class="price_container '+$_has_sale_price+'">';
		$_template_html += '<p class="regular_price">'+$currency +' '+$price+'</p>';
		$_template_html += '<p class="sale_price">'+$currency +' '+$sale_price+'</p>';
		$_template_html += '</div>';
		$_template_html += '<div class="cart_button_template">';
		$_template_html += '<ul>';
		$_template_html += '<li>';
		$_template_html += '<a href="" class="close_popup" class="blue_button">Continue Shopping</a>';
		$_template_html += '</li>';
		$_template_html += '<li>';
		$_template_html += '<a href="'+$cart_url+'" class="green_button">View Cart</a>';
		$_template_html += '</li>';
		$_template_html += '</ul>';
		$_template_html += '</div>';
		$_template_html += '</div>';
		$_template_html += '</div>';
		$_template_html += '<div class="bottom_products_container">'+$more_products_template+'</div>';
		$_template_html += '</div>'; 
		return $_template_html;
	}
	
	PENGUINARTS.check_product = function($product_id){
		jQuery.ajax({
			type:'POST',
          	data:{
          		action:'return_product_details',
          		product_id: $product_id
      		},
          	url: $_base_website_url+"/wp-admin/admin-ajax.php",
	        success: function(result) {
	        	if(result != false){ 
	        		var data = jQuery.parseJSON(result);
	        		console.log(data);
	        		console.log(data.template);
	        		if(data.template == 'template1'){
	        			$_body.append(PENGUINARTS.template1(data.product_name, data.cart_url));
	        			setTimeout(function(){
							jQuery('.alert_add_cart').remove(); 
						}, 3000);
	        		}else if(data.template == 'template2'){
	        			$_body.append(PENGUINARTS.template2(data.product_name, data.product_image, data.product_price, data.sale_price, data.currency, data.cart_url));
	        			jQuery.magnificPopup.open({
	        				items:{
	        					type:'inline',
	        					src: '.alert_add_cart_template2'
	        				},
	        				callbacks:{
	        					close:function(){
	        						jQuery('.custom_popup_template').remove(); 
	        					}
	        				}
	        			});
	        		}else if(data.template == 'template3'){
	        			$_body.append(PENGUINARTS.template3(data.product_name, data.product_image, data.product_price, data.sale_price, data.currency, data.more_products, data.cart_url));
	        			jQuery.magnificPopup.open({
	        				items:{
	        					type:'inline',
	        					src: '.alert_add_cart_template2'
	        				},
	        				callbacks:{
	        					close:function(){
	        						jQuery('.custom_popup_template').remove(); 
	        					}
	        				}
	        			});
	        		}else{
	        			$_body.append(PENGUINARTS.template3(data.product_name, data.product_image, data.product_price, data.sale_price, data.currency, data.more_products, data.cart_url));
	        			jQuery.magnificPopup.open({
	        				items:{
	        					type:'inline',
	        					src: '.alert_add_cart_template2'
	        				},
	        				callbacks:{
	        					close:function(){
	        						jQuery('.custom_popup_template').remove(); 
	        					}
	        				}
	        			});
	        		}
	        	}else{
	        		alert('error');
	        	}
	        }
		});
	}
	$_body.on('click', '.custom_popup_template .close_popup', function(e){
		e.preventDefault();
		jQuery.magnificPopup.close();
		jQuery('.custom_popup_template').remove();
	});
	$_body.on( 'added_to_cart', function( e, fragments, cart_hash, this_button ) {
		var product_data = this_button.data(),
			product_id = product_data.product_id;
		PENGUINARTS.check_product(product_id);

	});
})(jQuery);