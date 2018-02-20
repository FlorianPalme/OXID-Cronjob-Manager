<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\OXIDCronjobManager\Core\DebugBar\Elements;


use FlorianPalme\DebugBar\Core\DebugBar\Elements\Element;
use FlorianPalme\DebugBar\Core\DebugBar\Renderer;
use FlorianPalme\OXIDCronjobManager\Application\Models\Cronjob;
use FlorianPalme\OXIDCronjobManager\Application\Models\CronjobList;
use FlorianPalme\OXIDCronjobManager\Application\Models\Log;
use FlorianPalme\OXIDCronjobManager\Application\Models\LogList;
use OxidEsales\Eshop\Core\Registry;

class Cronjobs implements Element
{

    /**
     * Gibt den Titel des Elements zurück
     *
     * Wird im Tab verwendet
     *
     * @return string
     */
    public function getTitle()
    {
        return Registry::getLang()->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS');
    }

    /**
     * Gibt den Content des Elements zurück
     *
     * @return string
     */
    public function getContent()
    {
        /** @var Renderer $renderer */
        $renderer = Registry::get(Renderer::class);
        $lang = Registry::getLang();

        $html = $renderer->createHeadline($lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_ACTIVECRONJOBS'));

        // Aktive Cronjobs laden
        /** @var CronjobList $cronjobs */
        $cronjobs = oxNew(CronjobList::class);
        $cronjobs->loadActiveCronjobs();

        if ($cronjobs->count()) {
            $html .= $renderer->createTableFromList([
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_ACTIVECRONJOBS_MODULEID'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_ACTIVECRONJOBS_CRONJOBID'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_ACTIVECRONJOBS_CRONTAB'),
            ], $cronjobs, [
               'fpocmcronjobs__oxmoduleid',
               'fpocmcronjobs__oxcronjobid',
               'fpocmcronjobs__oxcrontab',
            ]);
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_ACTIVECRONJOBS_NOJOBS')}</div>
HTML;
        }


        $html .= $renderer->createHeadline($lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG'));

        /** @var LogList $logs */
        $logs = oxNew(LogList::class);
        $logs->setSqlLimit(0, 10);
        $logs->getList();

        if ($logs->count()) {
            $html .= $renderer->createTableFromList([
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_CRONJOBID'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_STARTTIME'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_ENDTIME'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_STATE'),
                $lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_EXCEPTION'),
            ], $logs, [
                function(Log $log) {
                    /** @var Cronjob $cronjob */
                    $cronjob = oxNew(Cronjob::class);
                    $cronjob->load($log->fpocmlog__oxcronjobid->value);

                    return $cronjob->fpocmcronjobs__oxcronjobid->value;
                },
                function(Log $log) {
                    return date('d.m.Y H:i:s', $log->fpocmlog__oxstarttime->rawValue);
                },
                function(Log $log) {
                    return date('d.m.Y H:i:s', $log->fpocmlog__oxendtime->rawValue);
                },
                'fpocmlog__oxstate',
                'fpocmlog__oxexception',
            ]);
        } else {
            $html .= <<<HTML
<div class="novalues">{$lang->translateString('FP_CRONJOBMANAGER_DEBUGBAR_TABS_CRONJOBS_LOG_EMPTY')}</div>
HTML;
        }

        return $html;
    }
}