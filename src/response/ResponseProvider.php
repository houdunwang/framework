<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace hdphp\response;

use hdphp\kernel\ServiceProvider;

class ResponseProvider extends ServiceProvider {

	//延迟加载
	public $defer = FALSE;

	public function boot() {
		define('NOW',$_SERVER['REQUEST_TIME']);
		define( 'IS_GET', $_SERVER['REQUEST_METHOD'] == 'GET' );
		define( 'IS_POST', $_SERVER['REQUEST_METHOD'] == 'POST' );
		define( 'IS_DELETE', $_SERVER['REQUEST_METHOD'] == 'DELETE' ? TRUE : ( isset( $_POST['_method'] ) && $_POST['_method'] == 'DELETE' ) );
		define( 'IS_PUT', $_SERVER['REQUEST_METHOD'] == 'PUT' ? TRUE : ( isset( $_POST['_method'] ) && $_POST['_method'] == 'PUT' ) );
		define( 'IS_AJAX', isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' );
		define( 'IS_WEIXIN', isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'MicroMessenger' ) !== FALSE );
		define( '__ROOT__', trim( 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/\\' ) );
		define( '__URL__', trim( 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim( $_SERVER['REQUEST_URI'], '/\\' ), '/' ) );
		define( "__HISTORY__", isset( $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : '' );
	}

	public function register() {
		$this->app->single( 'Response', function ( $app ) {
			return new Response( $app );
		} );
	}
}