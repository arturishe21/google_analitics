<?php namespace Vis\Analitics;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
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

    public function getSettings()
    {

        $this->doSaveSsttings();

        $title =  "Настройки";
        $view = "analitics::pages.settings";
        if (Request::ajax()) {
            $view = "analitics::partials.settings";
        }

        $siteId = Config::get("analyticsReports.siteId");
        $clientId = Config::get("analytics::client_id");
        $serviceEmail = Config::get("analytics::service_email");
        $certificatePath = Config::get("analytics::certificate_path");

        return View::make($view, compact("title", "siteId", "clientId", "serviceEmail", "certificatePath"));
    }

    private function doSaveSsttings()
    {
        $data = Input::all();

        if (count($data)) {
            $fileAnalyticsReports = app_path()."/config/analyticsReports.php";
            $fileAnalyticsConfig = app_path()."/config/packages/thujohn/analytics/config.php";
            $analyticsReports = array(
                "siteId" => $data['site_id'],
                "cacheLifetime" => "0"
            );

            $analyticsConfig = array(
                "use_objects" => true,
                "client_id" => $data['client_id'],
                "service_email" => $data['service_email'],
            );

            $certificate = Input::file('certificate');
            if ($certificate) {
                $destinationPath = app_path()."/config/packages/thujohn/analytics";

                $certificate -> move($destinationPath,  $certificate->getClientOriginalName());
                $analyticsConfig["certificate_path"] = '__dir__/'.$certificate->getClientOriginalName();
            } else {
                $analyticsConfig["certificate_path"] = '__dir__/'.basename(Config::get("analytics::certificate_path"));
            }

            $fileConfigAnalitics = "<?php \n\n return ".var_export($analyticsConfig, true)."; ";
            $fileConfigAnalitics = str_replace("'__dir__", "__DIR__ . '", $fileConfigAnalitics);

            file_put_contents($fileAnalyticsReports, "<?php \n\n return ".var_export($analyticsReports, true)."; ");

            file_put_contents($fileAnalyticsConfig, $fileConfigAnalitics);

            Session::flash('text_success', 'Настройки сохранены');
        }
     }

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