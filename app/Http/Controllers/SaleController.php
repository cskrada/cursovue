<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Sale;
use App\SaleDetail;

class SaleController extends Controller
{
    public function index (Request $request){
	    if (!$request->ajax()) return redirect('/');

	    $buscar = $request->buscar;
	    $criterio = $request->criterio;

	    if ($buscar == ''){
	       $sales = Sale::join('persons','sales.idclient','=','persons.id')
            ->join('users','sales.iduser','=','users.id')
            ->select('sales.id','sales.type_voucher','sales.serie_voucher',
            'sales.num_voucher','sales.fecha_hora','sales.tax','sales.total',
            'sales.status','persons.name','users.user')
            ->orderBy('sales.id', 'desc')->paginate(3);
	    }
	    else{
	        $sales = Sale::join('persons','sales.idclient','=','persons.id')
            ->join('users','sales.iduser','=','users.id')
            ->select('sales.id','sales.type_voucher','sales.serie_voucher',
            'sales.num_voucher','sales.fecha_hora','sales.tax','sales.total',
            'sales.status','persons.name','users.user')
	       	->where('sales.'.$criterio, 'like', '%'. $buscar . '%')
	       	->orderBy('sales.id', 'desc')->paginate(3);    
	    }

	    return [
	        'pagination' => [
	            'total'         => $sales->total(),
	            'current_page'  => $sales->currentPage(),
	            'per_page'      => $sales->perPage(),
	            'last_page'     => $sales->lastPage(),
	            'from'          => $sales->firstItem(),
	            'to'            => $sales->lastItem(),
	        ],
	        'sales'        => $sales
	    ];
    }

    public function getHeader(Request $request){
      if (!$request->ajax()) return redirect('/');

      $id = $request->id;
       $ingress = Ingress::join('persons','income.idprovider','=','persons.id')
        ->join('users','income.iduser','=','users.id')
        ->select('income.id','income.type_voucher','income.serie_voucher',
        'income.num_voucher','income.fecha_hora','income.tax','income.total',
        'income.status','persons.name','users.user')
        ->where('income.id','=',$id)
        ->orderBy('income.id', 'desc')->take(1)->get();

      return ['ingress'=> $ingress];
    }

    public function getDetails(Request $request){
      if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $details = IncomeDetail::join('articles','income_detail.idarticle','=','articles.id')
        ->select('income_detail.quantity','income_detail.price','articles.name as article')
        ->where('income_detail.idingress','=',$id)
        ->orderBy('income_detail.id', 'desc')->get();
        
        return ['details' => $details];
    }

    public function store (Request $request){
        if (!$request->ajax()) return redirect('/');

        try{
        	DB::beginTransaction();

          $mytime= Carbon::now('America/Caracas');

			    $ingress = new Ingress();
        	$ingress->idprovider = $request->idprovider;
          $ingress->iduser = \Auth::user()->id;
          $ingress->type_voucher = $request->type_voucher;
          $ingress->serie_voucher = $request->serie_voucher;
          $ingress->num_voucher = $request->num_voucher;
          $ingress->fecha_hora = $mytime->toDateString();
          $ingress->tax = $request->tax;
          $ingress->total = $request->total;
          $ingress->status = 'Registrado';
       	 	$ingress->save();

          $details = $request->data;//array de detalles
          //recorro todos los elementos

          foreach($details as $ep=>$det)
          {
            $detail = new IncomeDetail();
            $detail->idingress = $ingress->id;
            $detail->idarticle = $det['idarticle'];
            $detail->quantity = $det['quantity'];
            $detail->price = $det['price'];
            $detail->save();
          }

       	 	DB::commit();

        }catch (Exception $e){
        	DB::rollBack();
        }      
    }

    public function desactivar (Request $request){
    	if (!$request->ajax()) return redirect('/');
        $ingress = Ingress::findOrFail($request->id);
    		$ingress->status = 'Anulado';
    		$ingress->save();
    }
}
