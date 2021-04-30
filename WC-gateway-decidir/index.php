<?php
/**
 * Plugin Name: WooCommerce DECIDIR 2.0 Gateway
 
 *
 * @package   WC-Gateway-DECIDIR
 * @author    Iurco
 * @category  Admin
 * @copyright Copyright (c) 2021 IURCO SAS
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */
 
/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'decidir_add_gateway_class' );
function decidir_add_gateway_class( $gateways ) {
    $gateways[] = 'WC_Decidir_Gateway'; // your class name is here
    return $gateways;
}
 
/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'decidir_init_gateway_class' );
function decidir_init_gateway_class() {
 
    class WC_Decidir_Gateway extends WC_Payment_Gateway {
 
        /**
         * Class constructor, more about it in Step 3
         */
        public function __construct() {
         
            $this->id = 'decidir_gateway'; // payment gateway plugin ID
            $this->icon = apply_filters( 'woocommerce_decidir_icon', plugins_url( 'WC-gateway-decidir/img/logos-tarjetas.png', plugin_dir_path( __FILE__ ) ) );            
            $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = 'DECIDIR';
            $this->method_description = 'El Sistema de Pago Seguro DECIDIR (SPS) ddescripcion completa';
            // will be displayed on the options page
         
            // gateways can support subscriptions, refunds, saved payment methods,
            // but in this tutorial we begin with simple payments
            $this->supports = array(
                'products'
            );
         
            // Method with all the options fields
            $this->init_form_fields();
         
            // Load the settings.
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            $this->testmode = 'yes' === $this->get_option( 'testmode' );
            $this->private_key = $this->testmode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
            $this->publishable_key = $this->testmode ? $this->get_option( 'test_publishable_key' ) : $this->get_option( 'publishable_key' );
         
            // This action hook saves the settings
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
         
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

            add_action( 'woocommerce_api_decidir', array( $this, 'webhook' ) );

            // You can also register a webhook here
            // add_action( 'woocommerce_api_{webhook name}', array( $this, 'webhook' ) );
         }
 
        /**
         * Plugin options, we deal with it in Step 3 too
         */
         public function init_form_fields(){
         
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable Decidir SPS',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default'     => 'Credit Card',
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default'     => 'Pay with your credit card via our super-cool payment gateway.',
                ),
                'testmode' => array(
                    'title'       => 'Test mode',
                    'label'       => 'Enable Test Mode',
                    'type'        => 'checkbox',
                    'description' => 'Place the payment gateway in test mode using test API keys.',
                    'default'     => 'yes',
                    'desc_tip'    => true,
                ),
                'test_publishable_key' => array(
                    'title'       => 'Test Publishable Key',
                    'type'        => 'text'
                ),
                'test_private_key' => array(
                    'title'       => 'Test Private Key',
                    'type'        => 'password',
                ),
                'publishable_key' => array(
                    'title'       => 'Live Publishable Key',
                    'type'        => 'text'
                ),
                'private_key' => array(
                    'title'       => 'Live Private Key',
                    'type'        => 'password'
                ),
                'establishment_name' => array(
                  'title'       => __( 'Establishment Name', 'wc-gateway-decidir' ),
                  'type'        => 'text',
                  'description' => __( 'Enter your Establishment Name', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'desc_tip'    => true,
                ),
                
                'cuotas' => array(
                  'title'       => __( 'Maxima Cantidad de Cuotas', 'wc-gateway-decidir' ),
                  'type'        => 'select',
                  'description' => __( 'Selecciones máxima cantidad de cuotas', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'options' => array(
                            '001' => ' 1 CUOTA',
                            '003' => ' 3 CUOTAS',
                            '006' => ' 6 CUOTAS',
                            '012' => '12 CUOTAS',
                       ), // ,   
                      'desc_tip'    => true,
                      ),

                'option_name' => array(
                 'title' => 'Tarjetas Habilitadas',
                 'description' => 'Ctrl+ Click para habilitar la tarjeta',
                 'type' => 'multiselect',
                 'options' => array(
                      '001' => 'VISA',
                      '002' => 'VISA DEBITO',
                      '104' => 'MASTERCARD',
                 ) // array of options for select/multiselects only
)

             /*'financiacion' => array(
                  'title'       => __( 'Recargo por financiacion', 'wc-gateway-decidir' ),
                  'type'        => 'text',
                  'description' => __( 'Ingresar Cantidad de Cuotas y recargo: 12-30, 18-45', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'desc_tip'    => true,
                ),*/
            );
        }
        /**
         * You will need it if you want your custom credit card form, Step 4 is about it
         */
        public function payment_fields() {
         
            // ok, let's display some description before the payment form
            if ( $this->description ) {
                // you can instructions for test mode, I mean test card numbers etc.
                if ( $this->testmode ) {
                    $this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#" target="_blank" rel="noopener noreferrer">documentation</a>.';
                    $this->description  = trim( $this->description );
                }
                // display the description with <p> tags etc.
                echo wpautop( wp_kses_post( $this->description ) );
            }

            $_SESSION['publishable_key'] = $this->settings['publishable_key'];
            if($this->settings['testmode'] == 'no'){
               $_SESSION['urlSandbox'] = "https://live.decidir.com/api/v2"; 
            } else {
               $_SESSION['urlSandbox'] = "https://developers.decidir.com/api/v2"; 
            }
            ?>
          <!--     <div class='card-wrapper'></div>
       
                ,<?php echo $_SESSION['publishable_key'] ?>CSS is included via this JavaScript file -->
             <decidir_form>
                <div id="errorcard"></div> 
                <input type="text" id="nombre_titular" name="card_holder_name" placeholder="NOMBRE COMPLETO"/>
                <input type="text" id="dni_titular" name="dni_titular" placeholder="DNI"/>
                <select id="decidir_tarjeta_tipo" class="input-text wc-credit-card-form-card-name" name="decidir-tarjeta-tipo">
                  <option value="">Tipo Tarjeta</option>
                  <option value="1" cuotas="1.3.6.12">VISA</option>
                  <option value="31" cuotas="1">VISA DEBITO</option>
                  <option value="104" cuotas="1.3.6.12">MASTERCARD</option>
                  <option value="65" cuotas="1.3.6.12">AMERICAN EXPRESS</option>
                 
                  
                  
              </select>
                <input type="text" id="decidir_numero" name="number" placeholder="NUMERO DE TARJETA">
                <input type="text" id="card_expiration" name="expiry" placeholder="MM/AA"/>
                <input type="text" id="decidir_cvc" name="cvc" placeholder="CVC"/>
              <select id="decidir_installments" class="input-text wc-credit-card-form-card-name" name="decidir-cuotas">
              <option value="0">Seleccione cantidad de cuotas</option>
               
               </select>
             
           
              <fieldset id="<?php echo $this->id; ?>-cc-form"  style="display:none;" >
              
              <li>
                <label for="decidir-card-tipo">Seleccione su tarjeta <span class="required">*</span></label>
                <select id="decidir-card-tipo" class="input-text wc-credit-card-form-card-name" name="decidir-card-tipo">
     
                </select>
              </li>
              <li>
                  <input type="text" id="card_number" name="card_number" data-decidir="card_number" placeholder="Card Number"/>
              </li>     
              <li>
                <label for="card_expiration_month">Mes de vencimiento:</label>
                <input type="text" id="card_expiration_month"  data-decidir="card_expiration_month" placeholder="MM" value=""/>
              </li>
              <li>
                <label for="card_expiration_year">Año de vencimiento:</label>
                <input type="text" id="card_expiration_year"  data-decidir="card_expiration_year" placeholder="AA" value=""/>
              </li>
              <li>
                  <input type="text" id="security_code" name="security_code" data-decidir="security_code" placeholder="CVC"/>
              </li>
              <li>
                <label for="card_holder_name">Nombre del titular:</label>
                <input type="text" id="card_holder_name" data-decidir="card_holder_name" placeholder="TITULAR" value=""/>
              </li>
              <li>
                <label for="card_holder_doc_type">Tipo de documento:</label>
                <select data-decidir="card_holder_doc_type">
                  <option value="dni">DNI</option>
                </select>
              </li>
              <li>
                <label for="card_holder_doc_type">Numero de documento:</label>
                <input id="card_holder_doc_number" type="text"data-decidir="card_holder_doc_number" placeholder="" value=""/>
              </li>
               
              <div class="clear"></div>
            </fieldset>
        
            <input type="hidden" id="total_result_decidir" value="<?php echo WC()->cart->cart_contents_total ; ?>" />
            <input type="hidden" id="result_decidir"/>

            

            </decidir_form>

         

              
           
      
      <script type="text/javascript">

    
        jQuery(document).on("focusout","#dni_titular",function(){
          jQuery('#card_holder_doc_number').val(jQuery('#dni_titular').val());
        });  
 

     

        jQuery(document).on('updated_checkout', function() {
             
            jQuery('#decidir_tarjeta_tipo' ).on('change', function (e) {
            jQuery('#decidir_installments').html('');
            var optionSelected = jQuery('option:selected', this);
            var tarjeta = this.value;
            var cuotas = optionSelected[0].attributes.cuotas.value;
            var result = cuotas.split('.');
           
            
            jQuery.each( result, function( key, value ) { 

              if(value == 3){
               
                jQuery("#decidir_installments").append("<option value='13'>3 Cuotas </option>");

              } else if(value == 6){
                  jQuery("#decidir_installments").append("<option value='16'>6 Cuotas </option>");    
              } else if(value == 12){
                  jQuery("#decidir_installments").append("<option value='7'>12 Cuotas</option>");
                 
                    
              } else if(value == 18){
                  jQuery("#decidir_installments").append("<option value='8'>18 Cuotas </option>"); 
                  
             
              } else {
                jQuery("#decidir_installments").append("<option value='"+value+"'>"+value+" Cuota</option>");
              }

              
                  
            });

          });     
          
          jQuery('#card_holder_name').val(jQuery('#billing_first_name').val() + ' ' + jQuery('#billing_last_name').val());
           
          jQuery('#card_expiration').focusout(function () {
            var card_expiration = jQuery('#card_expiration').val();
            var month = card_expiration.substring(0, 2);
            var year = card_expiration.substring(card_expiration.length - 2);
            jQuery('#card_expiration_month').val(month);
            jQuery('#card_expiration_year').val(year);

          });  
 
          jQuery('#nombre_titular').focusout(function () {
            jQuery('#card_holder_name').val(jQuery('#nombre_titular').val());
          });  
          
          jQuery('#decidir_cvc').focusout(function () {
            jQuery('#security_code').val(jQuery('#decidir_cvc').val());
          });     
          
          jQuery('#decidir_numero').focusout(function () {
            jQuery('#card_number').val(jQuery('#decidir_numero').val());
            
             var num = jQuery('#decidir_numero').val();
             num = num.replace(/[^\d]/g,'');
             // now test the number against some regexes to figure out the card type.
             if (num.match(/^5[1-5]\d{14}$/)) {
               jQuery('#decidir-card-tipo').val(15);
             } else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
              jQuery('#decidir-card-tipo').val(1);
             } else if (num.match(/^3[47]\d{13}/)) {
               jQuery('#decidir-card-tipo').val(65);
             } else if (num.match(/^6011\d{12}/)) {
               return 'Discover';
             }
            
          });    

          


          
          const publicApiKey = "<?php echo $_SESSION['publishable_key']; ?>";
          const urlSandbox = "<?php echo $_SESSION['urlSandbox']; ?>";
        
          const decidir = new Decidir(urlSandbox,true);
          decidir.setPublishableKey(publicApiKey);
          decidir.setTimeout(5000);
          
          jQuery('#place_order').on('click', function(e) {

            if(jQuery('#payment_method_decidir_gateway').is(':checked')) { 
               // alert("Hola");
              e.preventDefault();
              var element = document.querySelectorAll('#decidir_gateway-cc-form');
              for (var i=0; element.length > i; i++) {
                console.log(element[i]);
                var form = element[i];
              }
              decidir.createToken(form, sdkResponseHandler);
            }
          });
          
          function sdkResponseHandler(status, response) {
            if (status != 200 && status != 201) {
             document.getElementById("nombre_titular").focus();
             jQuery("#errorcard").append("<strong>Verificar Datos Tarjeta</strong><br>");
             
            } else {      
              var title = JSON.stringify(response);
              var expires = "expires=60";
              document.cookie = "result_decidir=" + title + "; " + expires + ";path=/";    
              jQuery('#place_order').submit();
            }
          }
        });     
      </script>

        <style type="text/css">
        decidir_form {
          width: 100%;
          position: relative;
          display: table;
          margin: 0px auto;
          max-width: 300px;
        }
        .payment_method_decidir_gateway p {
            padding: 10px;
        }
        .card-wrapper {
            margin: 10px 0px;
        }
        .payment_method_decidir_gateway input {
            width: 100%;
            margin: 5px 0px;
            max-width: 300px;
            clear: both;
            float: left;
        }
        .payment_method_decidir_gateway .jp-card-name {
            font-size: 15px !important;
        }
        .payment_method_decidir_gateway img {
            max-width: 110px !important;
            float: right;
        }
        input#decidir_cvc {
            width: 80px;
            clear: none;
        }
        input#card_expiration {
            width: 100px;
            float: left;
            clear: left;
        }
        #payment_method_decidir_gateway {
          width: auto;
          clear: none;
          float: inherit;
        }

        #checkout-radio{
          display: none;
        }
             

        .jp-card .jp-card-front, .jp-card .jp-card-back {
                background: #6f5353 !important;
            }
              #decidir_installments{   
                float: left;
                position: relative;
                margin: 3px 0 14px;
                font-family: inherit;
                font-size: 15px;
                line-height: 18px;
                font-weight: inherit;
                color: #717171;
                background-color: #fff;
                border: 1px solid #e6e6e6;
                outline: 0;
                -webkit-appearance: none;
                box-sizing: border-box;
                border-radius: 0;
                width: 100%;
                text-align: center;
              }
                #decidir_tarjeta_tipo {
                  position: relative;
                width: 100%;
               
                margin: 3px 0 14px;
                font-family: inherit;
                font-size: 15px;
                line-height: 18px;
                font-weight: inherit;
                color: #717171;
                background-color: #fff;
                border: 1px solid #e6e6e6;
                outline: 0;
                -webkit-appearance: none;
                box-sizing: border-box;
                /* height: 50px; */
                border-radius: 0;
                }

                #errorcard{
                  margin: auto;
                  text-align: center;
                  color: #a94442;
                  background-color: #f2dede;
                  border-color: #ebccd1;
                  border-left: solid 5px;
                  width: 100%;
                  max-height: 25px;
                  overflow: hidden;
                }

                .fee{
                  /*display: none;*/
                }
            </style>

            <?php
         
 
         
        }
 
        /*
         * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
         */
        public function payment_scripts() {
         
            // we need JavaScript to process a token only on cart/checkout pages, right?
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
                return;
            }
         
            // if our payment gateway is disabled, we do not have to enqueue JS too
            if ( 'no' === $this->enabled ) {
                return;
            }
         
            // no reason to enqueue JavaScript if API keys are not set
            if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
                return;
            }
         
            // do not work with card detailes without SSL unless your website is in a test mode
            if ( ! $this->testmode && ! is_ssl() ) {
                return;
            }
         
            // let's suppose it is our payment processor JavaScript that allows to obtain a token
            wp_enqueue_script( 'decidir_js', 'https://live.decidir.com/static/v2/decidir.js' );
         
            // and this is our custom JS in your plugin directory that works with token.js
            wp_register_script( 'woocommerce_decidirb', plugins_url( 'dist/card.js', __FILE__ ), array( 'jquery', 'decidir_js' ) );
         
            wp_enqueue_script( 'woocommerce_decidir' );
            wp_enqueue_script( 'woocommerce_decidirb' );
         
        }
 
        /*
         * Fields validation, more in Step 5
         */
        public function validate_fields(){
         
            if( empty( $_POST[ 'billing_first_name' ]) ) {
                wc_add_notice(  'First name is required!', 'error' );
                return false;
            }
            return true;
         
        }
 
        /* add fee payment mode */





        /*
         * We're processing the payments here, everything about it is in Step 5
         */
        public function process_payment( $order_id ) {
         
            global $woocommerce;
         
              require_once __DIR__ . '/decidir/vendor/autoload.php';    
              $clear_slashes = stripslashes($_COOKIE['result_decidir']);
              $result_decidir= json_decode($clear_slashes);
                   
              $order = wc_get_order( $order_id );

              $keys_data = array('public_key' => $this->publishable_key, 'private_key' => $this->private_key);

              if($this->settings['testmode'] == 'no'){
                 $ambient = "prod"; 
              } else {
                 $ambient = "test"; 
              }
              
              $connector = new \Decidir\Connector($keys_data, $ambient);
            
              $decidir_MerchOrderIdnewdate = date("his");
              $site_transaction_id = $order_id .'-'. $decidir_MerchOrderIdnewdate ;
              $psp_Amount =  preg_replace( '#[^\d.]#', '', $order->order_total  );
              $amount = str_replace('.', '', $psp_Amount);  
                    
              $newdate = date("Y-m-d H:i:s");
              $psp_MerchTxRef = $order->customer_id .'-'. $decidir_MerchOrderIdnewdate;
              $psp_CardFirstName = $_POST['decidir_gateway-card-first-name'];
              $psp_CardLastName = $_POST['decidir_gateway-card-last-name'];
              $psp_Product = $_POST['decidir_gateway-card-tipo'];
              $psp_CardNumber = str_replace(' ', '', $_POST['decidir_gateway-card-number']);
              $data = $_POST['decidir_gateway-card-expiry'];
              $year = substr($data, strpos($data, "/") + 1);
              $month = str_split($data, 2); 
              $psp_CardExpDate = $year . $month[0];
              $psp_CardExpDate = str_replace(' ', '', $psp_CardExpDate);  
              $psp_CardSecurityCode = str_replace(' ', '', $_POST['decidir_gateway-card-cvc']);
              $psp_CustomerMail = $_POST['billing_email'];
              $psp_NumPayments = str_replace(' ', '', $_POST['decidir-cuotas']);   
              $tarjeta_tipo = str_replace(' ', '', $_POST['decidir-tarjeta-tipo']);
              $decidir_card_tipo = intval($_POST['decidir-card-tipo']);
              
         
              $data = array(
                    "site_transaction_id" => $site_transaction_id,
                    "token" => $result_decidir->id,
                    "customer" => array(
                                "id" => "customer", 
                                "email" => $order->get_billing_email(),
                                "ip_address" => $order->get_customer_ip_address(),
                                ),
                    "payment_method_id" => (int)$tarjeta_tipo,
                    "bin" => $result_decidir->bin,
                    "amount" => $psp_Amount,
                    "currency" => "ARS",
                    "installments" => (int)$psp_NumPayments,
                    "description" => $this->settings['establishment_name'],
                    "fraud_detection" => array(),
                    "establishment_name" => $this->settings['establishment_name'],
                    "payment_type" => "single",
                    "sub_payments" => array()
                  );
         

              try {
                $response = $connector->payment()->ExecutePayment($data);
                $status = $response->getStatus();
                if($status == 'approved'){
                  
                  $json = json_encode($response);
                  update_post_meta($order_id, 'decidir_response',$json );
                  
                  $order->update_status( 'processing', __( 'TRANSACCION ID: ' . $response->getId(), 'wc-gateway-decidir' ) );       
                  $order->add_order_note(
                    sprintf(
                      "Detalle pago: '%s'", $response->getId() . ' - ' . $status
                    )
                  );    
         
                  // Reduce stock levels
                  $order->reduce_order_stock();

                  // Remove cart
                  WC()->cart->empty_cart();

                  // Return thankyou redirect
                  return array(
                    'result'  => 'success',
                    'redirect'  => $this->get_return_url( $order )
                  );               
                  
                } else {
                  
                  $details = json_encode($response->getStatus_details());
                  //$order->update_status( 'on-hold', __( 'TRANSACCION ID: ' . $response->getId(), 'wc-gateway-decidir' ) );        
                  $order->add_order_note(
                    sprintf(
                      "Detalle pago: '%s'", $status
                    )
                  );    
                  $order->add_order_note(
                    sprintf(
                      "Detalle error: '%s'", $details
                    )
                  );          
                  
                  // Remove cart
                  //WC()->cart->empty_cart();
                  wc_add_notice( __( $status ), 'error' ); 
                        
                  
                }
           
          
               } catch( \Exception $e ) {
                
                $resultado = json_encode($e->getData());
                
                $order->add_order_note(
                    sprintf(
                      "Detalle error: '%s'", $resultado
                    )
                  );          
                $detalle = json_encode($data);
                $order->add_order_note(
                    sprintf(
                      "Detalle error data: '%s'", $detalle
                    )
                  );

                  
                wc_add_notice( __( $e->getData() ), 'error' ); 
              } 
         
        }
  




        /*
         * In case you need a webhook, like PayPal IPN etc
         */
        public function webhook() {
         
            $order = wc_get_order( $_GET['id'] );
            $order->payment_complete();
            $order->reduce_order_stock();
         
            update_option('webhook_debug', $_GET);
        }
    }
}


