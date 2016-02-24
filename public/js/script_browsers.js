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

                top: topOffset + pos.top - 20,
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
    function ajaxLoadChart(startDate, endDate) {

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

        $(".load_page").show();
        $.post("/admin/analitics/load_statistic_browsers", {
                start:	startDate.format('{yyyy}-{MM}-{dd}'),
                end:	endDate.format('{yyyy}-{MM}-{dd}'),
                type: $(".param_statistic a.active").attr("type"),
                nameType : $(".param_statistic a.active").text(),
            },
            function(data){

                $(".table_browsers").html(data);

            });

        $.getJSON('/admin/analitics/load_statistic', {

            start:	startDate.format('{yyyy}-{MM}-{dd}'),
            end:	endDate.format('{yyyy}-{MM}-{dd}'),
            type : $("[name=statistic] [name=type]").val(),
        }, function(data) {

            set = [];
            $.each(data.grafic, function() {
                set.push({
                    x : this.label,
                    y : parseInt(this.value)
                });

            });

            chart.setData({
                "xScale" : "time",
                "yScale" : "linear",
                "main" : [{
                    className : ".stats",
                    data : set
                }]
            });

            $(".load_page").hide();
        });
    }


    $(".param_statistic a").click(function(){
        $(".param_statistic a").removeClass("active");

        thisElement = $(this);

        $(".load_page").show();
        $.post("/admin/analitics/load_statistic_browsers", {
                start:	startDate.format('{yyyy}-{MM}-{dd}'),
                end:	endDate.format('{yyyy}-{MM}-{dd}'),
                type: thisElement.attr("type"),
                nameType : thisElement.text(),
            },
            function(data){

                $(".table_browsers").html(data);
                thisElement.addClass("active");
                $(".load_page").hide();
            });
    });
});
