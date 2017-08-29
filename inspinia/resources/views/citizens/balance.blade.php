@extends('layouts.app')

@push('stylesheets')
  <!-- CSS Datatables -->
  <link href="{{ URL::asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endpush

@section('page-header')

@endsection

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- ibox-title -->
                <div class="ibox-title">
                    <h5><i class="fa fa-th-list" aria-hidden="true"></i> Estado de Cuenta por Ciudadano</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a></li>
                            <li><a href="#">Config option 2</a></li>
                        </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                    </div>
                </div>
                <!-- /ibox-title -->
                    
            <!-- ibox-content- -->
            <div class="ibox-content">
                
            {{ Form::open(array('url' => 'citizens.balance/'.$citizen->id.'/3', 'id' => 'form', 'method' => 'get'), ['' ])}}
            

            @if($citizen->movements->count())
                

                <div class="table-responsive">
                    
                  @include('partials.errors')

                <div class="col-sm-7">
                    <h2>{{ $citizen->name }}</h2>
                    <h4>{{ $period_title }}</h4>
                    @if($period!='all')
                        <p>Saldo al {{ $initial_date->format('d/m/Y') }} : <strong>{{ money_fmt($initial_balance) }} {{ Session::get('coin') }}</strong></p>
                    @endif
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <label>Consultar otro período</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            {{ Form::select('period', ['3' => 'Ultimos 3 meses', '6' => 'Ultimos 6 meses', '12' => 'Ultimo 12 meses', 'all' => 'Completo'],  $period, ['id'=>'period', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>''])}}
                        </div>
                    </div>
                </div>
                                    
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Fecha</th>
                        <th>Descripción</th>
                        <th class="text-right">Débito {{ Session::get('coin') }}</th>
                        <th class="text-right">Crédito {{ Session::get('coin') }}</th>
                        <th class="text-right">Saldo {{ Session::get('coin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=1;
                        $balance=$initial_balance;
                    @endphp
                    @foreach($movements as $movement)
                    <tr class="gradeX">
                        @php( ($movement->movement_type=='C')?$balance=$balance+$movement->amount:$balance=$balance-$movement->amount )
                        <td class="text-center">{{ $i++ }}</td>
                        <td class="text-center">{{ $movement->date->format('d/m/Y') }}</td>                          
                        <td><small>{{ $movement->description }} - Nº {{ $movement->contract->number }}</small></td>
                        <td class="text-right">{{ ($movement->movement_type == 'D')?money_fmt($movement->amount):'' }}</td>
                        <td class="text-right">{{ ($movement->movement_type == 'C')?money_fmt($movement->amount):'' }}</td>
                        <td class="text-right">{{ money_fmt($balance) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Fecha</th>
                        <th>Descripción</th>
                        <th class="text-right">Débito {{ Session::get('coin') }}</th>
                        <th class="text-right">Crédito {{ Session::get('coin') }}</th>
                        <th class="text-right">Saldo {{ Session::get('coin') }}</th>
                    </tr>
                    </tfoot>
                    </table>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    </div>
                </div>
                @else
                  <div class="alert alert-info">
                    <ul>
                      <i class="fa fa-info-circle"></i> No existen registros para mostrar!
                    </ul>
                  </div>                
                @endif
                
                {{ Form::close() }} 
                </div>
                <!-- /ibox-content- -->
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>
    
    <!-- Page-Level Scripts -->
    <script>
        path_str_language = "{{URL::asset('js/plugins/dataTables/es_ES.txt')}}";
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
              "oLanguage":{"sUrl":path_str_language},
              "ordering": false,
              "bAutoWidth": false, // Disable the auto width calculation
              "aoColumns": [
                { "sWidth": "5%" },  // 1st column width 
                { "sWidth": "15%" }, // 2nd column width
                { "sWidth": "35%" }, // 3nd column width
                { "sWidth": "15%" }, // 4nd column width
                { "sWidth": "15%" }, // 5nd column width
                { "sWidth": "15%" }  // 6nd column width
              ],              
              responsive: false,
              paging: false,              
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: '{!! $period_title !!}',                  
                  className: "btn-sm",
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                  }                                    
                },
                {
                  extend: "pdf",
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  pageSize: 'LETTER',
                  titleAttr: 'Exportar a PDF',
                  title: '{!! $period_title !!}',                  
                  className: "btn-sm",
                  //Sub titulo
                  message: '',
                  exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                  },
                  customize: function ( doc ) {
                    //Tamaño de la fuente del body
                    doc.defaultStyle.fontSize = 8;
                    //Tamaño de la fuente del header
                    doc.styles.tableHeader.fontSize = 9;
                    //Configuracion de margenes de la pagina
                    doc.pageMargins = [30, 30, 30, 30 ];
                    //Codigo para el footer
                    var cols = [];
                    doc['footer']=(function(page, pages) {
                      cols[0] = {text: new Date().toLocaleString(), alignment: 'left', margin:[30] };
                      cols[1] = {text: '© '+new Date().getFullYear()+' {{ Session::get('app_name') }} . Todos los derechos reservados.', alignment: 'center', bold:true, margin:[0, 0,0] };
                      cols[2] = {text: 'Página '+page.toString()+ 'de'+pages.toString(), alignment: 'right', italics: true, margin:[0,0,30] };                    
                    return {
                      alignment:'center',
                      fontSize: 7,
                      columns: cols,
                    }
                    });
                    //Codigo para el logo
                    doc.content.splice( 0, 0, 
                      {
                        margin: [ 0, 0, 0, 2 ],
                        alignment: 'center',
                        fit: [100, 100],
                        image: 'data:image/png;base64,{{ $company->logo }}'
                      }                       
                    );
                    //Codigo para la leyenda del logo (Dirección del condominio)
                    doc.content.splice( 1, 0, 
                      {
                        margin: [ 0, 0, 0, 10 ],
                        fontSize: 7,
                        alignment: 'center',
                        text: '{{ $company->name }}',
                      }                       
                    );                    
                  }
                },
              ]
            });
        
        // Select2 
        $("#period").select2({
          language: "es",
          placeholder: "Seleccione un período",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });

        
      $('#period').on("change", function (e) { 
        console.log("Cambio "+$('#period').val());
        url = `{{URL::to('citizens.balance/'.Crypt::encrypt($citizen->id))}}/${e.target.value}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });

        });
    </script>
@endpush