<?php 
    $conn = getConnection();
    $items = $conn->osc_dbFetchResults('SELECT l.*, i.*, shopt.e_status, shopt.pk_i_id as txn_id FROM %st_item i, %st_item_location l, %st_shop_transactions shopt WHERE l.fk_i_item_id = i.pk_i_id AND shopt.fk_i_buyer_id = %d AND i.pk_i_id = shopt.fk_i_item_id ORDER BY i.pk_i_id DESC', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, osc_logged_user_id());
    $items = Item::newInstance()->extendData($items);
    $data = array();
    foreach($items as $item) {
        $data[] = '{"'.$item['fk_i_item_id'].'", "'.$item['s_title'].'", "'.$item['s_description'].'",  "'.$item['e_status'].'"}';
    };

?>



<script type="text/javascript" src="<?php echo osc_base_url().'oc-content/plugins/'.osc_plugin_folder(__FILE__).'jquery.dataTables.min.js' ; ?>"></script>
<link href="<?php echo osc_base_url().'oc-content/plugins/'.osc_plugin_folder(__FILE__).'demo_table.css' ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            $(function() {
                sSearchName = "<?php _e('Search'); ?>...";
                oTable = $('#datatables_list').dataTable({
                    "bAutoWidth": false,
                    "aaData": [
                        <?php foreach($items as $item){ ?>
                            [
                                "<?php echo $item['fk_i_item_id']; ?>",
                                "<?php echo $item['s_title']; ?>",
                                "<?php echo $item['e_status']; ?>",
                                "<?php 
                                    switch($item['e_status']) {
                                        case 'SOLD':
                                            echo '<a href=\"' . osc_render_file_url(osc_plugin_folder(__FILE__)."paying.php").'&item_id='.$item['fk_i_item_id']. '\" >' . __("Pay Now", "shop") . '</a>';
                                            echo ' '.__('or', 'shop').' ';
                                            echo '<a href=\'#\' >'.__('mark as paid', 'shop').'</a>';
                                            break;
                                        case 'PAID':
                                            echo _e('Seller has to post it', 'shop');
                                            break;
                                        case 'SHIPPED':
                                            echo '<a href=\''.osc_render_file_url(osc_plugin_folder(__FILE__)."vote.php").'&paction=vote_seller&txn_id='.$item['txn_id'].'\' >'.__('Vote transaction', 'shop').'</a>';
                                            break;
                                        case 'VOTE_BUYER':
                                            echo _e('Seller has to vote', 'shop');
                                            break;
                                        case 'ENDED':
                                            echo _e('No action needed', 'shop');
                                            break;
                                        default:
                                            break;
                                    }
                                ?>"
                            ] <?php echo $item != end($items) ? ',' : ''; ?>
                        <?php } ?>
                    ],
                   "aoColumns": [
                        {
                          "sTitle": "Id#","sClass": "center",
                        },
                        {
                           "sTitle": "Title" ,"sClass": "center"
                       },
                     
                        {
                            "sTitle": "Status","sClass": "center"
                       },
                        {
                            "sTitle": "Action","sClass": "center"
                        }
                   ],
                    "bPaginate": true,
                    "bFilter": false,
                    "bInfo": true
                });
            });
        </script>
            <div class="content user_account">
                <h1><strong><?php _e('User account manager', 'shop') ; ?></strong></h1>
                <div id="sidebar"><?php echo osc_private_user_menu() ; ?></div>
                <div id="main"><h3><?php _e('Items bought', 'shop'); ?></h3>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list" ></table></div></div>