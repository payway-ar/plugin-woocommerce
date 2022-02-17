/* global decidirCheckoutForm */
var decidirCheckoutForm;

/* global wc_gateway_decidir_params */
/* global wc_gateway_decidir_accounting_format */

jQuery( function( $ ) {
  function wc_decidir_initialization_error_message () {
    var message = 'WC Gateway Decidir: either required params or SDK could not be loaded';
    console.error( message );

    // hide form fields and display the exception
    jQuery( '#decidir_gateway-cc-form .fields-wrapper' ).hide();
    jQuery( '#decidir_gateway-cc-form #errorcard' ).html( message );
  }
console.log('begin DecidirCheckoutForm');
  var DecidirCheckoutForm = function ( $target ) {
    // this.onTokenResponse         = this.onTokenResponse.bind( this );
    this.init                       = this.init.bind( this );
    this.onUpdatedCheckout          = this.onUpdatedCheckout.bind( this );
    this.updateCardExpirationMonth  = this.updateCardExpirationMonth.bind( this );
    // this.updateCardExpiration       = this.updateCardExpiration.bind( this );
    this.loadPromotions             = this.loadPromotions.bind( this );
    this.onBankChange               = this.onBankChange.bind( this );
    this.onCardChange               = this.onCardChange.bind( this );
    this.onInstallmentChange        = this.onInstallmentChange.bind( this );
    this.onPlaceOrder               = this.onPlaceOrder.bind( this );
    this.loadToken                  = this.loadToken.bind( this );
    this.getFormValue               = this.getFormValue.bind( this );
    this.beforePlaceOrder           = this.beforePlaceOrder.bind( this );

    this.$form = $( $target || '#decidir_gateway-cc-form' );

    // Initializes the current total for Installments calculation
    this.cart_total = wc_gateway_decidir_params.cart_total;

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

    // Whether we've hydrate Payment form options or not
    this.loadReady = false;

    $( document.body )
      .on( 'updated_checkout', this.init )
      .on( 'updated_checkout', this.onUpdatedCheckout );
      // .on( 'updated_checkout', this.on );
  };

  /**
   * Custom logger func, to enable/disable based on the plugin configuration
   * It will be logging in sandbox mode only
   */
  DecidirCheckoutForm.prototype.log = function() {
    if ( this.config.sandboxEnabled && typeof console === 'object' ) {
      console.log( '** decidir', ...arguments );
    }
  };

  DecidirCheckoutForm.prototype.init = function () {
console.log('attaching event listeners...', $( this.config.form.fields.bankDropdown, this.$form ));

    if ( typeof wc_gateway_decidir_params === 'undefined'
      || typeof Decidir === 'undefined'
      || typeof DecidirCheckoutForm === 'undefined'
    ) {
      wc_decidir_initialization_error_message();
    } else {
      decidirCheckoutForm = new DecidirCheckoutForm( jQuery( '#decidir_gateway-cc-form' ) );

      if ( ! decidirCheckoutForm ) {
      	jQuery( '#decidir_gateway-cc-form' ).hide();
      	alert('Decidir Gateway could not be loaded');
      	// return;
      }
    }

    $( this.config.form.fields.bankDropdown, this.$form ).on( 'change', { context: this }, this.onBankChange );
    $( this.config.form.fields.cardDropdown, this.$form ).on( 'change', { context: this }, this.onCardChange );
    $( this.config.form.fields.installmentsDropdown, this.$form ).on( 'change', { context: this }, this.onInstallmentChange );
    // @TODO: switch to a `checkout_place_order` || `checkout_place_order_decidir_gateway` event listener
    $( this.config.form.fields.placeOrderButton ).on( 'click', { context: this }, this.onPlaceOrder );
    $( this.config.form.fields.cardExpirationMonth ).on( 'blur change', { context: this }, this.updateCardExpirationMonth );

  };

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


  // DecidirCheckoutForm.prototype.getCardHolderName = function () {
  //   return $( this.config.form.elements.cardHolderName, this.$form ).val();
  // };

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
  };

  // Token creation callback response
  // DecidirCheckoutForm.prototype.onTokenResponse = function (status, response) {
  //   this.log('onTokenResponse', status, response);
  //
  //   if (status === 200 || status === 201) {
  //       var title = JSON.stringify(response),
  //         expires = "expires=60";
  //
  //   // fill the hidden so it reaches back
  //     $('#dcdtoken').val(response);
  //     } else {
  //     this.log('onTokenResponse', 'ERROR');
  //   }
  // };

  /**
   *
   *
   * @param {Event} event jQuery Event `updated_checkout`
   * @param {Object} data WC Checkout event data
   */
  DecidirCheckoutForm.prototype.onUpdatedCheckout = function ( event, data ) {
    this.log('onUpdatedCheckout', this, event, data);

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
    this.loadPromotions(this);
    this.loadReady = true;
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

  DecidirCheckoutForm.prototype.updateCardExpirationMonth = function (event) {
    var fields = event.data.context.config.form.fields || {},
    $field = $(fields.cardExpirationMonth) || false;

    if (!$field) {
      return;
    }
      var value = $field.val() || '';
        // year = expirationValue.substring(expirationValue.length - 2);

    if (isNaN(value) || value == '' || value < 1 || value > 12) {
      $field.val('');
      // $field.closest('.form-row').addClass('woocommerce-invalid');
      return;
    }

    if (value.length === 1) {
      value = '0' + value;
    }

    $field.val(value);
    $field.addClass('woocommerce-validated');
    // $(fields.cardExpirationYear).val(year);
  };

  DecidirCheckoutForm.prototype.loadPromotions = function ( context ) {
    var promos = this.config.promotions,
      $bankField = $( context.config.form.fields.bankDropdown );

    this.log('loadPromotions', promos);
    this.log($bankField);

    if (
        !context.config.promotions.banks
        || !$bankField
      ) {
      console.error('DECIDIR - no banks found');
      return;
    }

    // default option
    $bankField.empty()
      .append( $('<option>', { value: '', text: 'Por favor seleccione...'}));

    $.each( context.config.promotions.banks, function ( i, bank ) {
      $bankField.append($('<option>', {
        value: bank.value,
        text: bank.name
      }));
    });
  };

  DecidirCheckoutForm.prototype.onBankChange = function (e) {
this.log('onBankChange', e);
    var context = e.data.context,
      bankId = context.config.form.elements.$banksDropdown.val(),
      $cardField = context.config.form.elements.$cardsDropdown,
      $installmentsDropdown = context.config.form.elements.$installmentsDropdown;


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

  // /**
  //  * Initialize
  //  */
  // if ( typeof wc_gateway_decidir_params === 'undefined'
  //      || typeof Decidir === 'undefined'
  //      || typeof DecidirCheckoutForm === 'undefined'
  //   ) {
  //    wc_decidir_initialization_error_message();
  // } else {
  //   decidirCheckoutForm = new DecidirCheckoutForm( $( '#decidir_gateway-cc-form' ) );
  //
  //   if ( ! decidirCheckoutForm ) {
  //     $( '#decidir_gateway-cc-form' ).hide();
  //     alert('Decidir Gateway could not be loaded');
  //     return;
  //   }
  //
  //   // $( document.body ).trigger('checkout_ready');
  // }

  // $( document.body ).on('updated_checkout', function () {
  //   if ( typeof wc_gateway_decidir_params === 'undefined'
  //     || typeof Decidir === 'undefined'
  //     || typeof DecidirCheckoutForm === 'undefined'
  //   ) {
  //     wc_decidir_initialization_error_message();
  //   } else {
  //     decidirCheckoutForm = new DecidirCheckoutForm( jQuery( '#decidir_gateway-cc-form' ) );
  //
  //     if ( ! decidirCheckoutForm ) {
  //     	jQuery( '#decidir_gateway-cc-form' ).hide();
  //     	alert('Decidir Gateway could not be loaded');
  //     	// return;
  //     }
  //   }
  // });
  console.log('EOF - payments.js');
});
