<?php /*

//fetch product name, price from db and add to new_product array
        $new_product["product_name"] = $product_name; 
        $new_product["product_price"] = $price;
        
        if(isset($_SESSION["cart_products"])){  //if session var already exist
            if(isset($_SESSION["cart_products"][$new_product['product_code']])) //check item exist in products array
            {
                unset($_SESSION["cart_products"][$new_product['product_code']]); //unset old array item
            }           
        }
        $_SESSION["cart_products"][$new_product['product_code']] = $new_product; //update or create product session with new item


if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
$total =0;
	$b = 0;
	foreach ($_SESSION["cart_products"] as $cart_itm)
	{
		$product_name = $cart_itm["name"];
		$product_qty = $cart_itm["product_qty"];
		$product_price = $cart_itm["product_price"];
		$product_code = $cart_itm["osc_item_id"];
		$product_color = $cart_itm["product_color"];
		$bg_color = ($b++%2==1) ? 'odd' : 'even'; //zebra stripe
		echo '<tr class="'.$bg_color.'">';
		echo '<td>Qty <input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
		echo '<td>'.$product_name.'</td>';
		echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /> Remove</td>';
		echo '</tr>';
		$subtotal = ($product_price * $product_qty);
		$total = ($total + $subtotal);
	}
}
*/
?>