<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package FlorianPalme
 * @subpackage OXIDCronjobManager
 */
namespace FlorianPalme\OXIDCronjobManager\Application\Models;


use FlorianPalme\OXIDCronjobManager\Core\Module\Module;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class Cronjob extends BaseModel
{
    /**
     * Current class name.
     *
     * @var string
     */
    protected $_sClassName = self::class;


    /**
     * Cronjob-Module
     *
     * @var Module
     */
    protected $_oModule;


    /**
     * Cronjob-Config
     *
     * @var null|array
     */
    protected $_aModuleCronjob = null;

    /**
     * Saves Title in current language
     *
     * @var string
     */
    protected $_sTitle = null;


    /**
     * Last saved log
     *
     * @var Log
     */
    protected $_oLastLog;


    /**
     * Cronjob log statistics
     *
     * @var \stdClass
     */
    protected $_oStatistics;


    /**
     * Class constructor, initiates parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('fpocmcronjobs');
    }


    /**
     * Lädt den Cronjob anhand der CronjobId
     *
     * @param string $sCronjobId
     * @param string $sModuleId
     * @return bool
     */
    public function loadByCronjobId( $sCronjobId, $sModuleId )
    {
        $this->_addField('oxid', 0);
        $sSelect = $this->buildSelectString([
            $this->getViewName() . '.oxcronjobid' => $sCronjobId,
            $this->getViewName() . '.oxmoduleid' => $sModuleId,
        ]);
        $this->_isLoaded = $this->assignRecord($sSelect);


        return $this->_isLoaded;
    }


    /**
     * Gibt den Titel des Cronjobs zurück
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        if( $this->_sTitle === null ){
            $this->_sTitle = $this->fpocmcronjobs__oxcronjobid->value;

            if( $aCronjob = $this->getModuleCronjob() ){
                if( isset( $aCronjob[ 'title' ] ) ){
                    $mTitle = $aCronjob[ 'title' ];
                    $oLang = Registry::getLang();
                    $sLangAbbr = $oLang->getLanguageAbbr( $oLang->getEditLanguage() );

                    if( is_array( $mTitle ) && isset( $mTitle[ $sLangAbbr ] ) ){
                        $this->_sTitle = $mTitle[ $sLangAbbr ];
                    } elseif( is_string( $mTitle ) ) {
                        $this->_sTitle = $$mTitle;
                    }
                }
            }
        }

        return $this->_sTitle;
    }


    /**
     * Gibt die auszuführende Funktione zurück
     *
     * @return mixed
     */
    public function getFnc()
    {
        return $this->getModuleCronjob()[ 'fnc' ];
    }


    /**
     * Gibt den Modul-Titel zurück
     *
     * @return string
     */
    public function getModuleTitle()
    {
        return $this->getModule()->getTitle();
    }

    /**
     * Gibt die Cronjob-Einstellungen für eine ID zurück
     *
     * @return array|null
     */
    public function getModuleCronjob()
    {
        if( $this->_aModuleCronjob === null ){
            $this->_aModuleCronjob = [];

            if( ! $this->getModule() ){
                return [];
            }

            if( $aCronjob = $this->getModule()->getCronjobs()[ $this->fpocmcronjobs__oxcronjobid->value ] ){
                $this->_aModuleCronjob = $aCronjob;
            }
        }

        return $this->_aModuleCronjob;
    }


    /**
     * Gibt das Modul zurück
     *
     * @return Module|bool
     */
    public function getModule()
    {
        if( $this->_oModule === null ){
            /** @var Module $oModule */
            $oModule = oxNew( \OxidEsales\Eshop\Core\Module\Module::class );
            if( ! $oModule->load( $this->fpocmcronjobs__oxmoduleid->value ) ){
                $this->_oModule = false;

                return false;
            }

            $this->_oModule = $oModule;
        }

        return $this->_oModule;
    }


    /**
     * Gibt den letzten gespeicherten Log zurück
     *
     * @return Log|bool
     */
    public function getLastLog()
    {
        if( $this->_oLastLog === null ){
            $this->_oLastLog = false;

            /** @var Log $oLog */
            $oLog = oxNew( Log::class );

            if( $oLog->loadLastLogForCronjob( $this->getId() ) ){
                $this->_oLastLog = $oLog;
            }
        }

        return $this->_oLastLog;
    }


    /**
     * Gibt Statistiken für einen Cronjob zurück
     *
     * @return \stdClass
     */
    public function getStatistics()
    {
        if( $this->_oStatistics === null ){
            $oStatistics = new \stdClass();

            $oDb = DatabaseProvider::getDb();
            /** @var Log $oLog */
            $oLog = oxNew( Log::class );
            $sLogView = $oLog->getViewName();

            $oStatistics->count = $oDb->getOne(
                "SELECT COUNT(*) FROM $sLogView WHERE oxcronjobid = '{$this->getId()}'"
            );

            $oStatistics->aborted = $oDb->getOne(
                "SELECT COUNT(*) FROM $sLogView WHERE oxcronjobid = '{$this->getId()}' AND oxstate = 'aborted'"
            );

            $oStatistics->averageDuration = $oDb->getOne(
                "SELECT AVG(oxendtime - oxstarttime) FROM $sLogView WHERE oxcronjobid = '{$this->getId()}'"
            );

            $this->_oStatistics = $oStatistics;
        }

        return $this->_oStatistics;
    }


    /**
     * Delete this object from the database, returns true on success.
     *
     * @param string $sOxId Object ID(default null)
     *
     * @return bool
     */
    public function delete($sOxId = null)
    {
        $blDeleted = parent::delete($sOxId);

        if (!$sOxId) {
            $sOxId = $this->getId();

            //do not allow derived deletion
            if (!$this->allowDerivedDelete()) {
                return $blDeleted;
            }
        }

        if (!$sOxId) {
            return $blDeleted;
        }


        // Log leeren
        $oDb = DatabaseProvider::getDb();
        $oDb->execute("DELETE FROM fpocmlog WHERE oxcronjobid = '$sOxId'");

        return $blDeleted;
    }
}