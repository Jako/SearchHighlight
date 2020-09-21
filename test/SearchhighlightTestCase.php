<?php
/**
 * Searchhighlight Test Case
 *
 * @package searchhighlight
 * @subpackage test
 */

class SearchhighlightTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var modX $modx
     */
    protected $modx = null;
    /**
     * @var SearchHighlight $searchhighlight
     */
    protected $searchhighlight = null;

    /**
     * Ensure all tests have a reference to the MODX and SearchHighlight objects
     */
    public function setUp()
    {
        $this->modx = SearchhighlightTestHarness::_getConnection();

        $corePath = $this->modx->getOption('searchhighlight.core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/searchhighlight/');
        require_once $corePath . 'model/searchhighlight/searchhighlight.class.php';

        $this->searchhighlight = new SearchHighlight($this->modx);
        $this->searchhighlight->options['debug'] = true;

        /* make sure to reset MODX placeholders so as not to keep placeholder data across tests */
        $this->modx->placeholders = array();
        $this->modx->searchhighlight = &$this->searchhighlight;

        error_reporting(E_ALL);
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown()
    {
        $this->modx = null;
        $this->searchhighlight = null;
    }
}