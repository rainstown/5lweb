<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $this->_var['site_url']; ?>/" />

<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7 charset=<?php echo $this->_var['charset']; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_var['charset']; ?>" />
<?php echo $this->_var['page_seo']; ?>
<meta name="author" content="ecmall.shopex.cn" />
<meta name="generator" content="ECMall <?php echo $this->_var['ecmall_version']; ?>" />
<meta name="copyright" content="ShopEx Inc. All Rights Reserved" />

<link href="<?php echo $this->res_base . "/" . 'css/ecmall.css'; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->res_base . "/" . 'css/css.css'; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="index.php?act=jslang"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/nav.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/select.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
//]]>
</script>

<?php echo $this->_var['_head_tags']; ?>
<!--<editmode></editmode>-->
</head>
<body >
<div class="body_content">
<div id="header">
     <div id="head" >
        <div class="fl"><a href="index.php" title="<?php echo htmlspecialchars($this->_var['site_title']); ?>"><img src="<?php echo $this->_var['site_logo']; ?>"/></a></div>
        <div class="header_login fr" style="width:620px">
            
            <?php if (! $this->_var['visitor']['user_id']): ?>
            <form method="post" id="login_form" action="index.php?app=member&act=login" style="float:right">
           
                用户名:<input type="text" class="login_row" name="user_name"  placeholder="输入用户名" >&nbsp;密　码:<input name="password" type="password" class="login_row">&nbsp;<input type="submit" value="登录" class="bt2 "/>&nbsp;<input type="button" onclick="javascript:window.location.href='<?php echo url('app=member&act=register&ret_url=' . $this->_var['ret_url']. ''); ?>'" value="注册" class="bt3 "/>
                 <span style="display: none">您好,<?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?>
                    [<a href="<?php echo url('app=member&act=login&ret_url=' . $this->_var['ret_url']. ''); ?>">登录</a>]
                    [<a href="<?php echo url('app=member&act=register&ret_url=' . $this->_var['ret_url']. ''); ?>">注册</a>]
                </span>
            </form>
            <?php else: ?><span>
            您好,<?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?>
            [<a href="<?php echo url('app=member&act=logout'); ?>">退出</a>]</span>
            
            <a class="shopping" href="<?php echo url('app=cart'); ?>">购物车</a> <span>|</span>
            <a class="favorite" href="<?php echo url('app=my_favorite'); ?>">收藏夹</a> <span>|</span>
            <a class="note" href="<?php echo url('app=message&act=newpm'); ?>">站内消息<?php if ($this->_var['new_message']): ?>(<?php echo $this->_var['new_message']; ?>)<?php endif; ?></a> <span>|</span>
            <a class="help" href="<?php echo url('app=article&code=help'); ?>">帮助中心</a>
            
            <div id="topbtn">
                <div class="topbtn1"></div>
                <div class="topbtn2"></div>
                <span id="child_nav">
                    <a class="link user" href="<?php echo url('app=member'); ?>">用户中心</a>
                    <ul id="float_layer">
                        <div id="adorn1"></div>
                        <div id="adorn2"></div>
                        <?php if ($this->_var['visitor']['store_id']): ?>
                        <li><a href="<?php echo url('app=my_goods'); ?>">商品管理</a></li>
                        <li><a href="<?php echo url('app=seller_order'); ?>">订单管理</a></li>
                        <li><a href="<?php echo url('app=my_qa'); ?>">咨询管理</a></li>
                        <?php else: ?>
                        <li><a href="<?php echo url('app=buyer_order'); ?>">我的订单</a></li>
                        <li><a href="<?php echo url('app=my_question'); ?>">我的咨询</a></li>
                        <?php endif; ?>
                    </ul>
                </span>
                <span>|</span>
                <a class="link" href="<?php echo url('app=category'); ?>">我要买</a>
                <span>|</span>
                <a class="link" href="<?php echo url('app=my_goods&act=add'); ?>">我要卖</a>
            </div>
            
            <?php endif; ?>
            <?php $_from = $this->_var['navs']['header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
            <li class="line" style="margin-top: 20px"><a href="<?php echo $this->_var['nav']['link']; ?>"<?php if ($this->_var['nav']['open_new']): ?> target="_blank"<?php endif; ?>><?php echo htmlspecialchars($this->_var['nav']['title']); ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            
        </div>
    </div>
    <div class="nav_wrap">
        <div id="navA">
        <div class="navAL fl">&nbsp;</div>
        <div class="navAR fr">&nbsp;</div>
        <div class="navA">
                <ul class="cc">
                     <li class="<?php if ($this->_var['index_page']): ?>current<?php else: ?>hover<?php endif; ?>" ><a href="index.php"><span>首页</span></a></li>
                    <?php $_from = $this->_var['navs']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
                    <li class="<?php if ($this->_var['nav']['link'] == $this->_var['current_url']): ?>current<?php else: ?>hover<?php endif; ?>" ><a href="<?php echo $this->_var['nav']['link']; ?>"<?php if ($this->_var['nav']['open_new']): ?> target="_blank"<?php endif; ?>><span><?php echo htmlspecialchars($this->_var['nav']['title']); ?></span></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                       
                </ul>
        </div>
        </div>
    </div>
    <div id="searchA">
    	<form method="GET" action="<?php echo url('app=search'); ?>">
        <div class="searchA cc">
            <div class="s_select select_js">
                    <ul >
                        <li ectype="index">搜索商品</li>
                        <li ectype="store">搜索店铺</li>
                    </ul>
                    <h6 class="w" id="search_act_name">搜索商品</h6>
                    <input id="search_act" type="hidden" name="act" value="index" /></div>
                    
	            <div class="ip"><input id="search_input" type="text" value="<?php echo $this->_var['keyword']; ?>" class="gray" name="keyword" onfocus="searchFocus(this)" onblur="searchBlur(this)"></div>
                    <input type="hidden" name="app" value="search" />
            <button type="submit" class="fl cp">搜索</button>
        </div>
        </form>
    </div>
</div>