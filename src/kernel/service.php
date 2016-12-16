<?php

return [
	//服务提供者
	'providers' => [
		'hdphp\error\ErrorProvider',
		'hdphp\config\ConfigProvider',
		'hdphp\session\SessionProvider',
		'hdphp\middleware\MiddlewareProvider',
		'hdphp\cli\CliProvider',
		'hdphp\route\RouteProvider',
		'hdphp\alipay\AliPayProvider',
		'hdphp\arr\ArrProvider',
		'hdphp\backup\BackupProvider',
		'hdphp\cache\CacheProvider',
		'hdphp\cart\CartProvider',
		'hdphp\code\CodeProvider',
		'hdphp\cookie\CookieProvider',
		'hdphp\curl\CurlProvider',
		'hdphp\data\DataProvider',
		'hdphp\db\DbProvider',
		'hdphp\dir\DirProvider',
		'hdphp\log\LogProvider',
		'hdphp\lang\LangProvider',
		'hdphp\mail\MailProvider',
		'hdphp\page\PageProvider',
		'hdphp\qq\QqProvider',
		'hdphp\rbac\RbacProvider',
		'hdphp\request\RequestProvider',
		'hdphp\validate\ValidateProvider',
		'hdphp\view\ViewProvider',
		'hdphp\form\FormProvider',
		'hdphp\cloud\CloudProvider',
		'hdphp\database\SchemaProvider',
		'hdphp\collection\CollectionProvider',
		'wechat\WeChatProvider',

		'houdunwang\tool\ToolProvider',
		'houdunwang\str\StrProvider',
		'houdunwang\crypt\CryptProvider',
		'houdunwang\image\ImageProvider',
		'houdunwang\html\HtmlProvider',
		'houdunwang\zip\ZipProvider',
		'houdunwang\file\FileProvider',
		'houdunwang\response\ResponseProvider',
		'houdunwang\xml\XmlProvider',
	],

	//服务外观
	'facades'   => [
		'Error'      => 'hdphp\error\ErrorFacade',
		'App'        => 'hdphp\kernel\AppFacade',
		'AliPay'     => 'hdphp\alipay\AliPayFacade',
		'Arr'        => 'hdphp\arr\ArrFacade',
		'Backup'     => 'hdphp\backup\BackupFacade',
		'Cache'      => 'hdphp\cache\CacheFacade',
		'Cart'       => 'hdphp\cart\CartFacade',
		'Code'       => 'hdphp\code\CodeFacade',
		'Config'     => 'hdphp\config\ConfigFacade',
		'Cookie'     => 'hdphp\cookie\CookieFacade',
		'Cli'        => 'hdphp\cli\CliFacade',
		'Curl'       => 'hdphp\curl\CurlFacade',
		'Data'       => 'hdphp\data\DataFacade',
		'Db'         => 'hdphp\db\DbFacade',
		'Dir'        => 'hdphp\dir\DirFacade',
		'Log'        => 'hdphp\log\LogFacade',
		'Lang'       => 'hdphp\lang\LangFacade',
		'Mail'       => 'hdphp\mail\MailFacade',
		'Page'       => 'hdphp\page\PageFacade',
		'Qq'         => 'hdphp\qq\QqFacade',
		'Rbac'       => 'hdphp\rbac\RbacFacade',
		'Request'    => 'hdphp\request\RequestFacade',
		'Route'      => 'hdphp\route\RouteFacade',
		'Session'    => 'hdphp\session\SessionFacade',
		'Validate'   => 'hdphp\validate\ValidateFacade',
		'View'       => 'hdphp\view\ViewFacade',
		'Middleware' => 'hdphp\middleware\MiddlewareFacade',
		'Form'       => 'hdphp\form\FormFacade',
		'Cloud'      => 'hdphp\cloud\CloudFacade',
		'Schema'     => 'hdphp\database\SchemaFacade',
		'Collection' => 'hdphp\collection\CollectionFacade',
		'WeChat'     => 'wechat\WeChatFacade',

		'Tool'     => 'houdunwang\tool\ToolFacade',
		'Str'      => 'houdunwang\str\StrFacade',
		'Crypt'    => 'houdunwang\crypt\CryptFacade',
		'Html'     => 'houdunwang\html\HtmlFacade',
		'Image'    => 'houdunwang\image\ImageFacade',
		'Zip'      => 'houdunwang\zip\ZipFacade',
		'File'     => 'houdunwang\file\FileFacade',
		'Xml'      => 'houdunwang\xml\XmlFacade',
		'Response' => 'houdunwang\response\ResponseFacade',
	],
];