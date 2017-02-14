<?php
  
    function shop_path() {
        return osc_base_path() . 'oc-content/plugins/' . osc_plugin_folder(__FILE__);
    }

    function shop_url() {
        return osc_base_url() . 'oc-content/plugins/' . osc_plugin_folder(__FILE__);
    }

    function shop_format_btc($btc, $symbol = "BTC") {
        if($btc<0.00001) {
            return ($btc*1000000).'µ'.$symbol;
        } else if($btc<0.01) {
            return ($btc*1000).'m'.$symbol;
        }
        return $btc.$symbol;
    }
  
    function ckt_item_amount($itemId)
         {
         $dao = new DAO();
            $dao->dao->select('*');
            $dao->dao->from(DB_TABLE_PREFIX.'t_item');
            $dao->dao->where('pk_i_id',$itemId);
            $result = $dao->dao->get();
            $detail = $result->result();
            $detail = $result->row();

$amount = min(Params::getParam('shop_amount')!=''?Params::getParam('shop_amount'):1, 
$detail['i_amount']);
if($amount<0) { $amount = 1; }
return $amount;

  }


function ckt_paypaladdress($itemId)
         {
         $dao = new DAO();
            $dao->dao->select('*');
            $dao->dao->from(DB_TABLE_PREFIX.'t_item');
            $dao->dao->where('pk_i_id',$itemId);

            $result = $dao->dao->get();

            $detail = $result->result();
            $detail = $result->row();


return $detail['s_paypal'];    
  }


/**
PAYPAL BUY NOW CURRENCY by ME
*/
function ckt_paypal_price() {
	$price=osc_item_field("i_price") ;

        $price = $price/1000000;
        $currencyFormat = osc_locale_currency_format();
	$currencyFormat = str_replace('{NUMBER}', number_format($price, osc_locale_num_dec(), osc_locale_dec_point(),        osc_locale_thousands_sep()), $currencyFormat);
	$currencyFormat = str_replace('{CURRENCY}', osc_premium_currency_symbol(), $currencyFormat);
	return osc_apply_filter('item_price', $currencyFormat );
	
}

    function shop_prepare_custom($extra_array = null) {
        if($extra_array!=null) {
            if(is_array($extra_array)) {
                $extra = '';
                foreach($extra_array as $k => $v) {
                    $extra .= $k.",".$v."|";
                }
            } else {
                $extra = $extra_array;
            }
        } else {
            $extra = "";
        }
        return $extra;
    }


    /**
     * Redirect to function via JS
     *
     * @param string $url
     */
    function shop_js_redirect_to($url) { ?>
        <script type="text/javascript">
            window.top.location.href = "<?php echo $url; ?>";
        </script>
    <?php }

    /**
     * Send email to buyer with payment options
     *
     * @param integer $code
     * @param float $item
     */
    function shop_send_sold_email($code) {

        if(osc_is_web_user_logged_in()) {
            return false;
        }

        $mPages = new Page() ;
        $aPage = $mPages->findByInternalName('email_shop_sold_buyer') ;
        $locale = osc_current_user_locale() ;
        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }
        $seller = User::newInstance()->findByPrimaryKey($code['fk_i_user_id']);
        $buyer = User::newInstance()->findByPrimaryKey($code['fk_i_buyer_id']);
        $item_url    = osc_item_url( ) ;
        $item_url    = '<a href="' . $item_url . '" >' . $item_url . '</a>';
        $from = osc_contact_email() ;
        $from_name = osc_page_title() ;
       
 

        $words   = array();
        $words[] = array('{CONTACT_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{USER_PHONE}',
                             '{WEB_URL}', '{ITEM_TITLE}','{ITEM_URL}', '{INSTRUCTIONS}','{PRICE}', '{TXN_CODE}');

        $words[] = array($item['s_contact_name'], $buyer['s_name'], $buyer['s_name'],
                         ($buyer['s_phone_land']==''?$buyer['s_phone_mobile']:$buyer['s_phone_land']), '<a href="'.osc_base_url().'" >'.osc_base_url().'</a>', $item['s_title'], $item_url, $instructions, $price, $code['s_code'] );

        $title = osc_mailBeauty(osc_apply_filter('email_title', osc_apply_filter('email_shop_sold_buyer_title', $content['s_title'])), $words);
        $body = osc_mailBeauty(osc_apply_filter('email_description', osc_apply_filter('email_shop_sold_buyer_description', $content['s_text'])), $words);

        $emailParams = array (
                            'from'      => $from
                            ,'from_name' => $from_name
                            ,'subject'   => $title
                            ,'to'        => $buyer['s_email']
                            ,'to_name'   => $buyer['s_name']
                            ,'body'      => $body
                            ,'alt_body'  => $body
                            ,'reply_to'  => $from
                        ) ;

        osc_sendMail($emailParams);
    }


?>