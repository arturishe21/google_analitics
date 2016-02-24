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
      <form class="smart-form" method="post" action="" enctype="multipart/form-data">

            <p style="text-align: center; color: green; padding: 10px 0">{{Session::get('text_success');}}</p>

            <div class="row">
                <section>
                 <fieldset>
                    <section>
                          <label class="label">{{__cms('Идентификатор представления')}} (site_id)</label>
                          <label class="input">
                             <input class="input-sm" name="site_id" placeholder="ga:116884201" value="{{$siteId}}">
                         </label>
                      </section>
                  </fieldset>

                  <fieldset>
                     <section>
                           <label class="label">Client ID</label>
                           <label class="input">
                              <input class="input-sm" name="client_id" placeholder="xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com" value="{{$clientId}}">
                          </label>
                       </section>
                   </fieldset>

                    <fieldset>
                        <section>
                              <label class="label">Service Account Name (Service Email)</label>
                              <label class="input">
                                 <input class="input-sm" name="service_email" placeholder="xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@developer.gserviceaccount.com" value="{{$serviceEmail}}">
                             </label>
                          </section>
                      </fieldset>
                      <fieldset>
                         <section>
                               <label class="label">Файл .p12 certificate</label>
                               <label class="file">
                                  <input class="input-sm" name="certificate" type="file" value="">
                              </label>
                              <div>{{basename($certificatePath)}}</div>
                           </section>
                       </fieldset>

                       <fieldset>
                            <section>
                                <input type="hidden" name="encryption" value="tls">
                                <button class="btn btn-primary" type="submit"> {{__cms('Сохранить')}} </button>
                            </section>
                       </fieldset>


                  </section>

            </div>
      </form>
 </div>
 <link href="/packages/vis/analitics/css/style.css" rel="stylesheet">