<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Padron;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\Reading;
use App\Models\Contract;
use App\Models\Citizen;
use App\Models\Movement;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Shared_Date;
use DB;
use Image;
use Carbon\Carbon;




class UploadFileController extends Controller
{
   public function index(){
   
      return view('upload.uploadfile');
   
   }
   
   public function showUploadFile(Request $request){
      
      //1. Se sube el archivo a la carpeta storage
         //$file = $request->file('file_xls');
     	   //$resource = fopen( $file->getRealPath(), 'r');
		   //Storage::put('PADRON.xls', $resource );
      //2. Se limpia la tabla padron
         //Padron::truncate();
      //2. Se importa la data a la tabla Padron de la BD      
         //$this->import_chunk();
      //3. Se actualiza a estatus DESACTIVO registros que tengan ultimo_mes y adeudo null
         //Padron::where('ultimo_mes', null)
         //      ->where('adeudo', null)
         //      ->where('status', '')
         //      ->update(['status' => 'DESACTIVO']);
      //4. Se limpian las tablas antes de la insercion
      ini_set('max_execution_time', 300);
      DB::statement("SET foreign_key_checks=0");
      Invoice::truncate();
      Movement::truncate();
      Payment::truncate();
      Reading::truncate();
      Contract::truncate();
      Citizen::truncate();
      //5. Se recorre la tabla padron con la logica
      $ciudadanos = Padron::orderBy('nombre')->get();
      $ciudadano_anterior='';
      $file = public_path()."/img/avatar_default.png";  
      $img = Image::make($file)->encode('jpg');
      $avatar = base64_encode((new ImgController)->resize_image($img, 'jpg', 200, 200));
      foreach ($ciudadanos as $ciudadano) {
         if($ciudadano->nombre != $ciudadano_anterior){            
            //5.1 Se registra el ciudadano
            $citizen = new Citizen();        
            $citizen->avatar = $avatar;         
            $citizen->ID_number = '';
            $citizen->RFC = '';
            $citizen->state_id = 24;
            $citizen->municipality_id = 1835;
            $citizen->name = $ciudadano->nombre;
            $citizen->birthdate = '1995-01-01';
            $citizen->profession = '';
            $citizen->email = '';
            $citizen->phone = '';
            $citizen->mobile = '';
            $citizen->street = ($ciudadano->calle != null)?$ciudadano->calle:'';
            $citizen->neighborhood = ($ciudadano->barrio != null)?$ciudadano->barrio:'';
            $citizen->number_ext = ($ciudadano->numero_ext != null)?$ciudadano->numero_ext:'';
            $citizen->number_int = ($ciudadano->numero_int != null)?$ciudadano->numero_int:'';
            $citizen->postal_code = '';
            $citizen->status = 'A';
            $citizen->save();
            //5.2 Se registra el Contrato
            $contract = new Contract();
            $contract->citizen_id= $citizen->id;
            $contract->number = $ciudadano->contrato;
            $contract->date= '2000-01-01';        
            $contract->rate_id= 1; 
            $contract->administration_id = 1;       
            $contract->state_id= 24;
            $contract->municipality_id= 1835;
            $contract->street= ($ciudadano->calle != null)?$ciudadano->calle:'';
            $contract->neighborhood= ($ciudadano->barrio != null)?$ciudadano->barrio:'';
            $contract->number_ext= ($ciudadano->numero_ext != null)?$ciudadano->numero_ext:'';
            $contract->number_int= ($ciudadano->numero_int != null)?$ciudadano->numero_int:'';
            $contract->postal_code= '';
            $contract->observation= $ciudadano->nota;
            $contract->status= $ciudadano->status;
            $contract->save();
            if ($ciudadano->ultimo_recibo != null){
               //5.3 Se registra ultimo recibo cancelado y el recibo con la deuda total  y adeudo
               $this->register_last_invoice_canceled($ciudadano, $citizen, $contract);
               //5.4 Se genera el recibo con deuda total 
               if($ciudadano->adeudo > 0 ){
                  $invoice = new Invoice();
                  $invoice->date = '2017-10-01';
                  $invoice->date_limit = '2017-10-31';
                  $invoice->month = '10';                    
                  $invoice->year = '2017';
                  $invoice->month_consume = '09';
                  $invoice->year_consume = '2017';
                  $invoice->citizen_id = $citizen->id;
                  $invoice->contract_id = $contract->id;
                  $invoice->message = 'Recibo Deuda Saldo Inicial';
                  $invoice->status = 'P';
                  $invoice->total = abs($ciudadano->adeudo);
                  $invoice->previous_debt = 0;
                  $invoice->save();
                  //Detalle del Recibo
                  $invoice_detail = new InvoiceDetail();
                  $invoice_detail->invoice_id = $invoice->id;              
                  $invoice_detail->movement_type = 'CT';
                  $invoice_detail->type = 'M';
                  $invoice_detail->description = 'Recibo Deuda Saldo Inicial';
                  $invoice_detail->sub_total = abs($ciudadano->adeudo);
                  $invoice_detail->save();                  
               }                              
               //5.5 Se registra el movimiento contable del adeudo
               $this->register_movement($ciudadano, $citizen, $contract, $invoice);
            } 
         }else{
            //5.2 Solo se registra el Contrato
            $contract = new Contract();
            $contract->citizen_id= $citizen->id;
            $contract->number = $ciudadano->contrato;
            $contract->date= '2000-01-01';        
            $contract->rate_id= 1; 
            $contract->administration_id = 1;       
            $contract->state_id= 24;
            $contract->municipality_id= 1835;
            $contract->street= ($ciudadano->calle != null)?$ciudadano->calle:'';
            $contract->neighborhood= ($ciudadano->barrio != null)?$ciudadano->barrio:'';
            $contract->number_ext= ($ciudadano->numero_ext != null)?$ciudadano->numero_ext:'';
            $contract->number_int= ($ciudadano->numero_int != null)?$ciudadano->numero_int:'';
            $contract->postal_code= '';
            $contract->observation= $ciudadano->nota;
            $contract->status= $ciudadano->status;
            $contract->save();
            if ($ciudadano->ultimo_recibo != null){
               //5.3 Se registra ultimo recibo cancelado (Recibo de Arranque) y adeudo
               $this->register_last_invoice_canceled($ciudadano, $citizen, $contract);
               //5.4 Se genera el recibo con deuda total 
               if($ciudadano->adeudo > 0 ){
                  $invoice = new Invoice();
                  $invoice->date = '2017-10-01';
                  $invoice->date_limit = '2017-10-31';
                  $invoice->month = '10';                    
                  $invoice->year = '2017';
                  $invoice->month_consume = '09';
                  $invoice->year_consume = '2017';
                  $invoice->citizen_id = $citizen->id;
                  $invoice->contract_id = $contract->id;
                  $invoice->message = 'Recibo Deuda Saldo Inicial';
                  $invoice->status = 'P';
                  $invoice->total = abs($ciudadano->adeudo);
                  $invoice->previous_debt = 0;
                  $invoice->save();
                  //Detalle del Recibo
                  $invoice_detail = new InvoiceDetail();
                  $invoice_detail->invoice_id = $invoice->id;              
                  $invoice_detail->movement_type = 'CT';
                  $invoice_detail->type = 'M';
                  $invoice_detail->description = 'Recibo Deuda Saldo Inicial';
                  $invoice_detail->sub_total = abs($ciudadano->adeudo);
                  $invoice_detail->save();
               }               
               //5.5 Se registra el movimiento contable del adeudo
               $this->register_movement($ciudadano, $citizen, $contract, $invoice);
            }             
         }
         $ciudadano_anterior = $ciudadano->nombre; 
      }
      DB::statement("SET foreign_key_checks=1");

      return "Ciudadanos registrados exitosamente";
   }

   public function register_last_invoice_canceled($ciudadano, $citizen, $contract){
            
      $month = (new Carbon($ciudadano->ultimo_recibo))->month;
      $month = (strlen($month)==1)?'0'.$month:$month; 
      $month_consume = (new Carbon($ciudadano->ultimo_recibo))->subMonths(1)->month;
      $month_consume = (strlen($month_consume)==1)?'0'.$month_consume:$month_consume;
      
      //Ultimo recibo cancelado
      Invoice::create([
         'date' => $ciudadano->ultimo_recibo,
         'date_limit' => date("Y-m-t", strtotime($ciudadano->ultimo_recibo)),
         'month' => $month,                    
         'year' => (new Carbon($ciudadano->ultimo_recibo))->year,
         'month_consume' => $month_consume,
         'year_consume' => (new Carbon($ciudadano->ultimo_recibo))->subMonths(1)->year,
         'citizen_id' => $citizen->id,
         'contract_id' => $contract->id,
         'message' => 'Ultimo Recibo Cancelado',
         'status' => 'C',
         'previous_debt' => 0
      ]);      
   }
   
   public function register_movement($ciudadano, $citizen, $contract, $invoice){

      if($ciudadano->adeudo <= 0 ){
            $movement_type = 'D';
            $type = 'P';
            $invoice_id = null;                  
      }else{
            $movement_type = 'C';
            $type = 'C';
            $invoice_id = $invoice->id;
      }
      
      Movement::create([
         'citizen_id' => $citizen->id,
         'contract_id' => $contract->id,
         'movement_type' => $movement_type,
         'type' => $type,
         'invoice_id' => $invoice_id,                  
         'date' => $ciudadano->ultimo_mes,
         'amount' => abs($ciudadano->adeudo),
         'description' => 'Saldo inicial'
      ]);      

   }
   
   public function import()
   {
      Excel::load(storage_path().'/app/PADRON.xls', function($citizens) {
      
         foreach ($citizens->get() as $citizen) {
            Padron::create([
               'name' => $citizen->name,
               'contract' =>$citizen->contract
            ]);
         }
      });
   }
 
   public function import_chunk(){

     Excel::filter('chunk')->load(storage_path().'/app/PADRON.xls')->chunk(250, function($rows){
    
         foreach ($rows as $row) {
            
            if($row->ultimo_mes != null){
               $ultimo_mes = PHPExcel_Style_NumberFormat::toFormattedString($row->ultimo_mes,PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);               
            }else{
               $ultimo_mes = null;
            }

            if($ultimo_mes != null){
               $ultimo_recibo = (new Carbon($ultimo_mes))->addDays(14)->subMonths(1)->subDays(14)->format('Y-m-d');               
            }else{
               $ultimo_recibo = null;
            }
            
            Padron::create([
               'nombre' => $row->nombre,
               'contrato' =>$row->contrato,
               'cuenta' =>$row->cuenta,
               'direccion' =>($row->direccion != null)?$row->direccion:'',
               'calle' =>($row->calle != null)?$row->calle:'',
               'nro_ext' =>($row->nro_ext != null)?$row->nro_ext:'',
               'nro_int' =>($row->nro_int != null)?$row->nro_int:'',
               'ultimo_mes' => $ultimo_mes,
               'ultimo_recibo' => $ultimo_recibo,
               'meses_adeudo' =>$row->meses_adeudo,
               'adeudo' =>$row->adeudo,
               'barrio' =>$row->barrio,
               'status' =>($row->status != null)?$row->status:'',
               'nota' =>($row->nota != null)?$row->nota:'',
            ]);

            //echo PHPExcel_Shared_Date::ExcelToPHP($row->ultimo_mes).'<br/>';
         }
      });
   }
}