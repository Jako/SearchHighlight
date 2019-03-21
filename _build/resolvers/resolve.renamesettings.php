<?php
/**
 * Resolves rename settings
 *
 * @package searchhighlight
 * @subpackage build
 *
 * @var mixed $object
 * @var array $options
 * @var xPDOTransport $transport
 */

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting', array('key' => 'searchhighlight.highlightclasses'));
        if ($setting) {
            $setting->set('key', 'searchhighlight.highlightClasses');
            $setting->set('description', 'searchhighlight.highlightClasses_desc');
            $setting->save();
        }
        break;
}

return true;
