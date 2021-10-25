<?php
/**
 * Plugin Name: WooCommerce DECIDIR 1.1 Gateway
 
 *
 * @package   WC-Gateway-DECIDIR
 * @author    IURCO - Prisma SA
 * @category  Admin
 * @copyright Copyright (c) 2021 IURCO SAS - Primas SA
 * 
 *
 */
 

add_filter( 'woocommerce_payment_gateways', 'decidir_add_gateway_class' );
function decidir_add_gateway_class( $gateways ) {
    $gateways[] = 'WC_Decidir_Gateway';  
    return $gateways;
}
 
require( plugin_dir_path( __FILE__ ) . '/init.php');
add_action( 'plugins_loaded', 'decidir_init_gateway_class' );
function decidir_init_gateway_class() {
 
    class WC_Decidir_Gateway extends WC_Payment_Gateway {
 
       
        public function __construct() {
         
            $this->id = 'decidir_gateway'; 
            $this->icon = apply_filters( 'woocommerce_decidir_icon', plugins_url( 'WC-gateway-decidir/assets/images/logos-tarjetas.png', plugin_dir_path( __FILE__ ) ) );            
            $this->has_fields = true; 
            $this->method_title = 'DECIDIR';
            $this->method_description = 'El Sistema de Pago Seguro DECIDIR (SPS)';
           
            $this->supports = array(
                'products'
            );
         
            $this->init_form_fields();
         
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            $this->testmode = 'yes' === $this->get_option( 'testmode' );
            $this->usecybersource = 'yes' === $this->get_option( 'usecybersource' );
            $this->private_key = $this->testmode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
            $this->publishable_key = $this->testmode ? $this->get_option( 'test_publishable_key' ) : $this->get_option( 'publishable_key' );
         
            
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                   
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );

            add_action( 'woocommerce_api_decidir', array( $this, 'webhook' ) );

            
         }
 
       
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
                 'usecybersource' => array(
                    'title'       => 'Use Cybersource',
                    'label'       => 'Enable Cybersource',
                    'type'        => 'checkbox',
                    'description' => 'Use Cybersource for transaction.',
                    'default'     => 'yes',
                    'desc_tip'    => true,
                ),
                  'establishment_name' => array(
                  'title'       => __( 'Establishment Name', 'wc-gateway-decidir' ),
                  'type'        => 'text',
                  'description' => __( 'Enter Establishment Name' ,'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'desc_tip'    => true,
                ),

                'sandbox_site_id' => array(
                  'title'       => __( 'Sandbox Site Id', 'wc-gateway-decidir' ),
                  'type'        => 'text',
                  'description' => __( 'Enter your Sandbox Site Id', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'desc_tip'    => true,
                ),


                'test_publishable_key' => array(
                    'title'       => 'Sandbox Public Key',
                    'type'        => 'text'
                ),
                'test_private_key' => array(
                    'title'       => 'Sandbox Private Key',
                    'type'        => 'password',
                ), 

                'production_site_id' => array(
                  'title'       => __( 'Production Site Id', 'wc-gateway-decidir' ),
                  'type'        => 'text',
                  'description' => __( 'Enter your roduction Site Id', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'desc_tip'    => true,
                ),

             
                'publishable_key' => array(
                    'title'       => 'Production Public Key',
                    'type'        => 'text'
                ),
                'private_key' => array(
                    'title'       => 'Production Private Key',
                    'type'        => 'password'
                ),
               
                
                
                'cuotas' => array(
                  'title'       => __( 'Avaliable Installments', 'wc-gateway-decidir' ),
                  'type'        => 'multiselect',
                  'description' => __( 'Select Installments', 'wc-gateway-decidir' ),
                  'default'     => __( '', 'wc-gateway-decidir' ),
                  'options' => array(
                            '1' => ' 1 CUOTA',
                            '2' => ' 2 CUOTAS',
                            '3' => ' 3 CUOTAS',
                            '4' => ' 4 CUOTAS',
                            '5' => ' 5 CUOTAS',
                            '6' => ' 6 CUOTAS',
                            '7' => ' 7 CUOTAS',
                            '8' => ' 8 CUOTAS',
                            '9' => ' 9 CUOTAS',
                            '10' => ' 10 CUOTAS',
                            '11' => ' 11 CUOTAS',
                            '12' => '12 CUOTAS',
                            '18' => '18 CUOTAS',
                       ), // ,   
                      'desc_tip'    => true,
                      ),

                'option_name' => array(
                 'title' => 'Tarjetas Habilitadas',
                 'description' => 'Ctrl + Click para habilitar la tarjeta',
                 'type' => 'multiselect',
                 'options' => array(
                      '001' => 'VISA',
                      '002' => 'VISA DEBITO',
                      '104' => 'MASTERCARD',
                      '65' => 'AMERICAN EXPRESS',
                 ) 
              )

            
            );
        }
        
        public function payment_fields() {
         
          
            if ( $this->description ) {
                
                if ( $this->testmode ) {
                    $this->description .= ' TEST MODE ENABLED. In test mode, No orders will be fulfilled.';
                    $this->description  = trim( $this->description );
                }
                
                echo wpautop( wp_kses_post( $this->description ) );
            }
          ?>
         <script src="https://live.decidir.com/static/v2.5/decidir.js"></script>
          <?php
             $_SESSION['publishable_key'] = $this->settings['publishable_key'];
             $_SESSION['testmode'] = $this->settings['testmode'];
             $_SESSION['usecybersource'] = $this->settings['usecybersource'];
            
             if($this->settings['testmode'] == 'no'){
               $_SESSION['urlSandbox'] = "https://live.decidir.com/api/v2"; 
            } else {
               $_SESSION['urlSandbox'] = "https://developers.decidir.com/api/v2"; 
            }
          
            
           require( plugin_dir_path( __FILE__ ) . 'template/form-checkout.php');

           ?>
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
          const testmode = "<?php echo $_SESSION['testmode']; ?>";
          const useCS = "<?php echo $_SESSION['usecybersource']; ?>";

          console.log(useCS);

          if (useCS=="yes") {
            let decidir = new Decidir(urlSandbox,false);
            decidir.setPublishableKey(publicApiKey);
            decidir.setTimeout(0);
            console.log("si entra");
            jQuery('#place_order').on('click', function(e) {

            if(jQuery('#payment_method_decidir_gateway').is(':checked')) { 
              e.preventDefault();
              var element = document.querySelectorAll('#decidir_gateway-cc-form');
              for (var i=0; element.length > i; i++) {
                console.log(element[i]);
                var form = element[i];
              }
              decidir.createToken(form, sdkResponseHandler);
            }
          });
         
          }else{
            let decidir = new Decidir(urlSandbox,true);
            decidir.setPublishableKey(publicApiKey);
            decidir.setTimeout(0);
            jQuery('#place_order').on('click', function(e) {

            if(jQuery('#payment_method_decidir_gateway').is(':checked')) { 
              e.preventDefault();
              var element = document.querySelectorAll('#decidir_gateway-cc-form');
              for (var i=0; element.length > i; i++) {
                console.log(element[i]);
                var form = element[i];
              }
              decidir.createToken(form, sdkResponseHandler);
            }
          });
            console.log("no entra");
          }
         //  const decidir = new Decidir(urlSandbox,true);
          
          
          
          
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
           <?php
         
        
         
        }
 
        
        public function payment_scripts() {
         
           
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
                return;
            }
         
            
            if ( 'no' === $this->enabled ) {
                return;
            }
         
            
            if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
                return;
            }
         
            
            if ( ! $this->testmode && ! is_ssl() ) {
                return;
            }
         
            
            wp_enqueue_script( 'decidir_js', 'https://live.decidir.com/static/v2/decidir.js' );
            wp_register_script( 'woocommerce_decidir', plugins_url( 'assets/js/card.js', __FILE__ ), array( 'jquery', 'decidir_js' ) );
            wp_enqueue_script( 'woocommerce_decidir' );
            //wp_enqueue_script( 'form_js', plugins_url( 'assets/js/form.js', __FILE__ ));

            wp_register_style('woocommerce_decidir', plugins_url('assets/css/style.css',__FILE__ ));
            wp_enqueue_style('woocommerce_decidir');
            
         
        }
 
       
        public function validate_fields(){
         
            if( empty( $_POST[ 'billing_first_name' ]) ) {
                wc_add_notice(  'First name is required!', 'error' );
                return false;
            }
            return true;
         
        }
 
      
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
              $site_transaction_id = $order_id ;
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
                    "amount" =>(int)$psp_Amount,
                    "currency" => "ARS",
                    "installments" => (int)$psp_NumPayments,
                    "description" => $this->settings['establishment_name'],
                    "fraud_detection" => array(),
                    "establishment_name" => $this->settings['establishment_name'],
                    "payment_type" => "single",
                    "sub_payments" => array()
                  );
             
               $service = "SDK-PHP"; 
               $developer ="IURCO - Prisma SA";
               $grouper = "WC-Gateway-DECIDIR";
       

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
         
                 
                  $order->reduce_order_stock();

                 
                  WC()->cart->empty_cart();

                 
                  return array(
                    'result'  => 'success',
                    'redirect'  => $this->get_return_url( $order )
                  );               
                  
                } else {
                  
                  $details = json_encode($response->getStatus_details());
                       
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
  

        public function webhook() {
         
            $order = wc_get_order( $_GET['id'] );
            $order->payment_complete();
            $order->reduce_order_stock();
         
            update_option('webhook_debug', $_GET);
        }
    }
}


