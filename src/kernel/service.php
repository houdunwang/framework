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
		'hdphp\crypt\CryptProvider',
		'hdphp\curl\CurlProvider',
		'hdphp\data\DataProvider',
		'hdphp\db\DbProvider',
		'hdphp\dir\DirProvider',
		'hdphp\log\LogProvider',
		'hdphp\html\HtmlProvider',
		'hdphp\image\ImageProvider',
		'hdphp\lang\LangProvider',
		'hdphp\mail\MailProvider',
		'hdphp\page\PageProvider',
		'hdphp\qq\QqProvider',
		'hdphp\rbac\RbacProvider',
		'hdphp\request\RequestProvider',
		'hdphp\string\StrProvider',
		'hdphp\upload\UploadProvider',
		'hdphp\validate\ValidateProvider',
		'hdphp\view\ViewProvider',
		'hdphp\zip\ZipProvider',
		'hdphp\form\FormProvider',
		'hdphp\tool\ToolProvider',
		'hdphp\cloud\CloudProvider',
		'hdphp\database\SchemaProvider',
		'hdphp\collection\CollectionProvider',
		'wechat\WeChatProvider',

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
		'Crypt'      => 'hdphp\crypt\CryptFacade',
		'Curl'       => 'hdphp\curl\CurlFacade',
		'Data'       => 'hdphp\data\DataFacade',
		'Db'         => 'hdphp\db\DbFacade',
		'Dir'        => 'hdphp\dir\DirFacade',
		'Log'        => 'hdphp\log\LogFacade',
		'Html'       => 'hdphp\html\HtmlFacade',
		'Image'      => 'hdphp\image\ImageFacade',
		'Lang'       => 'hdphp\lang\LangFacade',
		'Mail'       => 'hdphp\mail\MailFacade',
		'Page'       => 'hdphp\page\PageFacade',
		'Qq'         => 'hdphp\qq\QqFacade',
		'Rbac'       => 'hdphp\rbac\RbacFacade',
		'Request'    => 'hdphp\request\RequestFacade',
		'Route'      => 'hdphp\route\RouteFacade',
		'Session'    => 'hdphp\session\SessionFacade',
		'Str'        => 'hdphp\string\StrFacade',
		'Upload'     => 'hdphp\upload\UploadFacade',
		'Validate'   => 'hdphp\validate\ValidateFacade',
		'View'       => 'hdphp\view\ViewFacade',
		'Zip'        => 'hdphp\zip\ZipFacade',
		'Middleware' => 'hdphp\middleware\MiddlewareFacade',
		'Form'       => 'hdphp\form\FormFacade',
		'Tool'       => 'hdphp\tool\ToolFacade',
		'Cloud'      => 'hdphp\cloud\CloudFacade',
		'Schema'     => 'hdphp\database\SchemaFacade',
		'Collection' => 'hdphp\collection\CollectionFacade',
		'WeChat'     => 'wechat\WeChatFacade',
		'Xml'        => 'houdunwang\xml\XmlFacade',
		'Response'   => 'houdunwang\response\ResponseFacade',
	],
];