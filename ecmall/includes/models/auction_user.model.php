<?php
class Auction_userModel extends BaseModel
{
	public $table = 'auction_user';
	public $prikey = 'id';
	
	public $_name = 'auction_user';
	
	/**
	 * 获得用户当前的审核状态
	 * @param integer $auction_id
	 * @param integer $user_id
	 */
	public function getUserStatus($auction_id, $user_id)
	{
		$db = db();
		$sql = 'SELECT status FROM ' . $this->table . ' 
			WHERE user_id = ' . intval($user_id) . ' AND auction_id = ' . intval($auction_id);
		$status = $db->getOne($sql);
		return $status;
	}
	/**
	 * @deprecated
	 * 判断是否可以添加商品
	 * @param integer $user_id
	 * @param integer $auction_id
	 * @return boolean
	 */
	public function canAddGoods($user_id, $auction_id)
	{
		$can = false;
		$db = db();
		$sql = 'SELECT a.type, a.end_time FROM ' . $this->table . ' AS au INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = au.auction_id
				WHERE au.user_id = ' . intval($user_id) . ' AND au.status = "' . EnumStatus::PASS . '"
					AND a.status = "'. EnumStatus::VALID .'" AND au.auction_id = ' . intval($auction_id) . '
		';
		$row = $db->getRow($sql);
		if ($row) {
			if ($row['type'] == Auction::TYPE_MARKET) {
				$can = true;
			}
			else {
				if (strtotime('+1 day', strtotime($row['end_time'])) >= time()) {
					$can = true;
				}
			}
		}
		return $can;
	}
	/**
	 * 获得拍卖会的用户
	 * @param integer $auction_id
	 */
	public function getAuctionUser($auction_id)
	{
		$db = db();
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE auction_id = ' . intval($auction_id) ;
		return $db->getAll($sql);
	}
	/**
	 * 设置保证金已经退还
	 * @param integer $user_id
	 * @param integer $auction_id
	 */
	public function backMoney($user_id, $auction_id)
	{
		$db = db();
		$sql = 'UPDATE ' . $this->table . ' SET back_money = 1 WHERE user_id = ' . intval($user_id) . ' AND auction_id = ' . intval($auction_id);
		$db->query($sql);
	}
	/**
	 * 获得用户ID和拍卖会ID获得主键
	 * @param unknown $auction_id
	 * @param unknown $user_id
	 */
	public function getId($auction_id, $user_id)
	{
		$db = db();
		$sql = 'SELECT id FROM ' . $this->table . ' WHERE auction_id = ' . intval($auction_id) . ' AND user_id = ' . intval($user_id);
		return $db->getOne($sql);
	}
}