<?php

namespace App\Jobs;

use Twilio\Rest\Client;
use App\Models\Consulta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarLembreteWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $consulta;

    public function __construct($consulta)
    {
        $this->consulta = $consulta;
    }

    public function handle()
    {
        $sid = config('twilio.sid');
        $token = config('twilio.token');
        $from = config('twilio.from');

        $client = new Client($sid, $token);

        $client->messages->create(
            "whatsapp:{$this->consulta->numero}",
            [
                'from' => $from,
                'body' => 'Sua consulta é em 5 minutos!',
            ]
        );
    }

    public static function verificarEEnviarLembretes()
    {
        $consultas = Consulta::where('inicio_epoch', '<=', now()->addMinutes(5)->timestamp)
            ->where('fim_epoch', '>=', now()->timestamp)
            ->get();

        foreach ($consultas as $consulta) {
            self::dispatch($consulta);
        }

        return response()->json(['message' => 'Lembretes enviados.']);
    }
}
