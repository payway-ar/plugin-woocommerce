/* global wc_gateway_payway_params */
jQuery( function( $ ) {

  if ( typeof wc_gateway_payway_params === 'undefined'
     || typeof Decidir === 'undefined' ) {
     console.error( 'WC Gateway Decidir: either required params or SDK could not be loaded' );
     return false;
   }

   var PaywayCheckoutForm = function ( $target ) {
     this.$form = $( $target );

     this.config = {
       endpointUrl: wc_gateway_payway_params.endpoint_url,
       testmode: wc_gateway_payway_params.test_mode || true,
       credentials: {
         publicKey: wc_gateway_payway_params.creds.public_key,
         privateKey: wc_gateway_payway_params.creds.private_key,
         siteId: wc_gateway_payway_params.creds.site_id,
       },
       // when enabled, SDK needs to be initialized with `false`
       // if CS needs to be active
       enableCyberSource: !wc_gateway_payway_params.cybersource,
       form: {
         id: wc_gateway_payway_params.form.id,
         fields: {
           cardHolderDocumentNumber: '#card_holder_doc_number',
           cardHolderName: '#card_holder_name',
           cardExpiration: '#card_expiration',
           cardExpirationYear: '#card_expiration_year',
           cardExpirationMonth: '#card_expiration_month',
           customerDocumentNumber: '#dni_titular',
         }
       },
       billing: {
         fields: {
           firstName: '#billing_first_name',
           lastName: '#billing_last_name'
         }
       }
     };

     this.api = {};

     // this.api = new Decidir(
     //   this.config.endpointUrl,
     //   !this.config.isCyberSourceEnabled
     // );
     //
     // this.api.setPublishableKey(this.config.credentials.publicApiKey);
     // this.api.setTimeout(0);

     // $( document.body )
     //   .on( 'focusout', this.config.form.fields.customerDocumentNumber, { instance: this }, this.onCustomerDocumentNumberUpdate )
     //   .on( 'focusout', this.config.form.fields.cardExpiration, { instance: this }, this.updateCardExpiration )
       // .on( 'updated_checkout', { instance: this }, this.onUpdatedCheckout )
     // ;
   };

   // PaywayCheckoutForm.prototype.init = function () {
       // jQuery('#place_order').on('click', function(e) {
       // 	if(jQuery('#payment_method_payway_gateway').is(':checked')) {
       // 		e.preventDefault();
       // 		var element = document.querySelectorAll('#payway_gateway-cc-form');
       // 		for (var i=0; element.length > i; i++) {
       // 			var form = element[i];
       // 		}
       // 		decidir.createToken(form, sdkResponseHandler);
       // 	}
       // });
   // };

   /**
    * Updates Card Holder document number when field lost focus
    *
    * @param MouseEvent event
    * @return void
    */
   PaywayCheckoutForm.prototype.onCustomerDocumentNumberUpdate = function (event) {
  console.log('onCustomerDocumentNumberUpdate', event, event.data.instance);
     var fields = event.data.instance.config.form.fields || {};

     if (!fields.length) {
  console.error('onCustomerDocumentNumberUpdate - Form configuration cannot be loaded');
       return;
     }

     $(fields.cardHolderDocumentNumber).val(
       $(fields.customerDocumentNumber).val()
     );
   };

   PaywayCheckoutForm.prototype.onUpdatedCheckout = function ( event ) {
  console.log('onUpdatedCheckout', event);
     var config = event.data.instance.config || {};

     $(config.form.fields.cardHolderName).val(
       $(config.billing.fields.firstName).val() + ' ' + $(config.billing.fields.lastName).val()
     );
   }

   PaywayCheckoutForm.prototype.updateCardExpiration = function (event) {
     var fields = event.data.instance.config.form.fields || {},
       expirationValue = $(fields.cardExpiration).val() || '',
       month = expirationValue.substring(0, 2),
       year = expirationValue.substring(expirationValue.length - 2);

     $(fields.cardExpirationMonth).val(month);
     $(fields.cardExpirationYear).val(year);
   }

  // $('#nombre_titular').focusout(function () {
	// 	$('#card_holder_name').val($('#nombre_titular').val());
	// });
  //
	// $('#payway_cvc').focusout(function () {
	// 	$('#security_code').val($('#payway_cvc').val());
	// });

  // $('#payway_numero').focusout(function () {
	// 	$('#card_number').val($('#payway_numero').val());
  //
	// 	var num = $('#payway_numero').val();
	// 	num = num.replace(/[^\d]/g,'');
	// 	   // now test the number against some regexes to figure out the card type.
	// 	if (num.match(/^5[1-5]\d{14}$/)) {
	// 		$('#payway-card-tipo').val(15);
	// 	} else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
	// 		$('#payway-card-tipo').val(1);
	// 	} else if (num.match(/^3[47]\d{13}/)) {
	// 		$('#payway-card-tipo').val(65);
	// 	} else if (num.match(/^6011\d{12}/)) {
	// 		return 'Discover';
	// 	}
	// });

  // var paywayCheckoutForm = new PaywayCheckoutForm('.wc-gateway-payway form');

  /**
	 * Initialize
	 */
   $ ( document.body )
     .on( 'updated_checkout wc-gateway-payway-form-init', function () {
console.log('updated_checkout wc-gateway-payway-form-init - has been fired!');
       // var decidirForm = $( 'ul.woocommerce-wc-gateway-payway' );
       new PaywayCheckoutForm( $( 'ul.woocomerce-wc-gateway-payway') );
     })
     .trigger( 'wc-gateway-payway-form-init' );

	// $( document.body ).on( 'updated_checkout wc-gateway-payway-form-init', function() {
	// 	// Loop over gateways with saved payment methods
	// 	var $saved_payment_methods = $( 'ul.woocommerce-SavedPaymentMethods' );
  //
	// 	$saved_payment_methods.each( function() {
	// 		$( this ).wc_tokenization_form();
	// 	} );
	// } );
});


// jQuery(document).on('updated_checkout', function() {
	// jQuery('#payway_tarjeta_tipo' ).on('change', function (e) {
	// 	jQuery('#payway_installments').html('');
	// 	var optionSelected = jQuery('option:selected', this);
	// 	var tarjeta = this.value;
	// 	var cuotas = optionSelected[0].attributes.cuotas.value;
	// 	var result = cuotas.split('.');
  //
	// 	jQuery.each( result, function( key, value ) {
	// 		if (value == 3) {
	// 		  jQuery("#payway_installments").append("<option value='13'>3 Cuotas </option>");
	// 		} else if(value == 6) {
	// 		  jQuery("#payway_installments").append("<option value='16'>6 Cuotas </option>");
	// 		} else if(value == 12) {
	// 			jQuery("#payway_installments").append("<option value='7'>12 Cuotas</option>");
	// 		} else if(value == 18) {
	// 			jQuery("#payway_installments").append("<option value='8'>18 Cuotas </option>");
	// 		} else {
	// 		  jQuery("#payway_installments").append("<option value='"+value+"'>"+value+" Cuota</option>");
	// 		}
	// 	});
	// });

	// jQuery('#card_holder_name').val(jQuery('#billing_first_name').val() + ' ' + jQuery('#billing_last_name').val());

	// jQuery('#card_expiration').focusout(function () {
	// 	var card_expiration = jQuery('#card_expiration').val();
	// 	var month = card_expiration.substring(0, 2);
	// 	var year = card_expiration.substring(card_expiration.length - 2);
	// 	jQuery('#card_expiration_month').val(month);
	// 	jQuery('#card_expiration_year').val(year);
	// });

	// jQuery('#nombre_titular').focusout(function () {
	// 	jQuery('#card_holder_name').val(jQuery('#nombre_titular').val());
	// });
  //
	// jQuery('#payway_cvc').focusout(function () {
	// 	jQuery('#security_code').val(jQuery('#payway_cvc').val());
	// });

	// jQuery('#payway_numero').focusout(function () {
	// 	jQuery('#card_number').val(jQuery('#payway_numero').val());
  //
	// 	var num = jQuery('#payway_numero').val();
	// 	num = num.replace(/[^\d]/g,'');
	// 	   // now test the number against some regexes to figure out the card type.
	// 	if (num.match(/^5[1-5]\d{14}$/)) {
	// 		jQuery('#payway-card-tipo').val(15);
	// 	} else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
	// 		jQuery('#payway-card-tipo').val(1);
	// 	} else if (num.match(/^3[47]\d{13}/)) {
	// 		jQuery('#payway-card-tipo').val(65);
	// 	} else if (num.match(/^6011\d{12}/)) {
	// 		return 'Discover';
	// 	}
	// });

	// const publicApiKey = "<?php echo $_SESSION['publishable_key']; ?>";
	// const urlSandbox = "<?php echo $_SESSION['urlSandbox']; ?>";
	// const testmode = "<?php echo $_SESSION['testmode']; ?>";
	// const useCS = "<?php echo $_SESSION['cybersource']; ?>";

	if (useCS == "yes") {
		let decidir = new Decidir(urlSandbox,false);
		decidir.setPublishableKey(publicApiKey);
		decidir.setTimeout(0);
		jQuery('#place_order').on('click', function(e) {
			if(jQuery('#payment_method_payway_gateway').is(':checked')) {
				e.preventDefault();
				var element = document.querySelectorAll('#payway_gateway-cc-form');
				for (var i=0; element.length > i; i++) {
					var form = element[i];
				}
				decidir.createToken(form, sdkResponseHandler);
			}
		});
	} else {
		let decidir = new Decidir(urlSandbox, true);
		decidir.setPublishableKey(publicApiKey);
		decidir.setTimeout(0);

		jQuery('#place_order').on('click', function(e) {
			if(jQuery('#payment_method_payway_gateway').is(':checked')) {
				e.preventDefault();
				var element = document.querySelectorAll('#payway_gateway-cc-form');
				for (var i=0; element.length > i; i++) {
				  var form = element[i];
				}
				decidir.createToken(form, sdkResponseHandler);
			}
		});

	}

	function sdkResponseHandler(status, response) {
		if (status != 200 && status != 201) {
			document.getElementById("nombre_titular").focus();
			jQuery("#errorcard").append("<strong>Verificar Datos Tarjeta</strong><br>");
		} else {
			var title = JSON.stringify(response);
			var expires = "expires=60";
			document.cookie = "result_payway=" + title + "; " + expires + ";path=/";
			jQuery('#place_order').submit();
		}
	}
// });

// jQuery(document).on("focusout","#dni_titular",function(){
//          jQuery('#card_holder_doc_number').val(jQuery('#dni_titular').val());
//        });

// jQuery(document).on('updated_checkout', function() {
//             jQuery('#payway_tarjeta_tipo' ).on('change', function (e) {
//             jQuery('#payway_installments').html('');
//             var optionSelected = jQuery('option:selected', this);
//             var tarjeta = this.value;
//             var cuotas = optionSelected[0].attributes.cuotas.value;
//             var result = cuotas.split('.');
//
//
//             jQuery.each( result, function( key, value ) {
//
//               if(value == 3){
//
//                 jQuery("#payway_installments").append("<option value='13'>3 Cuotas </option>");
//
//               } else if(value == 6){
//                   jQuery("#payway_installments").append("<option value='16'>6 Cuotas </option>");
//               } else if(value == 12){
//                   jQuery("#payway_installments").append("<option value='7'>12 Cuotas</option>");
//
//
//               } else if(value == 18){
//                   jQuery("#payway_installments").append("<option value='8'>18 Cuotas </option>");
//
//
//               } else {
//                 jQuery("#payway_installments").append("<option value='"+value+"'>"+value+" Cuota</option>");
//               }
//
//
//
//             });
//
//           });
//
//           jQuery('#card_holder_name').val(jQuery('#billing_first_name').val() + ' ' + jQuery('#billing_last_name').val());
//
//           jQuery('#card_expiration').focusout(function () {
//             var card_expiration = jQuery('#card_expiration').val();
//             var month = card_expiration.substring(0, 2);
//             var year = card_expiration.substring(card_expiration.length - 2);
//             jQuery('#card_expiration_month').val(month);
//             jQuery('#card_expiration_year').val(year);
//
//           });
//
//           jQuery('#nombre_titular').focusout(function () {
//             jQuery('#card_holder_name').val(jQuery('#nombre_titular').val());
//           });
//
//           jQuery('#payway_cvc').focusout(function () {
//             jQuery('#security_code').val(jQuery('#payway_cvc').val());
//           });
//
//           jQuery('#payway_numero').focusout(function () {
//             jQuery('#card_number').val(jQuery('#payway_numero').val());
//
//              var num = jQuery('#payway_numero').val();
//              num = num.replace(/[^\d]/g,'');
//              // now test the number against some regexes to figure out the card type.
//              if (num.match(/^5[1-5]\d{14}$/)) {
//                jQuery('#payway-card-tipo').val(15);
//              } else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
//               jQuery('#payway-card-tipo').val(1);
//              } else if (num.match(/^3[47]\d{13}/)) {
//                jQuery('#payway-card-tipo').val(65);
//              } else if (num.match(/^6011\d{12}/)) {
//                return 'Discover';
//              }
//
//           });
//
//
//
//
//
//           const publicApiKey = "<?php echo $_SESSION['publishable_key']; ?>";
//           const urlSandbox = "<?php echo $_SESSION['urlSandbox']; ?>";
//
//           const decidir = new Decidir(urlSandbox,true);
//           decidir.setPublishableKey(publicApiKey);
//           decidir.setTimeout(5000);
//
//           jQuery('#place_order').on('click', function(e) {
//
//             if(jQuery('#payment_method_payway_gateway').is(':checked')) {
//               e.preventDefault();
//               var element = document.querySelectorAll('#payway_gateway-cc-form');
//               for (var i=0; element.length > i; i++) {
//                 console.log(element[i]);
//                 var form = element[i];
//               }
//               decidir.createToken(form, sdkResponseHandler);
//             }
//           });
//
//           function sdkResponseHandler(status, response) {
//             if (status != 200 && status != 201) {
//              document.getElementById("nombre_titular").focus();
//              jQuery("#errorcard").append("<strong>Verificar Datos Tarjeta</strong><br>");
//
//             } else {
//               var title = JSON.stringify(response);
//               var expires = "expires=60";
//               document.cookie = "result_payway=" + title + "; " + expires + ";path=/";
//               jQuery('#place_order').submit();
//             }
//           }
//         });
