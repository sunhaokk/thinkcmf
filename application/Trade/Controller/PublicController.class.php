<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
/**
 */

namespace Trade\Controller;
use Common\Controller\TradebaseController;
class PublicController extends TradebaseController {

    public function _initialize() {
        C(S('sp_dynamic_config'));//加载动态配置
    }
    
    //后台登陆界面
    public function login(){
        $admin_id=session('ADMIN_ID');
        if(!empty($admin_id)){//已经登录
            $this->error('已经登录admin后台,三秒后跳转',U('admin/index/index'),3);
        }
        $admin_id=session('WULIU_ID');
        if(!empty($admin_id)){//已经登录
            $this->error('已经登录wuliu后台,三秒后跳转',U('wuliu/index/index'),3);
        }
        $admin_id=session('TRADE_ID');
        if(!empty($admin_id)){//已经登录
            redirect(U("trade/index/index"));
        }else{
    	    $site_admin_url_password =C("SP_SITE_TRADE_URL_PASSWORD");
    	    $upw=session("__SP_UPW__");
    		if(!empty($site_admin_url_password) && $upw!=$site_admin_url_password){
    			redirect(__ROOT__."/");
    		}else{
    		    session("__SP_TRADE_LOGIN_PAGE_SHOWED_SUCCESS__",true);
    			$this->display(":login");
    		}
    	}
    }
    
    public function logout(){
    	session('TRADE',null);
        session('USER_ID',null);
        session('TRADE_ID',null); 
        redirect(__ROOT__."/trade");
    }
    
    public function dologin(){
        $login_page_showed_success=session("__SP_TRADE_LOGIN_PAGE_SHOWED_SUCCESS__");
        if(!$login_page_showed_success){
            $this->error('login error!');
        }
    	$name = I("post.username");
    	if(empty($name)){
    		$this->error(L('USERNAME_OR_EMAIL_EMPTY'));
    	}
    	$pass = I("post.password");
    	if(empty($pass)){
    		$this->error(L('PASSWORD_REQUIRED'));
    	}
    	$verrify = I("post.verify");
    	if(empty($verrify)){
    		$this->error(L('CAPTCHA_REQUIRED'));
    	}
    	//验证码
    	if(!sp_check_verify_code()){
    		$this->error(L('CAPTCHA_NOT_RIGHT'));
    	}else{
    		$user = D("Common/Users");
    		if(strpos($name,"@")>0){//邮箱登陆
    			$where['user_email']=$name;
    		}else{
    			$where['user_login']=$name;
    		}
    		
    		$result = $user->where($where)->find();
            if(!empty($result) && $result['user_type']==2){
                if(sp_compare_password($pass,$result['user_pass'])){
                    
                    $role_user_model=M("OswTradeRoleUser");                 
                   
                    $role_user_join = C('DB_PREFIX').'osw_trade_role as b on a.role_id =b.id';

                    $groups=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$result["id"],"status"=>1))->getField("role_id",true);
                    
                    if( $result["id"]!=1 && ( empty($groups) || empty($result['user_status']) ) ){
                        $this->error(L('USE_DISABLED'));
                    }
                    $trade = M("OswTrade");
                    $row = $trade->where('user_id='.$result['id'])->find();
                    
                    if(empty($row)){
                        $trade_user = M('OswTradeUser');
                        $trade_id = $trade_user->where('user_id='.$result['id'])->getField('trade_id');
                        $row = $trade->where('trade_id='.$trade_id)->find();

                    }
                    if(empty($row)){
                        $this->error('无法获取你公司的信息！');
                    }

                    session('TRADE',$row);
                    //登入成功页面跳转
                    
                    session('TRADE_ID',$result["id"]);

                    session('USER_ID',$result["id"]);
                    session('name',$result["user_login"]);
                    $result['last_login_ip']=get_client_ip(0,true);
                    $result['last_login_time']=date("Y-m-d H:i:s");
                    $user->save($result);
                    cookie("admin_username",$name,3600*24*30);
                    $this->success(L('LOGIN_SUCCESS'),U("Index/index"));
                }else{
                    $this->error(L('PASSWORD_NOT_RIGHT'));
                }
            }else{
                $this->error(L('USERNAME_NOT_EXIST'));
            }
        }
    }

}