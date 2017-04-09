<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 * @see https://oxidforge.org/en/extension-metadata-file.html
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Modul-Informationen
 */
$aModule = [
    'id'			=>	'fpOxidCronjobManager',
    'title'			=>	'<span style="color:#d35400;font-weight:bold;">{</span>FP<span style="color:#d35400;font-weight:bold;">}</span> OXID Cronjob Manager',
    'description'	=>	'',
    'thumbnail'		=>	'picture.png',
    'version'		=>	'0.1',
    'author'		=>	'Florian Palme',
    'url'			=>	'http://www.florian-palme.de/',
    'email'			=>	'info@florian-palme.de',

    /** Files (fp/Module/) **/
    'files'			=>	[
        'fpocm_admin' => 'fp/oxid-cronjob-manager/application/controllers/admin/fpocm_admin.php',
        'fpocm_list' => 'fp/oxid-cronjob-manager/application/controllers/admin/fpocm_list.php',
        'fpocm_main' => 'fp/oxid-cronjob-manager/application/controllers/admin/fpocm_main.php',
        'fpocm_events' => 'fp/oxid-cronjob-manager/events.php',
        'fpocm_cronjob' => 'fp/oxid-cronjob-manager/application/models/fpocm_cronjob.php',
        'fpocm_cronjoblist' => 'fp/oxid-cronjob-manager/application/models/fpocm_cronjoblist.php',
    ],

    /** Extend Classes (fp/Module) **/
    'extend'		=>	[
        'oxmodule' => 'fp/oxid-cronjob-manager/core/fpocm_oxmodule',
        'oxmaintenance' => 'fp/oxid-cronjob-manager/core/fpocm_oxmaintenance',
    ],

    /** Blocks **/
    'blocks'		=>	[
        /*[
            'template' => 'headitem.tpl',
            'block' => 'admin_headitem_incjs',
            'file' => 'application/views/admin/tpl/headitem/incjs.tpl',
        ],*/

        /*[
            'template' => 'headitem.tpl',
            'block' => 'admin_headitem_inccss',
            'file' => 'application/views/admin/tpl/headitem/inccss.tpl',
        ],*/
    ],

    /** Settings **/
    'settings'		=>	[

    ],

    /** Templates (fp/Module) **/
    'templates'		=>	[
        'fpocm_admin.tpl' => 'fp/oxid-cronjob-manager/application/views/admin/tpl/fpocm_admin.tpl',
        'fpocm_list.tpl' => 'fp/oxid-cronjob-manager/application/views/admin/tpl/fpocm_list.tpl',
        'fpocm_main.tpl' => 'fp/oxid-cronjob-manager/application/views/admin/tpl/fpocm_main.tpl',
    ],

    /** Events **/
    'events'        =>  [
        'onActivate' => 'fpOCM_Events::onActivate',
    ],


    /** Cronjobs */
    'cronjobs' => [
        'fpocm_excronjob1' => [
            'fnc' => 'doMyJob',
            'title' => [
                'de' => 'Test-Titel',
            ],
            'crontab' => '* * * * *',
        ],
    ],
];