<?php if( osc_get_preference('allow_shop', 'shop')==1) { ?>
<h2><?php _e("Item options", 'shop');?></h2>
<div class="form-group">
<?php CKTForm::show_paypal_checkbox(); ?><label for="paypal"><?php _e('Tick For Buy It Now', 'flatter'); ?> <abbr title="<em>Paypal Address</em><p>Your PayPal address is required if you want paying for your listing.<br /><i>Enter the address below</i></p>" rel="tooltip"><i class="fa fa-question-circle fa-lg"></i></abbr> </label>
<div class="controls"><?php CKTForm::paypal_input_text(); ?></div></div>
<label for="shop_amount"><?php _e('Amount of items', 'shop'); ?></label>
<div class="form-group">
<div class="controls"><?php CKTForm::shop_amount_input_text(); ?></div>
<?php  if(osc_get_preference('paypal_enabled', 'shop')==1) { ?><div class="controls">
<label><?php CKTForm::accept_paypal_checkbox(); ?><?php _e('Accept Paypal', 'shop'); ?></label></div><?php }?>
<?php  if(osc_get_preference('bank_details', 'shop')==1) { ?><div class="controls">
<label><?php CKTForm::accept_bank_transfer_checkbox(); ?><?php _e('Accept Bank Transfers', 'shop'); ?></label></div><?php }?>
</div>
<?php }?>