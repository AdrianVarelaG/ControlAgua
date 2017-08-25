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
                    <h5><i class="fa fa-money" aria-hidden="true"></i> Consulta de Pagos</h5>
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
                            
            @if($citizen->payments->count())
                <div class="table-responsive">
                    
                  @include('partials.errors')

                <div class="col-sm-7">
                    <h2>{{ $citizen->name }}</h2><br/>
                </div>                  
                                    
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Contrato</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($citizen->payments()->orderBy('date', 'DESC')->get() as $payment)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('payments.show', Crypt::encrypt($payment->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('payments.print_voucher', Crypt::encrypt($payment->id)) }}"><i class="fa fa-print"></i> Imprimir Comprobante</a></li>
                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>
                        <td>
                          <strong>{{ $payment->contract->number }}</strong><br/>
                        </td>                          
                        <td>{{ $payment->date->format('d/m/Y') }}</td>
                        <td>{{ $payment->type_description }}</td>
                        <td><small>{{ $payment->description }}</small></td>
                        <td>{{ money_fmt($payment->amount) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Contrato</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                    </tr>
                    </tfoot>
                    </table>
                	</div>
                </div>
                @else
                  <div class="alert alert-info">
                    <ul>
                      <i class="fa fa-info-circle"></i> No existen registros para mostrar!
                    </ul>
                  </div>                
                @endif
                  <div class="form-group pull-right">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                      <a href="{{URL::to('citizens')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                    </div>
                  </div>
                  <br/>
                  <br/>                
                </div>
                <!-- /ibox-content- -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset("js/plugins/dataTables/datatables.min.js") }}"></script>
<script src="{{ URL::asset('"js/plugins/dataTables/sortDate.js') }}"></script>

<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>

    <!-- Page-Level Scripts -->
    <script>
        path_str_language = "{{URL::asset('js/plugins/dataTables/es_ES.txt')}}";
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
              "oLanguage":{"sUrl":path_str_language},
              "ordering": true,
              "bLengthChange": true, //Habilitar o deshabilitar el nro de registros por paginacion
              "bAutoWidth": false, // Disable the auto width calculation
              "aoColumns": [
                { "sWidth": "5%" },  // 1st column width 
                { "sWidth": "15%" }, // 2nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 3nd column width
                { "sWidth": "15%" }, // 4nd column width
                { "sWidth": "30%" }, // 5nd column width
                { "sWidth": "20%" }  // 6nd column width                
              ],              
              responsive: false,
              paging: true,
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Pagos de {!! $citizen->name !!}',                  
                  className: "btn-sm",
                  exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                  }                                    
                },
                {
                  extend: "pdf",
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  pageSize: 'LETTER',
                  titleAttr: 'Exportar a PDF',
                  title: 'Pagos de {!! $citizen->name !!}',                  
                  className: "btn-sm",
                  //Sub titulo
                  message: '',
                  exportOptions: {
                    columns: [1, 2, 3, 4, 5],
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
          });                      

    </script>
@endpush