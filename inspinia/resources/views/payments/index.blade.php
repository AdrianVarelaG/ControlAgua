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
                    <h5><i class="fa fa-money" aria-hidden="true"></i> Pagos</h5>
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
                
            {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}
            {{ Form::close() }} 
            
            @if($payments->count())
                <div class="table-responsive">
                    
                  @include('partials.errors')

                <div class="col-sm-7">
                    <h4>{{ $period_title }}</h4>
                </div>
                
                <div class="col-sm-5">
                    <div class="form-group">
                        <label>Consultar otro período</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            {{ Form::select('period', ['1' => 'Ultimo mes', '3' => 'Ultimos 3 meses', '6' => 'Ultimos 6 meses', '12' => 'Ultimo 12 meses', 'all' => 'Completo'],  $period, ['id'=>'period', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>''])}}
                        </div>
                    </div>
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
                    @foreach($payments as $payment)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('payments.show', Crypt::encrypt($payment->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('payments.print_voucher', Crypt::encrypt($payment->id)) }}"><i class="fa fa-print"></i> Imprimir Comprobante</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <!-- href para eliminar registro -->                                        
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el pago?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" style="color:inherit"><i class="fa fa-trash-o"></i> Eliminar</a>
                                        </form>
                                        <br/><br/>
                                    </li>

                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>
                        <td>
                          <strong>{{ $payment->contract->number }}</strong><br/>
                          <small>{{ $payment->contract->citizen->name }}</small>
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
                { "sWidth": "20%" }, // 2nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 3nd column width
                { "sWidth": "15%" }, // 4nd column width
                { "sWidth": "25%" }, // 5nd column width
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
                  title: 'Pagos',                  
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
                  title: 'Pagos',                  
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
        url = `{{URL::to('payments.change_period/')}}/${e.target.value}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });


    </script>
@endpush