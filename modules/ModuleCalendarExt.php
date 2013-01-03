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
 * Namespace
 */
namespace Contao;


/**
 * Class ModuleCalendarExt 
 *
 * @copyright  Kester Mielke 2010-2013 
 * @author     Kester Mielke 
 * @package    Devtools
 */
class ModuleCalendarExt extends \EventsExt
{

    /**
     * Current date object
     * @var integer
     */
    protected $Date;
    protected $calConf = array();

    /**
     * Redirect URL
     * @var string
     */
    protected $strLink;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_calendar';


    /**
     * Do not show the module if no calendar has been selected
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### CALENDAR ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->cal_calendar = $this->sortOutProtected(deserialize($this->cal_calendar_ext, true));
        $this->cal_holiday = $this->sortOutProtected(deserialize($this->cal_holiday, true));

        // Return if there are no calendars
        if (!is_array($this->cal_calendar) || empty($this->cal_calendar))
        {
            return '';
        }

        //Get the bg color of the calendar
        foreach ($this->cal_calendar as $cal)
        {
            $objBG = $this->Database->prepare("select title, bg_color, fg_color from tl_calendar where id = ?")
                ->limit(1)->executeUncached($cal);

            $this->calConf[$cal]['calendar'] = $objBG->title;
            if ($objBG->bg_color)
            {
                $cssBgValues = deserialize($objBG->bg_color);
                $this->calConf[$cal]['background'] = 'background-color:#'.$cssBgValues[0].';';
                if ($cssBgValues[1] > 0)
                {
                    $this->calConf[$cal]['background'] .= ' opacity:'.((int)$cssBgValues[1]/100).';';
                }
            }

            if ($objBG->fg_color)
            {
                $cssFgValues = deserialize($objBG->fg_color);
                $this->calConf[$cal]['foreground'] = 'color:#'.$cssFgValues[0].';';
                if ($cssFgValues[1] > 0)
                {
                    $this->calConf[$cal]['foreground'] .= ' opacity:'.((int)$cssFgValues[1]/100).';';
                }
            }
        }

        //Get the bg color of the holiday calendar
        foreach ($this->cal_holiday as $cal)
        {
            $objBG = $this->Database->prepare("select title, bg_color, fg_color from tl_calendar where id = ?")
                ->limit(1)->executeUncached($cal);

            $this->calConf[$cal]['calendar'] = $objBG->title;
            if ($objBG->bg_color)
            {
                $cssBgValues = deserialize($objBG->bg_color);
                $this->calConf[$cal]['background'] = 'background-color:#'.$cssBgValues[0].';';
                if ($cssBgValues[1] > 0)
                {
                    $this->calConf[$cal]['background'] .= ' opacity:'.((int)$cssBgValues[1]/100).';';
                }
            }

            if ($objBG->fg_color)
            {
                $cssFgValues = deserialize($objBG->fg_color);
                $this->calConf[$cal]['foreground'] = 'color:#'.$cssFgValues[0].';';
                if ($cssFgValues[1] > 0)
                {
                    $this->calConf[$cal]['foreground'] .= ' opacity:'.((int)$cssFgValues[1]/100).';';
                }
            }
        }

        $this->strUrl = preg_replace('/\?.*$/', '', \Environment::get('request'));
        $this->strLink = $this->strUrl;

        if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) !== null)
        {
            $this->strLink = $this->generateFrontendUrl($objTarget->row());
        }

        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
        // Respond to month
        if (\Input::get('month'))
        {
            $this->Date = new \Date(\Input::get('month'), 'Ym');
        }
        // Respond to day
        elseif (\Input::get('day'))
        {
            $this->Date = new \Date(\Input::get('day'), 'Ymd');
        }
        // Fallback to today
        else
        {
            $this->Date = new \Date();
        }

        // Find the boundaries
        $objMinMax = \CalendarEventsModel::findBoundaries($this->cal_calendar);
        $intLeftBoundary = date('Ym', $objMinMax->dateFrom);
        $intRightBoundary = date('Ym', max($objMinMax->dateTo, $objMinMax->repeatUntil));

        // Instantiate the template
        $objTemplate = new \FrontendTemplate(($this->calext_ctemplate ? $this->calext_ctemplate : 'calext_default'));

        // Store year and month
        $intYear = date('Y', $this->Date->tstamp);
        $intMonth = date('m', $this->Date->tstamp);
        $objTemplate->intYear = $intYear;
        $objTemplate->intMonth = $intMonth;

        // Previous month
        $prevMonth = ($intMonth == 1) ? 12 : ($intMonth - 1);
        $prevYear = ($intMonth == 1) ? ($intYear - 1) : $intYear;
        $lblPrevious = $GLOBALS['TL_LANG']['MONTHS'][($prevMonth - 1)] . ' ' . $prevYear;
        $intPrevYm = intval($prevYear . str_pad($prevMonth, 2, 0, STR_PAD_LEFT));

        // Only generate a link if there are events (see #4160)
        if ($intPrevYm >= $intLeftBoundary)
        {
            $objTemplate->prevHref = $this->strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . \Input::get('id') . '&amp;' : '?') . 'month=' . $intPrevYm;
            $objTemplate->prevTitle = specialchars($lblPrevious);
            $objTemplate->prevLink = $GLOBALS['TL_LANG']['MSC']['cal_previous'] . ' ' . $lblPrevious;
            $objTemplate->prevLabel = $GLOBALS['TL_LANG']['MSC']['cal_previous'];
        }

        // Current month
        $objTemplate->current = $GLOBALS['TL_LANG']['MONTHS'][(date('m', $this->Date->tstamp) - 1)] .  ' ' . date('Y', $this->Date->tstamp);

        // Next month
        $nextMonth = ($intMonth == 12) ? 1 : ($intMonth + 1);
        $nextYear = ($intMonth == 12) ? ($intYear + 1) : $intYear;
        $lblNext = $GLOBALS['TL_LANG']['MONTHS'][($nextMonth - 1)] . ' ' . $nextYear;
        $intNextYm = $nextYear . str_pad($nextMonth, 2, 0, STR_PAD_LEFT);

        // Only generate a link if there are events (see #4160)
        if ($intNextYm <= $intRightBoundary)
        {
            $objTemplate->nextHref = $this->strUrl . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '?id=' . \Input::get('id') . '&amp;' : '?') . 'month=' . $intNextYm;
            $objTemplate->nextTitle = specialchars($lblNext);
            $objTemplate->nextLink = $lblNext . ' ' . $GLOBALS['TL_LANG']['MSC']['cal_next'];
            $objTemplate->nextLabel = $GLOBALS['TL_LANG']['MSC']['cal_next'];
        }

        // Set the week start day
        if (!$this->cal_startDay)
        {
            $this->cal_startDay = 0;
        }

        $objTemplate->days = $this->compileDays();
        $objTemplate->weeks = $this->compileWeeks();
        $objTemplate->substr = $GLOBALS['TL_LANG']['MSC']['dayShortLength'];

        $this->Template->calendar = $objTemplate->parse();
    }


    /**
     * Return the week days and labels as array
     * @return array
     */
    protected function compileDays()
    {
        $arrDays = array();

        for ($i=0; $i<7; $i++)
        {
            $intCurrentDay = ($i + $this->cal_startDay) % 7;
            $arrDays[$intCurrentDay] = $GLOBALS['TL_LANG']['DAYS'][$intCurrentDay];
        }

        return $arrDays;
    }


    /**
     * Return all weeks of the current month as array
     * @return array
     */
    protected function compileWeeks()
    {
        $intDaysInMonth = date('t', $this->Date->monthBegin);
        $intFirstDayOffset = date('w', $this->Date->monthBegin) - $this->cal_startDay;

        if ($intFirstDayOffset < 0)
        {
            $intFirstDayOffset += 7;
        }

        $intColumnCount = -1;
        $intNumberOfRows = ceil(($intDaysInMonth + $intFirstDayOffset) / 7);
        $arrAllEvents = $this->getAllEventsExt($this->cal_holiday, $this->cal_calendar, $this->Date->monthBegin, $this->Date->monthEnd);
        $arrDays = array();

        // Compile days
        for ($i=1; $i<=($intNumberOfRows * 7); $i++)
        {
            $intWeek = floor(++$intColumnCount / 7);
            $intDay = $i - $intFirstDayOffset;
            $intCurrentDay = ($i + $this->cal_startDay) % 7;

            $strWeekClass = 'week_' . $intWeek;
            $strWeekClass .= ($intWeek == 0) ? ' first' : '';
            $strWeekClass .= ($intWeek == ($intNumberOfRows - 1)) ? ' last' : '';

            $strClass = ($intCurrentDay < 2) ? ' weekend' : '';
            $strClass .= ($i == 1 || $i == 8 || $i == 15 || $i == 22 || $i == 29 || $i == 36) ? ' col_first' : '';
            $strClass .= ($i == 7 || $i == 14 || $i == 21 || $i == 28 || $i == 35 || $i == 42) ? ' col_last' : '';

            // Empty cell
            if ($intDay < 1 || $intDay > $intDaysInMonth)
            {
                $arrDays[$strWeekClass][$i]['label'] = '&nbsp;';
                $arrDays[$strWeekClass][$i]['class'] = 'days empty' . $strClass ;
                $arrDays[$strWeekClass][$i]['events'] = array();

                continue;
            }

            $intKey = date('Ym', $this->Date->tstamp) . ((strlen($intDay) < 2) ? '0' . $intDay : $intDay);
            $strClass .= ($intKey == date('Ymd')) ? ' today' : '';

            // Mark the selected day (see #1784)
            if ($intKey == \Input::get('day'))
            {
                $strClass .= ' selected';
            }

            // Inactive days
            if (empty($intKey) || !isset($arrAllEvents[$intKey]))
            {
                $arrDays[$strWeekClass][$i]['label'] = $intDay;
                $arrDays[$strWeekClass][$i]['class'] = 'days' . $strClass;
                $arrDays[$strWeekClass][$i]['events'] = array();

                continue;
            }

            $arrEvents = array();

            // Get all events of a day
            foreach ($arrAllEvents[$intKey] as $v)
            {
                foreach ($v as $vv)
                {
                    $vv['pname'] = $this->calConf[$vv['pid']]['calendar'];

                    if ($this->calConf[$vv['pid']]['background'])
                    {
                        $vv['bgstyle'] = $this->calConf[$vv['pid']]['background'];
                    }
                    if ($this->calConf[$vv['pid']]['foreground'])
                    {
                        $vv['fgstyle'] = $this->calConf[$vv['pid']]['foreground'];
                    }
                    $arrEvents[] = $vv;
                }
            }

            $arrDays[$strWeekClass][$i]['label'] = $intDay;
            $arrDays[$strWeekClass][$i]['class'] = 'days active' . $strClass;
            $arrDays[$strWeekClass][$i]['href'] = $this->strLink . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'day=' . $intKey;
            $arrDays[$strWeekClass][$i]['title'] = sprintf(specialchars($GLOBALS['TL_LANG']['MSC']['cal_events']), count($arrEvents));
            $arrDays[$strWeekClass][$i]['events'] = $arrEvents;
        }

        return $arrDays;
    }
}
