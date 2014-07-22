<?php
/**
 * 后台我是卖家入口管理我发布的卖场
 * @author shearf
 *
 */
import('auction');
import('enum_status');
class My_auctionApp extends StoreadminbaseApp
{
	private $_auction_mod;
	private $_auction_user_mod;
	private $_store_id;
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->_auction_mod = &m('auction');
		$this->_auction_user_mod = &m('auction_user');
		$this->_store_id  = intval($this->visitor->get('manage_store'));
	}
	
	public function index()
	{
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_auction'), 'index.php?app=my_auction',
			LANG::get('auction_list'));
		
		
		$this->_curitem('my_auction');
		$this->_curmenu('auction_list');
		//$this->import_resource(array('script' => 'utils.js'));
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_auction'));
		
		$this->assign('status_options', $this->_getStatus());
		$search_fields = array(
			'start_time' => Lang::get('start_time'),
			'end_time' => Lang::get('end_time')
		);
		$this->assign('search_fields', $search_fields);
		
		$store_mod  =& m('store');
		$store = $store_mod->get_info($this->_store_id);
		$this->assign('store', $store);
		
		$this->import_resource(array(
			'script' => array(
				array(
					'path' => 'dialog/dialog.js',
					'attr' => 'id="dialog_js"',
				),
				array(
					'path' => 'jquery.ui/jquery.ui.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.plugins/jquery.validate.js',
					'attr' => '',
				),
				array(
					'path' => 'mlselection.js',
					'attr' => '',
				),
			),
			'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));
		
		
		//更新排序
		if (isset($_GET['sort']) && isset($_GET['order'])) {
			$sort  = strtolower(trim($_GET['sort']));
			$order = strtolower(trim($_GET['order']));
			if (!in_array($order,array('asc','desc')))
			{
				$sort  = 'create_time';
				$order = 'desc';
			}
		}
		else {
			$sort = 'create_time';
			$order = 'desc';
		}
		
		$page = $this->_get_page(10);
		
		$condition = $this->_getSearchConditions();
		$filtered = $condition == '' ? false : true;
		
		$auctions = $this->_auction_mod->find(array(
			'conditions' => 'create_user=' . $this->visitor->get('user_id') . ' 
				AND type="' . Auction::TYPE_PERSONAL . '"' . $condition,
			'order' => "$sort $order",
			'count' => true,
			'limit' => $page['limit'],
		));
		
		foreach ($auctions as $index => $auction) {
			$auctions[$index]['status_name'] = EnumStatus::getStatusName($auction['status']);
			$auctions[$index]['can_edit'] = $auction['status'] == EnumStatus::NOT_APPROVE || EnumStatus::DENY == $auction['status'];
			//是否可以添加商品
			$auctions[$index]['can_goods'] = ($auction['status'] == EnumStatus::PASS && 
				Auction::getEndTimeStamp($auction['end_time']) > time());
		}
		
		$page['item_count'] = $this->_auction_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('filtered', $filtered);
		$this->assign('auction_list', $auctions);
		
		$this->display('my_auction.index.html');
	}

	/**
	 * 新增个人拍卖
	 */
	public function edit()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		$auction = $this->_auction_mod->get_info($id);
		
		if (!IS_POST) {
			$this->import_resource(array(
				'script' => array(
					array(
						'path' => 'jquery.ui/jquery.ui.js',
						'attr' => '',
					),
					array(
						'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
						'attr' => '',
					),
					array(
						'path' => 'jquery.plugins/jquery.validate.js',
						'attr' => '',
					),
				),
				'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
			));
			$this->assign('auction', $auction);
			$this->display('my_auction.form.html');
		}
		else {
			$start_time = trim($_POST['start_time']);
			$end_time = trim($_POST['end_time']);
			$percent = intval($_POST['keep_percent']);
			$start_timestamp = strtotime($start_time);
			//验证开始时间和结束时间
			if ($start_timestamp >= Auction::getEndTimeStamp($end_time)) {
				$this->pop_warning('end_time_must_later_than_start_time');
				return ;
			}
			
			if ($start_timestamp <= time()) {
				//$this->pop_warning('start_time_must_later_than_now');
				//return ;
			}
			
			if ($percent <= 0 || $percent > 100) {
				$this->pop_warning('keep_percent_must_larger_than_1_and_less_than_100');
				return ;
			}
			
			$data = array(
				'auction_id' => $id,
				'name' => $_POST['name'],
				'start_time' => $_POST['start_time'],
				'end_time' => $_POST['end_time'],
				'type' => Auction::TYPE_PERSONAL,
				'status' => EnumStatus::NOT_APPROVE,
				'description' => $_POST['description'],
				'keep_percent' => intval($_POST['keep_percent']),
			);
			
			if ($id && $this->_auction_mod->get_info($id)) {
				$this->_auction_mod->edit($id, $data);
			}
			else {
				$data['create_time'] = get_system_time();
				$data['create_user'] = $this->visitor->get('user_id');
				$this->_auction_mod->add($data);
			}
			
			if ($this->_auction_mod->has_error()) {
				$this->pop_warning($this->_auction_mod->get_error());
			
				return;
			}
			$this->pop_warning('ok', APP . '_'. ACT);
		}
	}
	/**
	 * 获得搜索首页
	 * @return Ambigous <void, string>
	 */
	private function _getSearchConditions()
	{
		/* 搜索条件 */
		$conditions = "1 = 1";
		$search_field = $_GET['search_field'];
	
		$conditions = array(
			array(
				'field' => $search_field,
				'equal' => '>=',
				'name' => 'date_from',
			),
			array(
				'field' => $search_field,
				'equal' => '<=',
				'name' => 'date_to',
			),
			array(
				'field' => 'a.status',
				'equal' => '=',
				'name' => 'status'
			),
			array(
				'field' => 'name',
				'equal' => 'LIKE',
				'name' => 'keyword'
			),
		);
		
		return $this->_get_query_conditions($conditions);
	}
	/**
	 * 获得状态
	 * @return multitype:NULL Ambigous <mixed, string, unknown>
	 */
	private function _getStatus()
	{
		return array(
			EnumStatus::NOT_APPROVE => EnumStatus::getStatusName(EnumStatus::NOT_APPROVE),
			EnumStatus::PASS => EnumStatus::getStatusName(EnumStatus::PASS),
			EnumStatus::DENY => EnumStatus::getStatusName(EnumStatus::DENY),
		);
	}
	/**
	 * 我参与的
	 */
	public function apply_list()
	{
		$user_id = $this->visitor->get('user_id');
		
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_auction'), 'index.php?app=my_auction',
			LANG::get('apply_list'));
		
		$this->_curitem('my_auction');
		$this->_curmenu('apply_list');
		//$this->import_resource(array('script' => 'utils.js'));
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('apply_list'));
		
		
		$this->assign('status_options', Auction::getOfficalStatusOptions());
		$search_fields = array(
			'start_time' => Lang::get('start_time'),
			'end_time' => Lang::get('end_time'),
			'sign_start' => Lang::get('sign_start_time'),
			'sign_end' => Lang::get('sign_end_time'),
		);
		$this->assign('search_fields', $search_fields);
		
		$this->import_resource(array(
			'script' => array(
				array(
					'path' => 'dialog/dialog.js',
					'attr' => 'id="dialog_js"',
				),
				array(
					'path' => 'jquery.ui/jquery.ui.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
					'attr' => '',
				),
				array(
					'path' => 'jquery.plugins/jquery.validate.js',
					'attr' => '',
				),
				array(
					'path' => 'mlselection.js',
					'attr' => '',
				),
			),
			'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));
		
		
		//更新排序
		if (isset($_GET['sort']) && isset($_GET['order'])) {
			$sort  = strtolower(trim($_GET['sort']));
			$order = strtolower(trim($_GET['order']));
			if (!in_array($order,array('asc','desc')))
			{
				$sort  = 'create_time';
				$order = 'desc';
			}
		}
		else {
			$sort = 'create_time';
			$order = 'desc';
		}
		
		$page = $this->_get_page();
		
		$condition = $this->_getSearchConditions();
		$db = &db();
		
		$select = 'a.*, au.status AS status_user, au.id';
		$select_count = ' COUNT(au.auction_id)';
		$tables = DB_PREFIX . 'auction_user AS au
			INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = au.auction_id';
		$where = 'a.type = "' . Auction::TYPE_OFFICAL . '" AND au.user_id = ' . $user_id . $condition;
		
		$sql_count = 'SELECT ' . $select_count . ' FROM ' . $tables . ' WHERE ' . $where;
		$count = $db->getOne($sql_count);
		$page['item_count'] = $count;
		
		$sql = 'SELECT ' . $select . ' FROM ' . $tables . ' WHERE ' . $where . ' ORDER BY ' . $sort . ' ' . $order . ' LIMIT ' . $page['limit'];
		/*
		$sql = 'SELECT a.*, au.status AS status_user, au.id FROM ' . DB_PREFIX . 'auction_user AS au
			INNER JOIN ' . DB_PREFIX. 'auction AS a ON a.auction_id = au.auction_id
			WHERE au.user_id = ' . $user_id . $condition . ' 
			ORDER BY ' . $sort . ' ' . $order . '
			LIMIT ' . $page['limit'] . ' 
		';
		*/
		$auctions = $db->getAll($sql);
		$now = time();
		foreach ($auctions as $i => $auction) {
			
			if ($auction['status'] == EnumStatus::VALID) {
				
				$auctions[$i]['status_name'] = EnumStatus::getStatusName($auction['status']);
				//如果拍卖会状态是V而且没有过期的情况，已经报名成功了，可以发布商品
				if ($auction['status_user'] == EnumStatus::PASS && Auction::getEndTimeStamp($auction['end_time']) > $now) {
					$auctions[$i]['can_goods'] = true;
				}
				//如果拍卖会状态是V而且没有超过报名截止时间，如果报名不通过，可以再报名
				if (strtotime($auction['sign_end']) > $now) {
					if ($auction['status_user'] == EnumStatus::DENY) { 
						$auctions[$i]['can_apply'] = true;
					}
				}
				
				if ($auction['status_user'] == EnumStatus::NOT_APPROVE) {
					$auctions[$i]['user_status_name'] = Lang::get('status_not_approve');
				}
				
			}
		}
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('auction_list', $auctions);
		$this->assign('filtered', $condition != '');
		$this->display('my_auction.apply_list.html');
	}
	
	public function apply()
	{
		//检查用户是否有交保证金的能力
		$user_id = $this->visitor->get('user_id');
			
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
		$auction = $this->_auction_mod->get_info($auction_id);
			
		if (!Auction::checkUserHasMoney($user_id, $auction['keep_money'])) {
			$this->show_warning(Lang::get('do_not_have_money_to_pay_for_keep_money'));
			return ;
		}
			
		$this->_auction_user = &m('auction_user');
		$data = array(
			'status' => EnumStatus::NOT_APPROVE,
			'create_time' => get_system_time()
		);
			
		$this->_auction_user->edit($id, $data);
			
		$this->show_message('apply_ok', 'back_list', 'index.php?app=my_auction&&act=apply_list');
	}
	/**
	 * 添加商品到拍卖会
	 */
	public function add_goods()
	{
		$user_id = intval($this->visitor->get('user_id'));
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		/**
		 * @todo 判断是否可以进入这个添加拍卖品的页面
		 */
		$can_goods = Auction::canAddGoods($user_id, $auction_id);
		if (!$can_goods) {
			$this->show_warning('you_can_not_add_goods_to_this_auction');
			return ;
		}
		
		$db = & db();
		/* 取得店铺商品分类 */
		$this->assign('sgcategories', $this->_get_sgcategory_options());
		
		//获得当前的分类
		$sgcate_id = isset($_GET['sgcate_id']) ? intval($_GET['sgcate_id']) : 0;
		if ($sgcate_id > 0) {
			$cate_mod =& bm('gcategory', array('_store_id' => $this->_store_id));
			$cate_ids = $cate_mod->get_descendant_ids($sgcate_id);
		}
		else {
			$cate_ids = 0;
		}
		
		$conditions = '';
		if ($cate_ids)
		{
			$sql = "SELECT DISTINCT goods_id FROM " . DB_PREFIX . "category_goods WHERE cate_id " . db_create_in($cate_ids);
			$goods_ids = $db->getCol($sql);
			$conditions .= " AND g.goods_id " . db_create_in($goods_ids);
		}
		
		$keyword = trim($_GET['keyword']);
		if ($keyword) {
			$conditions .= ' AND g.goods_name LIKE "%' . $keyword . '%"';
		}
		
		$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'g.add_time';
		$order = isset($_GET['order']) ? trim($_GET['order']) : 'desc';
		
		
		$page = $this->_get_page();
		$goods_list = array();
		
		$pre_condition = 'g.store_id = ' . $this->_store_id . ' 
				AND g.closed = 0 AND gs.stock > 0
				AND NOT EXISTS(SELECT * FROM ' . DB_PREFIX . 'auction_goods AS ag 
					INNER JOIN ' . DB_PREFIX . 'goods AS g2 ON g2.goods_id = ag.goods_id
					INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = ag.auction_id
					WHERE a.end_time >= "' . get_system_date() . '"
						AND (a.status = "' . EnumStatus::VALID . '" OR a.status = "' . EnumStatus::PASS . '")
						AND ag.status = "' . EnumStatus::VALID . '"
						AND g2.goods_id = g.goods_id
				) AND NOT EXISTS (SELECT * FROM ' . DB_PREFIX . 'auction_goods AS ag 
					INNER JOIN ' . DB_PREFIX . 'goods AS g2 ON g2.goods_id = ag.goods_id
					INNER JOIN ' . DB_PREFIX . 'auction AS a ON a.auction_id = ag.auction_id
					WHERE a.type = "' . Auction::TYPE_MARKET . '" AND g2.goods_id = g.goods_id
				)';
		
		$tables = DB_PREFIX . 'goods AS g 
			LEFT JOIN ' . DB_PREFIX . 'goods_spec AS gs ON gs.goods_id = g.goods_id';
		
		$select = 'g.*, gs.stock';
		$select_count = 'COUNT(g.goods_id)';
		$sql = 'SELECT ' . $select . ' FROM ' . $tables . '
			WHERE ' . $pre_condition . $conditions . '
			ORDER BY ' . $sort . ' ' . $order . '
			LIMIT ' . $page['limit'];
		$sql_count = 'SELECT ' . $select_count . ' FROM '. $tables .'
			WHERE ' . $pre_condition . '  ' . $conditions . '
		';
		
		$count = $db->getOne($sql_count);
		$page['item_count'] = $count;
		
		$goods_list = $db->getAll($sql);
		$_goods_mod = &m('goods');
		foreach ($goods_list as $key => $goods)
		{
			$goods_list[$key]['cate_name'] = $_goods_mod->format_cate_name($goods['cate_name']);
			//使用默认图片
			$goods['default_image'] || $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
		}
		
		
		
		
		$this->assign('goods_list', $goods_list);
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('order', $order);
		$this->assign('sort', $sort);
		$this->assign('filtered', $conditions != '');
		$this->import_resource(array(
			'script' => array(
				array(
					'path' => 'dialog/dialog.js',
					'attr' => 'id="dialog_js" charset="utf-8"',
				),
				array(
					'path' => 'jquery.ui/jquery.ui.js',
					'attr' => 'charset="utf-8"',
				),
				array(
					'path' => 'jquery.plugins/jquery.validate.js',
					'attr' => 'charset="utf-8"',
				),
				array(
					'path' => 'utils.js',
					'attr' => 'charset="utf-8"',
				),
			),
			'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_auction'), 'index.php?app=my_auction',
			LANG::get('add_goods'));
		
		$this->_curitem('my_auction');
		$this->_curmenu('add_goods');
		//$this->import_resource(array('script' => 'utils.js'));
		$this->_config_seo('title', Lang::get('my_auction') . ' - ' . Lang::get('add_goods'));
		//$this->assign('goods_ids', implode(',', array_keys($all_goods)));
		$this->display('my_auction.add_goods.html');
	}
	/**
	 * 已经我已经添加的拍卖品列表
	 */
	public function my_goods()
	{
		$auction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$db = db();
		$page = $this->_get_page();
		
		$sort = isset($_GET['sort']) && $_GET['sort'] != '' ? trim($_GET['sort']) : 'update_time';
		$order = isset($_GET['order']) && $_GET['order'] != '' ? trim($_GET['order']) : 'desc';
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'goods AS g 
			INNER JOIN ' . DB_PREFIX . 'auction_goods AS ag ON ag.goods_id = g.goods_id
			WHERE ag.auction_id = ' . $auction_id . ' 
			ORDER BY ' . $sort . ' ' . $order . '
			LIMIT ' . $page['limit'] . '
		';
		$sql_count = 'SELECT COUNT(ag.goods_id) FROM ' . DB_PREFIX . 'goods AS g 
			INNER JOIN ' . DB_PREFIX . 'auction_goods AS ag ON ag.goods_id = g.goods_id
			WHERE ag.auction_id = ' . $auction_id . ' ';
		$count = $db->getOne($sql_count);
		$page['item_count'] = $count;
		
		$goods_list = $db->getAll($sql);
		$now = time();
		foreach ($goods_list as $key => $goods) {
			//使用默认图片
			$goods['default_image'] || $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
			$start_time_stamp = strtotime($goods['start_time']);
			$end_time_stamp = Auction::getEndTimeStamp($goods['end_time']);
			if ($start_time_stamp > $now) {
				$goods_list[$key]['status'] = Lang::get('not_started');
				$goods_list[$key]['can_edit'] = true;
			}
			
			if ($start_time_stamp <= $now && $end_time_stamp > $now) {
				$goods_list[$key]['status'] = Lang::get('inprocess');
			}
			
			if ($end_time_stamp < $now) {
				$goods_list[$key]['status'] = Lang::get('out_of_date');
			}
		}
		
		/**
		 * @todo 判断添加拍卖品的按钮是否可以存在
		 */
		$user_id = intval($this->visitor->get('user_id'));
		$can_goods = Auction::canAddGoods($user_id, $auction_id);
		
		$this->assign('can_goods', $can_goods);
		
		$this->import_resource(array(
			'script' => array(
				array(
					'path' => 'dialog/dialog.js',
					'attr' => 'id="dialog_js" charset="utf-8"',
				),
				array(
					'path' => 'jquery.ui/jquery.ui.js',
					'attr' => 'charset="utf-8"',
				),
				array(
					'path' => 'jquery.plugins/jquery.validate.js',
					'attr' => 'charset="utf-8"',
				),
				array(
					'path' => 'utils.js',
					'attr' => 'charset="utf-8"',
				),
			),
			'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_auction'), 'index.php?app=my_auction',
			LANG::get('my_auction_goods'));
		
		$this->_curitem('my_auction');
		$this->_curmenu('my_auction_goods');
		//$this->import_resource(array('script' => 'utils.js'));
		$this->_config_seo('title', Lang::get('my_auction') . ' - ' . Lang::get('my_auction_goods'));
		
		$this->_format_page($page);
		$this->assign('page_info', $page);
		$this->assign('goods_list', $goods_list);
		$this->display('my_auction.my_goods.html');
	}
	/**
	 * 添加到拍卖会
	 */
	public function edit_goods()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;
		$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		
		$_good_mod = &m('goods');
		$_auction_mod = &m('auction');
		$_auction_goods = &m('auction_goods');
		
		$a_goods = $_auction_goods->get_info($id);
		$auction = $_auction_mod->get_info($auction_id);
		$goods = $_good_mod->get_info($goods_id);
		
		if (!IS_POST) {
			
			$start_time = time() > strtotime($auction['start_time']) ? get_system_date() : $auction['start_time'];
			$a_goods['start_time'] = $a_goods['start_time'] ? $a_goods['start_time'] : $start_time;
			$a_goods['end_time'] = $a_goods['end_time'] ? $a_goods['end_time'] : $auction['end_time'];
			
			$this->assign('auction', $auction);
			$this->assign('goods', $a_goods);
			$this->assign('goods_name', $goods['goods_name']);
			$this->display('my_auction.edit_goods.html');
		}
		else {
			$start_time = trim($_POST['start_time']);
			$end_time = trim($_POST['end_time']);
			
			$start_time_stamp = strtotime($start_time);
			$end_time_stamp = Auction::getEndTimeStamp($end_time);
			if ($start_time_stamp < time()) {//--临时关闭
				//$this->pop_warning('auction_goods_start_time_earlier_than_now');
				//return ;
			}
			
			if ($auction['type'] != Auction::TYPE_MARKET) {			//if no market check 
				if ($start_time_stamp < strtotime($auction['start_time'])) {
					$this->pop_warning('auctions_goods_start_time_ealier_than_auction_start_time');
					return ;
				}
				
				if ($end_time_stamp > auction::getEndTimeStamp($auction['end_time'])) {
					$this->pop_warning('auction_goods_end_time_later_than_auction_end_time');
					return ;
				}
			}
			
			if ($end_time_stamp < $start_time_stamp) {
				$this->pop_warning('auction_goods_end_time_earlier_than_start_time');
				return ;
			}
			
			
			$step = intval($_POST['step_price']);
			$min = intval($_POST['min_price']);
			
			if ($step <= 0) {
				$this->pop_warning('auction_goods_step_price_should_larger_than_0');
				return ;
			}
			
			if ($min <= 0) {
				$this->pop_warning('auction_goods_min_price_should_larger_than_0');
				return ;
			}
			//验证是否可以继续添加商品
			$goods_num = $_auction_goods->getMyGoodsNum($auction_id, $this->_store_id);
			if (($goods_num + 1) * $auction['put_money'] > $auction['keep_money']) {
				$this->pop_warning('total_put_money_has_beyond_keeper_money');
				return ;
			}
			//验证该产品是否已经存在
			if ($_auction_goods->checkGoodsExists($goods_id, $auction_id)) {
				$this->pop_warning('goods_has_already_be_used');
				return ;
			}
			
			$data = array(
				'min_price' => $min,
				'step_price' => $step,
				'curr_price' => $min,
				'update_time' => get_system_time(),
				'auction_id' => $auction_id,
				'start_time' => $start_time,
				'end_time' => $end_time,
				'goods_id' => $goods_id,
				'create_user' => intval($this->visitor->get('user_id')),
				'create_time' => get_system_time(),
				'status' => EnumStatus::VALID
			);
			
			if ($a_goods) {
				$_auction_goods->edit($id, $data);
			}
			else {
				//扣除上架费到平台
				$bank_id = $this->visitor->get('user_id');
				$this->payCautionMoneyToPlat($bank_id, $auction_id,$goods_id);
				$_auction_goods->add($data);
			}
			//更新拍卖会商品数
			$_auction_mod->updateGoodsNum($auction_id);
			
			$this->pop_warning('ok', APP . '_'. ACT);
		}
	}
	/**
	 * 集市拍卖
	 */
	public function market()
	{
		$user_id = intval($this->visitor->get('user_id'));
		/* 当前页面信息 */
		$this->_curlocal(LANG::get('member_center'), 'index.php?app=member',
			LANG::get('my_auction'), 'index.php?app=my_auction',
			LANG::get('auction_market'));
		
		$this->_curitem('my_auction');
		$this->_curmenu('auction_market');
		//$this->import_resource(array('script' => 'utils.js'));
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('auction_market'));
		
		$page = $this->_get_page();
		$page['item_count'] = 1;			//集市只有一条数据
		$auction_list = $this->_auction_mod->find(array(
			'conditions' => 'type="' . Auction::TYPE_MARKET . '"'
		));
		
		$auction = current($auction_list);
		
		//get market auction user
		$_mod_auction_user = &m('auction_user');
		$arr_user = $_mod_auction_user->getAuctionUser($auction['auction_id']);
		$arr_user_id = array();
		foreach ($arr_user as $user) {
			$arr_user_id[] = $user['user_id'];
		}
		
		foreach ($auction_list as $index => $auction) {
			//没有报名的可以报名
			if (!in_array($user_id, $arr_user_id)) {
				$auction_list[$index]['can_apply'] = true;
			}
			else {			//报过名，但是未审核通过提示等待
				$i = array_search($user_id, $arr_user_id);
				if ($arr_user[$i]['status'] == EnumStatus::NOT_APPROVE) {
					$auction_list[$index]['can_apply'] = false;
					$auction_list[$index]['apply_status'] = Lang::get('wait_for_apply_approve');
				}
				else if ($arr_user[$i]['status'] == EnumStatus::DENY) {
					$auction_list[$index]['can_apply'] = true;
				}
				else if ($arr_user[$i]['status'] == EnumStatus::PASS) {
					$auction_list[$index]['can_goods'] = true;
				}
				
			}
		}
		
		$this->assign('auction_list', $auction_list);
		$this->display('my_auction.market.html');
	}
	/**
	 * 卖家拍卖子菜单
	 * @see MemberbaseApp::_get_member_submenu()
	 */
	public function _get_member_submenu() {
		// if (ACT == 'index' || ACT == 'apply_list') {
		$menus = array (
			array (
				'name' => 'auction_list',
				'url' => 'index.php?app=my_auction' 
			),
			array (
				'name' => 'apply_list',
				'url' => 'index.php?app=my_auction&amp;act=apply_list' 
			),
			array(
				'name' => 'auction_market',
				'url' => 'index.php?app=my_auction&act=market'	
			)
		);
		if (ACT == 'add_goods') {
			$menus[] = array('name' => 'add_goods');
		}
		
		if (ACT == 'my_goods') {
			$menus[] = array('name' => 'my_auction_goods');
		}
		// }
		return $menus;
	}
	/* 取得本店所有商品分类 */
	function _get_sgcategory_options()
	{
		$mod =& bm('gcategory', array('_store_id' => $this->_store_id));
		$gcategories = $mod->get_list();
		import('tree.lib');
		$tree = new Tree();
		$tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
		return $tree->getOptions();
	}
	
	/**
	 * 从保证金中扣除上架费到平台;
	 */
	public function payCautionMoneyToPlat($bank_id, $auction_id, $goods_id)
	{
		$m_auction = &m('auction');
		$info = $m_auction->get_info($auction_id);
		if ($info['put_money'] > 0)
		{
			/*
			 $pay = &m('pay');
			$pay->getAuctionCautionPayInfo($auction_id); //获取保证金的记录；
			*/
			$bank = &m('bank');
			$arr_bank = $bank->get_info($bank_id);
			if ($arr_bank['money'] < $info['put_money'])
			{
				// echo '金额不足';
				$this->pop_warning('金额不足');
				exit;
			}
			$money = $info['put_money'];
			$bank = &m('bank');
			$bank->payAccountToCautionMoney($bank_id, $money);
			if ($bank->has_error()) {
				$this->pop_warning($bank->get_error());
				exit;
			}
			$pay = &m('pay');
			$arr_pay = array(
				'order_id' => $auction_id .'_'.$goods_id,
				'type' => 'T', //上架费
				'money'  =>$money,
				'buyer_id'    => $bank_id,
				'buyer_name' => $this->visitor->get('user_name'),
				'seller_name' => '平台中心',
				'seller_id' => '0',
				'pay_name' => '上架费',
				'status' => ORDER_ACCEPTED,
				'create_time' => gmtime(),
				'create_user' => $bank_id
			);
			$pay_id = $pay->add($arr_pay);
			if ($pay->has_error()) {
				$this->pop_warning($pay->get_error());
				exit;
			}
			$time = get_system_time();
	
			$bank->payCautionMoneyToPlanByPayId($pay_id, $bank_id, $time);
			if ($bank->has_error()) {
				$this->pop_warning($bank->get_error());
				exit;
			}
			//平台增加金额;
			$account = & m('account');
			$account->addMoney($money, '上架费', $pay_id, $bank_id);
			if ($account->has_error()) {
				$this->pop_warning($account->get_error());
				exit;
			}
		}
	}
}