<?php namespace hdphp\cli;

/**
 * 命令行模式
 * Class Cli
 * @package hdphp\cli
 * @author 向军 <2300071698@qq.com>
 */
class Cli {
	/**
	 * 运行
	 */
	public static function run() {
		//去掉hd
		array_shift( $_SERVER['argv'] );
		$info = explode( ':', array_shift( $_SERVER['argv'] ) );
		//类文件
		$file = __DIR__ . '/' . $info[0] . '/' . ucfirst( $info[1] ) . '.php';
		if ( ! is_file( $file ) ) {
			self::error( 'Command does not exist' );
		}
		//命令类
		$class = 'hdphp\\cli\\' . $info[0] . '\\' . ucfirst( $info[1] );
		//实例
		$instance = new $class();
		if ( method_exists( $instance, 'run' ) ) {
			call_user_func_array( [ $instance, 'run' ], $_SERVER['argv'] );
		} else {
			self::error( "$info[1] method not found\n" );
		}
	}

	//输出错误信息
	public static function error( $content ) {
		die( "\033[;36m $content \x1B[0m\n" );
	}

	//成功信息
	public static function success( $content ) {
		die( "\033[;32m $content \x1B[0m\n" );
	}
}


