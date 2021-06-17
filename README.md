# Welcome to the SmartyModule for Laminas Framework!

SmartyModule is a module that integrates the Smarty templating engine with Laminas.

## Installation

### Composer

1. Add `"maglnet/smarty-module": "^2.0"` to your `composer.json` file and run `composer update`.
2. Add `SmartyModule` to your `config/application.config.php` file under the modules key.

### Configuration

Change you Application config like this:
    
    ...
    'view_manager' => array(
        'default_suffix' => 'tpl', // <-- new option for path stack resolver
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.tpl',
            'application/index/index' => __DIR__ . '/../view/application/index/index.tpl',
            'error/404'               => __DIR__ . '/../view/error/404.tpl',
            'error/index'             => __DIR__ . '/../view/error/index.tpl',
        ),
        'smarty' => array(
            'error_reporting'=> E_PARSE,
            'compile_dir' => 'path/to/compile/dir',
            'cache_dir' => 'path/to/cache/dir',
            //Other Smarty options
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    ...


Aditional info about view manager: [Laminas\View](http://framework.zend.com/manual/2.0/en/modules/zend.view.quick-start.html "Laminas\View").
