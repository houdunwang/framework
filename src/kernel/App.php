<?php
// .-------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |      Site: www.hdphp.com
// |-------------------------------------------------------------------
// |    Author: 向军 <2300071698@qq.com>
// |    WeChat: houdunwangxj
// | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
// '-------------------------------------------------------------------
namespace hdphp\kernel;

use ReflectionClass;
use Hdphp\Kernel\ServiceProviders;

class App extends Container {
	//应用已启动
	protected $booted = FALSE;

	//系统服务
	protected $servers = [ ];

	//外观别名
	protected $facades = [ ];

	//延迟加载服务提供者
	protected $deferProviders = [ ];

	//已加载服务提供者
	protected $serviceProviders = [ ];

	public function bootstrap() {
		header( "Content-type:text/html;charset=utf-8" );
		if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
			die( 'HDPHP 需要PHP版本大于php5.4,当前版本' . PHP_VERSION );
		}
		define( 'HDPHP_VERSION', '2.0.1' );
		define( 'RELEASE_VERSION', '20160818' );
		define( 'HDPHP_PATH', __DIR__ );
		define( 'DS', DIRECTORY_SEPARATOR );
		define( 'ROOT_PATH', '.' );
		defined( "DEBUG" ) or define( "DEBUG", FALSE );
		define( 'IS_CGI', substr( PHP_SAPI, 0, 3 ) == 'cgi' ? TRUE : FALSE );
		define( 'IS_WIN', strstr( PHP_OS, 'WIN' ) ? TRUE : FALSE );
		define( 'IS_CLI', PHP_SAPI == 'cli' ? TRUE : FALSE );
		define( 'NOW', $_SERVER['REQUEST_TIME'] );
		define( 'NOW_MICROTIME', microtime( TRUE ) );
		if ( IS_CLI ) {
			define( '__ROOT__', '' );
		} else {
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
		$this->run();
	}

	// 构造函数
	public function run() {
		$this->servers = require ROOT_PATH . '/system/config/service.php';
		//自动加载系统服务
		Loader::register( [ $this, 'autoload' ] );
		//composer自动加载
		if ( is_file( __DIR__ . '/../../vendor/autoload.php' ) ) {
			require __DIR__ . '/../../vendor/autoload.php';
		}
		//绑定核心服务提供者
		$this->bindServiceProvider();
		//添加初始实例
		$this->instance( 'App', $this );
		//设置外观类APP属性
		ServiceFacade::setFacadeApplication( $this );
		Error::bootstrap();
		//导入类库别名
		Loader::addMap( Config::get( 'app.alias' ) );
		//自动加载文件
		Loader::autoloadFile();
		//启动服务
		$this->boot();
		//应用目录
		define( 'APP_PATH', ROOT_PATH . '/' . C( 'app.path' ) );
		define( 'APP', basename( APP_PATH ) );
		//时区设置
		date_default_timezone_set( Config::get( 'app.timezone' ) );
		//CLI模式
		$this->cli();
		//导入钓子
		Hook::import( Config::get( 'hook' ) );
		//应用开始钩子
		Hook::listen( 'app_begin' );
		//解析路由
		Route::dispatch();
		//记录日志
		Log::save();
		//应用结束钩子
		Hook::listen( 'app_end' );
	}

	// 执行请求
	public function cli() {
		//命令模式
		if ( $_SERVER['SCRIPT_NAME'] == 'hd' ) {
			require_once __DIR__ . '/../cli/Cli.php';
			\hdphp\cli\Cli::run();
			exit;
		}

	}

	//类文件自动加载
	public function autoload( $class ) {
		//通过外观类加载系统服务
		$file   = str_replace( '\\', '/', $class );
		$facade = basename( $file );
		if ( isset( $this->servers['facades'][ $facade ] ) ) {
			//加载facade类
			return class_alias( $this->servers['facades'][ $facade ], $class );
		}
	}

	// 系统启动
	public function boot() {
		if ( $this->booted ) {
			return;
		}

		foreach ( $this->serviceProviders as $p ) {
			$this->bootProvider( $p );
		}
		$this->booted = TRUE;
	}

	// 服务加载处理
	public function bindServiceProvider() {
		foreach ( $this->servers['providers'] as $provider ) {
			$reflectionClass = new ReflectionClass( $provider );
			$properties      = $reflectionClass->getDefaultProperties();
			//获取服务延迟属性
			if ( isset( $properties['defer'] ) && $properties['defer'] ) {
				$alias = substr( $reflectionClass->getShortName(), 0, - 8 );
				//延迟加载服务
				$this->deferProviders[ $alias ] = $provider;
			} else {
				//立即加载服务
				$this->register( new $provider( $this ) );
			}
		}
	}

	/**
	 * 获取服务对象
	 *
	 * @param 服务名 $name 服务名
	 * @param bool $force 是否单例
	 *
	 * @return Object
	 */
	public function make( $name, $force = FALSE ) {
		if ( isset( $this->deferProviders[ $name ] ) ) {
			$this->register( new $this->deferProviders[$name]( $this ) );
			unset( $this->deferProviders[ $name ] );
		}

		return parent::make( $name, $force );
	}

	/**
	 * 注册服务
	 *
	 * @param $provider 服务名
	 *
	 * @return object
	 */
	public function register( $provider ) {
		//服务对象已经注册过时直接返回
		if ( $registered = $this->getProvider( $provider ) ) {
			return $registered;
		}
		if ( is_string( $provider ) ) {
			$provider = new $provider( $this );
		}
		$provider->register( $this );
		//记录服务
		$this->serviceProviders[] = $provider;

		if ( $this->booted ) {
			$this->bootProvider( $provider );
		}
	}

	/**
	 * 运行服务提供者的boot方法
	 *
	 * @param $provider
	 */
	protected function bootProvider( $provider ) {
		if ( method_exists( $provider, 'boot' ) ) {
			$provider->boot();
		}
	}

	/**
	 * 获取已经注册的服务
	 *
	 * @param $provider 服务名
	 *
	 * @return mixed
	 */
	protected function getProvider( $provider ) {
		$class = is_object( $provider ) ? get_class( $provider ) : $provider;
		foreach ( $this->serviceProviders as $value ) {
			if ( $value instanceof $class ) {
				return $value;
			}
		}
	}
}