<?php
function payeer_config() 
{
	$configarray = array(
		'FriendlyName' => array(
			'Type' => 'System',
			'Value' => 'Payeer'
		),
		'payeer_url' => array(
			'FriendlyName' => 'URL мерчанта (по умолчанию, //payeer.com/merchant/)',
			'Type' => 'text',
			'Size' => '20',
			'Default' => '//payeer.com/merchant/'
		),
		'payeer_shop' => array(
		  'FriendlyName' => 'Идентификатор магазина',
		  'Type' => 'text',
		  'Size' => '20'
		),
		'payeer_secret_key' => array(
		  'FriendlyName' => 'Секретный ключ',
		  'Type' => 'text',
		  'Size' => '20'
		),
		'payeer_comment' => array(
		  'FriendlyName' => 'Комментарий к оплате',
		  'Type' => 'text',
		  'Size' => '20'
		),
		'payeer_logfile' => array(
		  'FriendlyName' => 'Путь до файла для журнализации оплат (например, /payeer_orders.log)',
		  'Type' => 'text',
		  'Size' => '20'
		),
		'payeer_ipfilter' => array(
		  'FriendlyName' => 'IP - фильтр обработчика',
		  'Type' => 'text',
		  'Size' => '20'
		),
		'payeer_email_error' => array(
		  'FriendlyName' => 'Email для ошибок оплат',
		  'Type' => 'text',
		  'Size' => '20'
		)
	);

	return $configarray;
}

function payeer_link($params) 
{
	global $_LANG;

	if (isset($params['convertto'])) 
	{
		$params['curr'] = getCurrency(0, $params['convertto']);
	}
	
	$m_url = $params['payeer_url'];
	$m_shop = $params['payeer_shop'];
	$m_orderid = $params['invoiceid'];
	$m_amount = $params['amount'];
	$m_curr = $params['curr']['code'];
	$m_desc = base64_encode($params['payeer_comment']);
	$m_key = $params['payeer_secret_key'];
	
	$arHash = array(
		$m_shop,
		$m_orderid,
		$m_amount,
		$m_curr,
		$m_desc,
		$m_key
	);
	$sign = strtoupper(hash('sha256', implode(':', $arHash)));
	 
	$code = '
		<form id = "form_payment_payeer" method="GET" action="' . $m_url . '">
			<input type="hidden" name="m_shop" value="' . $m_shop . '">
			<input type="hidden" name="m_orderid" value="' . $m_orderid . '">
			<input type="hidden" name="m_amount" value="' . $m_amount . '">
			<input type="hidden" name="m_curr" value="' . $m_curr . '">
			<input type="hidden" name="m_desc" value="' . $m_desc . '">
			<input type="hidden" name="m_sign" value="' . $sign . '">
			<input type="submit" name="m_process" value="' . $_LANG['invoicespaynow'] . '" />
		</form>
		';

	return $code;
}