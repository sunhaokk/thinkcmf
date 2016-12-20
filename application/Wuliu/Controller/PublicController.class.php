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
namespace Wuliu\Controller;
use Common\Controller\WuliubaseController;
class PublicController extends WuliubaseController {

    public function _initialize() {
        C(S('sp_dynamic_config'));//加载动态配置

    }
    

    //后台登陆界面
    public function login() {
        $admin_id=session('ADMIN_ID');
        if(!empty($admin_id)){//已经登录
            $this->error('已经登录admin后台,三秒后跳转',U('admin/index/index'),3);
        }
        $admin_id=session('TRADE_ID');
        if(!empty($admin_id)){//已经登录
            $this->error('已经登录trade后台,三秒后跳转',U('trade/index/index'),3);
        }
        $admin_id=session('WULIU_ID');
        if(!empty($admin_id)){//已经登录
            redirect(U("wuliu/index/index"));
        }else{
    	    $site_admin_url_password =C("SP_SITE_WULIU_URL_PASSWORD");
    	    $upw=session("__SP_UPW__");
    		if(!empty($site_admin_url_password) && $upw!=$site_admin_url_password){
    			redirect(__ROOT__."/");
    		}else{
    		    session("__SP_WULIU_LOGIN_PAGE_SHOWED_SUCCESS__",true);
    			$this->display(":login");
    		}
    	}
    }
    
    public function logout(){
        session('WULIU',null);
        session('USER_ID',null);
    	session('WULIU_ID',null); 
    	redirect(__ROOT__."/wuliu");
    }
    
    public function dologin(){
        $login_page_showed_success=session("__SP_WULIU_LOGIN_PAGE_SHOWED_SUCCESS__");
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
    				
    				$role_user_model=M("OswWuliuRoleUser");   				
                    //C('DB_PREFIX')=cmf_; 注释
    				$role_user_join = C('DB_PREFIX').'osw_wuliu_role as b on a.role_id =b.id';

    				$groups=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$result["id"],"status"=>1))->getField("role_id",true);
    				if( $result["id"]!=1 && ( empty($groups) || empty($result['user_status']) ) ){
    					$this->error(L('USE_DISABLED'));
    				}
                    $wuliu = M("OswWuliu");
                    $row = $wuliu->where('user_id='.$result['id'])->find();
                    if(empty($row)){
                        $wuliu_user = M('OswWuliuUser');
                        $wuliu_id = $wuliu_user->where('user_id='.$result['id'])->getField('wuliu_id');
                        $row = $wuliu->where('wuliu_id='.$wuliu_id)->find();
                    }

                    if(empty($row)){
                        $this->error('无法获取你的公司信息！');
                    }

                    session('WULIU',$row);
    				//登入成功页面跳转
                    
                    session('WULIU_ID',$result["id"]);

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