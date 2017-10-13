<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Company;
use App\Http\Requests\Invoice\InvoiceRequestPrint;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use ZipArchive;
use File;
use PDF;
use Session;
use View;
use App;


class PDFController extends Controller
{
 
    /*
     * Download file from DB  
    */ 
    public function invoice_pdf($id)
    {
        $company = Company::first();
        $invoice = Invoice::find(Crypt::decrypt($id));
        $data=[
            'company' => $company,
            'invoice' => $invoice,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/invoice', $data);
        return $pdf->download('Recibo Nro '.Crypt::decrypt($id).'.pdf');

    }

    /*
     * Download file from DB  
    */ 
    public function invoices_pdf(InvoiceRequestPrint $request)
    {
        $final_view='';
        $start = intval($request->input('invoice_from'));
        $end = intval($request->input('invoice_to'));
        $total_invoices = intval($end - $start);
        $company = Company::first();
        $invoices = Invoice::where('id', '>=' , $start)
                            ->where('id', '<=' , $end);
        
        if($total_invoices <= 50){
            $final_view='';
            $company = Company::first();
            $invoices = Invoice::where('id', '>=' , $request->input('invoice_from'))
                            ->where('id', '<=' , $request->input('invoice_to'))->get();
        
            foreach ($invoices as $invoice) {
                $data=[
                    'company' => $company,
                    'invoice' => $invoice,
                ];

                $invoice_view = View::make('reports/invoice', $data )->render();
            
                $final_view = $final_view."<div class='saltopagina'></div>".$invoice_view;                    
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($final_view);
            $pdf->setPaper([0, 0, 420, 595], 'landscape'); 
            return $pdf->download('Recibos del Nro '.$request->input('invoice_from').' al Nro '.$request->input('invoice_to').'.pdf');            
        
        }elseif($total_invoices > 50){
        
            ini_set('max_execution_time', 600);
            $i=$start;
            $files = array();

            $invoices->chunk(10, function($invoices) use ($company, &$start, &$end, &$i, &$files)
            {        
                $final_view = '';
                $k=0;
                foreach ($invoices as $invoice) {
                    $i++;
                    $k++;
                    $data=[
                        'company' => $company,
                        'invoice' => $invoice,
                    ];

                    $invoice_view = View::make('reports/invoice', $data )->render();
            
                    $final_view = $final_view."<div class='saltopagina'></div>".$invoice_view;
                }
                if ($i<=$end){
                    $filename = 'Recibos desde '.intval($i-10).' hasta '.intval($i-1).'.pdf';
                }else{
                    $filename = 'Recibos desde '.intval($end-$k+1).' hasta '.intval($i-1).'.pdf';
                }
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($final_view);
                $pdf->setPaper([0, 0, 420, 595], 'landscape'); 
                $pdf->loadHTML($final_view);
                $path = public_path().'/pdf/';
                $files[] = $filename;
                $pdf->save($path.$filename);        
            }); // end chunk
            //Se empaquetan los archivos y se descargan en un solo ZIP
            $zipname = 'Paquete de Recibos desde '.$start.' hasta '.$end.'.zip';
            $zip = new ZipArchive;
            if ($zip->open(storage_path() . '/pdf/' . $zipname, ZipArchive::CREATE) === TRUE) {
                foreach ($files as $file) {
                    //La funcion basename() suprime la ruta larga al archivo a ser empaquetado.
                    $zip->addFile(public_path().'/pdf/'.$file,basename($file));
                }
                $zip->close();
            }
            //Se eliminan los archivos PDF una vez generado el ZIP
            foreach ($files as $file)  {
                unlink(public_path().'/pdf/'.$file);
            }
            $headers = array('Content-Type: application/zip');            
            return Response::download(storage_path(). "/pdf/".$zipname, $zipname, $headers)->deleteFileAfterSend(true); 
        }
    }

    /*
     * Download file from DB  
    */ 
    public function print_voucher($id)
    {
        $company = Company::first();
        $payment = Payment::find(Crypt::decrypt($id));
        $data=[
            'company' => $company,
            'payment' => $payment,
        ];
        $pdf = PDF::loadView('reports/voucher', $data);
        return $pdf->download('Voucher.pdf');

    }

}
