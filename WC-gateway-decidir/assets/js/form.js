
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