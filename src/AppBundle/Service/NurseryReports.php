<?php

namespace AppBundle\Service;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\NurseryCalendar;
use AppBundle\Model\Report;

/**
 * Class NurseryReports.
 */
class NurseryReports
{
    /**
     * Edit or build a report.
     *
     * @param Calendar $calendar
     * @param Report   $report
     *
     * @return Report $report
     */
    public function build(Calendar $calendar, Report $report = null)
    {
        if (null === $report) {
            $report = new Report();
        }

        /** @var NurseryCalendar $nurseryCalendar */
        foreach ($calendar->getNurseryCalendars() as $nurseryCalendar) {
            $month = (int) $nurseryCalendar->getDate()->format('m');
            $year = (int) $nurseryCalendar->getDate()->format('Y');

            $nurseryReportYear = $report->getNurseryReportYear($year);
            $nurseryReportMonth = $nurseryReportYear->getMonth($month);
            $nurseryReportMonth->addPeriod($nurseryCalendar->getDuration(), $nurseryCalendar->isMeal());
        }

        $report->update();

        return $report;
    }

    /**
     * @param Report $report
     *
     * @return array
     */
    public function getDisplayingVersion(Report $report)
    {
        $years = [];
        foreach ($report->getNurseryReportYears() as $reportYear) {
            $months = [];
            foreach ($reportYear->getMonths() as $month) {
                $months[] = [
                    'title' => $month->getMonth(),
                    'numberMeal' => $month->getNumberMeal(),
                    'duration' => $month->getDuration(),
                ];
            }

            $years[] = [
                'title' => $reportYear->getYear(),
                'numberMeal' => $reportYear->getNumberMeal(),
                'duration' => $reportYear->getDuration(),
                'months' => $months,
            ];
        }

        return $years;
    }
}
