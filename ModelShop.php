<?php if (!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');
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

   /**
     * Model database for shop classes
     *
     * @package OSClass
     * @subpackage Model
     * @since 3.0
     */
    class ModelShop extends DAO
    {
        /**
         * It references to self object: ModelShop.
         * It is used as a singleton
         *
         * @access private
         * @since 3.0
         * @var ModelShop
         */
        private static $instance ;

        /**
         * It creates a new ModelShop object class if it has been created
         * before, it returns the previous object
         *
         * @access public
         * @since 3.0
         * @return ModelShop
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_item table
         */
        function __construct()
        {
            parent::__construct();
            
        }

        public function getTable_log()        {            return DB_TABLE_PREFIX.'t_shop_log';        }
        public function getTable_transactions()        {            return DB_TABLE_PREFIX.'t_shop_transactions';        }
        public function getTable_paypal_log()        {            return DB_TABLE_PREFIX.'t_shop_paypal_log';        }
        public function getTable_favs()        {            return DB_TABLE_PREFIX.'t_shop_favs';        }
        public function getTable_message()        {            return DB_TABLE_PREFIX.'t_shop_message';        }
        public function getTable_items()        {            return DB_TABLE_PREFIX.'t_item';        }
       
       /**
	 * Import sql file
	 * @param type $file 
	 */
	public function import($file) {
		$path = osc_plugin_resource($file) ;
		$sql = file_get_contents($path);
		if(!$this->dao->importSQL($sql)) {
			throw new Exception($this->dao->getErrorLevel().' - '.$this->dao->getErrorDesc());
		}
	}
	
	/**
	 * Update checkout table if stuff not there 
	 * @return boolean
	 */
	public function updateTable_Item() {
		$columns_exists = $this->getColumns_Item();
		if ($columns_exists) {
			return '';
		} else {
			$this->import('shop/struct.sql');
			 osc_set_preference('version', '100', 'shop', 'INTEGER');
                         osc_set_preference('allow_shop', '1', 'shop', 'BOOLEAN');
                         osc_set_preference('paypal_enabled', '1', 'shop', 'BOOLEAN');
                         osc_set_preference('paypal_sandbox', '0', 'shop', 'BOOLEAN');
                         osc_set_preference('paypal_standard', '1', 'shop', 'BOOLEAN');
                         osc_set_preference('bank_details', '1', 'shop', 'BOOLEAN');
		}		
	}

        public function install() {

            $this->import('shop/struct.sql');

            osc_set_preference('version', '100', 'shop', 'INTEGER');
            osc_set_preference('allow_shop', '1', 'shop', 'BOOLEAN');
            osc_set_preference('paypal_enabled', '1', 'shop', 'BOOLEAN');
            osc_set_preference('paypalsandbox', '0', 'shop', 'BOOLEAN');
            osc_set_preference('paypalstandard', '1', 'shop', 'BOOLEAN');
            osc_set_preference('bank_details', '1', 'shop', 'BOOLEAN');
           
            $description[osc_language()]['s_title'] = '{WEB_TITLE} - Congratulations! You just bought {ITEM_TITLE} ({TXN_CODE})';
            $description[osc_language()]['s_text'] = '<p>Hi {CONTACT_NAME}!</p>\r\n<p> </p>\r\n<p>You just bought ({ITEM_TITLE}) on {WEB_TITLE} for {PRICE} (transaction #ID: {TXN_CODE}).</p>\r\n<p> You need to pay for it, follow these instructions : {INSTRUCTIONS}</p>\r\n<p>Thanks</p>';
            $res = Page::newInstance()->insert(array('s_internal_name' => 'email_shop_sold_buyer', 'b_indelible' => '1'),$description);
            $description[osc_language()]['s_title'] = '{WEB_TITLE} - Your item {ITEM_TITLE} has been sold';
            $description[osc_language()]['s_text'] = '<p>Hi {CONTACT_NAME}!</p>\r\n<p> </p>\r\n<p>We just sold your item ({ITEM_TITLE}) on {WEB_TITLE} to {BUYER_NAME}.</p>\r\n<p>Instructions have been sent to buyer, please wait until the buyer pay for it to continue the process </p>\r\n<p>Thanks</p>';
            $res = Page::newInstance()->insert(array('s_internal_name' => 'email_shop_sold_seller', 'b_indelible' => '1'), $description);
            $description[osc_language()]['s_title'] = '{WEB_TITLE} - Someone has a question';
            $description[osc_language()]['s_text'] = '<p>Hi {CONTACT_NAME}!</p>\n<p>{USER_NAME} ({USER_EMAIL}, {USER_PHONE}) left you a message as follows:</p>\n<p>{COMMENT}</p>\n<p>Regards,</p>\n<p>{WEB_TITLE}</p>';
            $res = Page::newInstance()->insert(array('s_internal_name' => 'email_shop_contact', 'b_indelible' => '1'),$description);
       }

        /**
         * Remove data and tables related to the plugin.
         */
        public function uninstall()

        {
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_log()) ) ;
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_transactions()) ) ;
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_paypal_log()) ) ;
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_favs()) ) ;
            $this->dao->query(sprintf('DROP TABLE %s', $this->getTable_message()) ) ;
            $page = Page::newInstance()->findByInternalName('email_shop_sold_buyer');
            Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);
            $page = Page::newInstance()->findByInternalName('email_shop_sold_seller');
            Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);
            $page = Page::newInstance()->findByInternalName('email_shop_contact');
            Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);
            osc_delete_preference('version', 'shop');
            osc_delete_preference('allow_shop', 'shop');  
            osc_delete_preference('paypal_enabled','shop');
            osc_delete_preference('paypal_sandbox','shop');
            osc_delete_preference('paypal_standard','shop');
            osc_delete_preference('bank_details',  'shop');


        }  

function shop_send_sold_email($code) {
        
        $conn = getConnection();
        $txn = $conn->osc_dbFetchResult("SELECT * FROM %st_shop_transactions WHERE pk_i_id = %d", DB_TABLE_PREFIX, $code);
        $item = Item::newInstance()->findByPrimaryKey($txn['pk_i_id']);
        View::newInstance()->_exportVariableToView('item', $item);
      //  $seller = User::newInstance()->findByPrimaryKey($code['fk_i_user_id']);
      //  $buyer = User::newInstance()->findByPrimaryKey($code['fk_i_buyer_id']);
        
        $shop_item = $conn->osc_dbFetchResult("SELECT * FROM %st_item WHERE pk_i_id = %d", DB_TABLE_PREFIX, $txn['pk_i_id']);
        
        $item_url = osc_item_url();
        $item_url = '<a href="'.$item_url.'" >'.$item_url.'</a>';
        $from = osc_contact_email() ;
        $from_name = osc_page_title() ;

}

public function getPayment($paymentId) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_log());
            $this->dao->where('pk_i_id', $paymentId);
            $result = $this->dao->get();
            if($result) {
                return $result->row();
            }
            return false;
        }

/**
         * Create a record on the DB for the paypal transaction
         *
         * @param string $concept
         * @param string $code
         * @param float $amount
         * @param string $currency
         * @param string $email
         * @param integer $user
         * @param integer $item
         * @param string $product_type (publish fee, premium, pack and which category)
         * @param string $source
         * @return integer $last_id
         */
public static function saveLog($paid,$concept,$code, $amount, $currency, $email) {
               $this->dao->insert($this->getTable_paypal_log(), array(
                
                'b_paid' => '1',
                's_concept' => $concept,
                'dt_date' => date("Y-m-d H:i:s"),
                's_code' => Params::getParam('return_code'),
                'f_amount' => $amount*1000000000000,
                's_currency_code' => $currency,
                's_email' => $email
               
                ));
            return $this->dao->insertedId();
        }

public static function standardButton($amount = '',$code = 'return_code',$extra_array = null) {
      if(osc_get_preference('paypal_standard', 'shop')==1) {
      
            $extra = payment_prepare_custom($extra_array);
            $r = rand(0,1000);
            $extra .= 'random,'.$r;

            $rpl = osc_item_id()."|".ckt_item_amount (osc_item_id())."|".osc_item_price()."|".osc_item_currency()."|".$r;  

            if(osc_get_preference('paypal_sandbox', 'shop')==1) {
                $ENDPOINT     = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $ENDPOINT     = 'https://www.paypal.com/cgi-bin/webscr';
            }
            $code = strtoupper(osc_genRandomPassword(12));
      
           
            $NOTIFYURL = osc_base_url() . 'oc-content/plugins/' . osc_plugin_folder(__FILE__) . 'notify_url.php?rpl=' . $rpl; ?>

<form class="nocsrf" action="<?php echo $ENDPOINT; ?>" method="post" id="paypal_<?php echo $r; ?>">
                  <input type="hidden" name="cmd" value="_xclick" />
                  <input type="hidden" name="notify_url" value="<?php echo $NOTIFYURL; ?>" />
                  <input type="hidden" name="amount" value="<?php echo ckt_paypal_price();?>" />
                  <input type="hidden" name="item_name" value="<?php echo osc_item_title(); ?>" />
                  <input type="hidden" name="item_number" value="<?php echo osc_item_id();?>" />
                  <input type="hidden" name="quantity" value="<?php echo ckt_item_amount (osc_item_id());?>" />
                  <input type="hidden" name="currency_code" value="<?php echo osc_get_preference('currency', 'payment'); ?>" />
                  <input type="hidden" name="custom" value="<?php echo $extra; ?>" />
                  <input type="hidden" name="return" value="<?php echo osc_route_url('shop-return'); ?>/<?php echo $extra;?>" />
                  <input type="hidden" name="rm" value="2" />
                  <input type="hidden" name="item_seller" value="<?php echo osc_item_user_id();?>" />
                  <input type="hidden" name="item_buyer" value="<?php echo  osc_logged_user_id();?>" />
                  <input type="hidden" name="cancel_return" value="<?php echo osc_route_url('shop-cancel'); ?>" />
                  <input type="hidden" name="business" value="<?php echo ckt_paypaladdress (osc_item_id());?>" />
                  <input type="hidden" name="upload" value="1" />

                  <input type="hidden" name="return_code" value="<?php echo $code; ?>" />
                  <input type="hidden" name="no_note" value="1" />
                  <input type="hidden" name="charset" value="utf-8" />
                </form>
                
<a id="button-confirm" class="btn btn-success" onclick="$('#paypal_<?php echo $r; ?>').submit();">Buy now using PayPal</a>
<hr />
<?php }
}


        

public function PaypalAccept($itemId) {
            $this->dao->select('*') ; 
            $this->dao->from($this->getTable_items());
            $this->dao->where('pk_i_id', $itemId);
            $result = $this->dao->get();
            $row = $result->row();
            if($row) {
                if($row['b_accept_paypal']==1) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }

public function BankAccept($itemId) {
            $this->dao->select('*') ; 
            $this->dao->from($this->getTable_items());
            $this->dao->where('pk_i_id', $itemId);
            $result = $this->dao->get();
            $row = $result->row();
            if($row) {
                if($row['b_accept_bank_transfer']==1) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }

public function ItemIsPaid($itemId) {
            $this->dao->select('*') ;
            $this->dao->from($this->getTable_paypal_log());
            $this->dao->where('fk_i_item_id', $itemId);
            $result = $this->dao->get();
            $row = $result->row();
            if($row) {
                if($row['b_paid']==1) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }
    }
?>