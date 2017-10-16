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
                    <h5><i class="fa fa-tachometer" aria-hidden="true"></i> Contratos Inactivos</h5>
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
              
              {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}
              {{ Form::close() }} 

              <div class="col-sm-8">
              </div>
                                        
              <div class="col-sm-4">
                  <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                      {!! Form::text('filter_name', Session::get('filter_name'), ['id'=>'filter_name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Buscar por nombre' ,'maxlength'=>'100']) !!}
                  </div>
              </div>

            
            @if($contracts->count())
              <div class="col-md-12 col-sm-12 col-xs-12">                  
                <div class="table-responsive">
                  <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                        <th>RFC</th>
                        <th>Dirección</th>
                    </tr>
                    </thead>
                    <tbody>
                  @foreach($contracts as $contract)
                    <tr class="gradeX">
                        <td class="text-center">                            
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-danger dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                    <ul class="dropdown-menu">
                                      @if($contract->movements->count()==0)
                                        <li><a href="{{ route('contracts.activate', Crypt::encrypt($contract->id)) }}"><i class="fa fa-check"></i> Registrar Datos Iniciales y Activar</a></li>
                                      @else
                                        <li><a href="{{ route('contracts.status', Crypt::encrypt($contract->id)) }}"><i class="fa fa-check"></i>Activar</a></li>
                                      @endif
                                    @if(Session::get('user_role') == 'ADM' || Session::get('user_role') == 'TES')
                                      <li>
                                        <!-- href para eliminar registro -->                            
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el Contrato {{ $contract->number }} ?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" style="color:inherit"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Eliminar</a>
                                        </form>
                                        <br/><br/>
                                      </li>
                                    @endif                                        
                                    </ul>
                            </div>
                        </td>
                        <td><strong>{{ $contract->number }}</strong></td>
                        <td>{{ $contract->citizen->name }}</td>
                        <td>{{ $contract->citizen->RFC }}</td>
                        <td>{{ $contract->citizen->address }}</td>
                    </tr>
                  @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                    </tr>
                    </tfoot>
                    </table>
                    <div class="text-right">
                      {{ $contracts->links() }}
                    </div>
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
</div>
@endsection

@push('scripts')
  <script>        
    
    //Filter Name
    var timerid;    
    $("#filter_name").on("input",function(e){
      var value = $(this).val().trim();;
      if($(this).data("lastval")!= value){

        $(this).data("lastval",value);        
        clearTimeout(timerid);

        timerid = setTimeout(function() {
          //change action
          if(value!=''){
            url = `{{URL::to('initial_balance.filter/')}}/${e.target.value}`;
          }else{
            {{ Session::put('filter_name', '') }}
            url = `{{URL::to('contracts.initial_balance')}}`;
          }
          $('#form').attr('action', url);
          $('#form').submit();
        },800);
      };
    });
  
  </script>
@endpush