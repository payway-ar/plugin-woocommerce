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
   <div class="admin__fieldset-wrapper-content _hide" data-bind="css: {'admin__collapsible-content': collapsible, '_show': opened, '_hide': !opened()}">
        <!-- ko if: opened() || _wasOpened || initializeFieldsetDataByDefault --><fieldset class="admin__fieldset" data-bind="foreach: {data: elems, as: 'element'}"><!-- ko template: getTemplate() -->
<div class="admin__field" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="entity_id" style="display: none;">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
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
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="is_active">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="A8LMUWN">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Enable Promotion</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
<div class="admin__actions-switch" data-role="switcher">
    <input type="checkbox" class="admin__actions-switch-checkbox" data-bind="attr: {id: uid, name: inputName}, value: value, disable: disabled, hasFocus: focused, simpleChecked: checked" id="A8LMUWN" name="is_active" value="1">
    <label class="admin__actions-switch-label" data-bind="attr: {for: uid}" for="A8LMUWN">
        <span class="admin__actions-switch-text" data-bind="attr: {'data-text-on': toggleLabels.on, 'data-text-off': toggleLabels.off}" data-text-on="Yes" data-text-off="No"></span>
    </label>
</div>
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="rule_name">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="GKLOLA9">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Rule Name</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
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
    }" name="rule_name" aria-describedby="notice-GKLOLA9" id="GKLOLA9" maxlength="255">
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="card_id">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="D8IY3UQ">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Applicable Cards</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
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
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="from_date">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="PNL6KI0">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Start Date</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
<input class="admin__control-text _has-datepicker" type="text" data-bind="
    hasFocus: focused,
    datepicker: { storage: shiftedValue, options: options },
    valueUpdate: valueUpdate,
    attr: {
        value: shiftedValue,
        name: inputName,
        placeholder: placeholder,
        'aria-describedby': noticeId,
        disabled: disabled
    }" value="" name="from_date" aria-describedby="notice-PNL6KI0" id="dp1632775780058" autocomplete="on"><button type="button" class="ui-datepicker-trigger v-middle"><span>Select Date</span></button>
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="bank_id">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="V61SWQG">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Applicable Banks</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
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
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="priority">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="O3WD70A">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Priority</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
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
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="to_date">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="S7KSOCW">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">End Date</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
<input class="admin__control-text _has-datepicker" type="text" data-bind="
    hasFocus: focused,
    datepicker: { storage: shiftedValue, options: options },
    valueUpdate: valueUpdate,
    attr: {
        value: shiftedValue,
        name: inputName,
        placeholder: placeholder,
        'aria-describedby': noticeId,
        disabled: disabled
    }" value="" name="to_date" aria-describedby="notice-S7KSOCW" id="dp1632775780059" autocomplete="on"><button type="button" class="ui-datepicker-trigger v-middle"><span>Select Date</span></button>
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="applicable_days">
    <div class="admin__field-label" data-bind="visible: $data.labelVisible">
        <!-- ko if: $data.label --><label data-bind="attr: {for: uid}" for="DYS6XG6">
            <span data-bind="attr: {'data-config-scope': $data.scopeLabel}, i18n: label">Applicable Days</span>
        </label><!-- /ko -->
    </div>
    <div class="admin__field-control" data-bind="css: {'_with-tooltip': $data.tooltip, '_with-reset': $data.showFallbackReset &amp;&amp; $data.isDifferedFromDefault}">
        <!-- ko ifnot: hasAddons() --><!-- ko template: elementTmpl -->
<select multiple="" class="admin__control-multiselect" data-bind="
    attr: {
        name: inputName,
        id: uid,
        size: size ? size : '6',
        disabled: disabled,
        'aria-describedby': noticeId,
        placeholder: placeholder
    },
    hasFocus: focused,
    optgroup: options,
    selectedOptions: value,
    optionsValue: 'value',
    optionsText: 'label'" name="applicable_days" id="DYS6XG6" size="6" aria-describedby="notice-DYS6XG6"><option data-title="Sunday" value="0">Sunday</option><option data-title="Monday" value="1">Monday</option><option data-title="Tuesday" value="2">Tuesday</option><option data-title="Wednesday" value="3">Wednesday</option><option data-title="Thursday" value="4">Thursday</option><option data-title="Friday" value="5">Friday</option><option data-title="Saturday" value="6">Saturday</option></select>
<!-- /ko --><!-- /ko -->

        <!-- ko if: hasAddons() --><!-- /ko -->

        <!-- ko if: $data.tooltip --><!-- /ko -->

        <!-- ko if: $data.showFallbackReset && $data.isDifferedFromDefault --><!-- /ko -->

        <!-- ko if: error --><!-- /ko -->

        <!-- ko if: $data.notice --><!-- /ko -->

        <!-- ko if: $data.additionalInfo --><!-- /ko -->

        <!-- ko if: $data.hasService() --><!-- /ko -->
    </div>
</div>
<!-- /ko --><!-- ko template: getTemplate() -->
<div class="admin__field _required" data-bind="css: $data.additionalClasses, attr: {'data-index': index}, visible: visible" data-index="applicable_stores">
     
</div>
<!-- /ko --></fieldset><!-- /ko -->
    </div>
    <?php
}