<?php 

use App\Domains\Order\Models\Order; 
use App\Domains\Payment\Models\Payment; 
use App\Domains\Payment\Jobs\ProcessWompiWebhook; 

it('procesa un pago exitoso end-to-end', function () { 
    $order = Order::factory()->pending()->create(); 
    $payload = wompiWebhookPayload('txn_ok', $order->reference, 'APPROVED'); 
    ProcessWompiWebhook::dispatchSync($payload); 
    expect($order->fresh()->status->value)->toBe('paid'); 
    expect(Payment::where('transaction_id', 'txn_ok')->exists())->toBeTrue(); 
}); 
it('ignora un webhook duplicado — idempotencia', function () { 
    $order = Order::factory()->pending()->create(); 
    Payment::factory()->create(['transaction_id' => 'txn_dup', 'order_id' => $order->id]); 
    $payload = wompiWebhookPayload('txn_dup', $order->reference, 'APPROVED'); 
    ProcessWompiWebhook::dispatchSync($payload); 
    // El estado no debe cambiar — ya había un payment con ese transaction_id 
    expect($order->fresh()->status->value)->toBe('pending'); 
    expect(Payment::where('transaction_id', 'txn_dup')->count())->toBe(1); 
}); 

it('no decrementa stock si el pago falla', function () { 
        $product = \App\Domains\Product\Models\Product::factory()->create(['stock' => 5]); 
        $order = Order::factory()->pending()->withItem($product, 2)->create(); 
        $payload = wompiWebhookPayload('txn_fail', $order->reference, 'DECLINED'); 
        ProcessWompiWebhook::dispatchSync($payload); 
        expect($product->fresh()->stock)->toBe(5); 
    }); 

it('rechaza webhook con firma inválida', function () { 
    $response = $this->postJson('/webhooks/wompi', 
        ['event' => 'test'], 
        [ 'x-wompi-signature-v2' => 'firma_invalida', ]); 
    $response->assertStatus(401); 
});