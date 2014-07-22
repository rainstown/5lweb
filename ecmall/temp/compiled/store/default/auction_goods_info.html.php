<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'goodsinfo.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
/* buy */
function buy()
{
    if (goodsspec.getSpec() == null)
    {
        alert(lang.select_specs);
        return;
    }
    var spec_id = goodsspec.getSpec().id;

    var quantity = $("#quantity").val();
    if (quantity == '')
    {
        alert(lang.input_quantity);
        return;
    }
    if (parseInt(quantity) < 1)
    {
        alert(lang.invalid_quantity);
        return;
    }
    add_to_cart(spec_id, quantity);
}

/* add cart */
function add_to_cart(spec_id, quantity)
{
    var url = SITE_URL + '/index.php?app=cart&act=add';
    $.getJSON(url, {'spec_id':spec_id, 'quantity':quantity}, function(data){
        if (data.done)
        {
            $('.bold_num').text(data.retval.cart.kinds);
            $('.bold_mly').html(price_format(data.retval.cart.amount));
            $('.ware_cen').slideDown('slow');
            setTimeout(slideUp_fn, 5000);
        }
        else
        {
            alert(data.msg);
        }
    });
}

var specs = new Array();
<?php $_from = $this->_var['goods']['_specs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
specs.push(new spec(<?php echo $this->_var['spec']['spec_id']; ?>, '<?php echo htmlspecialchars($this->_var['spec']['spec_1']); ?>', '<?php echo htmlspecialchars($this->_var['spec']['spec_2']); ?>', <?php echo $this->_var['spec']['price']; ?>, <?php echo $this->_var['spec']['stock']; ?>));
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var specQty = <?php echo $this->_var['goods']['spec_qty']; ?>;
var defSpec = <?php echo htmlspecialchars($this->_var['goods']['default_spec']); ?>;
var goodsspec = new goodsspec(specs, specQty, defSpec);

function updatePrice(elem) {
	var curr_price = parseInt($('#auction_goods_curr_price').val());
	var step_price = parseInt($('#auction_goods_step_price').val());
	var n = elem.value;
	var price = curr_price + n * step_price;
	$('#new_auction_pay').text('¥' + price);
	$('#form_new_price').val(price);
}
function paySubmit() {
	$('#auction_pay_form').submit();
}
//]]>
</script>

<h2 class="ware_title"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h2>
<div class="ware_info">
    <div class="ware_pic">
        <div class="big_pic">
            <a href="javascript:;"><span class="jqzoom"><img src="<?php echo ($this->_var['goods']['_images']['0']['thumbnail'] == '') ? $this->_var['default_image'] : $this->_var['goods']['_images']['0']['thumbnail']; ?>" width="300" height="300" jqimg="<?php echo $this->_var['goods']['_images']['0']['image_url']; ?>" /></span></a>
        </div>

        <div class="bottom_btn">
            <!--<a class="collect" href="javascript:collect_goods(<?php echo $this->_var['goods']['goods_id']; ?>);" title="收藏该商品"></a>-->
            <div class="left_btn"></div>
            <div class="right_btn"></div>
            <div class="ware_box">
                <ul>
                    <?php $_from = $this->_var['goods']['_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_image');$this->_foreach['fe_goods_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods_image']['total'] > 0):
    foreach ($_from AS $this->_var['goods_image']):
        $this->_foreach['fe_goods_image']['iteration']++;
?>
                    <li <?php if (($this->_foreach['fe_goods_image']['iteration'] <= 1)): ?>class="ware_pic_hover"<?php endif; ?> bigimg="<?php echo $this->_var['goods_image']['image_url']; ?>"><img src="<?php echo $this->_var['goods_image']['thumbnail']; ?>" width="55" height="55" /></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
        </div>
        <script>
            $(function(){
                var btn_list_li = $("#btn_list > li");
                btn_list_li.hover(function(){
                    $(this).find("ul:not(:animated)").slideDown("fast");
                },function(){
                    $(this).find("ul").slideUp("fast");
                });
            });
        </script>
        <?php if ($this->_var['share']): ?>
        <ul id="btn_list">
            <li id="btn_list1" title="收藏该商品">
                <ul class="drop_down">
                    <?php $_from = $this->_var['share']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                    <?php if ($this->_var['item']['type'] == 'collect'): ?><li><?php if ($this->_var['item']['logo']): ?><img src="<?php echo $this->_var['item']['logo']; ?>" /><?php endif; ?><a target="_blank" href="<?php echo $this->_var['item']['link']; ?>"><?php echo htmlspecialchars($this->_var['item']['title']); ?></a></li><?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </li>
            <li id="btn_list2" title="分享该商品">
                <ul class="drop_down">
                    <?php $_from = $this->_var['share']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                    <?php if ($this->_var['item']['type'] == 'share'): ?><li><?php if ($this->_var['item']['logo']): ?><img src="<?php echo $this->_var['item']['logo']; ?>" /><?php endif; ?><a target="_blank" href="<?php echo $this->_var['item']['link']; ?>"><?php echo htmlspecialchars($this->_var['item']['title']); ?></a></li><?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </li>
        </ul>
        <?php endif; ?>
    </div>

    <div class="ware_text">
    	<form name="auction_pay" method="post" action="<?php echo url('app=auction&act=pay'); ?>" id="auction_pay_form">
        <div class="rate">
        	<input type="hidden" name="auction_id" value="<?php echo $this->_var['auction']['auction_id']; ?>" />
        	<input type="hidden" name="goods_id" value="<?php echo $this->_var['goods']['goods_id']; ?>" />
        	<input type="hidden" name="new_price" value="<?php echo $this->_var['auction_goods']['new_price']; ?>" id="form_new_price" />
        	<input type="hidden" id="auction_goods_curr_price" value="<?php echo $this->_var['auction_goods']['curr_price']; ?>" />
        	<input type="hidden" id="auction_goods_step_price" value="<?php echo $this->_var['auction_goods']['step_price']; ?>" />
            <span class="letter1">价格: </span><span class="fontColor3" ectype="goods_price" id="goods_price"><?php echo price_format($this->_var['auction_goods']['curr_price']); ?></span><br />
            <span class="letter1">年代: </span><span class="fontColor3" ><?php echo $this->_var['auction_goods']['year']; ?></span><br />
           	<span class="letter1">大小: </span><span class="fontColor3" ><?php echo $this->_var['auction_goods']['size']; ?></span>&nbsp;&nbsp;厘米<br />
            <span class="letter1">重量: </span><span class="fontColor3" ><?php echo $this->_var['auction_goods']['weight']; ?></span>&nbsp;&nbsp;克<br />
            <span class="letter1">品相: </span><span class="fontColor3" ><?php echo $this->_var['auction_goods']['quality']; ?></span><br />
            <span class="letter1">品牌: </span><?php echo htmlspecialchars($this->_var['goods']['brand']); ?><br />
            <span>每次加价: </span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fontColor3" ectype="goods_price"><?php echo price_format($this->_var['auction_goods']['step_price']); ?></span><br />
            <span>保证金: </span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fontColor3" ectype="goods_price"><?php echo price_format($this->_var['auction_goods']['keep_money']); ?></span><br />
            <span>状态:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span></span><?php echo $this->_var['auction_goods']['time_status']; ?><br />
            <?php if ($this->_var['auction_goods']['show_start_time']): ?>
            <span>起拍时间:</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_var['auction_goods']['start_time']; ?><br />
            <?php endif; ?>
            <?php if ($this->_var['auction_goods']['show_end_time']): ?>
            <span>结束时间:</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_var['auction_goods']['end_time']; ?> 23:59<br />
            <span>剩余时间:&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: bold; color: red; display: none;" id="goods_last_time"><?php echo $this->_var['auction_goods']['last_time']; ?></span></span><br />
            <?php endif; ?>
            <?php if ($this->_var['record']['price'] > 0): ?>
            <span>我的竞价: </span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fontColor3" ectype="goods_price"><?php echo price_format($this->_var['record']['price']); ?></span><br />
            <?php endif; ?>
            <!-- <span>竞拍记录:</span>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="<?php echo url('app=auction&act=records&auction_id=' . $this->_var['auction']['auction_id']. '&goods_id=' . $this->_var['goods']['goods_id']. ''); ?>">查看竞拍记录</a><br /> -->
            <?php if ($this->_var['auction_goods']['can_pay']): ?>
            <span>竞拍:</span>&nbsp;&nbsp;&nbsp;&nbsp;加价<input type="text" name="times" value="1" onchange="updatePrice(this);" style="width: 30px;"/>次&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="fontColor3" ectype="goods_price" id="new_auction_pay"><?php echo price_format($this->_var['auction_goods']['new_price']); ?></span>
            <?php endif; ?>
            <br />
        </div>
		
        <?php if ($this->_var['auction_goods']['can_pay']): ?>
        	<button id="auction_pay_button" type="submit" class="cp" title="竞拍">竞拍</button>
		<?php endif; ?>
        </form>
    </div>

    <div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var $elem = $('#goods_last_time');
	if ($elem.length > 0) {
		var count = parseInt($elem.text(), 10);
		var countdown = setInterval(function(){
			if (count >= 0) {
				var hour = parseInt(count / 3600, 10);
				var min = parseInt((count - hour * 3600) / 60, 10);
				var sec = count - hour * 3600 - min * 60;
				$elem.text(hour+':'+min+':'+sec);
				$elem.show();
				if (count > 0) {
					$('#auction_pay_button').show();
				}
				else {
					$('#auction_pay_button').hide();
				}
				count--;
			}
		}, 1000);
	}
});
</script>