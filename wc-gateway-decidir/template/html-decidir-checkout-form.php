<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

$gateway_identifier = $this->id;
$gateway_field_id = $gateway_identifier . '_';
?>
<fieldset id="<?php echo $gateway_identifier; ?>-cc-form" class="wc-gateway-decidir">
	<div id="error_container"></div>
	<div class="fields-wrapper">
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_bank"><?php echo "Bank"; ?> <span class="required">*</span></label>
			<!-- filled out based on current available promotions -->
			<select id="<?php echo $gateway_field_id; ?>cc_bank"
				class="input-text wc-credit-card-form-cc-bank"
				name="<?php echo $gateway_field_id; ?>cc_bank">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_type"><?php echo "Card"; ?> <span class="required">*</span></label>
			<!-- filled out based on current available promotions -->
			<select id="<?php echo $gateway_field_id; ?>cc_type"
				class="input-text wc-credit-card-form-cc-type"
				name="<?php echo $gateway_field_id; ?>cc_type">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_installments"><?php echo __('Installments', 'wc-gateway-decidir'); ?> <span class="required">*</span></label>
			<!-- filled out based on current available promotions -->
			<select id="<?php echo $gateway_field_id; ?>cc_installments"
				class="input-text wc-credit-card-form-cc-installments"
				name="<?php echo $gateway_field_id; ?>cc_installments">
				<option value="">Por favor seleccione...</option>
			</select>
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_number"><?php echo "Credit Card Number"; ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_number"
				name="<?php echo $gateway_field_id; ?>cc_number"
				data-decidir="<?php echo $gateway_field_id; ?>cc_number"
				placeholder="<?php echo "Credit Card Number"; ?>"
				class="input-text"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_month"><?php echo "Card Expiration Month"; ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_exp_month"
				name="<?php echo $gateway_field_id; ?>cc_exp_month"
				placeholder="MM"
				class="input-text"
				autocomplete="off" />
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_exp_year"><?php echo "Card Expiration Year"; ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_exp_year"
				name="<?php echo $gateway_field_id; ?>cc_exp_year"
				placeholder="AA"
				class="input-text"
				autocomplete="off" />
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_cid">CVV: <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_cid"
				name="<?php echo $gateway_field_id; ?>cc_cid"
				placeholder="CVV"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_holder_name"><?php echo "Holder Name"; ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_holder_name"
				name="<?php echo $gateway_field_id; ?>cc_holder_name"
				placeholder="Holder Name"
				class="input-text"
				autocomplete="off" />
		</div>

		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_type"><?php echo "Document Type"; ?> <span class="required">*</span></label>
			<select id="<?php echo $gateway_field_id; ?>cc_doc_type" name="<?php echo $gateway_field_id; ?>cc_doc_type">
				<option selected value="dni">DNI</option>
			</select>
		</div>
		<div class="form-row validate-required">
			<label for="<?php echo $gateway_field_id; ?>cc_doc_number"><?php echo "Document Number"; ?> <span class="required">*</span></label>
			<input type="text"
				id="<?php echo $gateway_field_id; ?>cc_doc_number"
				name="<?php echo $gateway_field_id; ?>cc_doc_number"
				placeholder=""
				class="input-text"
				autocomplete="off" />
		</div>

		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_token" name="<?php echo $gateway_field_id; ?>cc_token" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_bin" name="<?php echo $gateway_field_id; ?>cc_bin" />
		<input type="hidden" id="<?php echo $gateway_field_id; ?>cc_last_digits" name="<?php echo $gateway_field_id; ?>cc_last_digits" />
	</div>
	<div class="clear"></div>
</fieldset>
<script type="text/javascript">
jQuery(function ( $ ) {
	var wc_decidir_checkout_form = {
		id: 'decidir_gateway',

		// Initializes the current total for Installments calculation
		cart_total: wc_gateway_decidir_params.cart_total,

		$form: $( '#decidir_gateway-cc-form' ),
		$wrapper: $( '.fields-wrapper', this.form ),
		$error_container: $( '#error_container', this.form ),
		$banksDropdown: $( '#decidir_gateway_cc_bank', this.$form ),
		$cardsDropdown: $( '#decidir_gateway_cc_type', this.$form ),
		$installmentsDropdown: $( '#decidir_gateway_cc_installments', this.$form ),

		$cardExpirationMonth: $( '#decidir_gateway_cc_exp_month', this.$form ),
		$cardNumber: $( '#decidir_gateway_cc_number', this.$form),
		$cardHolderName: $( '#decidir_gateway_cc_holder_name', this.$form),
		$cardHolderDocumentType: $( '#decidir_gateway_cc_doc_type', this.$form),
		$cardHolderDocumentNumber: $( '#decidir_gateway_cc_doc_number', this.$form),
		$cardExpirationYear: $( '#decidir_gateway_cc_exp_year', this.$form),

		config: {
		  endpoint_url: wc_gateway_decidir_params.endpoint_url,
		  sandboxEnabled: Boolean( wc_gateway_decidir_params.sandbox_enabled ),
		  credentials: {
		    public_key: wc_gateway_decidir_params.creds.public_key
		  },
		  promotions: wc_gateway_decidir_params.promotions,
		  currencySettings: typeof wc_gateway_decidir_accounting_format !== 'undefined'
		    ? wc_gateway_decidir_accounting_format
		    : {},
			form: {
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
			}
		},
		// Holder for Decidir SDK
		api: false,
		isTokenReady: false,

		// Whether we've hydrate Payment form options or not
		loadReady: false,

		init_error_message: function () {
			var message = 'WC Gateway Decidir: either required params or SDK could not be loaded';
			console.error( message );

			// hide form fields and display the exception
			this.$wrapper.hide();
			this.$error_container.empty().html( message );
		},

		init: function () {
			console.log('init...');
			// if ( typeof wc_gateway_decidir_params === 'undefined'
			// 	 || typeof Decidir === 'undefined'
			// 	 || typeof DecidirCheckoutForm === 'undefined'
			//   ) {
			//    this.init_error_message();
			//    return
			// }

			// in case we've a previous error
			this.$error_container.empty().hide();

			this._attachListeners();
			this._loadPromotions();

			// TODO: hook up into the custom event instead of the place order button
			// (current issue place order triggering twice)
			// $( 'form.checkout' ).on( 'checkout_place_order_' + this.id, this.capturePlaceOrder.bind(this) );
		},

		log: function() {
			if ( this.config.sandboxEnabled && typeof console === 'object' ) {
				console.log( '** decidir', ...arguments );
			}
		},

		_attachListeners: function () {
			this.log('attaching listeners');
			$( this.$banksDropdown ).on('change', this.onBanksChange.bind(this) );
			$( this.$cardsDropdown ).on( 'change', this.onCardsChange.bind(this) );
			$( this.$installmentsDropdown ).on( 'change', this.onInstallmentsChange.bind(this) );

			$( this.$cardExpirationMonth ).on( 'blur change', this.validateCardExpirationMonth.bind(this) );
			$( this.$cardExpirationYear ).on( 'blur change', this.validateCardExpirationYear.bind(this) );
			$( this.$cardHolderDocumentNumber ).on( 'blur change', this.validateCardDocumentNumber.bind(this) );

			// TODO: replace with a listener into `checkout_place_order_`
			$( this.config.form.fields.placeOrderButton ).on( 'click', this.capturePlaceOrder.bind(this) );
		},

		_getFormValue: function ( fieldConfigKey ) {
			this.log('getFormValue', this.config.form.fields[fieldConfigKey]);
			this.log('getFormValue', $( this.config.form.fields[fieldConfigKey], this.$form ).val());

			return $( this.config.form.fields[fieldConfigKey], this.$form ).val();
		},

		_generateDummyForm: function ( formValues ) {
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
		},

		_buildDummyForm: function () {
			var holderName = this._getFormValue( 'cardHolderName' ),
				ccNumber = this._getFormValue( 'cardNumber' ),
				expMonth = this._getFormValue( 'cardExpirationMonth' ),
				expYear = this._getFormValue( 'cardExpirationYear' ),
				holderDocType = this._getFormValue( 'cardHolderDocumentType' ),
				holderDocNumber = this._getFormValue( 'cardHolderDocumentNumber' ),

			 formData = this._generateDummyForm({
				card_holder_name: holderName,
				cc_number: ccNumber,
				cc_exp_month: expMonth,
				cc_exp_year: expYear,
				card_holder_doc_type: holderDocType,
				card_holder_doc_number: holderDocNumber
			});

			return formData;
		},

		_loadToken: function ( data ) {
			this.log('loadToken', this, data);

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
		},

		_beforePlaceOrder: function ( data ) {
			this.log('* beforePlaceOrder', this, data);

			return this._loadToken( data )
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
	  	},

		_onSDKLoadFailure: function () {
			/** global wc_decidir_initialization_error_message */
			wc_decidir_initialization_error_message();
		},

		_validateGatewayForm: function() {
			// for now, check if a form field still shows an error or not
			return $('.form-row.woocommerce-invalid', this.$form).length < 1;
		},

		capturePlaceOrder: function (e) {
			this.log('capturePlaceOrder');

		    // ensure our gateway is the one selected
		    if( ! $('#payment_method_decidir_gateway').is(':checked') ) {
		      return true;
		    }

			if ( !this._validateGatewayForm()  ) {
				this.log('capturePlaceOrder: invalid, exiting...');
				return false;
			}

			this.log('* onPlaceOrder');

			this.isTokenReady = false;

			var formData = this._buildDummyForm();

			this.log('before-create-token', formData);
			if ( !formData ) {
				return false;
			}

		   this._beforePlaceOrder( formData )
		     .then(
		       function ( result ) {
		         var data = {
		           bin: result.bin ? result.bin : '',
		           token: result.id ? result.id : '',
		           last_four_digits: result.last_four_digits ? result.last_four_digits : ''
		         };

		         this.log('token success: ', result, data);

		         $( '#decidir_gateway_cc_bin', this.$form ).val(data.bin);
		         $( '#decidir_gateway_cc_token', this.$form ).val(data.token);
		         $( '#decidir_gateway_cc_last_digits', this.$form ).val(data.last_four_digits);

		         this.isTokenReady = true;

		         return typeof wc_checkout_form != 'undefined'
		           ? wc_checkout_form.$order_review.trigger('submit')
		           : $( '#payment_method_decidir_gateway' ).closest( 'form' ).submit();

		       }.bind(this),
		       function (error) {
		         console.error(error);
		         alert('Payment Token could not be generated at this time.');
			 	}.bind(this)
		     );

			return false;
		},

		_loadPromotions: function () {
			var promos = this.config.promotions,
			  $bankField = this.$banksDropdown;

			this.log('loadPromotions', promos);
			this.log($bankField);

			if ( !this.config.promotions.banks || !$bankField.length ) {
			  console.error('DECIDIR - no banks found');
			  return;
			}

			// default option
			$bankField.empty()
			  .append( $('<option>', { value: '', text: 'Por favor seleccione...'}));

			$.each( this.config.promotions.banks, function ( i, bank ) {
			  $bankField.append($('<option>', {
				value: bank.value,
				text: bank.name
			  }));
			});
		},

		validateCardExpirationMonth: function () {
		    var $field = this.$cardExpirationMonth || false,
				date = new Date(),
				currentYear = date.getFullYear().toString().substr(2),
				month = date.getMonth() + 1,
				currentMonth = month.length === 1
					? '0' + month
					: month;

			// field missing
			if ( !$field ) {
				return false;
			}

			var value = $field.val() || '';

			/**
			 * Validates if:
			 * - empty
			 * - length of the value is higher than 2
			 * - value can't be converted into a number
			 * - value is lower than 1
			 * - value is higher than 12
			 */
		    if (value === ''
				|| value.length > 2
				|| isNaN(value)
				|| value < 1
				|| value > 12
			) {
		      // $field.val('');
		      $field.closest('.form-row').addClass('woocommerce-invalid');
		      return false;
		    }

			if (value.length === 1) {
				value = '0' + value;
				$field.val(value);
			}

			// if there's a year already filled in, and it's the current year
			// then validate that month isn't lower than the current one
			if (this.$cardExpirationYear.val()
				&& this.$cardExpirationYear.val() === currentYear
				&& (value < currentMonth)
			) {
				$field.closest('.form-row').addClass('woocommerce-invalid');
				return false;
			}

		    $field.addClass('woocommerce-validated');
			return true;
		},

		validateCardExpirationYear: function () {
			var $field = this.$cardExpirationYear || false,
				date = new Date(),
				currentYear = date.getFullYear().toString().substr(2),
				month = date.getMonth() + 1,
				currentMonth = month.length === 1
					? '0' + month
					: month;

			if (!$field) {
				return false;
			}

			var value = $field.val() || '';

			/**
			 * Validates if:
			 * - empty
			 * - value can't be converted into a number
			 * - length of the value diff to 2
			 * - value is lower than the current Year
			 */
			if (value === ''
				|| isNaN(value)
				|| value.length !== 2
				|| value < currentYear
			) {
				$field.closest('.form-row').addClass('woocommerce-invalid');
				return false;
			}

			// We'll only allow same year if month it's equal to the current
			if (currentYear === value
				&& this.$cardExpirationMonth.val()
				&& this.$cardExpirationMonth.val() < currentMonth
			) {
				$field.closest('.form-row').addClass('woocommerce-invalid');
				return false;
			}

		    $field.val(value);
		    $field.addClass('woocommerce-validated');
			return true;
		},

		validateCardDocumentNumber: function () {
			var $field = this.$cardHolderDocumentNumber || false;

			if (!$field) {
				return false;
			}

			var value = $field.val() || '';

			/**
			 * Validates if:
			 * - document number has a value
			 * - value has, at least, 7 digits
			 * - value can be converted into a number
			 */
			if (value === '' || value.length <= 6 || isNaN(value)) {
				$field.closest('.form-row').addClass('woocommerce-invalid');
				return false;
			}

			$field.val(value);
		    $field.addClass('woocommerce-validated');
			return true;
		},

		onBanksChange: function () {
			this.log('onBankChange');
		    var bankId = this.$banksDropdown.val(),
		      $cardField = this.$cardsDropdown,
		      $installmentsDropdown = this.$installmentsDropdown;


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

		    $.each( this.config.promotions.cards[bankId], function ( i, card ) {
		      this.log(card);

		      $cardField.append($('<option>', {
		        value: card.value,
		        text: card.name
		      }));
		    }.bind( this ));
		},

		onCardsChange: function () {
			var bankId = this.$banksDropdown.val(),
			  cardId = this.$cardsDropdown.val(),
			  grandTotal = this.cart_total,
		      $installmentsField = this.$installmentsDropdown;

		    this.log('onCardChange');

		    if (!bankId) {
		      return;
		    }

		    // Removes validations
		    $installmentsField.closest('.form-row')
		      .removeClass( 'woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field' );

		    $installmentsField.empty()
		      .append($('<option>', {
		        value: '',
		        text: 'Por favor seleccione...'
		      }));

		    this.log(bankId, cardId);
		    this.log(this.config.promotions.plans[bankId][cardId]);

		    $.each( this.config.promotions.plans[bankId][cardId], function (i, plan) {
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
		        accounting.formatMoney(installmentPrice, this.config.currencySettings) +
		        ' (' + accounting.formatMoney((total + charge), this.config.currencySettings) + ')'
		      ;

		      $installmentsField.append($('<option>', {
		        value: plan.rule_id + '-' + cardId + '-' + plan.fee_to_send,
		        text: optionText
		      }));

		    }.bind(this));
		},

		onInstallmentsChange: function () {
			console.log('onInstallmentsChange');
			this.log('onInstallmentChange');

		    var bankId = this.$banksDropdown.val(),
		      cardId = this.$cardsDropdown.val(),
		      grandTotal = parseFloat(this.cart_total),
		      $installmentsField = this.$installmentsDropdown
		      value = $installmentsField.val();

		    this.log('installments value ', value);
		},

		onUpdatedCheckout: function () {
			this.log('onUpdatedCheckout', this, event);

			/**
			 * @TODO: implement an object create validation for the SDK
			 *
			 * currently there's no way for us to check if the object was loaded OK
			 * based on the attributes that the object currently holds
			 * ex: if (!this.api.hasOwnProperty('orgId')) { ... }
			 */

			// If we already processed the form, then exit
			// TODO: bootstrap in a different way and remove this `loadReady` property
			if ( this.loadReady ) {
				this.log('already initialized, exitting...');
				return;
			}

			// looking good lets load all available promotions
			this._loadPromotions(this);
			this.loadReady = true;
		},
	};

	wc_decidir_checkout_form.init();
});
</script>
