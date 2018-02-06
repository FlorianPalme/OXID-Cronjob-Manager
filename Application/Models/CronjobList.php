<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Models;

use OxidEsales\Eshop\Core\Model\ListModel;

class CronjobList extends ListModel
{
    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     */
    public function __construct()
    {
        parent::__construct(Cronjob::class);
    }
}