$(function() {

    $("#chart").width($('.analitics_stat').width()-40).height(200);

    $(window).resize(function(){
        $("#chart").width($('.analitics_stat').width()-40).height(200);
    });
    // Set the default dates
    var startDate	= Date.create().addDays(-29),	// 30 days ago
        endDate		= Date.create(); 				// today

    var range = $('#range');

    // Show the dates in the range input
    range.val(startDate.format('{MM}/{dd}/{yyyy}') + ' - ' + endDate.format('{MM}/{dd}/{yyyy}'));

    // Load chart
    ajaxLoadChart(startDate,endDate);

    range.daterangepicker({

        startDate: startDate,
        endDate: endDate,

        ranges: {
            'Сегодня': ['today', 'today'],
            'Вчера': ['yesterday', 'yesterday'],
            'За последнии 7 дней': [Date.create().addDays(-6), 'today'],
            'За последнии 30 дней': [Date.create().addDays(-29), 'today']
        },
        locale : {
            applyLabel: 'Применить',
            clearLabel: "Очистить",
            fromLabel: 'От',
            toLabel: 'До',
            weekLabel: 'Н',
            customRangeLabel: 'Диапазон',
            daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб'],
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Авгруст', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            firstDay: 1
        }
    },function(start, end){

        ajaxLoadChart(start, end);

    });

    // The tooltip shown over the chart
    var tt = $('<div class="ex-tooltip">').appendTo('body'),
        topOffset = -32;

    var data = {
        "xScale" : "time",
        "yScale" : "linear",
        "main" : [{
            className : ".stats",
            "data" : []
        }]
    };

    var opts = {
        paddingLeft : 50,
        paddingTop : 20,
        paddingRight : 10,
        axisPaddingLeft : 25,
        tickHintX: 9, // How many ticks to show horizontally
        tickHintY: 6,
        dataFormatX : function(x) {

            // This turns converts the timestamps coming from
            // ajax.php into a proper JavaScript Date object

            return Date.create(x);
        },

        tickFormatX : function(x) {

            // Provide formatting for the x-axis tick labels.
            // This uses sugar's format method of the date object.

            return x.format('{MM}/{dd}');
        },



        "mouseover": function (d, i) {
            var pos = $(this).offset();

            tt.html( "<strong> " + localizationDay(d.x.getDay()) + " " + d.x.getDate() + " " + localizationMonth(d.x.getMonth()) + " " + d.x.getFullYear() + " г. </strong>" + ' <br>Сеансы:  <strong>' + d.y +'</strong>').css({

                top: topOffset + pos.top-20,
                left: pos.left

            }).show();
        },

        "mouseout": function (x) {
            tt.hide();
        }
    };

    function localizationMonth(param) {


        var month = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля",	"августа", "сентября", "октября", "ноября", "декабря"];

        return month[param];
    }

    function localizationDay(param) {

        var days = ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"];

        return days[param];
    }

    // Create a new xChart instance, passing the type
    // of chart a data set and the options object

    var chart = new xChart('line-dotted', data, '#chart' , opts);

    // Function for loading data via AJAX and showing it on the chart
    function ajaxLoadChart(startDate,endDate) {

        // If no data is passed (the chart was cleared)

        if(!startDate || !endDate){
            chart.setData({
                "xScale" : "time",
                "yScale" : "linear",
                "main" : [{
                    className : ".stats",
                    data : []
                }]
            });

            return;
        }

        TableBuilder.showPreloader();
        $.getJSON('/admin/analitics/load_statistic', {

            start:	startDate.format('{yyyy}-{MM}-{dd}'),
            end:	endDate.format('{yyyy}-{MM}-{dd}'),
            type : $("[name=statistic] [name=type]").val(),
        }, function(data) {

            var set = [];
            $.each(data.grafic, function() {
                set.push({
                    x : this.label,
                    y : parseInt(this.value)
                });

            });

            $(".analitics_stat .seans").text(data['totalsForAllResults']['ga:visits']);
            $(".analitics_stat .user").text(data['totalsForAllResults']['ga:newUsers']);
            $(".analitics_stat .show_pages").text(data['totalsForAllResults']['ga:pageviews']);

            var seans_pages =  parseInt(data['totalsForAllResults']['ga:pageviews'])/parseInt(data['totalsForAllResults']['ga:visits']);

            $(".analitics_stat .seans_pages").text(seans_pages.toFixed(2));

            var bounceRate = parseInt(data['totalsForAllResults']['ga:bounceRate']);
            $(".analitics_stat .bounceRate").text(bounceRate.toFixed(2));

            var percentNewSessions = parseInt(data['totalsForAllResults']['ga:percentNewSessions']);
            $(".analitics_stat .new_seans").text(percentNewSessions.toFixed(2));

            var avgSessionDuration = parseInt(data['totalsForAllResults']['ga:avgSessionDuration']/60);
            $(".analitics_stat .avg_duration").text(avgSessionDuration.toFixed(2));



            chart.setData({
                "xScale" : "time",
                "yScale" : "linear",
                "main" : [{
                    className : ".stats",
                    data : set
                }]
            });

            TableBuilder.hidePreloader();

        });
    }
});
