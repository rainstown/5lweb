<?php

/* 会员认证 member_auth */
class MemberauthModel extends BaseModel
{
    var $table  = 'member_auth';
    //var $alias = '';
    var $prikey = 'auth_id';
    var $_name  = 'memberauth';

    var $_relation  = array(
        // 一个认证属于一个会员
        'belongs_to_user' => array(
            'model'         => 'member',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_auth',
        ),
    );
    
     /**
     * 取得信息
     */
    function get_info($auth_id)
    {
        $info = $this->get(array(
            'conditions' => $auth_id,
            'join'       => 'belongs_to_user',
            'fields'     => 'this.*, member.user_name, member.email',
        ));
        if (!empty($info['certification']))
        {
            $info['certifications'] = explode(',', $info['certification']);
        }
        return $info;
    }
}

?>