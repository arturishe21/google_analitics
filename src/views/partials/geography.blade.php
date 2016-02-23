 <script>
   $(".breadcrumb").html("<li><a href='/admin'>{{__cms('Главная')}}</a></li> <li>{{__cms($title)}}</li>");
   $("title").text("{{__cms($title)}} - {{{ __cms(Config::get('builder::admin.caption')) }}}");
 </script>

 <div class="analitics_stat">
     <div class="row">
         <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
             <h1 class="page-title txt-color-blueDark">
                 <i class="fa fa-google fa-fw "></i>
                 Google Analitics
                <span> > {{__cms($title)}} </span>
             </h1>
        </div>
     </div>
     <div class="clear"></div>

    <form name="statistic" class="form-horizontal" style="margin-bottom: 5px">
          <fieldset>
            <div class="input-prepend input-group">
              <span class="input-group-addon">
              <i class="fa fa-calendar"></i>
              </span>
              <input type="text" name="range" class="form-control" id="range" style="width: 200px;" />
              <input type="hidden" name="type" value="visitors_overview">
            </div>
          </fieldset>
    </form>
    <div id="placeholder">
        <figure id="chart" ></figure>
    </div>
    <div class="param_statistic">Параметр:
        <a class="active" type="ga:country">Страна</a>
        <a type="ga:region">Регион</a>
        <a type="ga:city">Город</a>

        </div>
    <div class="table_browsers">

    </div>
</div>
<link href="/packages/vis/analitics/css/style.css" rel="stylesheet">

<script src="/packages/vis/analitics/js/script_browsers.js"></script>