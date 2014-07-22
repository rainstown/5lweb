<?php
import('enum_status');
import('auction');
class AuctionApp extends MallbaseApp
{
	private $_auction_mod;
	private $_auction_user;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_auction_mod = &m('auction');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BaseApp::index()
	 */
	public function index()
	{
		
	}
	/**
	 * 报名
	 */
	public function apply()
	{
		//验证是否报名
		$this->_validateLogin();
		//只有开店的人才能添加商品
		$this->_validateStoreRequired();
		
		$user_id = intval($this->visitor->get('user_id'));
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		$_mod_auction_user = &m('auction_user');
		//检查是否已经报名
		$arr_user = $_mod_auction_user->getAuctionUser($auction_id);
		foreach ($arr_user as $user) {
			if ($user['user_id'] == $user_id && $user['status'] != EnumStatus::DENY) {
				$this->show_warning('already_apply_auction');
				return;
				exit;
				break;
			}
		}
		
		if (!IS_POST) {
			$auction = $this->_auction_mod->get_info($auction_id);
			
			$auction['show_time'] = $auction['type'] == Auction::TYPE_MARKET ? false : true;
			
			if (empty($auction)) {
				$this->show_warning(Lang::get('auction_not_exists'));
				return ;
			}
			
			$curlocal[] = array('text' => Lang::get('apply'));
			$this->_curlocal($curlocal);
			/* 取得导航 */
			$this->assign('navs', $this->_get_navs());
			$this->assign('auction', $auction);
			$this->display('auction.apply.html');
		}
		else {
			//检查用户是否有交保证金的能力
			
			$auction_id = intval($_POST['auction_id']);
			$auction = $this->_auction_mod->get_info($auction_id);
			if (!Auction::checkUserHasMoney($user_id, $auction['keep_money'])) {
				$this->show_warning(Lang::get('do_not_have_money_to_pay_for_keep_money'));
				return ;
			}
			
			$this->_auction_user = &m('auction_user');
			$data = array(
				'user_id' => $user_id,
				'auction_id' => $auction_id,
				'status' => EnumStatus::NOT_APPROVE,
				'create_time' => get_system_time()
			);
			
			$id = $this->_auction_user->getId($auction_id, $user_id);
			if ($id > 0) {
				$this->_auction_user->edit($id, $data);
			}
			else {
				$this->_auction_user->add($data);
			}
			
			$this->show_message('apply_ok', 'back_list', 'index.php?app=auction&&act=auction_list&type=O');
		}
	}
	/**
	 * 拍卖会列表
	 */
	public function auction_list()
	{
		$store_id = intval($this->visitor->get('manage_store'));
		$query = trim($_GET['query']);
		$type = trim($_GET['type']);
		
		$date_now = get_system_date();
		$sort = 'end_time';
		$order = 'desc';
		$curr = 'all';
		if ($query == 'apply') {
			$condition = 'sign_start <= "' . $date_now . '" AND sign_end >= "' . $date_now . '"';
			$sort = 'sign_end';
			$order = 'desc';
			$curr = 'applying';
		}
		else if ($query == 'process') {
			$condition = 'start_time <= "' . $date_now . '" AND end_time >= "' . $date_now . '"' ;
			$curr = 'inprocess';
		}
		else {
			if ($type != Auction::TYPE_MARKET) {
				$condition = ' end_time >= "' . $date_now . '"';
			}
		}
		
		$condition .= $this->_get_query_conditions(array(
			array(
				'field' => 'type',
				'equal' => '=',
			),
		));
		
		if ($type != Auction::TYPE_MARKET) {
			$conditions = '(status = "' . EnumStatus::PASS  . '" OR status="' . EnumStatus::VALID . '") AND ' . $condition;
		}
		else {
			$conditions = ' 1=1' . $condition;
		}
		$this->assign('market_id', Auction::getMarketAuctionId());
		
		$page = $this->_get_page();
		$auctions = $this->_auction_mod->find(
			array(
				'conditions' => $conditions,
				'limit' => $page['limit'],
				'order' => $sort . ' ' . $order,
				'count' => true,
			)
		);
		$page['item_count'] = $this->_auction_mod->getCount();
		
		$this->_auction_user = &m('auction_user');
		$auction_user = $this->_auction_user->find(
			array(
				'conditions' => 'user_id=' . $this->visitor->get('user_id'),
			)
		);
		//获得所有已经报名的auction_id
		$auction_id = array();
		foreach ($auction_user as $a_u) {
			$auction_id[] = $a_u['auction_id'];
		}
		$now = time();
		foreach ($auctions as $i => $auction) {
			if (!in_array($auction['auction_id'], $auction_id) 
				&& strtotime($auction['sign_start']) <= $now && Auction::getEndTimeStamp($auction['sign_end']) > $now 
				&& $store_id > 0
			) 
			{
				$auctions[$i]['can_apply'] = true;
			}
		}
		//面包屑
		$curlocal[] = array('text' => Lang::get($curr));
		$this->_curlocal($curlocal);
		
		/* 取得导航 */
		$this->assign('navs', $this->_get_navs());
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		
		$this->assign('auctions', $auctions);
		$this->display('auction.list.html');
	}
	/**
	 * 商品列表
	 */
	public function goods_list()
	{
		$user_id = intval($this->visitor->get('user_id'));
		$_mod_auction_goods = &m('auction_goods');
		
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$db = &db();
		$condition = ' s.state = 1 AND g.closed = 0
				AND ag.status = "' . EnumStatus::VALID . '" 
				AND ag.auction_id = ' . $auction_id ;
		
		$select = 'SELECT g.goods_name, g.default_image, ag.* ';
		$select_count = 'SELECT COUNT(ag.goods_id)';
		$tables = 'FROM ' . DB_PREFIX . 'goods AS g INNER JOIN ' . DB_PREFIX . 'auction_goods AS ag ON ag.goods_id = g.goods_id
			INNER JOIN ' . DB_PREFIX . 'store AS s ON g.store_id = s.store_id
			WHERE ';
		$count = $db->getOne($select_count . ' ' . $tables . ' ' . $condition);
		/* 分页信息 */
		$page = $this->_get_page(16);
		$limit = 'LIMIT ' . $page['limit'];
		$page['item_count'] = $count;
		$this->_format_page($page);
		$this->assign('page_info', $page);
		
		$goods_list = $db->getAll($select . ' ' . $tables . ' ' . $condition . ' ' . $limit);
		foreach ($goods_list as $key => $goods) {
			//使用默认图片
			$goods['default_image'] || $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
			
			$arr_status = $_mod_auction_goods->getStatus($goods, $user_id);
			$goods_list[$key] = array_merge($goods, $arr_status);
		}
		/* 商品展示方式 */
		$display_mode = ecm_getcookie('goodsDisplayMode');
		if (empty($display_mode) || !in_array($display_mode, array('list', 'squares')))
		{
			$display_mode = 'squares'; // 默认格子方式
		}
		$this->assign('display_mode', $display_mode);
		
		/* 取得导航 */
        $this->assign('navs', $this->_get_navs());
        /* 当前位置 */
        $cate_id = isset($param['cate_id']) ? $param['cate_id'] : 0;
        $this->_curlocal($this->_get_aucton_goods_curlocal($auction_id));
        /* 配置seo信息 */
        //$this->_config_seo($this->_get_seo_info('goods', $cate_id));
		
		$this->assign('goods_list', $goods_list);
		$this->display('auction.goods_list.html');
	}
	/**
	 * 拍卖品详情
	 */
	public function goods()
	{
		$goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
		
		$_mod_goods = &m('goods');
		$goods = $_mod_goods->get_info($goods_id);
		
		$goods['default_image'] = $goods['default_image'] ? $goods['default_image'] : Conf::get('default_goods_image');
		
		$this->assign('goods', $goods);
		$this->display('auction.goods.html');
		
	}
	/**
	 * 支付
	 * @return boolean
	 */
	public function pay()
	{
		//验证是否登录
		$this->_validateLogin();
		
		$user_id = intval($this->visitor->get('user_id'));
		
		$auction_id = intval($_POST['auction_id']);
		$goods_id = intval($_POST['goods_id']);
		$times = intval($_POST['times']);
		$price = intval($_POST['new_price']);
		
		$_mod_goods = &m('goods');
		$_mod_auction = &m('auction');
		$_mod_auction_goods = &m('auction_goods');
		$_mod_records = &m('auction_records');
		
		//验证拍卖品是否存在
		$auction_goods = $_mod_auction_goods->getGoodsDetail($goods_id, $auction_id);
		$pre_owner = $auction_goods['owner'];
		$goods = $_mod_goods->get_info($goods_id);
		if (!$goods || $goods['closed'] == 1 || $goods['state'] != 1 || empty($auction_goods))
        {
			$this->show_warning ( 'goods_not_exist' );
			return false;
		}
		if (!$goods['store_id'])
		{
			$this->show_warning('store of goods is empty');
			return false;
		}
		//验证是否拍卖品过期
		$arr_status = $_mod_auction_goods->getStatus($auction_goods, $user_id);
		$now = time();
		if ($arr_status['time_status'] == '尚未开始') {
			$this->show_warning('auction_goods_not_start');
			return false;
		}
		if ($arr_status['time_status'] == '已经结束') {
			$this->show_warning('auction_goods_out_of_date');
			return false;
		}
		//验证是否有新的竞价超过当前进价
		if ($price < $auction_goods['curr_price'] + $auction_goods['step_price']) {
			$this->show_warning('price_should_larger_than_required_money');
			return false;
		}
		
		//验证账户金额是否有有效的金额
		$auction = $_mod_auction->get_info($auction_id);
		$need_money = ceil($auction['keep_percent'] * $price / 100);
		/*
		 * 如果当前产品的最后一条拍卖记录是当前用户的，获得拍卖记录中的金额，
		 * 只需要补差价就可以了
		 */
		$has_pay_info = $_mod_records->getUserLastPay($auction_id, $goods_id, $user_id);
		$need_money = $has_pay_info["status"] == EnumStatus::VALID ? $need_money - $has_pay_info['keep'] : $need_money;
		
		if (!Auction::checkUserHasMoney($user_id, $need_money)) {
			$this->show_warning('has_no_money_pay_goods');
			return false;
		}
		//其他拍卖价格无效
		$_mod_records->disablePrice($auction_id, $goods_id);
		//添加拍卖纪录
		$data = array(
			'goods_id' => $goods_id,
			'auction_id' => $auction_id,
			'user_id' => $user_id,
			'price' => $price,
			'status' => EnumStatus::VALID,
			'keep' => $need_money,
			'create_time' => get_system_time(),
		);
		$_mod_records->add($data);
		//更新拍卖品的所述对象
		$_mod_auction_goods->updateOwner($user_id , $goods_id, $auction_id);
		//更新当前价格
		$_mod_auction_goods->updatePrice($price, $auction_id, $goods_id);
		$_mod_auction_goods->updatePayNum($auction_id, $goods_id);
		//发送系统消息，给被超过价格的人，说有人的竞价已经超过你的竞价
		$msg_content = '您竞拍中的拍卖品[' . $auction_goods ['goods_name'] . ']，有人的出价已经高出您的竞价，当前竞价：' . $price . '元';
		if ($pre_owner > 0 &&$pre_owner != $user_id) {
			$ms = & msecmall ();
			$msg_id = $ms->pm->send ( MSG_SYSTEM, array ($pre_owner), '', $msg_content);
			if (! $msg_id) {
				$rs = $ms->pm->get_error ();
				$msg = current ( $rs );
				$this->show_warning ( $msg ['msg'] );
				return;
			}
		}
		//操作成功
		$this->show_message('pay_ok','back_list', 'index.php?app=goods&act=auction&id=' . $goods_id . '&auction_id=' . $auction_id);
	}
	/**
	 * 
	 */
	public function records()
	{
		$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
		$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		
	}
	
	protected function _get_aucton_goods_curlocal($auction_id)
	{
		$auction = $this->_auction_mod->get_info($auction_id);
		$curlocal = array(
			array('text' => Lang::get('auction_list'), 'url' => 'index.php?app=auction&act=auction_list'),
			array('text' => $auction['name'] . Lang::get('goods_list'))
		);
		return $curlocal;
	}
	/**
	 * 验证是否已经登录
	 */
	private function _validateLogin()
	{
		/* 先判断是否登录 */
		/* 只有登录的用户才可访问 */
        if (!$this->visitor->has_login && !in_array(ACT, array('login', 'register', 'check_user')))
        {
            if (!IS_AJAX)
            {
                header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));
                exit;
                return;
            }
            else
            {
                $this->json_error('login_please');
                exit;
                return;
            }
        }
	}
	/**
	 * 验证是否已经开店
	 */
	private function _validateStoreRequired()
	{
		/* 检查是否是店铺管理员 */
		if (!$this->visitor->get('manage_store'))
		{
			$referer = $_SERVER['HTTP_REFERER'];
			if (strpos($referer, 'act=login') === false)
			{
				$ret_url = $_SERVER['HTTP_REFERER'];
				$ret_text = 'go_back';
			}
			else
			{
				$ret_url = SITE_URL . '/index.php';
				$ret_text = 'back_index';
			}
			/* 您不是店铺管理员 */
			$this->show_warning(
				'not_storeadmin',
				'apply_now', 'index.php?app=apply',
				$ret_text, $ret_url
			);
			exit;
			return;
		}
	}
}
?>