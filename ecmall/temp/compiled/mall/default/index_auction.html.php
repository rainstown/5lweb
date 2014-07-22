<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
function setTab(elem) {
	var $elem = $(elem);
	$elem.parent().children().removeClass('hover');
	$elem.addClass('hover');
	var con_id = 'con_' + elem.id;
	var target = $('#' + con_id);
	target.parent().children().hide();
	target.show();
}
</script>

<div class="main gg_980_80">
	<div class="fl ">
		<div class="fl">
			<div class="menuright_banner_1 fl">
				
				<div class="picb_1">
					<div class="picb_2">
						<script language="javascript" type="text/javascript">
                                                    //<![CDATA[
                                                    var _t1 = 0; //打开页面时等待图片载入的时间，单位为秒，可以设置为0
                                                    var _t2 = 5; //图片轮转的间隔时间
                                                    var _tnum = "<?php echo $this->_var['ppt_num']; ?>"; //焦点图个数
                                                    _tnum = _tnum > 0 ? _tnum : 4;
                                                    var _tn = 1;//当前焦点
                                                    var _tl =null;
                                                    _tt1 = setTimeout('change_img()',_t1*1000);
                                                    function change_img(){
                                                    setFocus(_tn);
                                                    _tt1 = setTimeout('change_img()',_t2*1000);
                                                    }
                                                    function setFocus(i){
                                                     if (i > _tnum) {
                                                        _tn = 1;
                                                        i = 1;
                                                    }
                                                    _tl?document.getElementById('focusPic'+_tl).style.display='none':'';
                                                    document.getElementById('focusPic'+i).style.display='block';
                                                    $('#index_page').find('strong').parent().html($('#index_page').find('strong').html());
                                                    $('#index_page'+i).html('<strong>'+i+'</strong>');

                                                    _tl=i;
                                                    _tn++;

                                                    }
                                                    //]]>
                                                    </script>
						
						<?php $_from = $this->_var['ad_list_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                                    <span id="focusPic<?php echo $this->_foreach['foo']['iteration']; ?>" <?php if ($this->_foreach['foo']['iteration'] == 1): ?>style="display:blank;"<?php else: ?>style="display:none;"<?php endif; ?>>
                                                        <a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" class="i_photo_a"  target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a>
                                                    </span>
                                                <?php endforeach; else: ?>
                                                        <span id="focusPic1">
                                                        <a href="#" class="i_photo_a" target="_blank">
                                                        <img src="<?php echo $this->res_base . "/" . 'images/01.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic2" style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/02.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic3"style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/03.jpg'; ?>"  /></a>
                                                        </span>
                                                        <span id="focusPic4" style="display:none;">
                                                            <a href="#" class="i_photo_a" target="_blank">
                                                            <img src="<?php echo $this->res_base . "/" . 'images/04.jpg'; ?>"  /></a>
                                                        </span>
                                                
                                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                                <div id="index_page">
                                                    <?php $_from = $this->_var['ad_list_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                                    <a href="javascript:setFocus(<?php echo $this->_foreach['foo']['iteration']; ?>);" id="index_page<?php echo $this->_foreach['foo']['iteration']; ?>"><strong><?php echo $this->_foreach['foo']['iteration']; ?></strong></a>
                                                    <?php endforeach; else: ?>
                                                        <a href="javascript:setFocus(1);" id="index_page1"><strong>1</strong></a>
                                                        <a href="javascript:setFocus(2);" id="index_page2">2</a>
                                                        <a href="javascript:setFocus(3);" id="index_page3">3</a>
                                                        <a href="javascript:setFocus(4);" id="index_page4">4</a>
                                                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                                </div>
						

					</div>
				</div>

				


			</div>
			<div class="fl menuright_1">
				 <?php $_from = $this->_var['ad_list_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                <?php if ($this->_foreach['foo']['iteration'] == 1): ?>
                                <div class="menu_190_170 fl"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img width="190" height="170" src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
                                <?php endif; ?>
                             <?php endforeach; else: ?>
                                <div class="menu_190_170 fl"><img src="<?php echo $this->res_base . "/" . 'images/gg_2.jpg'; ?>"></div>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                             <?php $_from = $this->_var['ad_list_3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
                                <?php if ($this->_foreach['foo']['iteration'] == 1): ?>
                                <div class="fl menu_190_80 padding_top"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img width="190" height="80" src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
                                <?php endif; ?>
                              <?php endforeach; else: ?>
                                <div class="fl menu_190_80 padding_top"><img src="<?php echo $this->res_base . "/" . 'images/gg_1.jpg'; ?>"></div>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="fl menuright_2 menuright_4">
				<b>拍卖公告</b>
				<ul>
                                    <?php $_from = $this->_var['articles_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['article']):
?>
                                    <li><a <?php if ($this->_var['article']['link']): ?>target="_blank"<?php endif; ?> href="<?php echo url('app=article&act=view&article_id=' . $this->_var['article']['article_id']. ''); ?>" ><?php echo htmlspecialchars($this->_var['article']['title']); ?></a></li>
                                    <?php endforeach; else: ?>
                                      <li>没有符合条件的记录</li>
                                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
					
				</ul>

			</div>

		</div>



	</div>


</div>

<div class="clear"></div>


 <?php $_from = $this->_var['ad_list_4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['foo']['iteration']++;
?>
    <div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" class="gg_980_80"  target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
    <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>" ></div>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>

<div class="main ">
	<div class="menuright_6 fl">
		<div class="index_pm_right">
			<div class="index_pm_2">
				<img src="<?php echo $this->res_base . "/" . 'images/smal-new-icon.gif'; ?>">
			</div>

			<span class="fl">三多专场---正在进行中</span> <span class="fr">
				<a href="<?php echo url('app=auction&act=auction_list&type=O'); ?>">查看更多</a></span>
			<div class="clear"></div>

			

			<div class="Menubox4">
				<ul>
					<?php $_from = $this->_var['o_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
					<li id="four_<?php echo $this->_var['index']; ?>" onmouseover="setTab(this);"><?php echo $this->_var['auction']['name']; ?></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
			<div class="Contentbox4 menuright_7">
				<?php $_from = $this->_var['o_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
				<div id="con_four_<?php echo $this->_var['index']; ?>">
					<ul>
						<?php $_from = $this->_var['goods_list'][$this->_var['index']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('goods_id', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods_id'] => $this->_var['goods']):
?>
						<li><div>
								<a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="60" height="60"></a>
							</div>
							<i><a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></i><span><?php echo price_format($this->_var['goods']['curr_price']); ?>元</span>
						<h2>
								出价<b><?php echo $this->_var['goods']['pay_num']; ?></b>次
							</h2>
							<h3>
								剩时<b class="goods_last_time"><?php echo $this->_var['goods']['last_time']; ?></b>
							</h3></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			

			<div class="clear"></div>
			<div class="index_pm_right">
				<span class="fl">个人拍卖---正在进行中</span> <span class="fr"><a href="<?php echo url('app=auction&act=auction_list&type=P'); ?>">查看更多</a></span>
			</div>
			<div class="clear"></div>


			

			<div class="Menubox4">
				<ul>
					<?php $_from = $this->_var['p_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
					<li id="five_<?php echo $this->_var['index']; ?>" onmouseover="setTab(this);" <?php if ($this->_var['index'] == 0): ?>class="hover"<?php endif; ?>><?php echo $this->_var['auction']['name']; ?></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
			<div class="Contentbox4 menuright_7">
				<?php $_from = $this->_var['p_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
				<div id="con_five_<?php echo $this->_var['index']; ?>">
					<ul>
					<?php $_from = $this->_var['goods_list'][$this->_var['index']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('goods_id', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods_id'] => $this->_var['goods']):
?>
						<li>
							<div>
								<a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="60" height="60"></a>
							</div>
							<i><a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></i><span><?php echo price_format($this->_var['goods']['curr_price']); ?>元</span>
						<h2>
								出价<b><?php echo $this->_var['goods']['pay_num']; ?></b>次
							</h2>
							<h3>
								剩时<b class="goods_last_time"><?php echo $this->_var['goods']['last_time']; ?></b>
							</h3></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			




		</div>

	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.Menubox4').each(function() {
		var $li = $(this).find('li');
		$li.eq(0).addClass('hover');
	});
	$('.Contentbox4').each(function() {
		$(this).children().hide();
		$(this).children().eq(0).show();
	});
	//计算剩余时间
	$('.goods_last_time').each(function(){
		var $elem = $(this);
		var count = parseInt($elem.text(), 10);
		var countdown = setInterval(function(){
			if (count >= 0) {
				var hour = parseInt(count / 3600, 10);
				var min = parseInt((count - hour * 3600) / 60, 10);
				var sec = count - hour * 3600 - min * 60;
				$elem.text(hour+':'+min+':'+sec);
			}
			count--;
		}, 1000);
	});
});
</script>
<?php echo $this->fetch('footer.html'); ?>
