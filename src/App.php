<?php namespace houdunwang\framework;
// .-------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |      Site: www.hdphp.com
// |-------------------------------------------------------------------
// |    Author: 向军 <2300071698@qq.com>
// |    WeChat: houdunwangxj
// | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
// '-------------------------------------------------------------------

use houdunwang\config\Config;
use houdunwang\container\Container;
use ReflectionClass;

class App {
	//应用已启动
	protected $booted = false;
	//系统服务
	protected $servers = [ ];
	//外观别名
	protected $facades = [ ];
	//延迟加载服务提供者
	protected $deferProviders = [ ];
	//已加载服务提供者
	protected $serviceProviders = [ ];

	public function bootstrap() {
		//加载配置文件
		$this->config();
		//常量定义
		$this->constant();
		//添加初始实例
		Container::instance( 'App', $this );
		$this->servers = Config::get('service');
		//自动加载系统服务
		Loader::bootstrap();
		Loader::register( [ $this, 'autoload' ] );
		//绑定核心服务提供者
		$this->bindServiceProvider();
		//设置外观类APP属性
		ServiceFacade::setFacadeApplication( $this );
		//启动服务
		$this->boot();
	}

	//初始配置
	protected function config() {
		//加载配置文件
		Config::loadFiles( ROOT_PATH . '/system/config' );
		//加载服务配置项
		$servers = require __DIR__ . '/service.php';
		Config::set( 'service.providers', array_merge( c( 'service.providers' ), $servers['providers'] ) );
		Config::set( 'service.facades', array_merge( c( 'service.facades' ), $servers['facades'] ) );
	}

	//定义常量
	protected function constant() {
		define( 'FRAMEWORK_VERSION', '3.0.32' );
		define( '__ROOT__', IS_CLI ? '' : trim( 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/\\' ) );
		define( 'DS', DIRECTORY_SEPARATOR );
	}

	//外观类文件自动加载
	public function autoload( $class ) {
		//通过外观类加载系统服务
		$file   = str_replace( '\\', '/', $class );
		$facade = basename( $file );
		if ( isset( $this->servers['facades'][ $facade ] ) ) {
			//加载facade类
			return class_alias( $this->servers['facades'][ $facade ], $class );
		}
	}

	//系统启动
	protected function boot() {
		if ( $this->booted ) {
			return;
		}
		foreach ( $this->serviceProviders as $p ) {
			$this->bootProvider( $p );
		}
		$this->booted = true;
	}

	//服务加载处理
	protected function bindServiceProvider() {
		foreach ( $this->servers['providers'] as $provider ) {
			$reflectionClass = new ReflectionClass( $provider );
			$properties      = $reflectionClass->getDefaultProperties();
			//获取服务延迟属性
			if ( isset( $properties['defer'] ) && $properties['defer'] === false ) {
				//立即加载服务
				$this->register( new $provider( $this ) );
			} else {
				//延迟加载服务
				$alias                          = substr( $reflectionClass->getShortName(), 0, - 8 );
				$this->deferProviders[ $alias ] = $provider;
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
	public function make( $name, $force = false ) {
		if ( isset( $this->deferProviders[ $name ] ) ) {
			$this->register( new $this->deferProviders[$name]( $this ) );
			unset( $this->deferProviders[ $name ] );
		}

		return Container::make( $name, $force );
	}

	/**
	 * 注册服务
	 *
	 * @param $provider 服务名
	 *
	 * @return object
	 */
	protected function register( $provider ) {
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
			$provider->boot( $this );
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