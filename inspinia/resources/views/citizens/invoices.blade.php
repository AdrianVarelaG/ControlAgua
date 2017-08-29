@extends('layouts.app')

@push('stylesheets')
  <!-- CSS Datatables -->
  <link href="{{ URL::asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
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
                
            @if($citizen->invoices->count())
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
                        <th class="text-center">Recibo #</th>
                        <th>Contrato</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($citizen->invoices()->orderBy('date', 'DESC')->get() as $invoice)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('invoices.invoice_pdf', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-print"></i> Imprimir</a></li>
                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>                          
                        <td class="text-center">
                          <a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}" class="client-link" title="Vista previa">{{ $invoice->id }}</a>
                        </td>
                        <td>
                          <strong>{{ $invoice->contract->number }}</strong><br/>
                        </td>
                        <td>{{ $invoice->date->format('d/m/Y') }}</td>
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
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/dataTables/sortDate.js') }}"></script>


    <!-- Page-Level Scripts -->
    <script>
        path_str_language = "{{URL::asset('js/plugins/dataTables/es_ES.txt')}}";
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
              "oLanguage":{"sUrl":path_str_language},
              "bAutoWidth": false, // Disable the auto width calculation
              "aoColumns": [
                { "sWidth": "5%" },  // 1st column width 
                { "sWidth": "15%" }, // 2nd column width
                { "sWidth": "15%" }, // 3nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 4nd column width
                { "sWidth": "15%" }, // 5nd column width
                { "sWidth": "15%", "sType": "date-uk" }, // 6nd column width
                { "sWidth": "20%" }  // 7nd column width
              ],              
              responsive: false,              
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Recibos de {!! $citizen->name !!}',                  
                  className: "btn-sm",
                  exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6],
                  }                                    
                },
                {
                  extend: "pdf",
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  pageSize: 'LETTER',
                  titleAttr: 'Exportar a PDF',
                  title: 'Recibos de {!! $citizen->name !!}',                  
                  className: "btn-sm",
                  //Sub titulo
                  message: '',
                  exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6],
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
    </script>
@endpush