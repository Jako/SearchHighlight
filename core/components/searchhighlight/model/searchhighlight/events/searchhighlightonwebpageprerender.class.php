<?php
/**
 * @package searchhighlight
 * @subpackage plugin
 */

class SearchhighlightOnWebPagePrerender extends SearchhighlightPlugin
{
    public function run()
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
