<?php
/**
 * @link        https://github.com/MurgaNikolay/SmartyModule for the canonical source repository
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 * @author      Murga Nikolay <work@murga.kiev.ua>
 * @package     SmartyModule
 */

namespace SmartyModule\View\Renderer;

use Laminas\EventManager\EventManager;
use Laminas\View\Renderer\PhpRenderer,
    Laminas\View\Exception,
    Laminas\View\Model\ModelInterface as Model;


class SmartyRenderer extends PhpRenderer
{
    /**
     * @var \Smarty $smarty
     */
    protected $smarty;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var null
     */
    private $__file = null;
    /**
     * @var array
     */
    private $__templates = array();
    /**
     * @var array
     */
    private $__template = array();
    /**
     * @var string
     */
    private $__content = '';
    
     /**
     * @var array Temporary variable stack; used when variables passed to render()
     */
    private $__varsCache = array();

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var int
     */
    private $__level = 0;

    /**
     * @var array
     */
    private $scopeVars = [];

    /**
     *
     */
    public function init()
    {
        //$this->setSmarty(new \Smarty());
    }

    /**
     * @param \Smarty $smarty
     */

    public function setSmarty($smarty)
    {
        $this->smarty = $smarty;
        $this->smarty->assign('this', $this);
    }

    /**
     * @return \Smarty
     */
    public function getEngine()
    {
        return $this->smarty;
    }

    /**
     * @param string|\Laminas\View\Model\ModelInterface $nameOrModel
     * @param null $values
     * @return mixed|string
     * @throws \Laminas\View\Exception\RuntimeException
     * @throws \Laminas\View\Exception\DomainException
     */
    public function render($nameOrModel, $values = null)
    {
        $this->__level++;

        if ($nameOrModel instanceof Model) {

            $model = $nameOrModel;
            $nameOrModel = $model->getTemplate();
            if (empty($nameOrModel)) {
                throw new Exception\DomainException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }
            $options = $model->getOptions();
            foreach ($options as $setting => $value) {
                $method = 'set' . $setting;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
                unset($method, $setting, $value);
            }
            unset($options);

            // Give view model awareness via ViewModel helper
            $helper = $this->plugin('view_model');
            $helper->setCurrent($model);

            $values = $model->getVariables();
            unset($model);
        }

        // find the script file name using the parent private method
        $this->addTemplate($nameOrModel);
        unset($nameOrModel); // remove $name from local scope
        
        $this->__varsCache[] = $this->vars();

        if (null !== $values) {
            $this->setVars($values);
        }
        unset($values);

        // extract all assigned vars (pre-escaped), but not 'this'.
        // assigns to a double-underscored variable, to prevent naming collisions
        $this->scopeVars[$this->__level] = $this->vars()->getArrayCopy();

        $scopedVars = [];
        foreach ($this->scopeVars as $vars) {
            $scopedVars = array_merge($scopedVars, $vars);
        }

        // add the PhpRenderer to the given smarty vars
        $scopedVars['this'] = $this;

        $this->smarty->clearAllAssign();
        $this->smarty->assign($scopedVars);

        while ($this->__template = array_pop($this->__templates)) {
            $this->__template;
            $this->__file = $this->resolver($this->__template);

            if (!$this->__file) {
                throw new Exception\RuntimeException(sprintf(
                    '%s: Unable to render template "%s"; resolver could not resolve to a file',
                    __METHOD__,
                    $this->__template
                ));
            }
            $this->smarty->addTemplateDir(dirname($this->__file), '__CURRENT__');
            $this->getEventManager()->trigger('smarty.fetch.pre', $this);
            $this->__content = $this->smarty->fetch($this->__file);
        }
        
        $this->setVars(array_pop($this->__varsCache));
        unset($this->scopeVars[$this->__level]);
        $this->__level--;
        
        return $this->getFilterChain()->filter($this->__content); // filter output
    }

    /**
     * Clone Smarty engine
     */
    public function __clone()
    {
        $this->smarty = clone $this->smarty;
        $this->smarty->assign('this', $this);
    }

    /**
     * @param string $template
     * @return SmartyRenderer
     */
    public function addTemplate($template)
    {
        $this->__templates[] = $template;
        return $this;
    }

    /**
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param EventManager $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return null
     */
    public function getFile()
    {
        return $this->__file;
    }

    /**
     * @param null $_file
     */
    public function setFile($_file)
    {
        $this->__file = $_file;
    }

    /**
     * @return array
     */
    public function getTemplate()
    {
        return $this->__template;
    }

    /**
     * @param array $_template
     */
    public function setTemplate($_template)
    {
        $this->__template = $_template;
    }

}
