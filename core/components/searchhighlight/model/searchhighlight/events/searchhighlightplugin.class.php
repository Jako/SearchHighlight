<?php
/**
 * @package searchhighlight
 * @subpackage plugin
 */

abstract class SearchhighlightPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var SearchHighlight $searchhighlight */
    protected $searchhighlight;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('searchhighlight.core_path', null, $this->modx->getOption('core_path') . 'components/searchhighlight/');
        $this->searchhighlight = $this->modx->getService('searchhighlight', 'SearchHighlight', $corePath . 'model/searchhighlight/', array(
            'core_path' => $corePath
        ));
    }

    abstract public function run();
}
