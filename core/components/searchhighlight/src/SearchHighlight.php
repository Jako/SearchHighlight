<?php
/**
 * SearchHighlight
 *
 * Copyright 2015-2022 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package searchhighlight
 * @subpackage classfile
 */

namespace TreehillStudio\SearchHighlight;

use modX;

/**
 * class SearchHighlight
 */
class SearchHighlight
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'searchhighlight';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'SearchHighlight';

    /**
     * The version
     * @var string $version
     */
    public $version = '2.2.0';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * The requests array
     * @var array $requests
     */
    public $requests = [];

    /**
     * The found request
     * @var array $found
     */
    public $found = [];

    /**
     * SearchHighlight constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php',
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->getOption('debug', $options, false),
            'modxversion' => $modxversion['version'],
            'disabledTags' => $this->getOption('disabledTags', $options, 'head,form,select'),
            'highlightClasses' => $this->getOption('highlightClasses', $options, 'highlight'),
            'highlightTpl' => $this->getOption('highlightTpl', $options, 'tplTermHighlight'),
            'searchedRequestKey' => $this->getOption('searchedRequestKey', $options, 'searched'),
            'searchTerms' => $this->getOption('searchTerms', $options, '<!--search_terms-->'),
            'searchTermsTpl' => $this->getOption('searchTermsTpl', $options, 'tplSearchTerms'),
            'searchtypeRequestKey' => $this->getOption('searchtypeRequestKey', $options, 'searchtype'),
            'sections' => (bool)$this->getOption('sections', $options, false),
            'sectionsEnd' => $this->getOption('sectionsEnd', $options, '<!-- SearchHighlightEnd -->'),
            'sectionsStart' => $this->getOption('sectionsStart', $options, '<!-- SearchHighlightStart -->'),
        ]);

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Get the searched terms
     *
     * @param string $chunkName
     * @return array
     */
    public function getTerms($chunkName)
    {
        $search = isset($_REQUEST[$this->getOption('searchedRequestKey')]) ? strip_tags($_REQUEST[$this->getOption('searchedRequestKey')]) : '';
        $exactphrase = isset($_REQUEST[$this->getOption('searchtypeRequestKey')]) && $_REQUEST[$this->getOption('searchtypeRequestKey')] == 'exactphrase';
        $highlightClasses = explode(' ', $this->getOption('highlightClasses'));

        if ($exactphrase) {
            $searchArray = [$search];
        } else {
            $searchArray = ($search) ? array_unique(explode(' ', $search)) : [];
        }

        $terms = [];
        foreach ($searchArray as $key => $value) {
            // Consider all possible combinations
            $wordEntities = [];
            $wordEntities[] = $value;
            $wordEntities[] = htmlentities($value, ENT_NOQUOTES, 'UTF-8');
            $wordEntities[] = htmlentities($value, ENT_COMPAT, 'UTF-8');
            $wordEntities[] = htmlentities($value, ENT_QUOTES, 'UTF-8');
            // Avoid duplication
            $wordEntities = array_unique($wordEntities);
            foreach ($wordEntities as $wordEntity) {
                $terms[$wordEntity] = trim($this->modx->getChunk($chunkName, [
                    'term' => $wordEntity,
                    'class' => $highlightClasses[0] . ((isset($highlightClasses[$key + 1])) ? ' ' . $highlightClasses[$key + 1] : ''),
                ]), "\r\n");
            }
        }
        return $terms;
    }

    /**
     * Highlight searched terms in the text
     *
     * @param string $text
     * @param array $terms
     * @return string
     */
    public function highlightTerms($text, $terms)
    {
        // Enable section markers
        $enableSections = $this->getOption('sections', null, false);
        if ($enableSections) {
            $splitEx = '~((?:' . $this->getOption('sectionsStart') . ').*?(?:' . $this->getOption('sectionsEnd') . '))~isu';
            $sections = preg_split($splitEx, $text, null, PREG_SPLIT_DELIM_CAPTURE);
        } else {
            $sections = [$text];
        }

        // Mask all terms first
        $maskStart = '<_»_>';
        $maskEnd = '<_«_>';
        $disabledTags = array_map('trim', explode(',', $this->getOption('disabledTags')));
        $splitExTags = [];
        foreach ($disabledTags as $disabledTag) {
            $splitExTags[] = '<' . $disabledTag . '.*?</' . $disabledTag . '>';
        }
        // No replacements in html tags and between disabled tags
        $splitExDisabled = '~(' . implode('|', $splitExTags) . '|</?[a-z0-9]+[^>]*?>)~isu';
        foreach ($terms as $termText => $termValue) {
            foreach ($sections as &$section) {
                if (($enableSections && strpos($section, $this->getOption('sectionsStart')) === 0 && stripos($section, $termText) !== false) ||
                    (!$enableSections && stripos($section, $termText) !== false)
                ) {
                    $subSections = preg_split($splitExDisabled, $section, null, PREG_SPLIT_DELIM_CAPTURE);
                    foreach ($subSections as &$subSection) {
                        if (!preg_match($splitExDisabled, $subSection)) {
                            $subSection = preg_replace('~(' . preg_quote($termText, '~') . ')~iu', $maskStart . '$1' . $maskEnd, $subSection);
                        }
                    }
                    $section = implode('', $subSections);
                }
            }
        }
        $text = implode('', $sections);

        // Replace the terms after to avoid nested replacement
        foreach ($terms as $termText => $termValue) {
            $text = preg_replace(
                '~' . $maskStart . '(' . preg_quote($termText, '~') . ')' . $maskEnd . '~iu',
                str_replace(['$', $termText], ['\$', '$1'], $termValue),
                $text
            );
        }

        // Remove remaining section markers
        return str_replace([
            $this->getOption('sectionsStart'), $this->getOption('sectionsEnd')
        ], '', $text);
    }

    /**
     * Show search terms
     *
     * @param string $text
     * @param string $chunkName
     * @return string
     */
    public function showTerms($text, $chunkName)
    {
        $search = isset($_REQUEST[$this->getOption('searchedRequestKey')]) ? strip_tags($_REQUEST[$this->getOption('searchedRequestKey')]) : '';
        $exactphrase = isset($_REQUEST[$this->getOption('searchtypeRequestKey')]) && $_REQUEST[$this->getOption('searchtypeRequestKey')] == 'exactphrase';

        if ($exactphrase) {
            $searchArray = [$search];
        } else {
            $searchArray = ($search) ? array_unique(explode(' ', $search)) : [];
        }

        if ($searchArray && strpos($text, $this->getOption('searchTerms')) !== false) {
            $parameters = $this->modx->request->getParameters();
            $url = (isset($parameters[$this->modx->getOption('request_param_alias')])) ? $parameters[$this->modx->getOption('request_param_alias')] : $this->modx->makeUrl($this->modx->resource->get('id'));
            unset($parameters[$this->getOption('searchedRequestKey')]);
            unset($parameters[$this->getOption('searchtypeRequestKey')]);
            $highlightText = $this->modx->getChunk($chunkName, [
                'terms' => implode(', ', $searchArray),
                'url' => $url . (($parameters) ? '?' . http_build_query($parameters) : '')
            ]);
            $text = str_replace('<!--search_terms-->', $highlightText, $text);
        }

        return $text;
    }
}
