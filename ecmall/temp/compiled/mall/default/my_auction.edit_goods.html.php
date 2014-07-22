<script type="text/javascript">
//<!CDATA[
$(function(){
	$('#auction_goods_start_time, #auction_goods_end_time').datepicker({
		dateFormat : 'yy-mm-dd'
	});
    $('#my_auction_goods_form').validate({
    	/*
        errorPlacement: function(error, element){
            var _message_box = $(element).parent().find('.field_message');
            _message_box.find('.field_notice').hide();
            _message_box.append(error);
        },
        success       : function(label){
            label.addClass('validate_right').text('OK!');
        },*/
        
        
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        onkeyup : false,
        rules : {
            start_time : {
                required : true,
                date   : true
            },
            end_time   : {
                required : true,
                date: true
            },
            min_price : {
                required : true,
                digits : true,
                min : 1,
            },
            step_price : {
            	required : true,
            	digits : true,
            	min : 1
            }
        },
        messages : {
            name : {
                required : '标题必填. '
            },
            start_time : {
            	required : '开始时间必填',
            	date : '开始时间日期格式不正确，应该为yy-mm-dd'
            },
            end_time : {
            	required : '结束时间必填',
            	date : '结束时间日期格式不正确，应该为yy-mm-dd'
            },
            min_price : {
            	required : '起拍价必填',
            	min : '起拍价必须大于或等于1',
            	digits : '起拍价必须为整数'
            },
            step_price : {
            	required : '每次加价必填',
            	min : '每次加价必须大于或等于1',
            	digits : '每次加价必须为整数'
            }
        }
    });
});

//]]>
</script>
<style type="text/css">
#my_auction_goods_form_ul li h3{width : 100px;}
</style>
<ul class="tab">
    <li class="active">
    	<?php if ($_GET['id']): ?>
    	编辑拍卖品
    	<?php else: ?>
    	添加拍卖品
    	<?php endif; ?>
    </li>
</ul>
<div class="eject_con">
	<div class="add">
	    <div id="warning"></div>
	    <form method="post" action="index.php?app=my_auction&act=edit_goods&id=<?php echo $this->_var['goods']['id']; ?>&auction_id=<?php echo $_GET['auction_id']; ?>&goods_id=<?php echo $_GET['goods_id']; ?>" id="my_auction_goods_form" target="iframe_post">
	    <ul id="my_auction_goods_form_ul">
	    	<li>
	            <h3>拍卖标题:</h3>
	            <p>
	            	<?php echo $this->_var['auction']['name']; ?>
	            </p>
	        </li>
	        <li>
	            <h3>拍卖会开始时间: </h3>
	            <p>
	            	<?php echo $this->_var['auction']['start_time']; ?>
	            </p>
	        </li>
	        <li>
	            <h3>拍卖会结束时间: </h3>
	            <p>
	            	<?php echo $this->_var['auction']['end_time']; ?>
	            </p>
	        </li>
	        <li>
	            <h3>商品名称: </h3>
	            <p>
	            	<?php echo $this->_var['goods_name']; ?>
	            </p>
	        </li>
	        <li>
	            <h3>开始时间: </h3>
	            <p>
	            	<input type="text" class="text width_normal" name="start_time" value="<?php echo htmlspecialchars($this->_var['goods']['start_time']); ?>" id="auction_goods_start_time" />
	            	<label class="field_message"><span class="field_notice">拍卖开始时间不能早于拍卖会开始时间</span></label>
	            </p>
	        </li>
	        <li>
	            <h3>结束时间: </h3>
	            <p>
	            	<input type="text" class="text width_normal" name="end_time" value="<?php echo htmlspecialchars($this->_var['goods']['end_time']); ?>" id="auction_goods_end_time" />
	            	<label class="field_message"><span class="field_notice">拍卖结束时间不能晚于拍卖会结束时间</span></label>
	            </p>
	        </li>
	        <li>
	            <h3>起拍价:</h3>
	            <p>
	            	<input type="text" class="text width_normal"  name="min_price" value="<?php echo $this->_var['goods']['min_price']; ?>"/>
	            	<label class="field_message"><span class="field_notice"></span></label>
	            </p>
	        </li>
	        <li>
	            <h3>每次加价:</h3>
	            <p>
	            	<input type="text" class="text width_normal"  name="step_price" value="<?php echo $this->_var['goods']['step_price']; ?>"/>
	            	<label class="field_message"><span class="field_notice"></span></label>
	            </p>
	        </li>
	    </ul>
	    <div class="submit"><input type="submit" class="btn" value="<?php if ($_GET['id']): ?>编辑拍卖品<?php else: ?>添加拍卖品<?php endif; ?>" /></div>
	    </form>
	</div>
</div>