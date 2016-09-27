<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdphp.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace hdphp\db\connection;

use hdphp\model\Model;
use hdphp\traits\HdArrayAccess;
use ArrayAccess;
use Iterator;

class Mysql implements DbInterface, ArrayAccess, Iterator {
	use HdArrayAccess, Connection;
	//模型类
	protected $model;
	//表名
	protected $table;
	//字段列表
	protected $fields;
	//表主键
	protected $primaryKey;

	/**
	 * pdo连接
	 * @return string
	 */
	public function getDns() {
		return $dns = 'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['database'];
	}

	/**
	 * 设置表
	 *
	 * @param $table
	 *
	 * @return $this
	 */
	public function table( $table ) {
		//模型实例时不允许改表名
		$this->table = $this->table ?: c( 'database.prefix' ) . $table;
		//缓存表字段
		$this->fields = Schema::getFields( $table );
		//获取表主键
		$this->primaryKey = Schema::getPrimaryKey( $table );

		return $this;
	}

	/**
	 * 获取表
	 * @return mixed
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * 获取表字段
	 * @return array|bool
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * 获取表主键
	 * @return mixed
	 */
	public function getPrimaryKey() {
		return $this->primaryKey;
	}

	/**
	 * 移除表中不存在的字段
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function filterTableField( array $data ) {
		$new = [ ];
		if ( is_array( $data ) ) {
			foreach ( $data as $name => $value ) {
				if ( key_exists( $name, $this->fields ) ) {
					$new[ $name ] = $value;
				}
			}
		}

		return $new;
	}

	/**
	 * 设置模型
	 *
	 * @param \hdphp\model\Model $model
	 *
	 * @return \hdphp\db\connection\Mysql
	 */
	public function model( Model $model ) {
		$this->model = $model;

		return $this->table( $this->model->getTableName() );
	}

	/**
	 * 获取模型
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}
}