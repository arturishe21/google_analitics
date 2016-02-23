  <table class="statistic_browsers">
         <thead>
            <tr>
                <td>
                    {{$dimensionsName}}
                </td>
                <td>
                    Сеансы
                </td>
                 <td>
                    Новые сеансы, %
                </td>
                 <td>
                    Новые пользователи
                </td>
                <td>
                    Показатель отказов
                </td>
                <td>
                    Страниц/сеанс
                </td>
                <td>
                    Сред. длительность сеанса
                </td>

            </tr>
           </thead>
           <tbody>
           @foreach($resultsBrowsers->rows as $browser)
                <tr>
                   <td>
                       {{$browser["0"]}}
                    </td>
                    <td>
                        {{$browser["1"]}}
                        @if($resultsBrowsers->totalsForAllResults['ga:sessions'] != 0)
                            ({{ round(($browser["1"]/$resultsBrowsers->totalsForAllResults['ga:sessions'])*100, 2)}} %)
                        @else
                            (0 %)
                        @endif
                    </td>
                    <td>
                        {{round($browser["2"], 2)}}
                    </td>
                    <td>
                        {{$browser["3"]}}
                        @if($resultsBrowsers->totalsForAllResults['ga:newUsers'] != 0)
                            ({{ round(($browser["3"]/$resultsBrowsers->totalsForAllResults['ga:newUsers'])*100, 2)}} %)
                        @else
                            (0 %)
                        @endif
                    </td>
                     <td>
                        {{round($browser["4"], 2)}} %
                    </td>
                     <td>
                        {{round($browser["5"], 2)}}
                    </td>
                    <td>
                        {{round($browser["6"]/60, 2)}}
                    </td>
                </tr>
            @endforeach
           </tbody>
  </table>
