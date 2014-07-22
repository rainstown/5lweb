<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
    function setTab(name, cursel, n) {
        for (i = 1; i <= n; i++) {
          var menu = document.getElementById(name + i);
          var con = document.getElementById("con_" + name + "_" + i);
          menu.className = i == cursel ? "hover": "";
          con.style.display = i == cursel ? "block": "none";
        }
      }
$(function() {
	var sWidth = $("#focus").width(); //获取焦点图的宽度（显示面积）
	var len = $("#focus ul li").length; //获取焦点图个数
	var index = 0;
	var picTimer;
	
	//以下代码添加数字按钮和按钮后的半透明条，还有上一页、下一页两个按钮
	var btn = "<div class='btnBg'></div><div class='btn1'>";
	for(var i=0; i < len; i++) {
		btn += "<span></span>";
	}
	btn += "</div><div class='preNext pre'></div><div class='preNext next'></div>";
	$("#focus").append(btn);
	$("#focus .btnBg").css("opacity",0.5);

	//为小按钮添加鼠标滑入事件，以显示相应的内容
	$("#focus .btn1 span").css("opacity",0.4).mouseenter(function() {
		index = $("#focus .btn1 span").index(this);
		showPics(index);
	}).eq(0).trigger("mouseenter");

	//上一页、下一页按钮透明度处理
	$("#focus .preNext").css("opacity",0.2).hover(function() {
		$(this).stop(true,false).animate({"opacity":"0.5"},300);
	},function() {
		$(this).stop(true,false).animate({"opacity":"0.2"},300);
	});

	//上一页按钮
	$("#focus .pre").click(function() {
		index -= 1;
		if(index == -1) {index = len - 1;}
		showPics(index);
	});

	//下一页按钮
	$("#focus .next").click(function() {
		index += 1;
		if(index == len) {index = 0;}
		showPics(index);
	});

	//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
	$("#focus ul").css("width",sWidth * (len));
	
	//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$("#focus").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			showPics(index);
			index++;
			if(index == len) {index = 0;}
		},4000); //此4000代表自动播放的间隔，单位：毫秒
	}).trigger("mouseleave");
	
	//显示图片函数，根据接收的index值显示相应的内容
	function showPics(index) { //普通切换
		var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
		$("#focus ul").stop(true,false).animate({"left":nowLeft},300); //通过animate()调整ul元素滚动到计算出的position
		//$("#focus .btn span").removeClass("on").eq(index).addClass("on"); //为当前的按钮切换到选中的效果
		$("#focus .btn1 span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //为当前的按钮切换到选中的效果
	}
});

function setAuctionTab(elem) {
	var $elem = $(elem);
	$elem.parent().children().removeClass('hover');
	$elem.addClass('hover');
	var con_id = 'con_' + elem.id;
	var target = $('#' + con_id);
	target.parent().children().hide();
	target.show();
}

</script>
 <?php $_from = $this->_var['ad_list_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');if (count($_from)):
    foreach ($_from AS $this->_var['ad']):
?>
<div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
 <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>"  /></div>
 <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<div class="main">

	<div class="index_help fl" >
		<i ><a href="<?php echo url('app=article&code=help'); ?>">更多...</a></i>
		<ul>
                    <?php $_from = $this->_var['help_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['article']):
?>
                <li><a <?php if ($this->_var['article']['link']): ?>target="_blank"<?php endif; ?> href="<?php echo url('app=article&act=view&article_id=' . $this->_var['article']['article_id']. ''); ?>" ><?php echo htmlspecialchars($this->_var['article']['title']); ?></a></li>
                <?php endforeach; else: ?>
                  <li>没有符合条件的记录</li>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
	</div>
	<div class="index_jiao">
	<div class="index_banner fl">
	
	
	<div id="focus">
		<ul>
                     <?php $_from = $this->_var['ad_list_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');if (count($_from)):
    foreach ($_from AS $this->_var['ad']):
?>
                      <li><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>"  target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></li>
                     <?php endforeach; else: ?>
                        <li><a href="#"><img src="<?php echo $this->res_base . "/" . 'images/01.jpg'; ?>"  /></a></li>
			<li><a href="#"><img src="<?php echo $this->res_base . "/" . 'images/02.jpg'; ?>"  /></a></li>
			<li><a href="#"><img src="<?php echo $this->res_base . "/" . 'images/03.jpg'; ?>" /></a></li>
			<li><a href="#"><img src="<?php echo $this->res_base . "/" . 'images/04.jpg'; ?>" /></a></li>
                     <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	
	</div>
	
	<div class="index_news fl">
		<div class="index_news_1"><span class="fl">网站公告</span><span class="fr"><a href="<?php echo url('app=article&code=web'); ?>">更多...</a></span></div>
		<ul>
                
		<?php $_from = $this->_var['web_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['article']):
?>
                <li><a <?php if ($this->_var['article']['link']): ?>target="_blank"<?php endif; ?> href="<?php echo url('app=article&act=view&article_id=' . $this->_var['article']['article_id']. ''); ?>" ><?php echo htmlspecialchars($this->_var['article']['title']); ?></a></li>
                <?php endforeach; else: ?>
                  <li>没有符合条件的记录</li>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                
                </ul>
	
	</div></div>

</div>
<div class="clear" ></div>

<div  class="main padding_top"><img src="<?php echo $this->res_base . "/" . 'images/luntan.jpg'; ?>" width="980" height="34"></div>
<div class="main">
	<div class="index_lutan fl">
		<b>最新帖子</b>
		<ul>
                    <?php $_from = $this->_var['dz_news_post']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <li><a href="bbs/forum.php?mod=viewthread&tid=<?php echo $this->_var['list']['tid']; ?>" target="_blank"><?php echo $this->_var['list']['subject']; ?></a><span><?php echo $this->_var['list']['views']; ?></span></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
	
	</div>
	<div class="index_lutan_1  fl">
	
		
     
        <div class="Menubox1">
              <ul>
            <li id="one1" onmouseover="setTab('one',1,1)" >最新回复</li>
            <li id="one2" onmouseover="setTab('one',2,2)" style="display: none">排行榜</li>
     
          </ul>
            </div>
        <div class="Contentbox1 ">
            <div id="con_one_1" class="con_one" style="display:block">  
          	<ul>
                    <?php $_from = $this->_var['dz_rep_post']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <li><a href="bbs/forum.php?mod=viewthread&tid=<?php echo $this->_var['list']['tid']; ?>" target="_blank"><?php echo $this->_var['list']['subject']; ?></a><span><?php echo $this->_var['list']['replies']; ?></span></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
          </div>
              <div id="con_one_2" class="con_one"  style="display:none">	  
          	<ul>
		</ul>
            </div>
          
         
           </div>
         
		
	
	</div>
	<div class="index_lutan_2 fl">
	
	
		
     
        <div class="Menubox2">
              <ul>
            <li id="two1" onmouseover="setTab('two',1,1)" >活跃会员</li>
            <li id="two2" onmouseover="setTab('two',2,2)"  style="display: none">活跃会员</li>
     
          </ul>
            </div>
        <div class="Contentbox1 Contentbox2">
              <div id="con_two_1" class="con_two" style="display:block">  
          	<ul>
                    
		</ul>
            </div>
              <div id="con_two_2" class="con_two" style="display:none">  
          	<ul></ul>
            </div>
          
         
           </div>
         
	
	
	</div>

</div>


<div class="clear" ></div>
 <?php $_from = $this->_var['ad_list_3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');if (count($_from)):
    foreach ($_from AS $this->_var['ad']):
?>
<div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
 <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>"  /></div>
 <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>

<div  class="main"><img src="<?php echo $this->res_base . "/" . 'images/luntan_1.jpg'; ?>" width="980" height="34"></div>
<div class="main">
	<div class="index_pm">
	
		
     
        <div class="Menubox3">
              <ul>
         
			
			 <li id="three1" onmouseover="setTab('three',1,4)" class="hover">最新竞拍</li>
            <li id="three2" onmouseover="setTab('three',2,4)">排行榜</li>
           
     
          </ul>
            </div>
        <div class="Contentbox3">
             
              <div id="con_three_1" >
			  
          	<ul>
          		<?php $_from = $this->_var['starting_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
					<li><b><a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></b>出价<?php echo $this->_var['goods']['pay_num']; ?>次 | <h2>￥<?php echo $this->_var['goods']['curr_price']; ?>元</h2></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
          </div>
		  
		   <div id="con_three_2" style="display:none" >
			  
          	<ul>
          		<?php $_from = $this->_var['popular_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
					<li><b><a href="<?php echo url('app=goods&act=auction&id=' . $this->_var['goods']['goods_id']. '&auction_id=' . $this->_var['goods']['auction_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></b>出价<?php echo $this->_var['goods']['pay_num']; ?>次 | <h2>￥<?php echo $this->_var['goods']['curr_price']; ?>元</h2></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
          </div>
          
         
           </div>
         
	
	
	</div>
	<div class="index_pm_1 " >
		<div class="index_pm_right"><div class="index_pm_2"><img src="http://scp.weilearn.com/themes/mall/default/styles/default/images/smal-new-icon.gif"></div>
		
		<span class="fl">三多专场---正在进行中</span>
		<span class="fr"><a href="<?php echo url('app=auction&act=auction_list&type=O'); ?>">查看更多</a></span>
		<div class="clear"></div>
		
		
     
        <div class="Menubox4">
            <ul>
				<?php $_from = $this->_var['o_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
					<li id="four_<?php echo $this->_var['index']; ?>" onmouseover="setAuctionTab(this);"><?php echo $this->_var['auction']['name']; ?></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
            </div>
        <div class="Contentbox4 ">
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
			<span class="fl">个人拍卖---正在进行中</span>
		<span class="fr"><a href="<?php echo url('app=auction&act=auction_list&type=P'); ?>">查看更多</a></span></div>
		<div class="clear"></div>
		
		
			
     
        <div class="Menubox4">
              <ul>
           <?php $_from = $this->_var['p_auction']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('index', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['index'] => $this->_var['auction']):
?>
					<li id="five_<?php echo $this->_var['index']; ?>" onmouseover="setAuctionTab(this);" <?php if ($this->_var['index'] == 0): ?>class="hover"<?php endif; ?>><?php echo $this->_var['auction']['name']; ?></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
            </div>
        <div class="Contentbox4 ">
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
<div class="clear"></div>
 <?php $_from = $this->_var['ad_list_4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');if (count($_from)):
    foreach ($_from AS $this->_var['ad']):
?>
<div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
 <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>"  /></div>
 <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<div  class="main"><img src="<?php echo $this->res_base . "/" . 'images/luntan_2.jpg'; ?>" width="980" height="34"></div>
<div class="main">
	<div class="index_dp fl">
		<b>店铺推荐</b>
                <div class="tjdp_2 ">
                    <ul>
                     <?php $_from = $this->_var['r_stores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'stores');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['stores']):
        $this->_foreach['foo']['iteration']++;
?>
                    <li><span><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><img src="<?php echo ($this->_var['stores']['store_logo'] == '') ? 'data/system/default_store_logo.gif' : $this->_var['stores']['store_logo']; ?>" width="46" height="46"></a></span><h2><a href="<?php echo url('app=store&id=' . $this->_var['stores']['store_id']. ''); ?>"><?php echo $this->_var['stores']['store_name']; ?></a></h2><h3><?php echo $this->_var['stores']['region_name']; ?><?php echo $this->_var['stores']['user_name']; ?></h3></li>
                    <?php endforeach; else: ?>
                     <li><span><img src="<?php echo $this->res_base . "/" . 'images/100_50.jpg'; ?>" ></span><h2>没有符合条件的记录</h2></li>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
		</ul>
                </div>
	</div>
	
	<div class="index_dp_2 fr">
        <ul>
        <?php $_from = $this->_var['rec_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
        <li><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"><img src="<?php echo $this->_var['goods']['default_image']; ?>"></a><h2><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></h2><h3>店主：<?php echo $this->_var['goods']['owner_name']; ?></h3></li>
        <?php endforeach; else: ?>
        <li>没有符合条件的记录</li>
        
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul></div>

</div>

<?php $_from = $this->_var['ad_list_5']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');if (count($_from)):
    foreach ($_from AS $this->_var['ad']):
?>
<div class="main gg_980_80"><a href="<?php echo $this->_var['ad']['link']; ?>" title="<?php echo $this->_var['ad']['title']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['logo']; ?>" ></a></div>
 <?php endforeach; else: ?>
 <div class="main gg_980_80"><img src="<?php echo $this->res_base . "/" . 'images/adv_1.jpg'; ?>"  /></div>
 <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>

<div class="main">
<div class="foot gg_980_80"><ul>
<?php $_from = $this->_var['navs']['footer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
        <li> <a href="<?php echo $this->_var['nav']['link']; ?>"<?php if ($this->_var['nav']['open_new']): ?> target="_blank"<?php endif; ?>><?php echo htmlspecialchars($this->_var['nav']['title']); ?></a></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>        
</ul></div></div>
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