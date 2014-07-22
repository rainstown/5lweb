<?php
class Auction extends Object
{
	const TYPE_OFFICAL = 'O';
	const TYPE_MARKET = 'M';
	const TYPE_PERSONAL = 'P';
	
	const STATUS_VALID = 'V';
	const STATUS_INVALID = 'I';
	const STATUS_NOT_APPROVED = 'N';
	const STATUS_PASS = 'P';
	const STATUS_DENY = 'D';
	
	/**
	 * 获得所有可选的类型
	 * @return multitype:mixed Ambigous <mixed, string, unknown>
	 */
	public static function getTypeOptions()
	{
		return array(
			self::TYPE_MARKET => Lang::get('auction_type_market'),
			self::TYPE_OFFICAL => Lang::get('auction_type_offical'),
			self::TYPE_PERSONAL => Lang::get('auction_type_personal'),
		);
	}
	/**
	 * 获得类型的名称
	 * @param string $type
	 */
	public static function getTypeName($type)
	{
		$options = self::getTypeOptions();
		return $options[$type];
	}
	
	public static function getStatusOptions()
	{
		return array(
			self::STATUS_INVALID => Lang::get('status_invalid'),
			self::STATUS_VALID	=> Lang::get('status_valid'),
			self::STATUS_NOT_APPROVED => Lang::get('status_not_approve'),
			self::STATUS_DENY => Lang::get('status_deny'),
			self::STATUS_PASS => Lang::get('status_pass'),
		);
	}
	/**
	 * 获得官方拍卖行状态
	 * @return multitype:mixed Ambigous <mixed, string, unknown>
	 */
	public static function getOfficalStatusOptions()
	{
		return array(
			self::STATUS_INVALID => Lang::get('status_invalid'),
			self::STATUS_VALID	=> Lang::get('status_valid'),
		);
	}
	/**
	 * 获得个人拍卖会状态选项
	 * @return multitype:mixed Ambigous <mixed, string, unknown>
	 */
	public static function getPersonalStatusOptions()
	{
		return array(
			self::STATUS_NOT_APPROVED => Lang::get('status_not_approve'),
			self::STATUS_DENY => Lang::get('status_deny'),
			self::STATUS_PASS => Lang::get('status_pass'),
		);
	}
	
	public static function getStatusName($status)
	{
		$options = self::getStatusOptions();
		return $options[$status];
	}
	/**
	 * get default money
	 * @param string $type
	 */
	public static function getDefaultKeepMoney($type)
	{
		$money = array(
			self::TYPE_MARKET => 0,
			self::TYPE_OFFICAL => 1500,
			self::TYPE_PERSONAL => 1000
		);
		return $money[$type];
	}
	/**
	 * 检查用户账户是否有指定的金额
	 * @param integer $user_id
	 * @param integer $required
	 */
	public static function checkUserHasMoney($user_id, $required)
	{
        $money =Auction::getCanUserMoney($user_id);
		return $money >= $required ? true : false;
	}
        
	/**
	 * 返回可用余额
	 * @param type $user_id
	 * @return type
	 */
	public static function getCanUserMoney($user_id)
	{
		$model = & m('bank');
		$user_id = empty($user_id) ? $this->visitor->get('user_id') : $user_id;
		$bank_info = $model->get($user_id);
		//获取拍卖的保证金;
		$model = & m('auction_records');
		$money = $model->getNotPayMoney($user_id);
		return $bank_info['money'] - $money;
	}

	/**
	 * 获得结束时间的时间戳,end date + 1 day
	 * @param string $end_time
	 */
	public static function getEndTimeStamp($end_time)
	{
		return strtotime('+1 day', strtotime($end_time));
	}
	/**
	 * 检查是否可以添加商品
	 * @param integer $user_id
	 * @param integer $auction_id
	 */
	public static function canAddGoods($user_id, $auction_id)
	{
		$can = false;
		$_mod_auction = &m('auction');
		$auction = $_mod_auction->get_info($auction_id);
		if ($auction['type'] == self::TYPE_MARKET) {
			$can = true;
		}
		else if ($auction['type'] == self::TYPE_OFFICAL) {
			//if ($auction['status'] == EnumStatus::VALID && $auction['']);
			$db = db();
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'auction AS a INNER JOIN ' . DB_PREFIX . 'auction_user AS au ON a.auction_id = au.auction_id
				WHERE a.status = "' . EnumStatus::VALID . '" AND au.status="' . EnumStatus::PASS . '" 
					AND a.auction_id = ' . intval($auction_id) . ' AND au.user_id = ' . intval($user_id) . '
						AND a.end_time >= "' . get_system_date() . '" 
			';
			$row = $db->getRow($sql);
			if ($row) {
				$can = true;
			}
		}
		else if ($auction['type'] == self::TYPE_PERSONAL) {
			if ($auction['status'] == EnumStatus::PASS && $auction['create_user'] == $user_id 
				&& self::getEndTimeStamp($auction['end_time']) > time()) {
				$can = true;
			}
		}
		return $can;
	}
	/**
	 * 
	 * @return unknown
	 */
	public static function getMarketAuctionId()
	{
		$db = db();
		$sql = 'SELECT auction_id FROM ' . DB_PREFIX . 'auction WHERE type = "' . self::TYPE_MARKET . '"';
		return $db->getOne($sql);
	}
}
?>