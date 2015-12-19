<?php

namespace AppBundle\Service;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\JobCalendar;
use AppBundle\Model\Report;

/**
 * Class JobReports.
 */
class JobReports
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

        /** @var JobCalendar $jobCalendar */
        foreach ($calendar->getJobCalendars() as $jobCalendar) {
            $month = (int) $jobCalendar->getDate()->format('m');
            $year = (int) $jobCalendar->getDate()->format('Y');
            $job = $jobCalendar->getJob();

            $jobReportYear = $report->getJobReportYear($year);
            $jobReportMonth = $jobReportYear->getMonth($month);
            $jobReportRow = $jobReportMonth->getRow($job->getCode(), $job->getTitle(), $job->getDuration());
            $jobReportRow->add();
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
        // Initialize array of jobs and sort
        $jobs = [];
        foreach ($report->getJobReportYears() as $reportYear) {
            foreach ($reportYear->getMonths() as $month) {
                foreach ($month->getRows() as $row) {
                    if (!in_array($row->getCodeJob(), $jobs)) {
                        $jobs[] = $row->getCodeJob();
                    }
                }
            }
        }
        sort($jobs);
        $jobs = array_flip($jobs);

        $years = [];
        foreach ($report->getJobReportYears() as $reportYear) {
            $months = [];
            foreach ($reportYear->getMonths() as $month) {
                $rows = array_fill(0, count($jobs), ['number' => null, 'duration' => null]);
                foreach ($month->getRows() as $row) {
                    $index = $jobs[$row->getCodeJob()];
                    $rows[$index] = [
                        'number' => $row->getNumber(),
                        'duration' => $row->getDuration(),
                    ];
                }

                $months[] = [
                    'title' => $month->getMonth(),
                    'number' => $month->getNumber(),
                    'duration' => $month->getDuration(),
                    'rows' => $rows,
                ];
            }

            $years[] = [
                'title' => $reportYear->getYear(),
                'number' => $reportYear->getNumber(),
                'duration' => $reportYear->getDuration(),
                'months' => $months,
            ];
        }

        return ['jobs' => $jobs, 'detail' => $years];
    }
}
