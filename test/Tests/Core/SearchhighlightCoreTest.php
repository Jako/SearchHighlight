<?php
/**
 * SearchHighlight Core Tests
 *
 * @package searchhighlight
 * @subpackage test
 */

class SearchhighlightCoreTest extends SearchhighlightTestCase
{
    public function testHighlightTerms()
    {
        $source = file_get_contents($this->modx->config['testPath'] . 'Data/Page/source.page.tpl');
        $links = [
            'Template' => '<span class="highlight">Template</span>',
            'section' => '<span class="highlight">section</span>'
        ];
        $this->searchhighlight->options['sections'] = false;
        $source = $this->searchhighlight->highlightTerms($source, $links);
        $result = file_get_contents($this->modx->config['testPath'] . 'Data/Page/result.page.tpl');

        $this->assertEquals($source, $result);
    }

    public function testHighlightTermsSection()
    {
        $source = file_get_contents($this->modx->config['testPath'] . 'Data/Page/source.page.tpl');
        $links = [
            'Template' => '<span class="highlight">Template</span>',
            'section' => '<span class="highlight">section</span>'
        ];
        $this->searchhighlight->options['sections'] = true;
        $source = $this->searchhighlight->highlightTerms($source, $links);
        $result = file_get_contents($this->modx->config['testPath'] . 'Data/Page/result_sections.page.tpl');

        $this->assertEquals($source, $result);
    }
}
