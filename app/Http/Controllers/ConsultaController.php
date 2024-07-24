<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Twilio\Rest\Client as TwilioClient;
use App\Jobs\EnviarLembreteWhatsApp; // Importa a classe do job

class ConsultaController extends Controller
{
    // Método para armazenar a consulta
    public function store(Request $request)
    {
        // Validação dos dados enviados
        $request->validate([
            'nome' => 'required|string',
            'numero' => 'required|string',
            'inicio_data' => 'required|date',
            'fim_data' => 'required|date',
        ]);

        // Converter as datas para timestamps epoch
        $inicioEpoch = Carbon::parse($request->inicio_data)->timestamp;
        $fimEpoch = Carbon::parse($request->fim_data)->timestamp;

        // Criar a nova consulta no banco de dados
        $consulta = Consulta::create([
            'nome' => $request->nome,
            'numero' => $request->numero,
            'inicio_epoch' => $inicioEpoch,
            'fim_epoch' => $fimEpoch,
        ]);

        // Enviar uma mensagem via WhatsApp informando a marcação
        $this->sendWhatsAppReminder($request->numero, 'Sua consulta foi marcada com sucesso para o dia ' . $request->inicio_data . '.');

        // Retornar a resposta JSON com os detalhes da consulta criada
        return response()->json($consulta, 201);
    }

    // Método para enviar um lembrete via WhatsApp
    private function sendWhatsAppReminder($numero, $mensagem)
    {
        $sid = config('twilio.sid');
        $token = config('twilio.token');
        $from = config('twilio.from'); // Número do remetente

        $client = new TwilioClient($sid, $token);

        try {
            $client->messages->create(
                "whatsapp:$numero", // Número do destinatário
                [
                    'from' => $from, // Número do remetente
                    'body' => $mensagem,
                ]
            );
        } catch (\Twilio\Exceptions\RestException $e) {
            // Logar o erro ou tratá-lo
        }
    }

    // Método para verificar e enviar lembretes para as consultas
    public function verificarEEnviarLembretes()
    {
        $consultas = Consulta::where('inicio_epoch', '<=', Carbon::now()->addMinutes(5)->timestamp)
            ->where('fim_epoch', '>=', Carbon::now()->timestamp)
            ->get();

        foreach ($consultas as $consulta) {
            EnviarLembreteWhatsApp::dispatch($consulta);
        }

        return response()->json(['message' => 'Lembretes enviados.']);
    }
}
