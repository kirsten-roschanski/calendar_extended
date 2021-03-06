<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package   Contao
 * @author    Kester Mielke
 * @license   LGPL
 * @copyright Kester Mielke 2010-2013
 */


/**
 * Add palettes to tl_module
 */

// Palette for calendar
$GLOBALS['TL_DCA']['tl_module']['palettes']['calendar'] = str_replace
(
    ';{redirect_legend}',
    ';{config_ext_legend},cal_holiday;{redirect_legend}',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['calendar']
);

// Palette for timetable
$GLOBALS['TL_DCA']['tl_module']['palettes']['timetable'] = $GLOBALS['TL_DCA']['tl_module']['palettes']['calendar'];
$GLOBALS['TL_DCA']['tl_module']['palettes']['timetable'] = str_replace
(
    ';{redirect_legend}',
    ',showDate,hideEmptyDays,use_navigation,linkCurrent,cal_times,cal_times_range,cellhight;{redirect_legend}',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['timetable']
);

// Palette for yearview
$GLOBALS['TL_DCA']['tl_module']['palettes']['yearview'] = $GLOBALS['TL_DCA']['tl_module']['palettes']['calendar'];
$GLOBALS['TL_DCA']['tl_module']['palettes']['yearview'] = str_replace
(
    ';{redirect_legend}',
    ',use_horizontal,use_navigation,linkCurrent;{protected_legend:hide}',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['yearview']
);

// Palette for eventlist
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist'] = str_replace
(
    ';{template_legend:hide}',
    ';{config_ext_legend},cal_holiday,show_holiday,cal_format_ext,range_date,displayDuration,showRecurrences,hide_started,pubTimeRecurrences,showOnlyNext;{template_legend:hide}',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']
);

// Palette for eventreader
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader'] = str_replace
(
    '{config_legend},cal_calendar',
    '{config_legend},cal_calendar,cal_holiday',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['eventreader']
);
// Palette for registration
$GLOBALS['TL_DCA']['tl_module']['palettes']['evr_registration'] = '{title_legend},name,headline,type;{registration_legend},nc_notification,regtype;';
//'{redirect_legend},jumpTo;{template_legend:hide},cal_ctemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['regtype'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['regtype'],
    'exclude' => true,
    'filter' => true,
    'default' => 0,
    'inputType' => 'radio',
    'options' => array(1, 0),
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['regtypes'],
    'eval' => array('tl_class' => 'w50 m12', 'chosen' => true),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_calendar'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cal_calendar'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'options_callback' => array('calendar_Ext', 'getCalendars'),
    'eval' => array('mandatory' => true, 'multiple' => true),
    'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_holiday'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cal_holiday'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'options_callback' => array('calendar_Ext', 'getHolidays'),
    'eval' => array('mandatory' => false, 'multiple' => true, 'tl_class' => 'long'),
    'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['show_holiday'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['show_holiday'],
    'default' => 0,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['pubTimeRecurrences'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['pubTimeRecurrences'],
    'default' => 0,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_format_ext'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cal_format_ext'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'clr'),
    'save_callback' => array
    (
        array('calendar_Ext', 'checkDuration')
    ),
    'sql' => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['displayDuration'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['displayDuration'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'clr'),
    'save_callback' => array
    (
        array('calendar_Ext', 'checkDuration')
    ),
    'sql' => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['showOnlyNext'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['showOnlyNext'],
    'default' => 0,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['showRecurrences'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['showRecurrences'],
    'default' => 0,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['use_horizontal'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['use_horizontal'],
    'default' => 0,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['use_navigation'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['use_navigation'],
    'default' => 1,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['showDate'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['showDate'],
    'default' => 1,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['linkCurrent'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['linkCurrent'],
    'default' => 1,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hideEmptyDays'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['hideEmptyDays'],
    'default' => 1,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_times'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cal_times'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

// list of exceptions
$GLOBALS['TL_DCA']['tl_module']['fields']['cal_times_range'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cal_times_range'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => array
    (
        'tl_class' => 'clr w50',
        'columnsCallback' => array('calendar_Ext', 'getTimeRange'),
        'buttons' => array('up' => false, 'down' => false, 'copy' => false)
    ),
    'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cellhight'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['cellhight'],
    'default' => 60,
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hide_started'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['hide_started'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50'),
    'sql' => "char(1) NOT NULL default ''"
);

// list of exceptions
$GLOBALS['TL_DCA']['tl_module']['fields']['range_date'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_module']['range_date'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => array
    (
        'columnsCallback' => array('calendar_Ext', 'getRange'),
        'buttons' => array('up' => false, 'down' => false, 'copy' => false)
    ),
    'sql' => "text NULL"
);

/**
 * Class timetableExt
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Kester Mielke 2011
 * @author     Kester Mielke
 * @package    Controller
 */
class calendar_Ext extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * @return array
     */
    public function listNotifications()
    {
        if (!class_exists('leads\leads')) {
            return null;
        }

        $return = array();

        $objNotifications = \NotificationCenter\Model\Notification::findAll();
        if ($objNotifications !== null) {
            while ($objNotifications->next()) {
                $return[$objNotifications->id] = $objNotifications->title;
            }
        }

        return $return;
    }


    /**
     * @return array|null
     */
    public function getTimeRange()
    {
        $columnFields = null;

        $columnFields = array
        (
            'time_from' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_module']['time_range_from'],
                'exclude' => true,
                'default' => null,
                'inputType' => 'text',
                'eval' => array('rgxp' => 'time', 'doNotCopy' => true, 'style' => 'width:120px', 'datepicker' => true, 'tl_class' => 'wizard')
            ),
            'time_to' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_module']['time_range_to'],
                'exclude' => true,
                'default' => null,
                'inputType' => 'text',
                'eval' => array('rgxp' => 'time', 'doNotCopy' => true, 'style' => 'width:120px', 'datepicker' => true, 'tl_class' => 'wizard')
            )
        );

        return $columnFields;
    }


    /**
     * @return array|null
     */
    public function getRange()
    {
        $columnFields = null;

        $columnFields = array
        (
            'date_from' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_module']['range_from'],
                'exclude' => true,
                'default' => null,
                'inputType' => 'text',
                'eval' => array('rgxp' => 'datim', 'doNotCopy' => true, 'style' => 'width:120px', 'datepicker' => true, 'tl_class' => 'wizard')
            ),
            'date_to' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_module']['range_to'],
                'exclude' => true,
                'default' => null,
                'inputType' => 'text',
                'eval' => array('rgxp' => 'datim', 'doNotCopy' => true, 'style' => 'width:120px', 'datepicker' => true, 'tl_class' => 'wizard')
            )
        );

        return $columnFields;
    }


    /**
     * @param $varValue
     * @return mixed
     * @throws Exception
     */
    public function checkDuration($varValue)
    {
        if (strlen($varValue) > 0) {
            if (($timestamp = strtotime($varValue, time())) === false) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['displayDurationError'] . ': ' . $timestamp);
            }
            if (($timestamp = date('dmY', strtotime($varValue, time()))) === date('dmY', time())) {
                throw new Exception($GLOBALS['TL_LANG']['tl_module']['displayDurationError2'] . ': ' . $timestamp);
            }
        }
        return $varValue;
    }


    /**
     * Get all calendars and return them as array
     * @return array
     */
    public function getCalendars()
    {
        if (!$this->User->isAdmin && !is_array($this->User->calendars)) {
            return array();
        }

        $arrCalendars = array();
        $objCalendars = $this->Database->execute("SELECT id, title FROM tl_calendar WHERE isHolidayCal != 1 ORDER BY title");

        while ($objCalendars->next()) {
            if ($this->User->isAdmin || $this->User->hasAccess($objCalendars->id, 'calendars')) {
                $arrCalendars[$objCalendars->id] = $objCalendars->title;
            }
        }

        return $arrCalendars;
    }


    /**
     * Get all calendars and return them as array
     * @return array
     */
    public function getHolidays()
    {
        if (!$this->User->isAdmin && !is_array($this->User->calendars)) {
            return array();
        }

        $arrCalendars = array();
        $objCalendars = $this->Database->execute("SELECT id, title FROM tl_calendar WHERE isHolidayCal = 1 ORDER BY title");

        while ($objCalendars->next()) {
            if ($this->User->isAdmin || $this->User->hasAccess($objCalendars->id, 'calendars')) {
                $arrCalendars[$objCalendars->id] = $objCalendars->title;
            }
        }

        return $arrCalendars;
    }
}
