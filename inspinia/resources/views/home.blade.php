@extends('layouts.app')

@push('stylesheets')
<style>
    .flot-x-axis .flot-tick-label {
    white-space: nowrap;
    transform: translate(-9px, 0) rotate(-60deg);
    text-indent: -100%;
    transform-origin: top right;
    text-align: right !important;
}
</style>
@endpush

@section('page-header')
<div class="wrapper wrapper-content">
    @php
    @endphp
    <!-- Widgets -->
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">{{ month_letter($current_month, 'lg') }}</span>
                    <h5>Pagos del Mes {{ Session::get('coin') }}</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ money_fmt($sum_payments_month) }}</h1>
                    <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                    <small>Total pagos {{ $count_payments_month }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-info pull-right">{{ month_letter($current_month, 'lg') }}</span>
                    <h5>Descuentos del Mes {{ Session::get('coin') }}</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ money_fmt($sum_discounts_month) }}</h1>
                    <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                    <small>Total descuentos {{ $count_discounts_month }}</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">{{ month_letter($current_month, 'lg') }}</span>
                    <h5>Cuentas por Cobrar {{ Session::get('coin') }}</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{ money_fmt($sum_invoices_month_pending) }}</h1>
                    <div class="stat-percent font-bold text-navy">{{ ($count_invoices_month!=0)?round(($count_invoices_month_pending/$count_invoices_month*100),2):0 }}% </div>
                    <small>Recibos Pendientes {{ $count_invoices_month_pending }} de {{ $count_invoices_month }}</small>
                </div>
            </div>
        </div>
    </div>
    <!-- /Widgets -->
    
    
    <!-- Graph -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Gestión de Cobro {{ $current_year }}</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-dashboard-chart"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins ">{{ money_fmt($sum_invoices_year_pending) }} {{ Session::get('coin') }}</h2>
                                    <small>Por pagar en {{ $current_year }}: <strong>{{ $count_invoices_year_pending }} recibos</strong></small>
                                </li>
                                <li>
                                    <h2 class="no-margins ">{{ money_fmt($sum_payments_year) }} {{ Session::get('coin') }}</h2>
                                    <small>Pagos en {{ $current_year }}: <strong>{{ $count_payments_year }}</strong></small>
                                </li>
                                <li>
                                    <h2 class="no-margins">{{ money_fmt($sum_discounts_year) }} {{ Session::get('coin') }}</h2>
                                    <small>Descuentos en {{ $current_year }}: <strong>{{ $count_discounts_year }}</strong></small>
                                </li>                                        
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Graph -->

    <!-- Graph -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Gestión de Cobro {{ $current_year }} por Colonias</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-neighborhood"></div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </div>
    <!-- /Graph -->                
</div>
@endsection

@push('scripts')    
    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Jvectormap -->
    <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- EayPIE -->
    <script src="js/plugins/easypiechart/jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>


    <script>
        $(document).ready(function() {
            

            //Notifications
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 2000
                };
                if('{{ Session::get('notity') }}'=='create' &&  '{{ Session::get('create_notification') }}'=='1'){
                  toastr.success('Registro añadido exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='update' &&  '{{ Session::get('update_notification') }}'=='1'){
                  toastr.success('Registro actualizado exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='delete' &&  '{{ Session::get('delete_notification') }}'=='1'){
                  toastr.success('Registro eliminado exitosamente', '{{ Session::get('app_name') }}');
                }
            }, 1300);

    //Flot Chart Gestion de Cobro
    var data1 = {!! $total_incomes_year !!}
    var data2 = {!! $total_invoices_year !!}

    var dataset = [
        {
            label: "Ingreso Esperado",
            data: data2,
            color: "#1ab394",
            bars: {
                show: true,
                align: "center",
                barWidth: 0.7,
                lineWidth:0
            }

        }, {
            label: "Ingreso Real",
            data: data1,
            //yaxis: 2,  //Para manejar ejes Y para cada serie
            color: "#1C84C6",
            lines: {
                lineWidth:1,
                    show: true,
                    fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.4
                    }]
                }
            },
            splines: {
                show: false,
                tension: 0.6,
                lineWidth: 1,
                fill: 0.1
            },
        }
    ];

    var ticks = [
        [1, "Ene"], [2, "Feb"], [3, "Mar"], [4, "Abr"], [5, "May"], [6, "Jun"], 
        [7, "Jul"], [8, "Ago"], [9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dic"],
    ];

    var options = {
        xaxis: {
            ticks: ticks,
            tickLength: 0,                    
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 10,
            color: "#d5d5d5"
        },
        yaxes: [{
            position: "left",
            color: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 3
        }, {
            position: "right",
            clolor: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: ' Arial',
            axisLabelPadding: 67
        }
        ],
        legend: {
            noColumns: 1,
            labelBoxBorderColor: "#000000",
            position: "nw"
        },
        grid: {
            hoverable: true,
            borderWidth: 0
        },
    };
            
    $.plot($("#flot-dashboard-chart"), dataset, options);
    $("#flot-dashboard-chart").UseTooltip();
    // FIN Flot Chart Gestion de Cobro


    // INICIO Flot Gestion de Cobro por Colonia
    var data3 = {!! $incomes_by_neighborhood !!}
    var data4 = {!! $invoices_by_neighborhood !!}

    var dataset_neighborhood = [
        {
            label: "Ingreso Esperado",
            data: data4,
            color: "#1ab394",
            bars: {
                show: true,
                align: "center",
                barWidth: 0.7,
                lineWidth:0
            }

        }, {
            label: "Ingreso Real",
            data: data3,
            //yaxis: 2, //Para manejar ejes Y para cada serie.
            color: "#1C84C6",
            lines: {
                lineWidth:1,
                    show: true,
                    fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.2
                    }, {
                        opacity: 0.4
                    }]
                }
            },
            splines: {
                show: false,
                tension: 0.6,
                lineWidth: 1,
                fill: 0.1
            },
        }
    ];

    var ticks_neighborhood = {!! $ticks_neighborhood !!}

    var options_neighborhood = {
        xaxis: {
            ticks: ticks_neighborhood,
            tickLength: 0,                    
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 10,
            color: "#d5d5d5"
        },
        yaxes: [{
            position: "left",
            color: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Arial',
            axisLabelPadding: 3
        }, {
            position: "right",
            clolor: "#d5d5d5",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: ' Arial',
            axisLabelPadding: 67
        }
        ],
        legend: {
            noColumns: 1,
            labelBoxBorderColor: "#000000",
            position: "nw"
        },
        grid: {
            hoverable: true,
            borderWidth: 0
        },
    };
            
    $.plot($("#flot-neighborhood"), dataset_neighborhood, options_neighborhood);
    $("#flot-neighborhood").UseTooltip();
    //Flot FIN Gestion de Cobro por Colonias

    });


        
        var previousPoint = null, previousLabel = null;

        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0]-1;
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                        //console.log(item.series.xaxis.ticks[x].label);                
 
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> {{ Session::get('coin') }}");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }

    </script>
    
@endpush