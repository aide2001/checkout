<?php
/*
Plugin Name: Shop
Plugin URI: http://www.osclass.org/
Description: This plugin transforms your OSClass into a shop!
Version: 1.0.1
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: shop
Plugin update URI: shop
*/

    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';
    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'ModelShop.php';
    

    function shop_install() {
        ModelShop::newInstance()->install();
    }

    function shop_uninstall() {
        ModelShop::newInstance()->uninstall();
    }

    function shop_admin_menu() {
        osc_add_admin_submenu_divider('plugins', 'Checkout plugin', 'shop_divider', 'administrator');
        osc_admin_menu_plugins('Checkout / Shop', osc_admin_render_plugin_url('shop/admin/conf.php'), 'shop');

    }

    function shop_user_menu() {
         echo '<li class="opt_shop" ><a href="'.osc_route_url('shop-seller').'" >'.__("Sold Items", "shop").'</a></li>' ;
          echo '<li class="opt_shop" ><a href="'.osc_route_url('shop-buyer').'" >'.__("Your Purchases Items", "shop").'</a></li>' ;
     }

    function shop_update_version() {
        ModelShop::newInstance()->versionUpdate();
    }

    
    function shop_configure_link() {
        osc_plugin_configure_view(osc_plugin_path(__FILE__) );
    }
    
    function shop_item_detail() {
      $conn = getConnection();
      $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item WHERE pk_i_id = %d", DB_TABLE_PREFIX, osc_item_id());
      require_once 'item_detail.php';
    }



    function shop_form($catId = null) {
     $detail['i_amount'] = 1;
        include_once 'item_edit.php';
    }

function shop_item_edit($catId = null, $item_id = null) {
$conn = getConnection() ;
$detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item WHERE pk_i_id = %d", DB_TABLE_PREFIX, $item_id);
if(isset($detail['pk_i_id'])) {
include_once 'item_edit.php';
}  
}

class CKTForm extends Form {

public static function show_paypal_checkbox($item = null) {
            if($item==null) { $item = osc_item(); };
            if( Session::newInstance()->_getForm('showPaypal') != 0) {
               $item['b_show_paypal'] = Session::newInstance()->_getForm('showPaypal');
           }
            parent::generic_input_checkbox('showPaypal', '1', (isset($item['b_show_paypal']) ) ? $item['b_show_paypal'] : false );
 return true;
}
public static function paypal_input_text($item = null) {
            if($item==null) { $item = osc_item(); };
            if( Session::newInstance()->_getForm('addressPaypal') != "" ) {
                $item['s_paypal'] = Session::newInstance()->_getForm('addressPaypal');
           }
            parent::generic_input_text('addressPaypal', (isset($item['s_paypal'])) ? $item['s_paypal'] : null);
 return true;
}
public static function accept_paypal_checkbox($item = null) {
            if($item==null) { $item = osc_item(); };
            if( Session::newInstance()->_getForm('acceptPaypal') != 0) {
                $item['b_accept_paypal'] = Session::newInstance()->_getForm('acceptPaypal');
           }
            parent::generic_input_checkbox('acceptPaypal', '1', (isset($item['b_accept_paypal']) ) ? $item['b_accept_paypal'] : false );
 return true;
}
public static function accept_bank_transfer_checkbox($item = null) {
             if($item==null) { $item = osc_item(); };
             if( Session::newInstance()->_getForm('acceptBankTransfer') != 0) {
               $item['b_accept_bank_transfer'] = Session::newInstance()->_getForm('acceptBankTransfer');
           }
            parent::generic_input_checkbox('acceptBankTransfer', '1', (isset($item['b_accept_bank_transfer']) ) ? $item['b_accept_bank_transfer'] : false );
return true;
}
public static function shop_amount_input_text($item = null) {
             if($item==null) { $item = osc_item(); };
             if( Session::newInstance()->_getForm('shopamount') != "" ) {
                $item['i_amount'] = Session::newInstance()->_getForm('shopamount');
           }
            parent::generic_input_text('shopamount', (isset($item['i_amount'])) ? $item['i_amount'] : null);
return true;
        }
}
   
    /**
     * ADD ROUTES (VERSION 3.2+)
     */
    osc_add_route('shop-admin-conf', 'shop/admin/conf', 'shop/admin/conf', osc_plugin_folder(__FILE__).'admin/conf.php');
    osc_add_route('shop-seller', 'shop/seller', 'shop/seller', osc_plugin_folder(__FILE__).'menu_seller.php', true);
    osc_add_route('shop-buyer', 'shop/buyer', 'shop/buyer', osc_plugin_folder(__FILE__).'menu_buyer.php', true);
//  osc_add_route('shop-selling', 'shop/selling', 'shop/selling', osc_plugin_folder(__FILE__).'/selling.php', true);
    osc_add_route('shop-cart', 'shop/checkout/cart', 'shop/checkout/cart', osc_plugin_folder(__FILE__).'/checkout/cart.php', true);
    osc_add_route('shop-return', 'shop/checkout/return', 'shop/checkout/return', osc_plugin_folder(__FILE__).'/checkout/return.php', true);
    osc_add_route('shop-cancel', 'shop/checkout/cancel', 'shop/checkout/cancel', osc_plugin_folder(__FILE__).'/checkout/cancel.php', true);
    
    /**
     * ADD HOOKS
     */
    osc_register_plugin(osc_plugin_path(__FILE__), 'shop_install');
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'shop_uninstall');
    osc_add_hook('item_detail', 'shop_item_detail');
    osc_add_hook('item_form', 'shop_form');
    osc_add_hook('item_edit', 'shop_item_edit');
   // osc_add_hook('posted_item', 'shop_posted_item');
    osc_add_hook('edited_item', 'shop_edited_item');
    osc_add_hook('delete_item', 'shop_delete_item');
    osc_add_hook('user_menu', 'shop_user_menu');
    osc_add_hook('cron_hourly', 'shop_calculate_scores');
    osc_add_hook('user_register_completed', 'shop_new_user');
    osc_add_hook('item_form_post', 'shop_new_item');
    osc_add_hook('admin_menu_init', 'shop_admin_menu');
      
?>