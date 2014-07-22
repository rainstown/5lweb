<?php
class EnumStatus
{
	const VALID 		= 'V';
	const INVALID	= 'I';
	const NOT_APPROVE =	'N';
	const PASS	= 'P';
	const DENY	= 'D';
	
	public static function getStatusOptions()
	{
		return array(
			self::VALID => Lang::get('status_valid'),
			self::INVALID => Lang::get('status_invalid'),
			self::NOT_APPROVE => Lang::get('status_not_approve'),
			self::PASS	=> Lang::get('status_pass'),
			self::DENY	=> Lang::get('status_deny')
		);
	}
	
	public static function getStatusName($status)
	{
 		$options = self::getStatusOptions();
 		return $options[$status];
	}
	/**
	 * 获得个人拍卖会的状态
	 * @return multitype:mixed Ambigous <mixed, string, unknown>
	 */
	public static function getPAuctionStatus()
	{
		return array(
			self::NOT_APPROVE => Lang::get('status_not_approve'),
			self::PASS => Lang::get('status_pass'),
			self::DENY => Lang::get('status_deny'),
		);
	}
}