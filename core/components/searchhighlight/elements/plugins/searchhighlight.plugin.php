<?php
/**
 * SearchHighlight
 *
 * Based on the code of the MODX Evolution SearchHighlight Plugin.
 *
 * @package searchhighlight
 * @subpackage plugin
 *
 * SearchHighlight plugin
 */

/** @var modX $modx */

if (isset($_REQUEST['searched'])) {
    $modx->lexicon->load('searchhighlight:default');

    $searched = strip_tags($_REQUEST['searched']);

    $exactphrase = ($_REQUEST['searchtype'] == 'exactphrase') ? true : false;

    if ($exactphrase) {
        $searchArray = array($searched);
    } else {
        $searchArray = array_unique(explode(' ', $searched));
    }

    $searchTerms = array();
    foreach ($searchArray as $key => $value) {
        // Consider all possible combinations
        $word_ents = array();
        $word_ents[] = $value;
        $word_ents[] = htmlentities($value, ENT_NOQUOTES, 'UTF-8');
        $word_ents[] = htmlentities($value, ENT_COMPAT, 'UTF-8');
        $word_ents[] = htmlentities($value, ENT_QUOTES, 'UTF-8');
        // Avoid duplication
        $word_ents = array_unique($word_ents);
        foreach ($word_ents as $word) {
            $searchTerms[] = array('term' => $word, 'class' => $key + 1);
        }
    }

    $output = &$modx->resource->_output; // get the parsed document

    if (strpos($output, '<!--highlight_start-->') !== false) {
        $parts = explode('<!--highlight_start-->', $output); // break out the page
        $bodyOnly = false;
    } else {
        $parts = explode('<body', $output); // break out the head
        $bodyOnly = true;
    }

    $highlightClasses = explode(' ', $modx->getOption('searchhighlight.highlightclasses', null, 'highlight')); // break out the highlight classes

    $pcreDelimiter = '/';
    $pcreModifier = 'iu';
    $lookBehind = '(?<!&|&[\w#]|&[\w#]\w|&[\w#]\w\w|&[\w#]\w\w\w|&[\w#]\w\w\w\w|&[\w#]\w\w\w\w\w)';  // avoid a match with a html entity
    $lookAhead = '(?=[^>]*<)'; // avoid a match with a html tag

    $highlightTerms = array();
    foreach ($searchTerms as $key => $value) {
        $word = $value['term'];
        $class = trim($highlightClasses[0] . ' ' . $highlightClasses[$value['class']]);

        $highlightTerms[] = '<span class="' . $class . '">' . $word . '</span>';

        $pattern = $pcreDelimiter . $lookBehind . preg_quote($word, '/') . $lookAhead . $pcreDelimiter . $pcreModifier;
        $replacement = '<span class="' . $class . '">${0}</span>';

        foreach ($parts as $k => &$part) {
            if ($k) {
                $section = explode('<!--highlight_end-->', $part, 2); // break out the part in section
                if (count($section) == 2 || $bodyOnly) {
                    $section[0] = preg_replace($pattern, $replacement, $section[0]);
                }
                $part = implode((!$bodyOnly) ? '<!--highlight_end-->' : '', $section);
            }
        }
    }

    if (!$bodyOnly) {
        $output = implode('<!--highlight_start-->', $parts);
    } else {
        $output = implode('<body', $parts);
    }

    if (strpos($output, '<!--search_terms-->') !== false) {
        $removeUrl = $modx->makeUrl($modx->resource->get('id'));
        $highlightText = '<div class="search_terms">' .
            $modx->lexicon('searchhighlight.search_terms') . ': ' . implode(', ', $highlightTerms) . '<br />' .
            '<a href="' . $removeUrl . '" class="remove_highlight">' . $modx->lexicon('searchhighlight.remove_highlighting') . '</a>' .
            '</div>';

        $output = str_replace('<!--search_terms-->', $highlightText, $output);
    }
}