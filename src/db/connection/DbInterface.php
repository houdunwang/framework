<?php namespace hdphp\db\connection;

use hdphp\model\Model;

interface DbInterface {
	//设置表
	public function table( $table );

	//获取表
	public function getTable();

	//获取表主键
	public function getPrimaryKey();

	//获取表字段
	public function getFields();

	//设置模型
	public function model( Model $model );

	//过滤表字段
	public function filterTableField( array $data );

	//获取连接dns字符串
	public function getDns();
}