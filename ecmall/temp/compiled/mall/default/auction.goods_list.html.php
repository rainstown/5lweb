<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'search_goods.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
var upimg   = '<?php echo $this->res_base . "/" . 'images/up.gif'; ?>';
var downimg = '<?php echo $this->res_base . "/" . 'images/down.gif'; ?>';
imgUping = new Image();
imgUping.src = upimg;
</script>

<?php echo $this->fetch('curlocal.html'); ?>

<style type="text/css">
#auction_goods_list .squares li{height: 300px;}
</style>
<div class="content">
    <?php if ($this->_var['goods_list']): ?>
    <div class="left">
        <div class="module_sidebar">
            <h2><b></b></h2>
            <div class="wrap">
                <div class="wrap_child">
                    <div class="side_textlist">
                        <ul ectype="ul_category">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
    <div class="right">
        <div class="shop_con_list" id="auction_goods_list">
            <h2>
                <div class="ornament1"></div>
                <div class="ornament2"></div>
                <div class="h2_wrap">
                    <div class="table_title">
                        <p class="title">显示:</p>
                        <p class="list_ico" ectype="display_mode" ecvalue="list" title="display_by_list"></p>
                        <p class="squares_ico" ectype="display_mode" ecvalue="squares" title="display_by_item"></p>
                        <p class="line_ico"></p>
                       	<!-- 
                        <p class="title">搜索:</p>
                        <p>
                        	<select name="condition">
                        	</select>
                        </p>
                       	 -->
                    </div>
                    <div class="top_page">
                        <?php echo $this->fetch('page.top.html'); ?>
                    </div>
                </div>
            </h2>

            <div class="<?php echo $this->_var['display_mode']; ?>" ectype="current_display_mode">
                <?php if ($this->_var['goods_list']): ?>
                <ul class="list_pic">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                    <li>
                        <p><a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" /></a></p>
                        <h3>
                            <span class="text_link">
                                <span class="depict">
                                    <a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a>
                                </span>
                                <span class="info">
                                    <span class="fontColor5"><?php echo htmlspecialchars($this->_var['goods']['grade_name']); ?></span>
                                </span>
                            </span>
                            <span class="price"><?php echo price_format($this->_var['goods']['curr_price']); ?></span>
                            <b>起拍价: <?php echo price_format($this->_var['goods']['min_price']); ?></b>
                            <!--  <b><a href="<?php echo url('app=goods&act=records&goods_id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank">出价数:<?php echo ($this->_var['goods']['pay_num'] == '') ? '0' : $this->_var['goods']['pay_num']; ?></a></b> -->
                            <b>出价数:<?php echo ($this->_var['goods']['pay_num'] == '') ? '0' : $this->_var['goods']['pay_num']; ?></b>
                            <b><span class="fontColor5"><?php echo $this->_var['goods']['time_status']; ?></span></b>
                        </h3>
                    </li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                    <div class="clear"></div>
                </ul>
                <?php else: ?>
                <div id="no_results">没有符合条件的记录</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="shop_list_page">
            <?php echo $this->fetch('page.bottom.html'); ?>
        </div>
    </div>
    <?php else: ?>
    <div class="module_common">
        <p class="no_info">没有符合条件的记录</p>
    </div>
    <?php endif; ?>
</div>

<?php echo $this->fetch('footer.html'); ?>
