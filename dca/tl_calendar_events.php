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

foreach ($GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'] as $k => $v)
{
    if ($v[0] == 'tl_calendar_events' && $v[1] == 'adjustTime')
    {
        unset($GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][$k]);
        array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'], 0, array(array('tl_calendar_events_ext','adjustTime')));
    }
}

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace
(
    'addTime,',
    'showOnFreeDay,addTime,',
    $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
);

if (class_exists('Efg\Formdata'))
{
    $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace
    (
        '{recurring_legend},recurring;',
        '{location_legend},location_name,location_str,location_plz,location_ort;{contact_legend},location_link,location_contact,location_mail;{regform_legend},useRegistration;{recurring_legend},recurring;{recurring_legend_ext},recurringExt;{repeatFixedDates_legend},repeatFixedDates;{exception_legend},useExceptions;',
        $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
    );
}
else
{
    $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace
    (
        '{recurring_legend},recurring;',
        '{location_legend},location_name,location_str,location_plz,location_ort;{contact_legend},location_link,location_contact,location_mail;{recurring_legend},recurring;{recurring_legend_ext},recurringExt;{repeatFixedDates_legend},repeatFixedDates;{exception_legend},useExceptions;',
        $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
    );
}

// change the default palettes
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'], 99, 'recurringExt');
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'], 99, 'useExceptions');
array_insert($GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'], 99, 'useRegistration');

// change the default palettes
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['recurring'] = str_replace
(
    'repeatEach,recurrences',
    'hideOnWeekend,repeatEach,recurrences,repeatEnd',
    $GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['recurring']
);

// change the default palettes
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['addTime'] = str_replace
(
    'startTime,endTime',
    'ignoreEndTime,startTime,endTime',
    $GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['addTime']
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['regform'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['regform'],
    'exclude'			=> true,
    'filter'			=> true,
    'inputType'			=> 'select',
    'options_callback'  => array('tl_calendar_events_ext', 'listRegForms'),
    'eval'				=> array('tl_class'=>'w50 m12', 'includeBlankOption'=>true, 'chosen'=>true),
    'sql'               => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['regperson'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['regperson'],
    'default'           => 0,
    'exclude'			=> true,
    'filter'			=> false,
    'inputType'         => 'multiColumnWizard',
    'load_callback'     => array(array('tl_calendar_events_ext', 'getmaxperson')),
    'eval'				=> array
    (
        'columnsCallback'   => array('tl_calendar_events_ext', 'setmaxperson'),
        'buttons'           => array('up' => false, 'down' => false, 'delete' => false, 'copy' => false)
    ),
    'sql'               => "text NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatFixedDates'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatFixedDates'],
    'exclude'			=> true,
    'inputType'         => 'multiColumnWizard',
    'eval'				=> array
    (
        'columnsCallback'   => array('tl_calendar_events_ext', 'listFixedDates'),
        'buttons'           => array('up' => false, 'down' => false)
    ),
    'sql'               => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['ignoreEndTime'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['ignoreEndTime'],
    'default'           => 0,
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('tl_class'=>'long clr'),
    'sql'               => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['useExceptions'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['useExceptions'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class'=>'long clr'),
    'sql'               => "char(1) NOT NULL default ''",
    'save_callback'     => array
    (
        array('tl_calendar_events_ext', 'checkExceptions')
    )
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['useRegistration'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['useRegistration'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class'=>'long clr'),
    'sql'               => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['showOnFreeDay'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['showOnFreeDay'],
    'exclude'			=> true,
    'filter'			=> false,
    'inputType'			=> 'checkbox',
    'sql'               => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['weekday'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['weekday'],
    'exclude'			=> true,
    'filter'			=> true,
    'inputType'			=> 'select',
    'options'			=> array(0, 1, 2, 3, 4, 5, 6),
    'reference'			=> &$GLOBALS['TL_LANG']['DAYS'],
    'sql'               => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurring'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['recurring'],
    'exclude'           => true,
    'filter'            => true,
    'inputType'         => 'checkbox',
    'eval'              => array('submitOnChange'=>true, 'tl_class'=>'w50'),
    'sql'               => "char(1) NOT NULL default ''",
    'save_callback'     => array
    (
        array('tl_calendar_events_ext', 'checkRecurring')
    )
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatEach'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach'],
    'default'           => 1,
    'exclude'           => true,
    'inputType'         => 'timePeriod',
    'options'           => array('days', 'weeks', 'months', 'years'),
    'reference'         => &$GLOBALS['TL_LANG']['tl_calendar_events'],
    'eval'              => array('mandatory'=>true, 'rgxp'=>'natural', 'minval'=>1, 'tl_class'=>'w50'),
    'sql'               => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['hideOnWeekend'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['hideOnWeekend'],
    'exclude'			=> true,
    'filter'			=> false,
    'inputType'			=> 'checkbox',
    'eval'				=> array('tl_class'=>'w50'),
    'sql'               => "char(1) NOT NULL default ''"
);

// change the default palettes
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['recurringExt'] = 'repeatEachExt,recurrences,repeatEnd';
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['useExceptions'] = 'repeatExceptionsInt,repeatExceptionsPer,repeatExceptions';
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['useRegistration'] = 'regform,regperson';

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurringExt'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['recurringExt'],
    'exclude'			=> true,
    'inputType'			=> 'checkbox',
    'eval'				=> array('submitOnChange'=>true, 'tl_class'=>'long clr'),
    'sql'               => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_name'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_name'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_str'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_str'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_plz'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_plz'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('rgxp'=>'digit', 'maxlength'=>10, 'tl_class'=>'w50'),
    'sql'               => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_ort'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_ort'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_link'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_link'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'long'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_contact'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_contact'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['location_mail'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['location_mail'],
    'exclude'           => true,
    'search'            => true,
    'inputType'         => 'text',
    'eval'              => array('rgxp'=>'email', 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
    'sql'               => "varchar(255) NOT NULL default ''"
);

// new repeat options for events
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatEachExt'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEachExt'],
    'exclude'			=> true,
    'inputType'			=> 'timePeriodExt',
    'options'			=> array
    (
        array('first', 'second', 'third', 'fourth', 'fifth', 'last'),
        array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')
    ),
    'reference'         => &$GLOBALS['TL_LANG']['tl_calendar_events'],
    'eval'				=> array('mandatory'=>true, 'tl_class'=>'w50'),
    'default'           => &$GLOBALS['TL_CONFIG']['tl_calendar_events']['weekdays'][date('w', time())],
    'sql'               => "text NULL"
);

// added submitOnChange to recurrences
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurrences'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences'],
    'exclude'           => true,
    'inputType'         => 'text',
    'eval'				=> array('mandatory'=>true, 'rgxp'=>'digit', 'submitOnChange'=>true, 'tl_class'=>'w50'),
    'sql'               => "smallint(5) unsigned NOT NULL default '0'"
);

// list of exceptions
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatExceptions'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatExceptions'],
    'exclude'			=> true,
    'inputType'         => 'multiColumnWizard',
    'eval'				=> array
        (
            'columnsCallback'   => array('tl_calendar_events_ext', 'listMultiExceptions'),
            'buttons'           => array('up' => false, 'down' => false)
        ),
    'sql'               => "text NULL"
);

// list of exceptions
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatExceptionsInt'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatExceptionsInt'],
    'exclude'			=> true,
    'inputType'         => 'multiColumnWizard',
    'eval'				=> array
        (
            'columnsCallback'   => array('tl_calendar_events_ext', 'listMultiExceptions'),
            'buttons'           => array('up' => false, 'down' => false)
        ),
    'sql'               => "text NULL"
);

// list of exceptions
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatExceptionsPer'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatExceptionsPer'],
    'exclude'			=> true,
    'inputType'         => 'multiColumnWizard',
    'eval'				=> array
        (
            'columnsCallback'   => array('tl_calendar_events_ext', 'listMultiExceptions'),
            'buttons'           => array('up' => false, 'down' => false)
        ),
    'sql'               => "text NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatDates'] = array
(
    'sql'               => "text NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['exceptionList'] = array
(
    'sql'               => "text NULL"
);

// display the end of the recurrences (read only)
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatEnd'] = array
(
    'label'				=> &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEnd'],
    'exclude'			=> true,
    'inputType'			=> 'text',
    'eval'				=> array('readonly'=>true, 'rgxp'=>'date', 'tl_class'=>'clr'),
    'sql'               => "int(10) unsigned NOT NULL default '0'"
);


/**
 * Class tl_calendar_events
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_calendar_events_ext extends \Backend
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
     * Just check that only one option is active for recurring events
     * @param $varValue
     * @param DataContainer $dc
     * @return mixed
     * @throws Exception
     */
    public function checkRecurring($varValue, DataContainer $dc)
    {
        if ($varValue)
        {
            if ($dc->activeRecord->recurring && $dc->activeRecord->recurringExt)
            {
                throw new Exception($GLOBALS['TL_LANG']['tl_calendar_events']['checkRecurring']);
            }
        }

        return $varValue;
    }


    /**
     * Just check if any kind of recurring is in use
     * @param $varValue
     * @param DataContainer $dc
     * @return mixed
     * @throws Exception
     */
    public function checkExceptions($varValue, DataContainer $dc)
    {
        if ($varValue)
        {
            if (!$dc->activeRecord->recurring && !$dc->activeRecord->recurringExt)
            {
                throw new Exception($GLOBALS['TL_LANG']['tl_calendar_events']['checkExceptions']);
            }
        }

        return $varValue;
    }


    /**
     * Adjust start end end time of the event based on date, span, startTime and endTime
     * @param object
     */
    public function adjustTime(DataContainer $dc)
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord)
        {
            return;
        }

        $maxCount = ($GLOBALS['TL_CONFIG']['tl_calendar_events']['maxRepeatExceptions']) ? $GLOBALS['TL_CONFIG']['tl_calendar_events']['maxRepeatExceptions'] : 365;
        $maxELCount = 250;

        $arrSet['weekday'] = (int)date("w", $dc->activeRecord->startDate);
        $arrSet['startTime'] = $dc->activeRecord->startDate;
        $arrSet['endTime'] = $dc->activeRecord->startDate;

        // Set end date
        if (strlen($dc->activeRecord->endDate))
        {
            if ($dc->activeRecord->endDate > $dc->activeRecord->startDate)
            {
                $arrSet['endDate'] = $dc->activeRecord->endDate;
                $arrSet['endTime'] = $dc->activeRecord->endDate;
            }
            else
            {
                $arrSet['endDate'] = $dc->activeRecord->startDate;
                $arrSet['endTime'] = $dc->activeRecord->startDate;
            }
        }

        // Add time
        if ($dc->activeRecord->addTime)
        {
            $arrSet['startTime'] = strtotime(date('Y-m-d', $arrSet['startTime']) . ' ' . date('H:i:s', $dc->activeRecord->startTime));
            $arrSet['endTime'] = strtotime(date('Y-m-d', $arrSet['endTime']) . ' ' . date('H:i:s', $dc->activeRecord->endTime));
        }

        // Set endtime to starttime always...
        if ($dc->activeRecord->ignoreEndTime)
        {
            $arrSet['endTime'] = $arrSet['startTime'];
        }

        // Adjust end time of "all day" events
        elseif ((strlen($dc->activeRecord->endDate) && $arrSet['endDate'] == $arrSet['endTime']) || $arrSet['startTime'] == $arrSet['endTime'])
        {
            $arrSet['endTime'] = (strtotime('+ 1 day', $arrSet['endTime']) - 1);
        }

        $arrSet['repeatEnd'] = 0;

        // Array of possible repeatEnd dates...
        $maxRepeatEnd = array();
        $maxRepeatEnd[] = $arrSet['repeatEnd'];

        // Set the repeatEnd date
        $arrayFixedDates = deserialize($dc->activeRecord->repeatFixedDates) ? deserialize($dc->activeRecord->repeatFixedDates) : null;
        if (!is_null($arrayFixedDates))
        {
            foreach ($arrayFixedDates as $fixedDate)
            {
                $nextValueDate = (strlen($fixedDate['new_repeat'])) ? strtotime($fixedDate['new_repeat']) : $dc->activeRecord->startTime;
                if (strlen($fixedDate['new_end']))
                {
                    $nextEndTime = strtotime(date("Y-m-d", $nextValueDate).' '.date("H:i:s", strtotime($fixedDate['new_end'])));
                }
                else
                {
                    $nextEndTime = strtotime(date("Y-m-d", $nextValueDate).' '.date("H:i:s", $dc->activeRecord->endTime));
                }
                $maxRepeatEnd[] = $nextEndTime;
            }
        }
        else
        {
            $arrSet['repeatFixedDates'] = null;
        }

        // changed default recurring
        if ($dc->activeRecord->recurring)
        {
            $arrRange = deserialize($dc->activeRecord->repeatEach);

            $arg = $arrRange['value'] * $dc->activeRecord->recurrences;
            $unit = $arrRange['unit'];

            $strtotime = '+ ' . $arg . ' ' . $unit;
            $arrSet['repeatEnd'] = strtotime($strtotime, $arrSet['endTime']);

            //store the list of dates
            $next = $arrSet['startTime'];
            $count = $dc->activeRecord->recurrences;

            //array of the exception dates
            $arrDates = array();

            if ($count == 0)
            {
                $arrSet['repeatEnd'] = 2145913200;
            }

            // last date of the recurrences
            $end = $arrSet['repeatEnd'];

            while ($next <= $end)
            {
                $timetoadd = '+ ' . $arrRange['value'] . ' ' . $unit;

                // Check if we are at the end
                if (!strtotime($timetoadd, $next))
                {
                    break;
                }

                $strtotime = strtotime($timetoadd, $next);
                $next = $strtotime;
                $weekday = date('w', $next);

                //check if we are at the end
                if ($next >= $end)
                {
                    break;
                }

                $store = true;
                if ($dc->activeRecord->hideOnWeekend)
                {
                    if ($weekday == 0 || $weekday == 6)
                    {
                        $store = false;
                    }
                }
                if ($store === true)
                {
                    $arrDates[$next] = date('d.m.Y H:i', $next);
                }

                //check if have the configured max value
                if (count($arrDates) == $maxCount && $unit == "days")
                {
                    break;
                }
            }
            $maxRepeatEnd[] = $arrSet['repeatEnd'];
        }

        //list of months we need
        $arrMonth = array(1=>'january', 2=>'february', 3=>'march', 4=>'april', 5=>'may', 6=>'june',
            7=>'july', 8=>'august', 9=>'september', 10=>'october', 11=>'november', 12=>'december',
        );

        //extended version recurring
        if ($dc->activeRecord->recurringExt)
        {
            $arrRange = deserialize($dc->activeRecord->repeatEachExt);

            $arg = $arrRange['value'];
            $unit = $arrRange['unit'];

            //next month of the event
            $month = (int)date('n', $dc->activeRecord->startDate);
            //year of the event
            $year = (int)date('Y', $dc->activeRecord->startDate);
            //search date for the next event
            $next = (int)$arrSet['startTime'];
            //last month
            $count = (int)$dc->activeRecord->recurrences;

            //array of the exception dates
            $arrDates = array();

            if ($count > 0)
            {
                for ($i = 0; $i < $count; $i++)
                {
                    $month++;
                    if (($month % 13) == 0)
                    {
                        $month = 1;
                        $year += 1;
                    }

                    $timetoadd = $arg . ' ' . $unit . ' of ' . $arrMonth[$month] . ' ' . $year;
                    if (!strtotime($timetoadd, $next))
                    {
                        break;
                    }

                    $strtotime = strtotime($timetoadd, $next);
                    $next = $strtotime;
                    $arrDates[$next] = date('d.m.Y H:i', $next);
                }
                $arrSet['repeatEnd'] = $next;
            }
            else
            {
                // 2038.01.01
                $arrSet['repeatEnd'] = 2145913200;
                $end = $arrSet['repeatEnd'];

                while ($next <= $end)
                {
                    $timetoadd = $arg . ' ' . $unit . ' of ' . $arrMonth[$month] . ' ' . $year;

                    if (!strtotime($timetoadd, $next))
                    {
                        break;
                    }

                    $strtotime = strtotime($timetoadd, $next);
                    $next = $strtotime;

                    $arrDates[$next] = date('d.m.Y H:i', $next);

                    $month++;
                    if (($month % 13) == 0)
                    {
                        $month = 1;
                        $year += 1;
                    }
                }
            }
            $maxRepeatEnd[] = $arrSet['repeatEnd'];
        }
        unset($next);

        // the last repeatEnd Date
        if (count($maxRepeatEnd) > 1)
        {
            $arrSet['repeatEnd'] = max($maxRepeatEnd);
        }
        $currentEndDate = $arrSet['repeatEnd'];

        if ($dc->activeRecord->useExceptions)
        {
            // this will be he list of the exception
            $exceptionRows = array();

            // ... then we check them by interval...
            if ($dc->activeRecord->repeatExceptionsInt)
            {
                // weekday
                $unit = $GLOBALS['TL_CONFIG']['tl_calendar_events']['weekdays'][$dc->activeRecord->weekday];

                // exception rules
                $rows = deserialize($dc->activeRecord->repeatExceptionsInt);

                // run thru all dates
                foreach ($rows as $row)
                {
                    if (!$row['exception'])
                    {
                        continue;
                    }

                    // now we have to find all dates matching the exception rules...
                    $arg = $row['exception'];

                    $searchNext = $arrSet['startTime'];
                    $searchEnd = $arrSet['repeatEnd'];
                    $month = (int)date('n', $searchNext);
                    $year = (int)date('Y', $searchNext);
                    while ($searchNext <= $searchEnd)
                    {
                        $strDateToFind = $arg.' '.$unit.' of '.$arrMonth[$month].' '.$year;
                        $searchNext = strtotime($strDateToFind);

                        if ($searchNext < $arrSet['startTime'])
                        {
                            $month++;
                            if (($month % 13) == 0)
                            {
                                $month = 1;
                                $year += 1;
                            }
                            continue;
                        }

                        $row['new_start'] = ($row['new_start']) ? $row['new_start'] : date('H:i', $dc->activeRecord->startTime); #'00:00';
                        $row['new_end'] = ($row['new_end']) ? $row['new_end'] : date('H:i', $dc->activeRecord->endTime); #'23:59';
                        // Set endtime to starttime always...
                        if ($dc->activeRecord->ignoreEndTime)
                        {
                            $row['new_end'] = '';
                        }

                        $row['exception'] = strtotime(date('Y-m-d', $searchNext));
                        $row['exception_date'] = date('Y-m-d', $searchNext);
                        if (count($exceptionRows) < $maxELCount)
                        {
                            $exceptionRows[$searchNext] = $row;
                        }

                        $month++;
                        if (($month % 13) == 0)
                        {
                            $month = 1;
                            $year += 1;
                        }
                    }
                }
            }

            // ... and last but not least by range
            if ($dc->activeRecord->repeatExceptionsPer)
            {
                // exception rules
                $rows = deserialize($dc->activeRecord->repeatExceptionsPer);

                // all recurrences...
                $repeatDates = deserialize($dc->activeRecord->repeatDates);

                // run thru all dates
                foreach ($rows as $row)
                {
                    if (!$row['exception'])
                    {
                        continue;
                    }

                    $row['new_start'] = ($row['new_start']) ? $row['new_start'] : date('H:i', $dc->activeRecord->startTime); #'00:00';
                    $row['new_end'] = ($row['new_end']) ? $row['new_end'] : date('H:i', $dc->activeRecord->endTime); #'23:59';
                    // Set endtime to starttime always...
                    if ($dc->activeRecord->ignoreEndTime)
                    {
                        $row['new_end'] = '';
                    }

                    // now we have to find all dates matching the exception rules...
                    $dateFrom = strtotime($row['exception'].' '.$row['new_start']);
                    $dateTo = strtotime($row['exceptionTo'].' '.$row['new_end']);
                    unset($row['exceptionTo']);

                    foreach ($repeatDates as $k => $repeatDate)
                    {
                        if ($k >= $dateFrom && $k <= $dateTo)
                        {
                            $k = strtotime(date('Y-m-d', $k));
                            $row['exception'] = $k;
                            $row['exception_date'] = date('Y-m-d', $k);
                            if (count($exceptionRows) < $maxELCount)
                            {
                                $exceptionRows[$k] = $row;
                            }
                        }
                    }
                }
            }

            // first we check the exceptions by date...
            if ($dc->activeRecord->repeatExceptions)
            {
                $rows = deserialize($dc->activeRecord->repeatExceptions);
                // set repeatEnd
                // my be we have an exception move that is later then the repeatEnd
                foreach ($rows as $row)
                {
                    if (!$row['exception'])
                    {
                        continue;
                    }

                    $row['exception'] = strtotime(date('Y-m-d', $row['exception']));
                    $row['exception_date'] = date('Y-m-d', $row['exception']);

                    $row['new_start'] = ($row['new_start']) ? $row['new_start'] : date('H:i', $dc->activeRecord->startTime); #'00:00';
                    $row['new_end'] = ($row['new_end']) ? $row['new_end'] : date('H:i', $dc->activeRecord->endTime); #'23:59';
                    // Set endtime to starttime always...
                    if ($dc->activeRecord->ignoreEndTime)
                    {
                        $row['new_end'] = '';
                    }

                    if ($row['action'] == 'move')
                    {
                        $newDate = strtotime($row['new_exception'], $row['exception']);
                        if ($newDate > $currentEndDate)
                        {
                            $arrSet['repeatEnd'] = $newDate;
                            $maxRepeatEnd[] = $arrSet['repeatEnd'];
                        }

                        // Find the date and replace it
                        if (array_key_exists($row['exception'], $arrDates))
                        {
                            $arrDates[$row['exception']] = date('d.m.Y H:i', $newDate);
                        }
                    }
                    if (count($exceptionRows) < $maxELCount)
                    {
                        $exceptionRows[$row['exception']] = $row;
                    }
                }
            }

            if (count($exceptionRows) > 1)
            {
                ksort($exceptionRows);
            }
            $arrSet['exceptionList'] = (count($exceptionRows) > 0) ? serialize($exceptionRows) : null;
        }

        if (count($maxRepeatEnd) > 1)
        {
            $arrSet['repeatEnd'] = max($maxRepeatEnd);
        }
        // Set the array of dates
        $arrSet['repeatDates'] = $arrDates;

        // Execute the update sql
        $this->Database->prepare("UPDATE tl_calendar_events %s WHERE id=?")->set($arrSet)->execute($dc->id);
        unset($maxRepeatEnd);
    }


    /**
     * @param $dc
     * @return string
     */
    public function getmaxperson($var, $dc)
    {
        $values = deserialize($var);
        if (!is_array($values))
        {
            $values = [];
            $values[0]['mini'] = 0;
            $values[0]['maxi'] = 0;
            $values[0]['curr'] = 0;
            $values[0]['free'] = 0;
            return $values;
        }

        $eid = (int)$dc->activeRecord->id;
        $fid = (int)$dc->activeRecord->regform;

        $arrsql[] = 'select count(td.id) as count';
        $arrsql[] = 'from tl_form tf, tl_formdata td, tl_formdata_details dd';
        $arrsql[] = 'where tf.id = '.$fid.' and td.form = tf.title';
        $arrsql[] = 'and dd.pid = td.id and dd.ff_name = "eventid"';
        $arrsql[] = 'and dd.value = '.$eid;
        $sql = implode(' ', $arrsql);

        $regform = $this->Database->prepare($sql)->execute();

        $values[0]['mini'] = ($values[0]['mini']) ? $values[0]['mini'] : 0;
        $values[0]['curr'] = (int)$regform->count;
        $values[0]['free'] = $values[0]['maxi'] - $values[0]['curr'];

        return serialize($values);
    }


    /**
     * @param $dc
     * @return array
     */
    public function setmaxperson($dc)
    {
        $columnFields = null;

        $columnFields = array
        (
            'mini' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['mini'],
                'default'   => '0',
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:60px')
            ),
            'maxi' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['maxi'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:60px')
            ),
            'curr' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['curr'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:60px', 'disabled'=>'true')
            ),
            'free' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['free'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:60px', 'disabled'=>'true')
            )
        );

        return $columnFields;
    }


    /**
     * listMultiExceptions()
     *
     * Read the list of exception dates from the db
     * to fill the select list
     */
    public function listMultiExceptions($var1)
    {
        $columnFields = null;

        // arrays for the select fields
        $arrSource1 = array();
        $arrSource2 = array();
        $arrSource3 = array();
        $arrSource4 = array();

        if (\Input::get('id'))
        {
            if ($var1->activeRecord->repeatDates)
            {
                $arrDates = deserialize($var1->activeRecord->repeatDates);
                if (is_array($arrDates))
                {
                    if ($var1->id == "repeatExceptions")
                    {
                        // fill array for option date
                        foreach ($arrDates as $k => $arrDate)
                        {
                            $date = \Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $k);
                            $arrSource1[$k] = $date;
                        }
                    }

                    // fill array for option action
                    $arrSource2['move'] = $GLOBALS['TL_LANG']['tl_calendar_events']['move'];
                    $arrSource2['hide'] = $GLOBALS['TL_LANG']['tl_calendar_events']['hide'];
                }
            }

            // fill array for option new date
            $moveDays = ((int)$GLOBALS['TL_CONFIG']['tl_calendar_events']['moveDays']) ? (int)$GLOBALS['TL_CONFIG']['tl_calendar_events']['moveDays'] : 7;
            $start = $moveDays * -1;
            $end = $moveDays * 2;

            for ($i = 0; $i <= $end; $i++)
            {
                $arrSource3[$start. ' days'] = $start . ' ' . $GLOBALS['TL_LANG']['tl_calendar_events']['days'];
                $start++;
            }

            list($start, $end, $interval) = explode("|", $GLOBALS['TL_CONFIG']['tl_calendar_events']['moveTimes']);

            // fill array for option new time
            $start = strtotime($start);
            $end = strtotime($end);
            while ($start <= $end)
            {
                $newTime = \Date::parse($GLOBALS['TL_CONFIG']['timeFormat'], $start);
                $arrSource4[$newTime] = $newTime;
                $start = strtotime('+ ' . $interval . ' minutes', $start);
            }
        }

        $columnFields = array
        (
            'new_start' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['new_start'],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => $arrSource4,
                'eval'      => array('style'=>'width:60px', 'includeBlankOption'=>true)
            ),
            'new_end' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['new_end'],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => $arrSource4,
                'eval'      => array('style'=>'width:60px', 'includeBlankOption'=>true)
            ),
            'action' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['action'],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => $arrSource2,
                'eval'      => array('style'=>'width:80px', 'includeBlankOption'=>true)
            ),
            'new_exception' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['new_exception'],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => $arrSource3,
                'eval'      => array('style'=>'width:80px', 'includeBlankOption'=>true)
            ),
            'cssclass' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['cssclass'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:50px')
            ),
            'reason' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['reason'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style'=>'width:150px')
            )
        );

        // normal exceptions by date
        if ($var1->id == "repeatExceptions")
        {
            $firstField = array(
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['exception'],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => $arrSource1,
                'eval'      => array('style'=>'width:120px', 'includeBlankOption'=>true)
            );
        }

       // exceptions by interval
        elseif ($var1->id == "repeatExceptionsInt")
        {
            $firstField = array(
                'label'     => $GLOBALS['TL_LANG']['tl_calendar_events']['exceptionInt'].$GLOBALS['TL_LANG']['DAYS'][$var1->activeRecord->weekday],
                'exclude'   => true,
                'inputType' => 'select',
                'options'   => array('first', 'second', 'third', 'fourth', 'fifth', 'last'),
                'reference' => &$GLOBALS['TL_LANG']['tl_calendar_events'],
                'eval'      => array('style'=>'width:120px', 'includeBlankOption'=>true)
            );
        }

        // exceptions by time period
        elseif ($var1->id == "repeatExceptionsPer")
        {
            $firstField = array(
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['exceptionFr'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('rgxp'=>'date', 'doNotCopy'=>true, 'style'=>'width:60px', 'datepicker'=>true, 'tl_class'=>'wizard')
            );
            $secondField = array(
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['exceptionTo'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('rgxp'=>'date', 'doNotCopy'=>true, 'style'=>'width:60px', 'datepicker'=>true, 'tl_class'=>'wizard')
            );
            $columnFields['reason'] = array
            (
                    'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['reason'],
                    'exclude'   => true,
                    'inputType' => 'text',
                    'eval'      => array('style'=>'width:80px')
            );

            // add the field to the columnFields array
            array_insert($columnFields, 0, array("exceptionTo"=> $secondField));
        }

        // add the field to the columnFields array
        array_insert($columnFields, 0, array("exception"=> $firstField));

        return $columnFields;
    }

    /**
     * listFixedDates()
     */
    public function listFixedDates($var1)
    {
        $columnFields = null;

        $columnFields = array
        (
            'new_repeat' => array(
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['exception'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('rgxp'=>'date', 'datepicker'=>true, 'doNotCopy'=>true, 'style'=>'width:100px', 'tl_class'=>'wizard')
            ),
            'new_start' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['new_start'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('rgxp'=>'time', 'datepicker'=>true, 'doNotCopy'=>true, 'style'=>'width:40px')
            ),
            'new_end' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['new_end'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('rgxp'=>'time', 'datepicker'=>true, 'doNotCopy'=>true, 'style'=>'width:40px')
            ),
            'reason' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_calendar_events']['reason'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('doNotCopy'=>true, 'style'=>'width:350px')
            )
        );

        return $columnFields;
    }

    /**
     * @param $a
     * @return array
     */
    public function listRegForms($a)
    {
        if ($this->User->isAdmin)
        {
            $objForms = \FormModel::findAll();
        }
        else
        {
            $objForms = \FormModel::findMultipleByIds($this->User->forms);
        }

        $return = array();

        if ($objForms !== null)
        {
            while ($objForms->next())
            {
                $return[$objForms->id] = $objForms->title;
            }
        }

        return $return;
    }

}