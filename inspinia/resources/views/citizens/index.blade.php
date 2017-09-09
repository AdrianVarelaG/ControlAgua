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
                    <h5><i class="fa fa-users" aria-hidden="true"></i> Ciudadanos</h5>
                    <div class="ibox-tools">
                    	<a class="collapse-link">
                        	<i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        	<i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-citizen">
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
              
              {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}
              {{ Form::close() }} 

              <div class="col-sm-8">
                <a href="{{URL::to('citizens.change_view', 'contact')}}" class="btn btn-sm btn-default" title="Vista Contactos"><i class="fa fa-th-large"></i></a>
                @if(Session::get('user_role') == 'ADM')
                  <a href="{{ route('citizens.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Registrar</a><br/><br/>
                @endif
              </div>
            
                            
              <div class="col-sm-4">
                <div class="form-group">
                  <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                      {!! Form::text('filter_name', Session::get('filter_name'), ['id'=>'filter_name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Buscar por nombre' ,'maxlength'=>'100']) !!}
                  </div>
                </div>
              </div>


            @if($citizens->count())
              <div class="col-md-12 col-sm-12 col-xs-12">  
                <div class="table-responsive">
              
                  @include('partials.errors')
                    
                    <table class="table dataTables-example table-striped table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($citizens as $citizen)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                          @if($citizen->status == "A")
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('contracts.citizen_contracts', Crypt::encrypt($citizen->id)) }}"><i class="fa fa-tachometer"></i> Contratos</a></li>
                                    <li><a href="{{ route('citizens.balance', [Crypt::encrypt($citizen->id), '3']) }}"><i class="fa fa-th-list"></i> Estado de Cuenta</a></li>
                                    <li><a href="{{ route('citizens.invoices', [Crypt::encrypt($citizen->id)]) }}"><i class="fa fa-file-text-o"></i> Recibos</a></li>
                                    <li><a href="{{ route('citizens.payments', [Crypt::encrypt($citizen->id)]) }}"><i class="fa fa-money"></i> Pagos</a></li>
                                    <li class="divider"></li>
                                    @if(Session::get('user_role') == 'ADM') 
                                      <li><a href="{{ route('citizens.edit', Crypt::encrypt($citizen->id)) }}"><i class="fa fa-pencil"></i> Editar perfil</a></li>
                                      <li><a href="{{ route('citizens.status', Crypt::encrypt($citizen->id)) }}"><i class="fa fa-ban"></i> Deshabilitar</a></li>
                                      <li class="divider"></li>
                                      <li>
                                        <!-- href para eliminar registro -->                            
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('citizens.destroy', $citizen->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el ciudadano?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" style="color:inherit"><i class="fa fa-trash-o"></i> Eliminar</a>
                                        </form>
                                        <br/><br/>
                                      </li>
                                    @endif
                                </ul>
                            </div>
                          @else                              
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-danger dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('citizens.status', Crypt::encrypt($citizen->id)) }}"><i class="fa fa-check"></i> Habilitar</a></li>
                                    </ul>
                            </div>
                          @endif
                        <!-- /Split button -->
                        </td>                          
                        <td>
                            <div class="client-avatar" style="display: inline;">
                              <img alt="image" src="{{ url('citizen_avatar/'.$citizen->id) }}">&nbsp;&nbsp;
                              <a href="{{ route('contracts.citizen_contracts', Crypt::encrypt($citizen->id)) }}" class="client-link">{{ $citizen->name }}
                              </a>
                            </div>
                        </td>
                        <td>{{ $citizen->RFC }}</td>
                        <td>
                          @if($citizen->balance>=0)
                            {{ money_fmt($citizen->balance) }}
                          @else
                            {{ money_fmt(abs($citizen->balance)) }} <i class="fa fa-level-up" style="color:#1ab394;cursor:help;" title="Saldo a favor"></i>
                          @endif
                        </td>
                        <td>
                        <p><span class="label {{ $citizen->label_status }}">{{ $citizen->status_description }}</span></p>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Estatus</th>
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
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="alert alert-info">
                      <ul>
                        <i class="fa fa-info-circle"></i> Ningún registro coincide con su criterio de busqueda!
                      </ul>
                    </div>
                  </div>
                @endif
                </div><!-- /row- --> 
              </div><!-- /ibox-content- -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

    <!-- Page-Level Scripts -->
    <script>
        path_str_language = "{{URL::asset('js/plugins/dataTables/es_ES.txt')}}";
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
              "oLanguage":{"sUrl":path_str_language},
              "bAutoWidth": false, // Disable the auto width calculation
              "aoColumns": [
                { "sWidth": "10%" }, // 1st column width 
                { "sWidth": "30%" }, // 2nd column width
                { "sWidth": "20%" }, // 3th column width
                { "sWidth": "20%" }, // 4th column width
                { "sWidth": "20%" } // 5th column width
              ],              
              responsive: false,              
              dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                {
                  extend: "excel",
                  text: '<i class="fa fa-file-excel-o"></i>',
                  titleAttr: 'Exportar a Excel',
                  //Titulo
                  title: 'Ciudadanos',                  
                  className: "btn-sm",
                  exportOptions: {
                    columns: [1, 2, 3, 4],
                  }                                    
                },
                {
                  extend: "pdf",
                  text: '<i class="fa fa-file-pdf-o"></i>',
                  pageSize: 'LETTER',
                  titleAttr: 'Exportar a PDF',
                  title: 'Ciudadanos',                  
                  className: "btn-sm",
                  //Sub titulo
                  message: '',
                  exportOptions: {
                    columns: [1, 2, 3, 4],
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
        
            //Filter Name
            var timerid;    
            $("#filter_name").on("input",function(e){
              var value = $(this).val();
              if($(this).data("lastval")!= value){

                $(this).data("lastval",value);        
                clearTimeout(timerid);

                timerid = setTimeout(function() {
                  //change action
                  if(value!=''){
                    url = `{{URL::to('citizens.filter/')}}/${e.target.value}`;
                    $('#form').attr('action', url);
                    $('#form').submit();
                  }
                },800);
              };
            });

        });
    </script>
@endpush