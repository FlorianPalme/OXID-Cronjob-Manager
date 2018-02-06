<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Controller\Admin;

use FlorianPalme\OXIDCronjobManager\Application\Models\Cronjob;
use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

class CronjobList extends AdminListController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'fpocm_list.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = Cronjob::class;

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = \FlorianPalme\OXIDCronjobManager\Application\Models\CronjobList::class;
}