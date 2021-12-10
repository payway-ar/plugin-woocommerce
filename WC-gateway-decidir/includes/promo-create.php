<?php

function prisma_promo_create() {
    $id = $_POST["id"];
    $name = $_POST["name"];
    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "promotion";

        $wpdb->insert(
                $table_name, //table
                array('id' => $id, 'name' => $name), //data
                array('%s', '%s') //data format			
        );
        $message.="School inserted";
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
   <div class="admin__fieldset-wrapper-content _hide">
       <fieldset class="admin__fieldset">
<div class="admin__field" style="display: none;">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
       
    </div>
    <div class="admin__field-control" >
      
<input class="admin__control-text" type="text" data-bind="
        event: {change: userChanges},
        value: value,
        hasFocus: focused,
        valueUpdate: valueUpdate,
        attr: {
            name: inputName,
            placeholder: placeholder,
            'aria-describedby': noticeId,
            id: uid,
            disabled: disabled,
            maxlength: 255
    }" name="entity_id" aria-describedby="notice-DQDC28K" id="DQDC28K" maxlength="255">
 
    </div>
</div>

<div class="admin__field" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="is_active">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="A8LMUWN">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Enable Promotion</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<div class="admin__actions-switch" data-role="switcher">
    <input type="checkbox" class="admin__actions-switch-checkbox" data-bind="attr: {id: uid, name: inputName}, value: value, disable: disabled, hasFocus: focused, simpleChecked: checked" id="A8LMUWN" name="is_active" value="1">
    <label class="admin__actions-switch-label" data-bind="attr: {for: uid}" for="A8LMUWN">
        <span class="admin__actions-switch-text" data-bind="attr: {'data-text-on': toggleLabels.on, 'data-text-off': toggleLabels.off}" data-text-on="Yes" data-text-off="No"></span>
    </label>
</div>

    </div>
</div>

<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="rule_name">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="GKLOLA9">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Rule Name</span>
        </label>
    </div>
    <div class="admin__field-control" >
       
<input class="admin__control-text" type="text"  maxlength="255">

    </div>
</div>

<div class="admin__field _required">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="D8IY3UQ">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Applicable Cards</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<select class="admin__control-select" data-bind="
    attr: {
        name: inputName,
        id: uid,
        disabled: disabled,
        'aria-describedby': noticeId
    },
    hasFocus: focused,
    optgroup: options,
    value: value,
    optionsCaption: caption,
    optionsValue: 'value',
    optionsText: 'label'" name="card_id" id="D8IY3UQ" aria-describedby="notice-D8IY3UQ"><option value="">Please Select a Card</option><option data-title="Visa" value="5">Visa</option><option data-title="Mastercard Prisma" value="6">Mastercard Prisma</option><option data-title="Amex Prisma" value="8">Amex Prisma</option></select>
</div>
</div>



<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="bank_id">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="V61SWQG">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Applicable Banks</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<select class="admin__control-select" data-bind="
    attr: {
        name: inputName,
        id: uid,
        disabled: disabled,
        'aria-describedby': noticeId
    },
    hasFocus: focused,
    optgroup: options,
    value: value,
    optionsCaption: caption,
    optionsValue: 'value',
    optionsText: 'label'" name="bank_id" id="V61SWQG" aria-describedby="notice-V61SWQG"><option value="">Please Select a Bank</option><option data-title="Banco Galicia" value="4">Banco Galicia</option><option data-title="Ahora 12" value="5">Ahora 12</option><option data-title="Banco Provincia" value="6">Banco Provincia</option><option data-title="Banco Frances" value="7">Banco Frances</option></select>


    </div>
</div>

<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="priority">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
       <label data-bind="attr: {for: uid}" for="O3WD70A">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Coeficiente</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<input class="admin__control-text" type="text" data-bind="
        event: {change: userChanges},
        value: value,
        hasFocus: focused,
        valueUpdate: valueUpdate,
        attr: {
            name: inputName,
            placeholder: placeholder,
            'aria-describedby': noticeId,
            id: uid,
            disabled: disabled,
            maxlength: 255
    }" name="priority" aria-describedby="notice-O3WD70A" id="O3WD70A" maxlength="255">


     
    </div>
</div>

<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="priority">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
       <label data-bind="attr: {for: uid}" for="O3WD70A">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Value</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<input class="admin__control-text" type="text" data-bind="
        event: {change: userChanges},
        value: value,
        hasFocus: focused,
        valueUpdate: valueUpdate,
        attr: {
            name: inputName,
            placeholder: placeholder,
            'aria-describedby': noticeId,
            id: uid,
            disabled: disabled,
            maxlength: 255
    }" name="priority" aria-describedby="notice-O3WD70A" id="O3WD70A" maxlength="255">


     
    </div>
</div>



<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="priority">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
       <label data-bind="attr: {for: uid}" for="O3WD70A">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Priority</span>
        </label>
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
     
<input class="admin__control-text" type="text" data-bind="
        event: {change: userChanges},
        value: value,
        hasFocus: focused,
        valueUpdate: valueUpdate,
        attr: {
            name: inputName,
            placeholder: placeholder,
            'aria-describedby': noticeId,
            id: uid,
            disabled: disabled,
            maxlength: 255
    }" name="priority" aria-describedby="notice-O3WD70A" id="O3WD70A" maxlength="255">


     
    </div>
</div>





 <input type='submit' name="insert" value='Save' class='button'>
</fieldset>
    </div>
    <?php
}