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

$className = 'TreehillStudio\SearchHighlight\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('searchhighlight.core_path', null, $modx->getOption('core_path') . 'components/searchhighlight/');
/** @var SearchHighlight $searchhighlight */
$searchhighlight = $modx->getService('searchhighlight', 'SearchHighlight', $corePath . 'model/searchhighlight/', [
    'core_path' => $corePath
]);

if ($searchhighlight) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'SearchHighlight Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'SearchHighlight Plugin');
    }
}

return;