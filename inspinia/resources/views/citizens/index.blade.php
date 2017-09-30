@extends('layouts.app')

@push('stylesheets')

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
                <div class="col-sm-10">
                  <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                      {!! Form::text('filter_name', Session::get('filter_name'), ['id'=>'filter_name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Buscar por nombre' ,'maxlength'=>'100']) !!}
                  </div>
                </div>
                <button type="button" id="btn_print" class="btn btn-sm btn-default" title="Imprimir PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12">
                @include('partials.errors')
              </div>


            @if($citizens->count())
              <div class="col-md-12 col-sm-12 col-xs-12">  
                <div class="table-responsive">                                
                  <table class="table dataTables-example table-striped table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th class="text-right">Deuda {{ Session::get('coin') }}</th>
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
                        <td class="text-right">
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
                        <th class="text-rigth">Deuda {{ Session::get('coin') }}</th>
                        <th>Estatus</th>
                    </tr>
                    </tfoot>
                    </table>
                    <div class="text-right">
                      {{ $citizens->links() }}
                    </div>                    
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

            <!-- Modal advertencia para imprimir-->
            <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i><strong> Atención!</strong></h4>
                        </div>
                        <div class="modal-body">
                          <p>Debido a la gran cantidad de registros. Primero debido filtrar los registros para poder imprimirlos. Gracias!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_close" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal advertencia para reverso de ventas-->


@endsection

@push('scripts')
  
<script>        
  
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
      var value = $(this).val().trim();
      if($(this).data("lastval")!= value){

        $(this).data("lastval",value);        
        clearTimeout(timerid);

        timerid = setTimeout(function() {
          //change action
          if(value!=''){
          url = `{{URL::to('citizens.filter/')}}/${e.target.value}`;
          }else{
            {{ Session::put('filter_name', '') }}
            url = `{{URL::to('citizens')}}`;
          }
          $('#form').attr('action', url);
          $('#form').submit();
        },800);
      };
    });

    $('#btn_print').on("click", function (e) { 
      filter = $("#filter_name").val().trim();
      if(filter ==''){
        $("#myModal1").modal("show"); 
      }else{
        url = `{{URL::to('citizens.rpt_citizens/')}}/${filter}`;
        $('#form').attr('action', url);
        $('#form').submit();
      }
    });


</script>
@endpush