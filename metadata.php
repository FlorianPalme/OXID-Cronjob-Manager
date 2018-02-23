<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 * @see https://oxidforge.org/en/extension-metadata-file.html
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Modul-Informationen
 */
$aModule = [
    'id'			=>	'fpcronjobmanager',
    'title'			=>	'<span style="color:#d35400;font-weight:bold;">{</span>FP<span style="color:#d35400;font-weight:bold;">}</span> OXID Cronjob Manager',
    'description'	=>	'Fügt dem OXID-Backend einen Cronjob-Manager ein.',
    'thumbnail'		=>	'picture.png',
    'version'		=>	'2.0.0',
    'author'		=>	'Florian Palme',
    'url'			=>	'http://www.florian-palme.de/',
    'email'			=>	'info@florian-palme.de',

    'controllers' => [
        'fpocm_admin'
            => \FlorianPalme\OXIDCronjobManager\Application\Controller\Admin\Admin::class,

        'fpocm_adminlog'
            => \FlorianPalme\OXIDCronjobManager\Application\Controller\Admin\AdminLog::class,

        'fpocm_list'
            => \FlorianPalme\OXIDCronjobManager\Application\Controller\Admin\CronjobList::class,

        'fpocm_main'
            => \FlorianPalme\OXIDCronjobManager\Application\Controller\Admin\CronjobMain::class,
    ],

    /** Extend Classes (fp/Module) **/
    'extend'		=>	[
        \OxidEsales\Eshop\Application\Model\Maintenance::class
            => \FlorianPalme\OXIDCronjobManager\Application\Models\Maintenance::class,

        \OxidEsales\Eshop\Core\Module\Module::class
            => \FlorianPalme\OXIDCronjobManager\Core\Module\Module::class,
    ],

    /** Blocks **/
    'blocks'		=>	[
        [
            'template' => 'headitem.tpl',
            'block' => 'admin_headitem_inccss',
            'file' => 'application/views/admin/tpl/headitem/inccss.tpl',
        ],
    ],

    /** Settings **/
    'settings'		=>	[

    ],

    /** Templates (fp/Module) **/
    'templates'		=>	[
        'fpocm_admin.tpl' => 'fp/cronjobmanager/Application/views/admin/tpl/admin.tpl',
        'fpocm_list.tpl' => 'fp/cronjobmanager/Application/views/admin/tpl/list.tpl',
        'fpocm_main.tpl' => 'fp/cronjobmanager/Application/views/admin/tpl/main.tpl',
        'fpocm_adminlog.tpl' => 'fp/cronjobmanager/Application/views/admin/tpl/adminlog.tpl',
    ],

    /** Events **/
    'events'        =>  [
        'onActivate' => 'FlorianPalme\OXIDCronjobManager\Core\Events::onActivate',
        'onDeactivate' => 'FlorianPalme\OXIDCronjobManager\Core\Events::onDeactivate',
    ],

    /** Debugbar Erweiterungen */
    'debugbar' => [
        'fpcronjobmanager_cronjobs' => 'FlorianPalme\OXIDCronjobManager\Core\DebugBar\Elements\Cronjobs',
    ],
];