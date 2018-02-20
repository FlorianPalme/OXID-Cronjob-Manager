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


    /**
     * Lädt aktive Cronjobs in die Liste
     *
     * @return $this
     */
    public function loadActiveCronjobs()
    {
        $listObject = $this->getBaseObject();
        $fieldList = $listObject->getSelectFields();

        $query = "select $fieldList from " . $listObject->getViewName();
        $query .= " where oxstatus = 'active'";
        $this->selectString($query);

        return $this;
    }
}