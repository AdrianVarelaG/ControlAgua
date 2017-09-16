@extends('layouts.app')

@push('stylesheets')
<!-- CSS Datatables -->
<link href="{{ URL::asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<!-- DatePicker -->
<link href="{{ URL::asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

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
            <div class="row">
                
            
              <div class="col-sm-5">
                  <h4>Total Pagos: {{ $payments_count }}</h4>
                  <h4>Total {{ Session::get('coin') }}: {{ money_fmt($payments_total) }}</h4>
              </div>
                
              {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}              
              <div class="col-md-7">                
                <div class="col-sm-5">
                  <div class="form-group" id="data_1">
                      <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          {{ Form::text ('from', $from, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'01/01/2017', 'required']) }}
                      </div>
                  </div>
                </div>
                <div class="col-sm-5">
                  <div class="form-group" id="data_2">
                      <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          {{ Form::text ('to', $to, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'31/01/2017', 'required']) }}
                      </div>
                  </div>
                </div>
                <button type="button" id="btn_change" class="btn btn-sm btn-default" title="Refrescar"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                <button type="button" id="btn_print" class="btn btn-sm btn-default" title="Imprimir PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
              </div>
              {{ Form::close() }} 

            
            @if($payments->count())
              <div class="col-md-12 col-sm-12 col-xs-12">              

                @include('partials.errors')
                
                <div class="table-responsive">                                        
                    <table class="table table-striped table-hover" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Tarifa</th>
                        <th>Contrato</th>
                        <th>Domicilio</th>
                        <th>Fecha</th>
                        <th class="text-right">Monto {{ Session::get('coin') }}</th>
                        <th>Folio</th>
                        <th>Descripción</th>
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
                                    @if(Session::get('user_role') == 'ADM' || Session::get('user_role') == 'TES')
                                      <li><a href="{{ route('payments.edit', Crypt::encrypt($payment->id)) }}"><i class="fa fa-pencil-square-o"></i> Registrar Folio</a></li>
                                    @endif
                                    <li><a href="{{ route('payments.show', Crypt::encrypt($payment->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('payments.print_voucher', Crypt::encrypt($payment->id)) }}"><i class="fa fa-print"></i> Imprimir Comprobante</a></li>
                                    @if(Session::get('user_role') == 'ADM' || Session::get('user_role') == 'DDA')
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
                                    @endif
                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>
                        <td><small><strong>{{ $payment->contract->citizen->name }}</strong></small></td>
                        <td><small>{{ $payment->contract->rate->name }}</small></td>                        
                        <td><small><strong>{{ $payment->contract->number }}</strong></small></td>
                        <td><small>{{ $payment->contract->address }}</small></td>
                        <td><small>{{ $payment->date->format('d/m/Y') }}</small></td>
                        <td class="text-right"><small>{{ money_fmt($payment->amount) }}</small></td>
                        <td><small>{{ $payment->folio }}</small></td>
                        <td><small>{{ $payment->description }}</small></td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Tarifa</th>
                        <th>Contrato</th>
                        <th>Domicilio</th>
                        <th>Fecha</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Folio</th>
                        <th>Descripción</th>
                    </tr>
                    </tfoot>
                    </table>                    
                    <div class="text-right">
                      {{ $payments->links() }}
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <br/>                    
                    <br/>
                    <br/>
                	</div> <!-- /table-responsive- -->
                </div> 
                @else
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="alert alert-info">
                      <ul>
                        <i class="fa fa-info-circle"></i> No existen registros para mostrar!
                      </ul>
                    </div>
                  </div>
                @endif
              </div> <!-- /row- -->
            </div> <!-- /ibox-content- -->                            

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/dataTables/sortDate.js') }}"></script>
<!-- DatePicker --> 
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>

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
                { "sWidth": "5%" },  // 2st column width 
                { "sWidth": "10%", "sType": "date-uk" }, // 3nd column width
                { "sWidth": "20%" }, // 4nd column width
                { "sWidth": "10%" }, // 5nd column width
                { "sWidth": "10%" }, // 6nd column width
                { "sWidth": "25%" }, // 7nd column width
                { "sWidth": "15%" }  // 8nd column width                
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
          
        //Datepicker fecha del contrato
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })

        //Datepicker fecha del contrato
        var date_input_2=$('#data_2 .input-group.date');
        date_input_2.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })

      $('#btn_change').on("click", function (e) { 
        url = `{{URL::to('payments.change_period')}}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });

      $('#btn_print').on("click", function (e) { 
        url = `{{URL::to('payments.report_period')}}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });

    </script>
@endpush