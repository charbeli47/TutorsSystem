<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Download Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/download_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Force Download
 *
 * Generates headers that force a download to happen
 *
 * @access	public
 * @param	string	filename
 * @param	mixed	the data to be downloaded
 * @return	void
 */


if ( ! function_exists('call_cybersource'))
{
	function call_cybersource($params)
	{
		if( !is_array( $params ) )
		{
			return 'Parameters should be in form of array';
		}		
		$params['reference_number'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $params['transaction_type'] = 'sale';
        $params['transaction_uuid' ] = uniqid();
        $params['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $params['locale'] = 'en';
        $params['currency'] = 'USD';
        $params['signed_field_names'] = "bill_to_address_line2,amount,access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,currency,bill_to_surname,bill_to_forename,bill_to_address_country,bill_to_address_line1,bill_to_address_city,bill_to_email,bill_to_phone,override_custom_receipt_page,consumer_id,item_0_code,line_item_count,item_0_unit_price,item_0_quantity,item_0_name,item_0_sku";
		$params['unsigned_field_names'] = "";
        $required = array('profile_id', 'access_key', 'amount', 'bill_to_forename', 'bill_to_surname', 'bill_to_email', 'bill_to_phone', 'productinfo', 'transaction_type');
		$missed = '';
		$procede = TRUE;
		for( $i = 0; $i < count( $required ); $i++ )
		{
			if( !in_array( $required[$i], array_keys( $params ) ) )
			{
				$missed .= $required[$i] . ', ';
				$procede = FALSE;
			}
		}
		
		if( !$procede )
		{
			return 'Parameters missed <b>' . $missed . '</b>';
		}			
		$str = '<form name="csForm" method="post" action="csconfirm">';
		foreach( $params as $key => $val )
		{
				$str .= '<input type="hidden" name="'.$key.'" value="'.$val.'">';
		}
		
		$str .= '</form>';		
		$str .= '
			<script>
		window.onload = function() { 
		document.csForm.submit();
		}
		</script>';		
		return $str;
	}
}


/* End of file download_helper.php */
/* Location: ./system/helpers/download_helper.php */