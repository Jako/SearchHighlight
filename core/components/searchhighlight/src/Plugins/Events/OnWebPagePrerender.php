<?php
/**
 * @package searchhighlight
 * @subpackage plugin
 */

namespace TreehillStudio\SearchHighlight\Plugins\Events;

use TreehillStudio\SearchHighlight\Plugins\Plugin;

class OnWebPagePrerender extends Plugin
{
    public function process()
    {
        $highlightTpl = $this->searchhighlight->getOption('highlightTpl');
        $searchTermsTpl = $this->searchhighlight->getOption('searchTermsTpl');

        $output = $this->modx->resource->_output;
        $terms = $this->searchhighlight->getTerms($highlightTpl);
        $output = $this->searchhighlight->highlightTerms($output, $terms);
        $output = $this->searchhighlight->showTerms($output, $searchTermsTpl);
        $this->modx->resource->_output = $output;
    }
}
