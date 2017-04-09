<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package fp
 * @subpackage oxid-cronjob-manager
 */
class fpOCM_Cronjob extends oxBase
{
    /**
     * Current class name.
     *
     * @var string
     */
    protected $_sClassName = 'fpocm_cronjob';


    /**
     * Cronjob-Module
     *
     * @var null|oxModule|fpOCM_oxModule|bool
     */
    protected $_oModule = null;


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
     * @var null|fpOCM_Log|false
     */
    protected $_oLastLog = null;


    /**
     * Cronjob log statistics
     *
     * @var null|stdClass
     */
    protected $_oStatistics = null;


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
                    $oLang = oxRegistry::getLang();
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
     * @return fpOCM_oxModule|null|oxModule|bool
     */
    public function getModule()
    {
        if( $this->_oModule === null ){
            /** @var oxModule|fpOCM_oxModule $oModule */
            $oModule = oxNew( 'oxModule' );
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
     * @return bool|fpOCM_Log|null
     */
    public function getLastLog()
    {
        if( $this->_oLastLog === null ){
            $this->_oLastLog = false;

            /** @var fpOCM_Log $oLog */
            $oLog = oxNew( 'fpOCM_Log' );

            if( $oLog->loadLastLogForCronjob( $this->getId() ) ){
                $this->_oLastLog = $oLog;
            }
        }

        return $this->_oLastLog;
    }


    /**
     * Gibt Statistiken für einen Cronjob zurück
     *
     * @return null|stdClass
     */
    public function getStatistics()
    {
        if( $this->_oStatistics === null ){
            $oStatistics = new stdClass();

            $oDb = oxDb::getDb();
            $oLog = oxNew( 'fpOCM_Log' );
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
}