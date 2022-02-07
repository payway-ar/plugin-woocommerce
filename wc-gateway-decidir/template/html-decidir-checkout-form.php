<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

$gateway_identifier = $this->id;
$gateway_field_id = $gateway_identifier . '_';
?>
<fieldset id="<?php echo $gateway_identifier; ?>-cc-form" class="wc-gateway-decidir">
	<div class="fields-wrapper">
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_bank"><?php echo "Bank"; ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_bank" class="input-text wc-credit-card-form-cc-bank" name="<?php echo $gateway_field_id; ?>cc_bank">
				<!-- filled out based on current available promotions -->
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_type"><?php echo "Card"; ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_type" class="input-text wc-credit-card-form-cc-type" name="<?php echo $gateway_field_id; ?>cc_type">
				<!-- filled out based on current available promotions -->
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_installments"><?php echo __('Installments', 'wc-gateway-decidir'); ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_installments" class="input-text wc-credit-card-form-cc-installments" name="<?php echo $gateway_field_id; ?>cc_installments">
				<!-- filled out based on current available promotions -->
			</select>
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_number"><?php echo "Credit Card Number"; ?> <span class="required">*</span></label>
			<input value="" type="text" id="<?php echo $gateway_field_id; ?>cc_number" name="<?php echo $gateway_field_id; ?>cc_number" data-decidir="<?php echo $gateway_field_id; ?>cc_number" placeholder="<?php echo "Credit Card Number"; ?>"/>
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_month"><?php echo "Card Expiration Month"; ?> <span class="required">*</span></label>
			<input value="" type="text" id="<?php echo $gateway_field_id; ?>cc_exp_month" name="<?php echo $gateway_field_id; ?>cc_exp_month" placeholder="MM" value="" />
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_year"><?php echo "Card Expiration Year"; ?> <span class="required">*</span></label>
			<input value="" type="text" id="<?php echo $gateway_field_id; ?>cc_exp_year" name="<?php echo $gateway_field_id; ?>cc_exp_year" placeholder="AA" value="" />
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_cid">CVV: <span class="required">*</span></label>
			<input value="" type="text" id="<?php echo $gateway_field_id; ?>cc_cid" name="<?php echo $gateway_field_id; ?>cc_cid" placeholder="CVV" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_holder_name"><?php echo "Holder Name"; ?> <span class="required">*</span></label>
			<input value="" type="text" id="<?php echo $gateway_field_id; ?>cc_holder_name" name="<?php echo $gateway_field_id; ?>cc_holder_name" placeholder="Holder Name" value=""/>
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_type"><?php echo "Document Type"; ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_doc_type" name="<?php echo $gateway_field_id; ?>cc_doc_type">
				<option selected value="dni">DNI</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_number"><?php echo "Document Number"; ?> <span class="required">*</span></label>
			<input value="" id="<?php echo $gateway_field_id; ?>cc_doc_number" type="text" name="<?php echo $gateway_field_id; ?>cc_doc_number" placeholder="" value=""/>
		</div>

		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_token" name="<?php echo $gateway_field_id; ?>cc_token" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_bin" name="<?php echo $gateway_field_id; ?>cc_bin" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_last_digits" name="<?php echo $gateway_field_id; ?>cc_last_digits" />
	</div>
	<div class="clear"></div>
</fieldset>
<style type="text/css">
.form-row.woocommerce-invalid input.input-text,
.form-row.woocommerce-invalid select {
    border-color: red;
}
.form-row.woocommerce-validated input.input-text,
.form-row.woocommerce-validated select {
    border-color: green;
}
</style>
<script type="text/javascript">
/* global decidirCheckoutForm */
var decidirCheckoutForm;

/* global wc_gateway_decidir_params */
/* global wc_gateway_decidir_accounting_format */
jQuery( function( $ ) {
  function wc_decidir_initialization_error_message () {
	  var message = 'WC Gateway Decidir: either required params or SDK could not be loaded';
	  console.error( message );

	  // hide form fields and display the exception
	  $( '#decidir_gateway-cc-form .fields-wrapper' ).hide();
	  $( '#decidir_gateway-cc-form #errorcard' ).html( message );
  }

  if ( typeof wc_gateway_decidir_params === 'undefined'
    || typeof Decidir === 'undefined' ) {

	wc_decidir_initialization_error_message();
	return false;
  }

  var DecidirCheckoutForm = function ( $target ) {
	this.onTokenResponse 				= this.onTokenResponse.bind( this );
	this.onUpdatedCheckout 				= this.onUpdatedCheckout.bind( this );
	this.updateCardExpiration 			= this.updateCardExpiration.bind( this );
	this.loadPromotions 				= this.loadPromotions.bind( this );
	this.onBankChange 					= this.onBankChange.bind( this );
	this.onCardChange 					= this.onCardChange.bind( this );
	this.onInstallmentChange 			= this.onInstallmentChange.bind( this );
	this.onPlaceOrder 					= this.onPlaceOrder.bind( this );
	this.loadToken 						= this.loadToken.bind( this );
	this.getFormValue 					= this.getFormValue.bind( this );
	this.beforePlaceOrder 				= this.beforePlaceOrder.bind( this );

    this.$form = $( $target || '#decidir_gateway-cc-form' );

    this.config = {
      endpoint_url: wc_gateway_decidir_params.endpoint_url,
      sandboxEnabled: Boolean( wc_gateway_decidir_params.sandbox_enabled ),
      credentials: {
        public_key: wc_gateway_decidir_params.creds.public_key,
      },
      // when enabled, SDK needs to be initialized with `false`
      // if CS needs to be active
      disableCybersource: !Boolean(wc_gateway_decidir_params.cybersource_enabled),
	  promotions: wc_gateway_decidir_params.promotions,
      form: {
        id: '#decidir_gateway-cc-form',
        fields: {
          cardNumber: '#decidir_gateway_cc_number',
          cardHolderName: '#decidir_gateway_cc_holder_name',
          cardHolderDocumentType: '#decidir_gateway_cc_doc_type',
          cardHolderDocumentNumber: '#decidir_gateway_cc_doc_number',
		  cardExpirationMonth: '#decidir_gateway_cc_exp_month',
          cardExpirationYear: '#decidir_gateway_cc_exp_year',
		  bankDropdown: '#decidir_gateway_cc_bank',
		  cardDropdown: '#decidir_gateway_cc_type',
		  installmentsDropdown: '#decidir_gateway_cc_installments',
		  placeOrderButton: '#place_order',
	  	},
		elements: {
			$banksDropdown: {},
			$cardsDropdown: {},
			$installmentsDropdown: {},
		}
	  },
	currencySettings: typeof wc_gateway_decidir_accounting_format !== 'undefined'
		? wc_gateway_decidir_accounting_format
		: {}
    };

	this.config.form.elements.$banksDropdown = $( this.config.form.fields.bankDropdown );
	this.config.form.elements.$cardsDropdown = $( this.config.form.fields.cardDropdown );
	this.config.form.elements.$installmentsDropdown = $( this.config.form.fields.installmentsDropdown );

	this.formData = {
		banks: {},
		cards: {},
		installments: {},
	};

	// Holder for Decidir SDK
	this.api = false;
	this.isTokenReady = false;

	$( document.body )
      .on( 'updated_checkout', { instance: this }, this.onUpdatedCheckout )
    ;

	$( this.config.form.fields.bankDropdown, this.$form ).on( 'change', { context: this }, this.onBankChange );
	$( this.config.form.fields.cardDropdown, this.$form ).on( 'change', { context: this }, this.onCardChange );
	$( this.config.form.fields.installmentsDropdown, this.$form ).on( 'change', { context: this }, this.onInstallmentChange );
	$( this.config.form.fields.placeOrderButton ).on( 'click', { context: this }, this.onPlaceOrder );
  };

  /**
   * Custom logger func, to enable/disable based on the plugin configuration
   * It will be logging in sandbox mode only
   */
  DecidirCheckoutForm.prototype.log = function() {
	  if ( this.config.sandboxEnabled && typeof console === 'object' ) {
		  console.log( '** DecidirCheckoutForm.log' );
		  console.log( ...arguments );
	  }
  }
  /**
   * Returns the form field's value using the config object
   * Pass the field key within the config object, to return it's identifier
   *
   * @see DecidirCheckoutForm.config.form.fields
   * @param {string} fieldConfigKey key from the config object
   * @return {string} the field value
   */
  DecidirCheckoutForm.prototype.getFormValue = function ( fieldConfigKey ) {
	this.log('getFormValue', this.config.form.fields[fieldConfigKey]);
	this.log('getFormValue', $( this.config.form.fields[fieldConfigKey], this.$form ).val());

	return $( this.config.form.fields[fieldConfigKey], this.$form ).val();
  };


  DecidirCheckoutForm.prototype.getCardHolderName = function () {
	return $( this.config.form.elements.cardHolderName, this.$form ).val();
  };

  DecidirCheckoutForm.prototype.generateDummyForm = function ( formValues ) {
	  var htmlInputList = '',
		  fieldList = [
			  {'name': 'card_number', 'value': formValues.cc_number},
			  {'name': 'card_expiration_month', 'value': formValues.cc_exp_month},
			  {'name': 'card_expiration_year', 'value': formValues.cc_exp_year},
			  {'name': 'card_holder_name', 'value': formValues.card_holder_name},
			  {'name': 'card_holder_doc_type', 'value': formValues.card_holder_doc_type},
			  {'name': 'card_holder_doc_number', 'value': formValues.card_holder_doc_number}
		  ];

	  htmlInputList = document.createElement('div');

	  for (var i = 0; i < fieldList.length; i++) {
		  var field = document.createElement('input')
		  field.setAttribute('type', 'text');
		  field.setAttribute('name', fieldList[i].name);
		  field.setAttribute('data-decidir', fieldList[i].name);
		  field.setAttribute('value', fieldList[i].value);

		  htmlInputList.appendChild(field);
	  }

	  return htmlInputList;
  };

  DecidirCheckoutForm.prototype.onPlaceOrder = function ( event ) {
	  	this.log('* onPlaceOrder');

		// ensure our gateway is the one selected
		if( ! $('#payment_method_decidir_gateway').is(':checked') ) {
			return true;
		}

		event.preventDefault();
		this.isTokenReady = false;

		var holderName = this.getFormValue( 'cardHolderName' ),
			ccNumber = this.getFormValue( 'cardNumber' ),
			expMonth = this.getFormValue( 'cardExpirationMonth' ),
			expYear = this.getFormValue( 'cardExpirationYear' ),
			holderDocType = this.getFormValue( 'cardHolderDocumentType' ),
			holderDocNumber = this.getFormValue( 'cardHolderDocumentNumber' ),
			formData = this.generateDummyForm({
				card_holder_name: holderName,
				cc_number: ccNumber,
				cc_exp_month: expMonth,
				cc_exp_year: expYear,
				card_holder_doc_type: holderDocType,
				card_holder_doc_number: holderDocNumber
			});

		this.log('before-create-token', formData);

		event.data.context.beforePlaceOrder( formData )
			.then(
				function ( result ) {
					var data = {
						bin: result.bin ?? '',
						token: result.id ?? '',
						last_four_digits: result.last_four_digits ?? ''
					};

					this.log('token success: ', result, data);

					$( '#decidir_gateway_cc_bin', this.$form ).val(data.bin);
					$( '#decidir_gateway_cc_token', this.$form ).val(data.token);
					$( '#decidir_gateway_cc_last_digits', this.$form ).val(data.last_four_digits);

					this.isTokenReady = true;

					// submit the form
					return typeof wc_checkout_form != 'undefined'
						? wc_checkout_form.$order_review.trigger('submit')
						: $( '#payment_method_decidir_gateway' ).closest( 'form' ).submit();

				}.bind(event.data.context),
				function (error) {
					console.error(error);
					alert('Payment Token could not be generated at this time.');
				}.bind(event.data.context)
			);
  };

  DecidirCheckoutForm.prototype.beforePlaceOrder = function ( data ) {
  	this.log('* beforePlaceOrder', this, data);
	return this.loadToken( data )
		.then(
			function ( result ) {
				this.log('* beforePlaceOrder resolve:', result);
				return Promise.resolve( result );
			}.bind(this),
			function ( error ) {
				this.log('* beforePlaceOrder reject:', error);
				return Promise.reject( error );
			}.bind(this)
		);
  };

  DecidirCheckoutForm.prototype.loadToken = function ( data ) {
  	this.log('* loadToken', this, data);

	this.api = new Decidir(
		this.config.endpoint_url,
		this.config.disableCybersource
	);

	this.api.setPublishableKey(this.config.credentials.public_key);
	this.api.setTimeout(0);

	return new Promise(function ( resolve, reject ) {
		this.api.createToken( data, function ( status, data ) {
			this.log('* createToken success:', status, data);
			if ( status === 200 || status === 201 ) {
				resolve( data );
			}

			reject( new Error('Token cannot be generated at this moment') );
		}.bind(this));
	  }.bind(this));
  };

  // Token creation callback response
  DecidirCheckoutForm.prototype.onTokenResponse = function (status, response) {
	  this.log('onTokenResponse', status, response);

	  if (status === 200 || status === 201) {
  		  var title = JSON.stringify(response),
  			  expires = "expires=60";

		// fill the hidden so it reaches back
		  $('#dcdtoken').val(response);
  	  } else {
		  this.log('onTokenResponse', 'ERROR');
	  }
  };

  DecidirCheckoutForm.prototype.onUpdatedCheckout = function ( event ) {
	this.log('onUpdatedCheckout', this.config);

	// TODO: implement an object create validation for the SDK
	// currently there's no way for us to check if the object was loaded OK
	// based on the attributes that the object currently holds
	// if (!this.api.hasOwnProperty('orgId')) {
	// 	return this.onSDKLoadFailure();
	// }

	// looking good lets load all available promotions
	this.loadPromotions(this);
  };

  /**
   * Displays an error message and hides the entire form
   *
   * @return void
   */
  DecidirCheckoutForm.prototype.onSDKLoadFailure = function () {
	/** global wc_decidir_initialization_error_message */
    wc_decidir_initialization_error_message();
  };

  DecidirCheckoutForm.prototype.updateCardExpiration = function (event) {
    var fields = event.data.instance.config.form.fields || {},
      expirationValue = $(fields.cardExpiration).val() || '',
      month = expirationValue.substring(0, 2),
      year = expirationValue.substring(expirationValue.length - 2);

    $(fields.cardExpirationMonth).val(month);
    $(fields.cardExpirationYear).val(year);
  };

  DecidirCheckoutForm.prototype.loadPromotions = function ( context ) {
	var promos = this.config.promotions,
		$bankField = $( context.config.form.fields.bankDropdown );

	this.log('loadPromotions', promos);
	this.log($bankField);

	if ( !context.config.promotions.banks ) {
		console.error('DECIDIR - no banks found');
		return;
	}

	// default option
	$bankField.append(
		$('<option>', { value: '', text: 'Por favor seleccione...'})
	);

	$.each( context.config.promotions.banks, function ( i, bank ) {
		$bankField.append($('<option>', {
			value: bank.value,
			text: bank.name
		}));
	});
  };

	DecidirCheckoutForm.prototype.onBankChange = function (e) {
		var context = e.data.context,
			bankId = context.config.form.elements.$banksDropdown.val(),
			$cardField = context.config.form.elements.$cardsDropdown,
			$installmentsDropdown = context.config.form.elements.$installmentsDropdown
		;

		// Removes validations
		$installmentsDropdown.closest('.form-row')
			.removeClass( 'woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field' );
		$cardField.closest('.form-row')
			.removeClass( 'woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field' );

		$installmentsDropdown.empty()
			.append($('<option>', {
				value: '',
				text: 'Por favor seleccione...'
			}));

		$cardField.empty()
			.append($('<option>', {
				value: '',
				text: 'Por favor seleccione...'
			}));

		$.each( context.config.promotions.cards[bankId], function ( i, card ) {
			this.log(card);

			$cardField.append($('<option>', {
				value: card.value,
				text: card.name
			}));
		}.bind( this ));
	};

	DecidirCheckoutForm.prototype.onCardChange = function (e) {
		var context = e.data.context,
			bankId = context.config.form.elements.$banksDropdown.val(),
			cardId = context.config.form.elements.$cardsDropdown.val(),
			grandTotal = context.cart_total,
			$installmentsField = context.config.form.elements.$installmentsDropdown;

		this.log('onCardChange');

		// Removes validations
		$installmentsField.closest('.form-row')
			.removeClass( 'woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field' );

		$installmentsField.empty()
			.append($('<option>', {
				value: '',
				text: 'Por favor seleccione...'
			}));

		this.log(bankId, cardId);
		this.log(context.config.promotions.plans[bankId][cardId]);

		$.each( context.config.promotions.plans[bankId][cardId], function (i, plan) {
			this.log('parsing plan: ', i, plan);

			var coefficient = parseFloat(plan.coefficient),
				charge = 0,
				optionText = "",
				feePeriod = plan.fee_period,
				installmentPrice = (grandTotal) / parseInt(feePeriod),
				total = parseFloat(grandTotal);

			if (coefficient > 1 ) {
				charge = parseFloat(parseFloat((total * coefficient) - total));
				installmentPrice = (total + charge) / parseInt(feePeriod);
			}

			this.log(total, charge, installmentPrice, feePeriod);

			optionText = feePeriod +
				' x ' +
				accounting.formatMoney(installmentPrice, context.config.currencySettings) +
				' (' + accounting.formatMoney((total + charge), context.config.currencySettings) + ')'
			;

			$installmentsField.append($('<option>', {
				value: plan.rule_id + '-' + cardId + '-' + plan.fee_to_send,
				text: optionText
			}));

		}.bind(this));
	};

	DecidirCheckoutForm.prototype.onInstallmentChange = function (e) {
		this.log('onInstallmentChange');

		var context = e.data.context,
			bankId = context.config.form.elements.$banksDropdown.val(),
			cardId = context.config.form.elements.$cardsDropdown.val(),
			grandTotal = parseFloat(context.cart_total),
			$installmentsField = context.config.form.elements.$installmentsDropdown
			value = $installmentsField.val();

		this.log('installments value ', value);
	}

	/**
	 * Initialize
	 */
	decidirCheckoutForm = new DecidirCheckoutForm( $( '#decidir_gateway-cc-form' ) );

	if ( ! decidirCheckoutForm ) {
		$( '#decidir_gateway-cc-form' ).hide();
		alert('Decidir Gateway could not be loaded');
		return;
	}

	decidirCheckoutForm.cart_total = <?php echo $this->get_order_total(); ?>
});
</script>
<script type="text/javascript">
jQuery( function( $ ) {
	if ( typeof wc_gateway_decidir_params === 'undefined' ) {
		return false;
	}
});
</script>
