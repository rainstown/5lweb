<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#brand_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
        onkeyup    : false,
        rules : {
            brand_name : {
                required : true,
                remote   : {                //唯一
                url :'index.php?app=brand&act=check_brand',
                type:'get',
                data:{
                    brand_name : function(){
                        return $('#brand_name').val();
                        },
                    id  : '<?php echo $this->_var['brand']['brand_id']; ?>'
                    }
                }
            },
            logo : {
                accept  : 'gif|png|jpe?g'
            },
            sort_order : {
                number   : true
            }
        },
        messages : {
            brand_name : {
                required : 'brand_empty',
                remote   : 'name_exist'
            },
            logo : {
                accept : 'limit_img'
            },
            sort_order  : {
                number   : 'number_only'
            }
        }
    });
});
</script>
<div id="rightTop">
    <ul class="subnav">
        <li><a class="btn1" href="index.php?app=funds">管理</a></li>
        <li><a class="btn1" href="index.php?app=in_funds">充值</a></li>
        <li>处理申请</li>
    </ul>
</div>

<div class="info">
    <form method="post" enctype="multipart/form-data" id="brand_form">
         <table class="infoTable">
            <tr>
            <th class="paddingT15"> 会员账户:</th>
            <td class="paddingT15 wordSpacing5">
               <?php echo $this->_var['info']['user_name']; ?>
             </td>
          </tr>
           <tr>
            <th class="paddingT15"> 交易编码:</th>
            <td class="paddingT15 wordSpacing5">
               <?php echo $this->_var['info']['order_sn']; ?>
             </td>
          </tr>
          <tr>
            <th class="paddingT15"> 金额:</th>
            <td class="paddingT15 wordSpacing5">
              <?php echo $this->_var['info']['money']; ?>
              </td>
          </tr>
          <tr >
            <th class="paddingT15"> 流向:</th>
            <td class="paddingT15 wordSpacing5">
               <?php echo call_user_func("funds_in_out",$this->_var['info']['in_out']); ?>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 资金名称:</th>
            <td class="paddingT15 wordSpacing5">
                <?php echo $this->_var['info']['funds_name']; ?>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 银行开户行:</th>
            <td class="paddingT15 wordSpacing5">
               <?php echo $this->_var['info']['name']; ?>
              </td>
          </tr>
           <tr>
            <th class="paddingT15"> 账户名称:</th>
            <td class="paddingT15 wordSpacing5">
                <?php echo $this->_var['info']['account_name']; ?>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> 银行账户:</th>
            <td class="paddingT15 wordSpacing5">
                <?php echo $this->_var['info']['account']; ?>
              </td>
          </tr>
         
          <tr>
            <th class="paddingT15"> 备注:</th>
            <td class="paddingT15 wordSpacing5">
                <?php echo $this->_var['info']['remark']; ?>
              </td>
          </tr>
          <tr>
            <th class="paddingT15"> <label for="state">处理:</label></th>
            <td class="paddingT15 wordSpacing5"><?php echo $this->html_radios(array('name'=>'auth_state','options'=>$this->_var['auth_states'],'checked'=>$this->_var['info']['auth_state'])); ?></td>
          </tr>
          <tr>
            <th class="paddingT15">处理备注:</th>
            <td class="paddingT15 wordSpacing5">
                <input class="infoTableInput2" id="remark" type="text" name="remark" />
                <label class="field_notice"></label>
              </td>
          </tr>
        <tr>
            <th></th>
            <td class="ptb20">
                <input class="formbtn" type="submit" name="Submit" value="提交" />
                <input class="formbtn" type="reset" name="Submit2" value="重置" />
            </td>
        </tr>
        </table>
    </form>
</div>
<?php echo $this->fetch('footer.html'); ?>
