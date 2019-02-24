<?php
/**
 * SearchHighlight plugin
 *
 * @package searchhighlight
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'SearchHighlight' . $modx->event->name;

$corePath = $modx->getOption('searchhighlight.core_path', null, $modx->getOption('core_path') . 'components/searchhighlight/');
/** @var SearchHighlight $searchhighlight */
$searchhighlight = $modx->getService('searchhighlight', 'SearchHighlight', $corePath . 'model/searchhighlight/', array(
    'core_path' => $corePath
));

$modx->loadClass('SearchHighlightPlugin', $searchhighlight->getOption('modelPath') . 'searchhighlight/events/', true, true);
$modx->loadClass($className, $searchhighlight->getOption('modelPath') . 'searchhighlight/events/', true, true);
if (class_exists($className)) {
    /** @var SearchHighlightPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;
