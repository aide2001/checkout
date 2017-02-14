<div class="filters"><h3><strong><?php _e('Buying Options', 'shop'); ?></strong></h3></div>
<div class="box"><div class="box dg_files"><form method="POST" action="<?php echo osc_route_url('shop-cart'); ?>">
<input type="hidden" name="item_id" value="<?php echo osc_item_id(); ?>" /><input type="hidden" name="page" value="custom" /><div>
<?php if(isset($detail) && isset($detail['i_amount']) && $detail['i_amount']>0) { if($detail['i_amount']>1) { ?>
<input type="text" name="shop_amount" value="1" onKeyPress="return numbersonly(this, event)"/> <?php echo sprintf(__('of %d items available for this product', 'shop'), $detail['i_amount'])?>
<?php } ?>
<?php if(isset($detail) && isset($detail['b_accept_paypal']) && $detail['b_accept_paypal']==1) { ?>
<div><?php _e('The seller accepts Paypal as payment for this item', 'shop'); ?></div><br /><?php }; ?>
<?php if(isset($detail) && isset($detail['b_accept_bank_transfer']) && $detail['b_accept_bank_transfer']==1) { ?>
<div><?php _e('The seller accepts bank transfers as payment for this item', 'shop'); ?></div><br /><?php }; ?>
<?php if (!osc_is_web_user_logged_in() ) { ?><strong><a class="btn btn-success" href="<?php echo osc_user_login_url(); ?>">
<?php _e('Login to add to basket', 'shop'); ?></a>
<?php } else { ?><input type="submit" class="btn btn-success" value="<?php _e('Add to basket', 'shop')?>" />
<?php }?><strong>&nbsp;<a class="btn btn-primary" href="<?php echo osc_user_public_profile_url( osc_item_user_id() ); ?>"><?php _e("Sellers Shop", "shop"); ?></a>
</strong><?php } else { ?><strong><?php _e('Sorry Item Now Sold', 'shop'); ?></strong><?php }; ?></div></form></div></div><hr />
<?php if(isset($detail) && isset($detail['i_amount']) && $detail['i_amount']>0) { if($detail['i_amount']>1) { ?><?php }?>
<?php if( osc_item_show_paypal() ){ ?>
<p>Or buy without storing this transaction with DrawAway below.</p>
<div class="email">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="form_paypal">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php echo osc_item_field("s_paypal"); ?>">
        <input type="hidden" name="receiver_email" value="<?php echo osc_item_field("s_paypal"); ?>">
        <input type="hidden" name="amount" value="<?php echo ckt_paypal_price(osc_item_id());?>">
        <input type="hidden" name="currency_code" value="GBP">
        <input type="hidden" name="step" value="done" />
        <input type="hidden" name="return" value="http://www.drawaway.co.uk/info/success">
        <input type="hidden" name="cancel_return" value="http://www.drawaway.co.uk/">
        <input type="hidden" name="item_name" value="<?php echo osc_item_title(); ?>">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="image" src="/images/paypal-button.png" name="submit" type="submit" value="Buy It Now" border="0">
    </form></div><?php }?><?php } else { ?> <?php }; ?>
<SCRIPT TYPE="text/javascript">
function numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("0123456789").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}

</SCRIPT>