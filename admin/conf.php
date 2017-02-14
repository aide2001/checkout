<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    if(Params::getParam('plugin_action')=='done') {
        
        osc_set_preference('allow_shop', Params::getParam("allow_shop") ? Params::getParam("allow_shop") : '', 'shop', 'BOOLEAN');
        osc_set_preference('paypal_enabled', Params::getParam("paypal_enabled") ? Params::getParam("paypal_enabled") : '', 'shop', 'BOOLEAN');
        osc_set_preference('bank_details', Params::getParam("bank_details") ? Params::getParam("bank_details") : '', 'shop', 'BOOLEAN');
        osc_set_preference('paypal_sandbox', Params::getParam("paypal_sandbox") ? Params::getParam("paypal_sandbox") : '', 'shop', 'BOOLEAN');
        osc_set_preference('paypal_standard', Params::getParam("paypal_standard") ? Params::getParam("paypal_standard") : '', 'shop', 'BOOLEAN');

        // HACK : This will make possible use of the flash messages ;)
        ob_get_clean();
        osc_add_flash_ok_message(__('Congratulations, the plugin is now configured', 'shop'), 'admin');
        osc_redirect_to(osc_route_admin_url('shop-admin-conf'));
    }
?>

<script type="text/javascript" >
    $(document).ready(function(){
        $("#dialog-paypal").dialog({
            autoOpen: false,
            modal: true,
            width: '90%',
            title: '<?php echo osc_esc_js( __('Shop help', 'shop') ); ?>'
        });
        
    });
</script>

<div id="general-setting">
    <div id="general-settings">
        <h2 class="render-title"><?php _e('Checkout settings', 'shop'); ?></h2>
        <ul id="error_list"></ul>
        <form name="payment_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="plugins" />
            <input type="hidden" name="action" value="renderplugin" />
            <input type="hidden" name="route" value="shop-admin-conf" />
            <input type="hidden" name="plugin_action" value="done" />
            <fieldset>
                <div class="form-horizontal">
                    <div class="form-row">
                        <div class="form-label"><?php _e('Allow Shop', 'shop'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('allow_shop', 'shop') ? 'checked="true"' : ''); ?> name="allow_shop" value="1" />
                                    <?php _e('Show the shop - Shows how to pay on Cart - Show on Item Post and Edit', 'shop'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                   <hr />
                     
<h3 class="render-title separate-top"><?php _e('PayPal Sandbox', 'shop'); ?></h3>
<div class="form-row">
                        <div class="form-label"><?php _e('Use Sandbox Mode', 'shop'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('paypal_sandbox', 'shop') ? 'checked="true"' : ''); ?> name="paypal_sandbox" value="1" />
                                    <?php _e('Allow sandbox for testing purpose', 'shop'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
<div class="form-row">
                        <div class="form-label"><?php _e('Standard Paypal', 'shop'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('paypal_standard', 'shop') ? 'checked="true"' : ''); ?> name="paypal_standard" value="1" />
                                    <?php _e('Allow the user to use PayPal Standard Button', 'shop'); ?>
                                </label>
                            </div>
                        </div>
                    </div>

<hr />
<h3 class="render-title separate-top"><?php _e('Payment settings', 'shop'); ?><br />
<span><a class="btn btn-success" href="javascript:void(0);" onclick="$('#dialog-paypal').dialog('open');" ><?php _e('Main help', 'shop'); ?></a></span>&nbsp;
<span><a class="btn btn-success" href="javascript:void(0);" onclick="$('.paypal').toggle();" ><?php _e('Show options', 'shop'); ?></a></span></h3>
                    <div class="form-row paypal hide">
                        <div class="form-label"><?php _e('Enable Paypal'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('paypal_enabled', 'shop') ? 'checked="true"' : ''); ?> name="paypal_enabled" value="1" />
                                    <?php _e('Enable Paypal as a method of payment - Shows on Item Post and Edit', 'shop'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row paypal hide">
                        <div class="form-label"><?php _e('Enable Bank Details'); ?></div>
                        <div class="form-controls">
                            <div class="form-label-checkbox">
                                <label>
                                    <input type="checkbox" <?php echo (osc_get_preference('bank_details', 'shop') ? 'checked="true"' : ''); ?> name="bank_details" value="1" />
                                    <?php _e('Enable Bank Details as a method of payment - Shows on Item Post and Edit', 'shop'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
<div class="clear"></div>
                    <div class="form-actions">
                        <input type="submit" id="save_changes" value="<?php echo osc_esc_html( __('Save changes') ); ?>" class="btn btn-submit" />
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<form id="dialog-paypal" method="get" action="#" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row">
            <h3><?php _e('Allow PayPal for customers?', 'shop'); ?></h3>
            <p>Main Help in here...[TODO]</p>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-paypal').dialog('close');"><?php _e('Cancel'); ?></a>
            </div>
        </div>
    </div>
</form>
