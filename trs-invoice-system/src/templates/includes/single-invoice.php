<?php if (! defined('ABSPATH') ) exit; ?>

<?php
    $get_data = filter_input_array(INPUT_GET);
    
    // PayPal error occour. And this is redirection.
    if (isset($get_data['pp-err'])){
        ?>


        <div class="alert alert-danger">
            <p>
                Sorry! PayPal error occurred. Please contact to admin. If you are admin please check debug logs.
            </p>
        </div>

        <?php
    }
	
	if (isset($get_data['pp-status']) && $get_data['pp-status'] === 'success' && is_invoice_paid(get_the_ID()) ){
		?>


        <div class="alert alert-success">
            <p>
                Thank you for your payment.
            </p>
        </div>
		
		<?php
	}
	
	
	// Variables
	
    $obj = (new \AdminInvoiceSystem\InvoiceMetaBoxes);
    
    $billing_info = $obj->get_metafield_sections($post->ID, \AdminInvoiceSystem\InvoiceMetaBoxes::getCustomerBillingInformationSectionSlug());
	
	
	$json_value = $obj->get_metafield_value($post->ID, \AdminInvoiceSystem\InvoiceMetaBoxes::getOutputSectionSlug(), \AdminInvoiceSystem\InvoiceMetaBoxes::getProductInfoArr());
	$row_listings = json_decode($json_value);
	$total_amount = round((float) 0.0, 3);
	
	//echo '<tt><pre>' . var_export($billing_info, true) . '</pre></tt>';
	
?>


<div class="invoice-heading">Your Order Invoice # <?php echo $post->ID; ?></div>

<div class="billing-address-wrapper">
	<div class="section-title">Billing Address</div>
	<div class="field-wrapper">
		<p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingFirstName()); ?></p>
	</div>
	<div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingLastName()); ?></p>
	</div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingCompanyName()); ?></p>
    </div>
	<div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingAddressLine1()); ?></p>
	</div>
	<div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingAddressLine2()); ?></p>
	</div>
	<div class="field-wrapper">
        <p>
            <?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingCity()); ?>
            ,
	        <?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingState()); ?>
            ,
	        <?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingZipCode()); ?>
        </p>
	</div>
	<div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingCountry()); ?></p>
	</div>
	<div class="field-wrapper">
        <p><?php
		       echo $obj->get_metafield_value($post->ID, $obj::getCustomerBillingInformationSectionSlug(), $obj::getBillingPhone())
            ?>
        </p>
    </div>
</div>

<div class="shipping-address-wrapper">
	<div class="section-title">Shipping Address</div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingFirstName()); ?></p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingLastName()); ?></p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingCompanyName()); ?></p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingAddressLine1()); ?></p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingAddressLine2()); ?></p>
    </div>
    <div class="field-wrapper">
        <p>
			<?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingCity()); ?>
            ,
			<?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingState()); ?>
            ,
			<?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingZipCode()); ?>
        </p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingCountry()); ?></p>
    </div>
    <div class="field-wrapper">
        <p><?php echo $obj->get_metafield_value($post->ID, $obj::getCustomerShippingInformationSectionSlug(), $obj::getShippingPhone()); ?></p>
    </div>
</div>

<div class="order-details-section">
	<h2 class="order-details-heading">Order Details</h2>
	<table class="order-details-table" id="order-details-table">
		<thead>
		<tr>
			<th><?php _e( 'Product Name', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Product Specification', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Size', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Stock', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Printing', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Finishing', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'QTY', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
			<th><?php _e( 'Price', TRS_INVOICE_SYSTEM_DOMAIN ); ?></th>
 		</tr>
		</thead>
		<tbody id="trs-invoice-output-tbody">
		
		<?php
			if (! empty($row_listings)){
				foreach ($row_listings as $row):
					echo '<tr>';
					echo '<td>' . $row->name . '</td>';
					echo '<td>' . $row->specification . '</td>';
					echo '<td>' . $row->size . '</td>';
					echo '<td>' . $row->stock . '</td>';
					echo '<td>' . $row->printing . '</td>';
					echo '<td>' . $row->finishing . '</td>';
					echo '<td>' . $row->quantity . '</td>';
					echo '<td>' . $row->price . '</td>';
					
					echo '</tr>';
					
					$price = explode(' ', $row->price);
					if (is_array($price) && count($price) > 0){
						$total_amount += round((floatval($price[count($price) - 1])), 3) ;
					}
					
				endforeach;
			}
		?>
		
		<tr class="order-total-row">
			<td colspan="7">Total: </td>
			<td colspan="7">$ <?=$total_amount;?></td>
		</tr>
		
		</tbody>
	</table>
</div>

<?php
    
    // if invoice paid then show already paid. else show PayPal button.
    if (is_invoice_paid($post->ID)){
        include_once ('paid-success.php');
        
        return '';
    }
    
    
    
	// PayPal Variables
	
	$secretKey = \InvoiceSystem\FrontendInvoiceSystem::getSecretKey();
	$method = \InvoiceSystem\FrontendInvoiceSystem::getEncryptionType();
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
	$key = openssl_encrypt(round($total_amount, 3), $method, $secretKey, false, $iv);
	
?>

<div class="checkout-section">
	
	<form method="post" action="<?php echo $url; ?>">
		<input type="hidden" name="enc_key" value="<?php echo $key; ?>">
		<input type="hidden" name="iv" value="<?php echo base64_encode($iv); ?>">
		<input type="hidden" name="in" value="<?php echo $post->ID; ?>">
		<input type="image" src="<?php echo trailingslashit(TRS_INVOICE_SYSTEM_FrontEnd_ASSETS_URL) . 'images/paypal-button.png'; ?>">
	</form>
</div>