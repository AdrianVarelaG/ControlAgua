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

              {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}
                {!! Form::hidden('hdd_year', null, ['id'=>'hdd_year']) !!}
                {!! Form::hidden('hdd_month', null, ['id'=>'hdd_month']) !!}
              {{ Form::close() }} 
              

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
                          <a href="#" data-year="{{ $ruotine->year }}" data-month="{{ $ruotine->month }}" class="modal-class btn btn-xs btn-default" title="Reversar"><i class="fa fa-history"></i></a>
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

            <!-- Modal advertencia para reverso de ventas-->
            <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i><strong> Atención!</strong></h4>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="hdd_lot_id" id="hdd_lot_id" value=""/>
                          <strong><p id="msj"></p></strong>
                          <ul>
                            <li>Se elimiminarán todos los recibos pendientes del período seleccionado. Esto podría afectar la deuda actual del ciudadano.</li>
                            <li>Sólo se conservarán los recibos cancelados del período seleccionado.</li>
                          </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_reverse" class="btn btn-sm btn-primary" data-dismiss="modal">Reversar</button>
                            <button type="button" id="btn_close" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal advertencia para reverso de ventas-->


@endsection

@push('scripts')


  <!-- Page-Level Scripts -->
  <script>
        
  $(function () {
    $(".modal-class").click(function () {
      $('#hdd_year').val($(this).data('year'));
      $('#hdd_month').val($(this).data('month'));
      $('#msj').html('Está seguro que desea reversar los recibos del período '+$(this).data('month')+'/'+$(this).data('year')+' ?');
      $("#myModal1").modal("show");    
    })
  });

  $('#btn_reverse').on("click", function (e) { 
      var year = $('#hdd_year').val();
      var month = $('#hdd_month').val();
      url = `{{URL::to('invoices.reverse_routine/')}}/${year}/${month}`;
      $('#form').attr('action', url);
      $('#form').submit();
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

    </script>
@endpush