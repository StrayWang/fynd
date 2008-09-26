<?php
/**
 * 配置类型,用于构造相应的配置类
 * @author fishtrees
 *
 */
final class Fynd_Config_ConfigType {
	/**
	 * 系统配置,该类型用于描述Fynd系统使用的基础配置
	 *
	 */
	const SystemConfig = "SystemConfig";
	/**
	 * 数据库连接配置,描述如何进行数据库连接
	 *
	 */
	const DbConfig = "DbConfig";
	/**
	 * 请求映射类型,描述如何将HTTP请求映射到实际处理器
	 *
	 */
	const RequstMapConfig = "RequestMapConfig";
	/**
	 * 缓存配置,描述如何执行缓存
	 *
	 */
	const CacheConfig = "CacheConfig";
	/**
	 * 日志系统配置,描述如何记录日志
	 *
	 */
	const LogConfig = "LogConfig";
	/**
	 * 视图配置,描述如何视图相关的设置
	 *
	 */
	const ViewConfig = "ViewConfig";
	/**
	 * 国际化资源配置
	 *
	 */
	const ResourceConfig = "resourceConfig";
	/**
	 * 服务配置,描述实际处理请求的服务如何运行
	 *
	 */
	const ServiceConfig = "ServiceConfig";
}
?>