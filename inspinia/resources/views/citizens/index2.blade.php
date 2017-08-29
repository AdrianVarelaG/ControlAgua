@extends('layouts.app')

@push('stylesheets')
  <!-- CSS Datatables -->
  <link href="{{ URL::asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('page-header')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-9">
    <h2>Ciudadanos</h2>
    <a href="{{URL::to('citizens.change_view', 'list')}}" class="btn btn-sm btn-default" title="Vista Lista"><i class="fa fa-list"></i></a>    
    <a href="{{ route('citizens.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Registrar</a>
  </div>
</div>
@endsection

@section('content')
  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
            
      @include('partials.errors')

      @if($citizens->count())
        @foreach($citizens as $citizen)
          
            <div class="col-lg-4">
                <div class="contact-box">
                  <a href="profile.html">
                    <div class="col-sm-4">
                        <div class="text-center">
                            <img alt="image" class="img-circle m-t-xs img-responsive" src="{{ url('citizen_avatar/'.$citizen->id) }}">
                            <div class="m-t-xs font-bold">{{ $citizen->profession }}</div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong>{{ $citizen->name }}</strong></h3>
                        <p><i class="fa fa-map-marker"></i> {{ $citizen->neighborhood }}. {{ $citizen->street }}.<br/> # Int {{ $citizen->number_int }}/ # Ext {{ $citizen->number_ext }}</p>
                        <address>
                          <strong>{{ $citizen->RFC }}</strong><br>
                          {{ $citizen->municipality->name }}, {{ $citizen->state->name }}<br/> 
                        </address>
                    </div>
                    <div class="clearfix"></div>
                    <div class="contact-box-footer">
                      <div class="m-t-xs btn-group">
                        <!-- active and desactive citizen -->
                        @php
                          if($citizen->status=='A'){
                           $btn_class='btn btn-sm btn-primary';
                           $title = 'Desactivar';
                           $display = 'Activo';
                           $color='color:inherit'; 
                          }else{
                           $btn_class='btn btn-sm btn-danger';
                           $title = 'Activar';
                           $display = 'Desactivo';
                           $color=''; 
                          }
                        @endphp
                        <a href="{{ route('citizens.status', Crypt::encrypt($citizen->id)) }}" style="{!! $color !!}"><button class="{!! $btn_class !!}" title="{!! $title !!}">{!! $display !!}</button></a></a>                        
                        <!-- contracts citizen -->
                        <a href="{{ route('contracts.citizen_contracts', Crypt::encrypt($citizen->id)) }}" style="color:inherit"><button class="btn btn-sm btn-default" title="Contratos"><i class="fa fa-tachometer"></i></button></a>
                        
                        <!-- edit_citizens -->
                        <a href="{{ route('citizens.edit', Crypt::encrypt($citizen->id)) }}" style="color:inherit"><button class="btn btn-sm btn-default" title="Editar"><i class="fa fa-pencil"></i></button></a>
                        
                        <!-- delete_citizens -->
                        <form action="{{ route('citizens.destroy', $citizen->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el ciudadano?')) { return true } else {return false };">
                          <input type="hidden" name="_method" value="DELETE">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-sm btn-default" title="Eliminar" style="color:inherit"><i class="fa fa-trash-o"></i></a>
                        </form>                      
                      </div>
                    </div>
                  </a>
                </div>
            </div>

        
        @endforeach
      @endif    
    </div>
        {{ $citizens->links() }}
    
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
              "aaSorting": [[1, "asc"]],
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
                  title: 'Inspectores',                  
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
                  title: 'Inspectores',                  
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
        });
    </script>
@endpush