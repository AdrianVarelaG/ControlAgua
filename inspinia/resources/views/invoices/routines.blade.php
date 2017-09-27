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
                    <h5><i class="fa fa-tasks" aria-hidden="true"></i> Control de Recibos Generados</h5>
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

              @include('partials.errors')
                
              <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Generar</a><br/><br/>

            @if(count($routines_generated))
                <div class="table-responsive">
                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Facturación</th>
                        <th>Consumo</th>
                        <th>Tarifa Aplicada</th>
                        <th># Desde</th>
                        <th># Hasta</th>
                        <th>Generados por</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routines_generated as $ruotine)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <a href="{{ route('invoices.reverse_routine', [Crypt::encrypt($ruotine->year), Crypt::encrypt($ruotine->month)] ) }}" class="btn btn-xs btn-default" onclick="return confirm('Desea reversar la rutina del mes {{ $ruotine->month_consume }} y el año {{ $ruotine->year_consume }}?')"><i class="fa fa-history" title="Reversar"></i></a>
                        <!-- /Split button -->                        
                        </td>                          
                        <td>{{ $ruotine->month}}/{{ $ruotine->year }}</td>
                        <td>{{ $ruotine->month_consume }}/{{ $ruotine->year_consume }}</td>
                        <td>{{ $ruotine->type_description }}</td>
                        <td>{{ $ruotine->start }}</td>
                        <td>{{ $ruotine->end }}</td>
                        <td>{{ $ruotine->created_by }}</td>
                        <td>{{ $ruotine->created_at->format('d/m/Y H:m') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Facturación</th>
                        <th>Consumo</th>
                        <th>Tarifa Aplicada</th>
                        <th># Desde</th>
                        <th># Hasta</th>
                        <th>Generados por</th>
                        <th>Fecha</th>
                    </tr>
                    </tfoot>
                    </table>
                    <br/>
                    <br/>
                    <br/>
                    <br/>                    
                    <br/>
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
                { "sWidth": "5%" }, // 1st column width 
                { "sWidth": "15%" }, // 2nd column width
                { "sWidth": "15%" }, // 3nd column width
                { "sWidth": "15%" }, // 3nd column width
                { "sWidth": "10%" }, // 3nd column width
                { "sWidth": "10%" }, // 4nd column width
                { "sWidth": "15%" }, // 5nd column width
                { "sWidth": "15%", "sType": "date-uk" } // 6nd column width

              ],              
              responsive: false,              
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Control de Recibos Generados',                  
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
                  title: 'Control de Recibos Generados',                  
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
                  toastr.success('Recibos generados exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='update' &&  '{{ Session::get('update_notification') }}'=='1'){
                  toastr.success('Registro actualizado exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='delete' &&  '{{ Session::get('delete_notification') }}'=='1'){
                  toastr.success('Recibos reversados exitosamente', '{{ Session::get('app_name') }}');
                }
            }, 1300);        
        });
    </script>
@endpush