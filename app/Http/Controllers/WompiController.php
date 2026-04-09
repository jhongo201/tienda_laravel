<?php 

namespace App\Http\Controllers; 

use App\Domains\Payment\Jobs\ProcessWompiWebhook; 
use App\Domains\Payment\Models\WebhookLog; 
use App\Domains\Payment\Services\WompiService; 
use Illuminate\Http\Request; 

class WompiController extends Controller { 

    public function webhook(Request $request, WompiService $wompi) { 
        $payload = $request->all(); 
        $signature = $request->header('x-wompi-signature-v2', ''); 
        $isValid = $wompi->verifyWebhookSignature($payload, $signature); 
        // 1. Loguear SIEMPRE antes de validar 
        WebhookLog::create([ 
            'gateway' => 'wompi', 'event_type' => $payload['event'] ?? null, 
            'payload' => $payload, 'signature_valid' => $isValid, 
            'processed' => false, 
        ]); 

        // 2. Rechazar si firma inválida 
        if (!$isValid) { 
            return response('Unauthorized', 401); 
        } 

        // 3. Despachar a queue payments (responder rápido a Wompi) 
        ProcessWompiWebhook::dispatch($payload)->onQueue('payments'); 
        return response('OK', 200); 
    } 
}