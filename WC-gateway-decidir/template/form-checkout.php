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
              <input type="hidden" id="device_unique_identifier" value="123456" />
              <input type="hidden" id="ip_address" value="<?php echo  $_SERVER['REMOTE_ADDR']; ?>" />
               

              <input type="hidden" id="result_decidir"/>   
              <div class="clear"></div>
            </fieldset>
        
            <input type="hidden" id="total_result_decidir" value="<?php echo WC()->cart->cart_contents_total ; ?>" />
            <input type="hidden" id="result_decidir"/>
</decidir_form>