<?php
/**
 * Plugin Name: WooCommerce DECIDIR 1.0 Gateway
 
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

                  'uselogmode' => array(
                    'title'       => 'Use Log Mode',
                    'label'       => 'Enable Log Mode',
                    'type'        => 'checkbox',
                    'description' => 'Use Log mode for debug.',
                    'default'     => 'yes',
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
                            
                       ), // ,   
                      'desc_tip'    => true,
                      ),

                'option_name' => array(
                 'title' => 'Tarjetas Habilitadas',
                 'description' => 'Ctrl + Click para habilitar la tarjeta',
                 'type' => 'multiselect',
                 'options' => array(
                       
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
             $_SESSION['cybersource'] = $this->settings['usecybersource'];


            
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

          //console.log(useCS);

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
              $useCybersource="false";     
              $order = wc_get_order( $order_id );
             // echo $this->settings['usecybersource']."-----".$this->publishable_key;
            
              $keys_data = array('public_key' => $this->publishable_key, 'private_key' => $this->private_key);

              if($this->settings['testmode'] == 'no'){
                 $ambient = "prod"; 
              } else {
                 $ambient = "test"; 
              }


              if(is_user_logged_in()){ 

                            $current_user = wp_get_current_user();
                            $now = time(); 
                            $your_date = strtotime($current_user->user_registered);
                            $datediff = $now - $your_date;
                            $csidayinsite=round(($datediff / (60 * 60 * 24)));
                            $csuserid=$current_user->user_nicename."--".$current_user->ID;
                            $csipass=$current_user->user_pass;
                            $csiguest=false;


                        }else{
                            $csidayinsite=0;
                            $csuserid="guest-user";
                            $csipass="password";
                            $csiguest=true; 
                        }

              $service = "SDK-PHP"; 
              $developer ="IURCO - Prisma SA";
              $grouper = "WC-Gateway-DECIDIR";
              $cs_city =$_POST['billing_city'];
              $cs_country=$_POST['billing_country'];
              $cs_address=$_POST['billing_city'];
              $cs_postal_code=$_POST['billing_postcode'];
              $cs_state=$_POST['billing_state'];
              $cs_first_name=$_POST['billing_first_name'];
              $cs_last_name=$_POST['billing_last_name'];
              $cs_street1=$_POST['billing_address_1'];
              $cs_street2=$_POST['billing_address_2'];
              $cs_phone=$_POST['billing_phone'];
              $cs_email=$_POST['billing_email'];

              $connector = new \Decidir\Connector($keys_data, $ambient, $service, $developer , $grouper);      


              $order = wc_get_order($order_id);

                
                foreach ($order->get_items() as $item_key => $item ):

                    $item_id = $item->get_id();
                    $item_name    = $item->get_name();
                    $item_description    = $item->get_name();
                    $line_total        = $item->get_total();
                    $quantity     = $item->get_quantity(); 
                    $product        = $item->get_product();  
                    $product_type   = $product->get_type();
                    $product_sku    = $product->get_sku();
                    $product_price  = $product->get_price();
                    $stock_quantity = $product->get_stock_quantity();
                    if ($product_sku =="") {
                        $product_sku=$item_id."--".$item_name;
                    }


                     $items[] = [
                        "csitproductcode" => $product_sku , 
                        "csitproductdescription" => $item_name, 
                        "csitproductname" => $item_description,  
                        "csitproductsku" => $product_sku,
                        "csittotalamount" => $product_price*$quantity, 
                        "csitquantity" => $quantity,
                        "csitunitprice" => $product_price   
                        ];

    
                        endforeach; 

           

        
         if ( $this->settings['usecybersource'] =="yes") {
              $useCybersource="true";  
              $cs_data = array(
                    "send_to_cs" => true,
                    "channel" => "Web",
                    "bill_to" => array(
                      "city" => $cs_city,
                      "country" => $cs_country,
                      "customer_id" => $csuserid,
                      "email" =>  $cs_email,
                      "first_name" => $cs_first_name,
                      "last_name" => $cs_last_name,
                      "phone_number" =>  $cs_phone,
                      "postal_code" =>$cs_postal_code,
                      "state" => $cs_state,
                      "street1" => $cs_street1,
                      "street2" => $cs_street2,
                    ),
                    "ship_to" => array(
                      "city" => $cs_city,
                      "country" => $cs_country,
                      "customer_id" => $csuserid,
                      "email" =>  $cs_email,
                      "first_name" => $cs_first_name,
                      "last_name" => $cs_last_name,
                      "phone_number" =>  $cs_phone,
                      "postal_code" =>$cs_postal_code,
                      "state" => $cs_state,
                      "street1" => $cs_street1,
                      "street2" => $cs_street2,
                    ),
                    "currency" => "ARS",
                    "amount" => (int)$dec_Amount,
                    "days_in_site" => $csidayinsite,
                    "is_guest" => $csipass,
                    "password" => $csipass,
                    "num_of_transactions" => $order_id,
                    "cellphone_number" =>$cs_phone,                    
                    "street" => $cs_street1,
                  
                  );

           
              $cs_products = $items ;   

            


             $cybersource = new Decidir\Cybersource\Retail(
                                $cs_data,  // Datos de la operación
                                $cs_products, // Datos de los productos
              );

            


            $connector->payment()->setCybersource($cybersource->getData());
            
            }     


              $dec_total=$order->get_total();
              $dec_customer=$order->get_customer_id();
             
            
              $decidir_MerchOrderIdnewdate = date("his");

              $site_transaction_id = $order_id."-".$decidir_MerchOrderIdnewdate;
              $dec_Amount = preg_replace( '#[^\d.]#', '', $dec_total );
              $amount = str_replace('.', '', $dec_Amount);  

              $newdate = date("Y-m-d H:i:s");
              $dec_MerchTxRef =  $dec_customer.'-'. $decidir_MerchOrderIdnewdate;
              $dec_CardFirstName = $_POST['decidir_gateway-card-first-name'];
              $dec_CardLastName = $_POST['decidir_gateway-card-last-name'];
              $dec_Product = $_POST['decidir_gateway-card-tipo'];
              $dec_CardNumber = str_replace(' ', '', $_POST['decidir_gateway-card-number']);
              $data = $_POST['decidir_gateway-card-expiry'];
              $year = substr($data, strpos($data, "/") + 1);
              $month = str_split($data, 2); 
              $dec_CardExpDate = $year . $month[0];
              $dec_CardExpDate = str_replace(' ', '', $dec_CardExpDate);  
              $dec_CardSecurityCode = str_replace(' ', '', $_POST['decidir_gateway-card-cvc']);
              $dec_CustomerMail = $_POST['billing_email'];
              $dec_NumPayments = str_replace(' ', '', $_POST['decidir-cuotas']);   
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

                    "amount" =>(int)$dec_Amount,

                    "currency" => "ARS",
                    "installments" => (int)$dec_NumPayments,
                    "description" => $this->settings['description'],
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

                $responsedecidir=json_encode($response);

              
                


                $status = $response->getStatus();

                 /* Add log  */

                $filename = "Log-WC-gateway.log";
        
                //create a file pointer
                $dir=plugin_dir_path( __FILE__ )."/log/" ;
                $file = fopen($dir.$filename, 'a');
                fwrite($file, "Order -> ".$order_id."-".$newdate.PHP_EOL);
                 
                fwrite($file, "Data CS" . PHP_EOL);
                fwrite($file, json_encode($cs_data) . PHP_EOL);
                fwrite($file, "Produsct CS" . PHP_EOL);
                fwrite($file, json_encode($cs_products) . PHP_EOL);
                fwrite($file, "Data Payment" . PHP_EOL);
                fwrite($file, json_encode($data ). PHP_EOL);
                fwrite($file, "Response Decidir" . PHP_EOL);
                fwrite($file, "Hola".$responsedecidir . PHP_EOL);

                fclose($file);


                /* End log */ 


                if($status == 'approved'){
                  
                  $json = json_encode($response);
                  update_post_meta($order_id, 'decidir_response',$json );
                  
                  $order->update_status( 'processing', __( 'TRANSACCION ID: ' . $response->getId(), 'wc-gateway-decidir' ) );       
                  $order->add_order_note(
                    sprintf(
                      "Detalle pago: '%s'", $response->getId() . ' - ' . $status
                    )
                  );    
         
                 
                 // $order->reduce_order_stock_levels();
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

