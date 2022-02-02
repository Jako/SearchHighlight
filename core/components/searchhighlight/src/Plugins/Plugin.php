<?php
/**
 * Abstract plugin
 *
 * @package searchhighlight
 * @subpackage plugin
 */

namespace TreehillStudio\SearchHighlight\Plugins;

use modX;
use SearchHighlight;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var SearchHighlight $searchhighlight */
    protected $searchhighlight;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('searchhighlight.core_path', null, $this->modx->getOption('core_path') . 'components/searchhighlight/');
        $this->searchhighlight = $this->modx->getService('searchhighlight', 'SearchHighlight', $corePath . 'model/searchhighlight/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}