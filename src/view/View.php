<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace hdphp\view;

use Exception;

//视图处理
class View {
	//模板变量集合
	protected static $vars = [ ];
	//模版文件
	protected $tpl;
	//缓存目录
	protected $cacheDir;
	//缓存时间
	protected $expire;

	public function __construct() {
		$this->cacheDir = ROOT_PATH . '/storage/view/cache';
	}

	/**
	 * 解析模板
	 *
	 * @param string $tpl
	 * @param int $expire
	 *
	 * @return $this
	 */
	public function make( $tpl = '', $expire = 0 ) {
		$this->tpl    = $tpl;
		$this->expire = $expire;

		return $this;
	}

	/**
	 * 根据模板文件生成编译文件
	 *
	 * @param $tpl
	 *
	 * @return string
	 */
	public function compile( $tpl ) {
		$tpl         = $this->template( $tpl );
		$compileFile = ROOT_PATH . '/storage/view/' . preg_replace( '/[^\w]/', '_', $tpl ) . '_' . substr( md5( $tpl ), 0, 5 ) . '.php';
		$status      = c( 'app.debug' )
		               || ! is_file( $compileFile )
		               || ( filemtime( $tpl ) > filemtime( $compileFile ) );
		if ( $status ) {
			Dir::create( dirname( $compileFile ) );
			//执行文件编译
			$compile = new Compile( $this );
			$content = $compile->run($tpl);
			file_put_contents( $compileFile, $content );
		}

		return $compileFile;
	}

	//解析编译文件,返回模板解析后的字符
	public function fetch( $tpl ) {
		$compileFile = $this->compile( $tpl );
		ob_start();
		extract( self::$vars );
		include $compileFile;

		return ob_get_clean();
	}

	//显示模板
	public function __toString() {
		if ( $this->isCache( $this->tpl ) ) {
			//缓存有效时返回缓存数据
			return Cache::dir( $this->cacheDir )->get( $this->cacheName( $this->tpl ) ) ?: '';
		}
		$content = $this->fetch( $this->tpl );
		//创建缓存文件
		if ( $this->expire > 0 ) {
			Cache::dir( $this->cacheDir )->set( $this->cacheName( $this->tpl ), $content, $this->expire );
		}

		return $content;
	}

	//获取模板文件
	public function getTpl() {
		return $this->template( $this->tpl );
	}

	//根据文件名获取模板文件
	public function template( $file ) {
		//没有扩展名时添加上
		if ( $file && ! preg_match( '/\.[a-z]+$/i', $file ) ) {
			$file .= c( 'view.prefix' );
		}
		if ( ! is_file( $file ) ) {
			if ( defined( 'MODULE' ) ) {
				//模块视图文件夹
				$file = strtolower( MODULE_PATH . '/view/' . CONTROLLER ) . '/' . ( $file ?: ACTION . c( 'view.prefix' ) );
				if ( ! is_file( $file ) ) {
					throw new Exception( "模板不存在:$file" );
				}
			} else {
				//路由访问时
				$file = ROOT_PATH . '/' . c( 'view.path' ) . '/' . $file . c( 'view.prefix' );
				if ( ! is_file( $file ) ) {
					throw new Exception( "模板不存在:$file" );
				}
			}
		}

		return $file;
	}

	//缓存标识
	protected function cacheName( $file ) {
		return md5( $_SERVER['REQUEST_URI'] . $this->template( $file ) );
	}

	//验证缓存文件
	public function isCache( $file = '' ) {
		return Cache::dir( $this->cacheDir )->get( $this->cacheName( $file ) ) ? true : false;
	}

	//删除模板缓存
	public function delCache( $file = '' ) {
		return Cache::dir( $this->cacheDir )->del( $this->cacheName( $file ) );
	}


	/**
	 * 分配变量
	 *
	 * @param mixed $name 变量名
	 * @param string $value 值
	 *
	 * @return $this
	 */
	public function with( $name, $value = '' ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $k => $v ) {
				self::$vars[ $k ] = $v;
			}
		} else {
			self::$vars[ $name ] = $value;
		}

		return $this;
	}
}