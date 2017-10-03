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
                    <h5><i class="fa fa-file-text-o" aria-hidden="true"></i> Consulta de Recibos</h5>
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
                  <h4>Total Recibos: {{ $invoices_count }}</h4>
                  <h4>Total {{ Session::get('coin') }}: {{ money_fmt($invoices_total) }}</h4>
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

            @if($invoices->count())
              <div class="col-md-12 col-sm-12 col-xs-12">

                @include('partials.errors')

                <div class="table-responsive">
                    <table class="table table-striped table-hover" >
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Recibo #</th>
                        <th>Contrato</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                    <tr class="gradeX">
                        <td class="text-center">
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('invoices.invoice_pdf', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-print"></i> Imprimir Recibo</a></li>
                                    @if(Session::get('user_role') == 'ADM' || Session::get('user_role') == 'TES')
                                    <li class="divider"></li>
                                    <li>
                                        <!-- href para eliminar registro -->
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el Recibo #{{ $invoice->id }}?')) { return true } else {return false };">
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
                        <td class="text-center">
                          <a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}" class="client-link" title="Vista previa">{{ $invoice->id }}</a>
                        </td>
                        <td>
                          <strong>{{ $invoice->contract->number }}</strong><br/>
                          <small>{{ $invoice->contract->citizen->name }}</small>
                        </td>
                        <td>{{ $invoice->date->format('d/m/Y') }}</td>
                        </td>
                        <td>{{ money_fmt($invoice->total) }}</td>
                        <td>{{ $invoice->date_limit->format('d/m/Y') }}</td>
                        @php
                        @endphp
                        <td><p><span class="label {{ $invoice->label_status }}">{{ $invoice->status_description }}</span></p></td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-center">Recibo #</th>
                        <th>Contrato</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </tfoot>
                    </table>

                  <div class="text-right">
                      {{ $invoices->links() }}
                  </div>

                  </div>
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
                  <div class="form-group pull-right">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                      <a href="{{URL::to('invoices.index_group')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                    </div>
                  </div>
                  <br/>
                  <br/>
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
              "aaSorting": [[1, "asc"]],
              "bAutoWidth": false, // Disable the auto width calculation
              "aoColumns": [
                { "sWidth": "5%" },  // 1st column width
                { "sWidth": "15%" }, // 2nd column width
                { "sWidth": "20%" }, // 3nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 4nd column width
                { "sWidth": "15%" }, // 5nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 6nd column width
                { "sWidth": "15%" }  // 7nd column width
              ],
              responsive: false,
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Consulta de Recibos',
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
                  title: 'Consulta de Recibos',
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
        url = `{{URL::to('invoices.change_period')}}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });

      $('#btn_print').on("click", function (e) {
        url = `{{URL::to('invoices.report_period')}}`;
        $('#form').attr('action', url);
        $('#form').submit();
      });


    </script>
@endpush
