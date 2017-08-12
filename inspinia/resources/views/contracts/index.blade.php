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
                    <h5><i class="fa fa-tachometer" aria-hidden="true"></i> Contratos</h5>
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

            @if($contracts->count())
                <div class="table-responsive">
                    
                    @include('partials.errors')

                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contracts as $contract)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                          @if($contract->status == "A")
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('contracts.edit', Crypt::encrypt($contract->id)) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                    <li><a href="{{ route('contracts.status', Crypt::encrypt($contract->id)) }}"><i class="fa fa-ban"></i> Deshabilitar</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <!-- href para eliminar registro -->                            
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el Contrato '.{{ $contract->number }}.' ?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" style="color:inherit"><i class="fa fa-trash-o"></i> Eliminar</a>
                                        </form>
                                        <br/><br/>
                                    </li>

                                </ul>
                            </div>
                          @else                              
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-danger dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('contracts.status', Crypt::encrypt($contract->id)) }}"><i class="fa fa-check"></i> Habilitar</a></li>
                                    </ul>
                            </div>
                          @endif
                        <!-- /Split button -->                        </td>                          
                        <td><strong>{{ $contract->number }}</strong></td>
                        <td>{{ $contract->citizen->name }}</td>
                        <td>{{ money_fmt($contract->balance) }}</td>
                        <td>
                        <p><span class="label {{ $contract->label_status }}">{{ $contract->status_description }}</span></p>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Estado</th>
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
	<script src="{{ asset("js/plugins/dataTables/datatables.min.js") }}"></script>

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
                { "sWidth": "25%" }, // 2nd column width
                { "sWidth": "25%" }, // 3nd column width
                { "sWidth": "25%" }, // 4nd column width 
                { "sWidth": "20%" }  // 5nd column width                
              ],              
              responsive: false,              
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Contratos',                  
                  className: "btn-sm",
                  exportOptions: {
                    columns: [1, 2, 3],
                  }                                    
                },
                {
                  extend: "pdf",
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  pageSize: 'LETTER',
                  titleAttr: 'Exportar a PDF',
                  title: 'Contratos',                  
                  className: "btn-sm",
                  //Sub titulo
                  message: '',
                  exportOptions: {
                    columns: [1, 2, 3],
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