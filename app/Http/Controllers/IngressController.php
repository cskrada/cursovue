<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingress;
use App\IncomeDetail;


class IngressController extends Controller
{
    public function index (Request $request){
	    if (!$request->ajax()) return redirect('/');

	    $buscar = $request->buscar;
	    $criterio = $request->criterio;

	    if ($buscar == ''){
	       $income = Ingress::join('persons','income.idprovider','=','persons.id')
            ->join('users','income.iduser','=','users.id')
            ->select('income.id','income.type_voucher','income.serie_voucher',
            'income.num_voucher','income.fecha_hora','income.tax','income.total',
            'income.status','persons.name','users.user')
            ->orderBy('income.id', 'desc')->paginate(3);
	    }
	    else{
	        $income = Ingress::join('persons','income.idprovider','=','persons.id')
            ->join('users','income.iduser','=','users.id')
            ->select('income.id','income.type_voucher','income.serie_voucher',
            'income.num_voucher','income.fecha_hora','income.tax','income.total',
            'income.status','persons.name','users.user')
	       	->where('income.'.$criterio, 'like', '%'. $buscar . '%')
	       	->orderBy('income.id', 'desc')->paginate(3);    
	    }

	    return [
	        'pagination' => [
	            'total'         => $income->total(),
	            'current_page'  => $income->currentPage(),
	            'per_page'      => $income->perPage(),
	            'last_page'     => $income->lastPage(),
	            'from'          => $income->firstItem(),
	            'to'            => $income->lastItem(),
	        ],
	        'income'        => $income
	    ];
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
