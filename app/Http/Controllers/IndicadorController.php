<?php

namespace App\Http\Controllers;

use App\Models\Indicador;
use Illuminate\Http\Request;
use Carbon\Carbon as Carbon;
use Carbon\CarbonPeriod as CarbonPeriod;

class IndicadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicadores = Indicador::paginate()->onEachSide(1);
 
        return $indicadores;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nombreIndicador'      => 'required | string | min:1 ',
            'codigoIndicador'  => 'required | string | min:1',
            'unidadMedidaIndicador'  => 'required | string | min:1',
            'valorIndicador'  => 'required | numeric',
            'fechaIndicador'  => 'required | string | min:1',
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error', 'errors' => $validator->errors()->all()), 403);
            }

            $indicador = Indicador::create($request->all());
            return response()->json(array('status' => 'success', 'data' => array('indicador' => $indicador)));
        } catch (\Exception $e) {
            \Log::info('Error creando indicador' . $e);
            return response()->json(array('message' => 'ERROR: ' . $e, 'status' => 'fail'), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function show(Indicador $indicador)
    {
        $indicador = Indicador::find($indicador->id);
        if (is_null($indicador)) {
            return response()->json(array('get' => false, 'errors' => 'No se encontro indicador con esa id'), 404);
        }
        return $indicador;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Indicador $indicador)
    {
        $rules = [
            'nombreIndicador'      => 'required | string | min:1 ',
            'codigoIndicador'  => 'required | string | min:1',
            'unidadMedidaIndicador'  => 'required | string | min:1',
            'valorIndicador'  => 'required | numeric',
            'fechaIndicador'  => 'required | string | min:1',
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error', 'errors' => $validator->errors()->all()), 403);
            }

            $indicador = Indicador::find($indicador->id);
	        if (is_null($indicador)) {
	            return Utilitarios::returnJSON(null, 'Indicador no encontrado', 'error', 404);
	        }
            $indicador->update($request->all());

            return response()->json(array('status' => 'success', 'data' => array('indicador' => $indicador)));
        } catch (\Exception $e) {
            \Log::info('Error creando indicador' . $e);
            return response()->json(array('message' => 'ERROR: ' . $e, 'status' => 'fail'), 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indicador $indicador)
    {
        $indicador = Indicador::find($indicador->id);
        if (is_null($indicador)) {
            return response()->json(array('deleted' => false, 'errors' => 'No se encontro indicador con esa id'), 404);
        }
        try {
            $indicador->delete();
            return response()->json(['deleted' => true], 204);
        } catch (\Exception $e) {
            \Log::info('Error  eliminando indicador: ' . $e);
            return \Response::json(['deleted' => false], 500);
        }
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function chart(Request $request)
    {
        
        $rules = [
            'from'   => 'required | date_format:Y-m-d',
            'to'     => 'required | date_format:Y-m-d| after_or_equal:from'
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error', 'errors' => $validator->errors()->all()), 403);
            }

            $from = Carbon::createFromFormat('Y-m-d', $request->from, 'America/Santiago');
            $to = Carbon::createFromFormat('Y-m-d', $request->to, 'America/Santiago');

            $data = Indicador::whereBetween('fechaIndicador', [$from, $to])
                ->select('valorIndicador', 'fechaIndicador', 'codigoIndicador')
                ->orderBy('fechaIndicador', 'asc')
                ->get();
            
            return $data;
        } catch (Exception $e) {
            return Utilitarios::returnJSON(null, 'Ocurrio un error al buscar la disponibilidad', 'error', 500);
        }
    }
}
