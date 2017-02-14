<?php 
    $conn = getConnection();
    $items = $conn->osc_dbFetchResults('SELECT l.*, i.*, shopt.e_status, shopt.pk_i_id as txn_id FROM %st_item i, %st_item_location l, %st_shop_transactions shopt WHERE l.fk_i_item_id = i.pk_i_id AND shopt.fk_i_user_id = %d AND i.pk_i_id = shopt.fk_i_item_id ORDER BY i.pk_i_id DESC', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, osc_logged_user_id());
    $items = Item::newInstance()->extendData($items);
    $data = array();
    foreach($items as $item) {
        $data[] = '{"'.$item['fk_i_item_id'].'", "'.$item['s_title'].'", "'.$item['s_description'].'", "'.$item['e_status'].'"}';
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
                                            echo _e('Buyer has to pay', 'shop');
                                            break;
                                        case 'PAID':
                                            echo '<a href=\'#\' >'.__('Mark as shipped', 'shop').'</a>';
                                            break;
                                        case 'SHIPPED':
                                            echo _e('Buyer has to vote', 'shop');
                                            break;
                                        case 'VOTE_BUYER':
                                            echo '<a href=\''.osc_render_file_url(osc_plugin_folder(__FILE__)."vote.php").'&paction=vote_buyer&txn_id='.$item['txn_id'].'\' >'.__('Vote transaction', 'shop').'</a>';
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
                            "sTitle": "<?php _e('#ID', 'shop'); ?>",
                            "sClass": "center",
                        },
                        {
                            "sTitle": "<?php _e('Title', 'shop'); ?>",
                            "sWidth": "200px"
                        },
                                                {
                            "sTitle": "<?php _e('Status', 'shop'); ?>",
                            "sClass": "center"
                        },
                        {
                            "sTitle": "<?php _e('Action', 'shop'); ?>",
                            "sClass": "center"
                        }
                    ],
                    "bPaginate": false,
                    "bFilter": false,
                    "bInfo": false
                });
            });
        </script>
            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'shop') ; ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu() ; ?>
                </div>
                <div id="main">
                    <h2><?php _e('Items sold', 'shop'); ?></h2>
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list" ></table>
                </div>
            </div>