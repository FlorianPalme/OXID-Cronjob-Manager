<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Models;

use OxidEsales\Eshop\Core\Model\ListModel;

class LogList extends ListModel
{
    /**
     * Class constructor, initiates parent constructor (parent::oxList()).
     */
    public function __construct()
    {
        parent::__construct(Log::class);
    }


    /**
     * Lädt die Logliste eines bestimmten Cronjobs
     *
     * @param string $sCronjobId
     * @return $this
     */
    public function loadForCronjob( $sCronjobId )
    {
        $oListObject = $this->getBaseObject();
        $sFieldList = $oListObject->getSelectFields();
        $sQ = "select $sFieldList from " . $oListObject->getViewName();

        if ($sActiveSnippet = $oListObject->getSqlActiveSnippet()) {
            $sQ .= " where $sActiveSnippet AND ";
        } else {
            $sQ .= " where ";
        }

        $sQ .= "OXCRONJOBID = '$sCronjobId' ORDER BY oxstarttime";

        $this->selectString($sQ);

        return $this;
    }
}