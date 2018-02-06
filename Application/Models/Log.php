<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Models;


use OxidEsales\Eshop\Core\Model\BaseModel;

class Log extends BaseModel
{
    /**
     * Current class name.
     *
     * @var string
     */
    protected $_sClassName = self::class;

    /**
     * Class constructor, initiates parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('fpocmlog');
    }


    /**
     * Lädt den letzten Log eines bestimmten Cronjobs
     *
     * @param $sCronjobId
     * @return bool
     */
    public function loadLastLogForCronjob( $sCronjobId )
    {
        //getting at least one field before lazy loading the object
        $this->_addField('oxid', 0);
        $sSelect = $this->buildSelectString(array($this->getViewName() . '.oxcronjobid' => $sCronjobId ));

        // Order
        $sSelect .= 'ORDER BY ' . $this->getViewName() . '.oxstarttime DESC LIMIT 1';

        $this->_isLoaded = $this->assignRecord($sSelect);

        return $this->_isLoaded;
    }
}