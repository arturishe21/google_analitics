<?php namespace Vis\Analitics;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;
use Thujohn\Analytics\AnalyticsFacade as Analytics;
use Spatie\AnalyticsReports\AnalyticsReportsFacade as AnalyticsReports;

class AnaliticsController extends Controller
{
    /**
     * get visitors overview
     */
    public function getVisitors()
    {
        $title =  "Обзор аудитории";
        $view = "analitics::pages.visitors_overview";
        if (Request::ajax()) {
            $view = "analitics::partials.visitors_overview_center";
        }

        return View::make($view, compact("title"));
    }  // end getVisitors

    public function getBrowsers()
    {
        $title =  "Браузеры и ОС";
        $view = "analitics::pages.browsers";
        if (Request::ajax()) {
            $view = "analitics::partials.browsers";
        }

        return View::make($view, compact("title"));
    }

    public function getGeography()
    {
        $title =  "География";
        $view = "analitics::pages.geography";
        if (Request::ajax()) {
            $view = "analitics::partials.geography";
        }

        return View::make($view, compact("title"));
    }


    public function getStatistic()
    {
        $start = new \DateTime(Input::get("start"));
        $end = new \DateTime(Input::get("end"));

        $results = AnalyticsReports::performQuery($start, $end,
            'ga:visits,ga:pageviews,ga:newUsers,ga:bounceRate, ga:percentNewSessions, ga:avgTimeOnPage, ga:avgSessionDuration',
            ['dimensions' => 'ga:date']
        );

        foreach ($results->rows as $k => $result) {
            $date = \DateTime::createFromFormat('Ymd', $result['0']);
            $data['grafic'][$k]['label'] = $date->format('Y-m-d');
            $data['grafic'][$k]['value'] = $result['1'];
        }
        $data['totalsForAllResults'] = $results->totalsForAllResults;

        return json_encode($data);
    }

    public function getStatisticBrowsers()
    {
        $start = new \DateTime(Input::get("start"));
        $end = new \DateTime(Input::get("end"));

        $dimensions = Input::get("type", "ga:browser");

        $resultsBrowsers = AnalyticsReports::performQuery($start, $end,
            'ga:sessions, ga:percentNewSessions, ga:newUsers, ga:bounceRate, ga:pageviewsPerSession, ga:avgSessionDuration',
            ['dimensions' => $dimensions]
        );
        $dimensionsName = Input::get("nameType");

        $htmlTable = View::make('analitics::partials.browsers_table', compact("resultsBrowsers", "dimensionsName"));

        return $htmlTable;
    }



}