<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\Indicador;
use Carbon\Carbon as Carbon;
use Carbon\CarbonPeriod as CarbonPeriod;

class ObtenerIndicadores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indicadores:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para obtener Indicadores desde api y poblar base de datos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ### estoy ignorando la validacion de cache, ya que por alguna razon la hora de expiracion del token que me devulve la api se esta adelandando, no se si estoy haciendo algo mal o sera por la zona horaria
        Cache::forget('key');
        $value = Cache::get('key');
        if(!$value){
            $current_datetime = Carbon::now('America/Santiago');

            $response = Http::post(env('SOLUTORIA_API').'/acceso', [
                'userName' => 'luismaldonadorwbdt_847@indeedemail.com',
                'flagJson' => true
            ]);

            if($response->status() != 200) return printf("Error al obtener token");
            $token_response = json_decode($response->body(), true);
            

            $expiration_datetime = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $token_response['expiracion'], 'America/Santiago');
            $dif_in_seconds = $current_datetime->diffInSeconds($expiration_datetime);
            Cache::put('key', $token_response['token'], $seconds = $dif_in_seconds);
            $value = Cache::get('key');
        }
        
        $response = Http::withHeaders([
                        'Authorization' => "Bearer $value"
                    ])->get(env('SOLUTORIA_API').'/indicadores');
        
        Indicador::truncate();
        $collection = collect($response->json());
        
        $filtered = $collection->where('codigoIndicador', 'UF');
        Indicador::insert($filtered->toArray());
        printf("Indicadores guardados");
    }
}
