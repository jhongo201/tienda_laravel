<?php namespace App\Domains\Payment\Services;
use App\Domains\Order\Models\Order;
class WompiService
{
    private string $publicKey;
    private string $integrityKey;
    private string $redirectUrl;
    public function __construct()
    {
        $this->publicKey = config("services.wompi.public_key");
        $this->integrityKey = config("services.wompi.integrity_key");
        $this->redirectUrl = config("services.wompi.redirect_url") ?: route("checkout.return");
    }
    public function buildCheckoutUrl(Order $order): string
    {
        $amountCents = (int) ($order->total * 100);
        $currency = "COP";
        $signature = $this->generateSignature(
            $order->reference,
            $amountCents,
            $currency
        );
        $params = http_build_query([
            "public-key" => $this->publicKey,
            "currency" => $currency,
            "amount-in-cents" => $amountCents,
            "reference" => $order->reference,
            "redirect-url" => $this->redirectUrl,
            "signature:integrity" => $signature,
        ]);
        return "https://checkout.wompi.co/p/?{$params}";
    }
    public function generateSignature(
        string $reference,
        int $amountCents,
        string $currency
    ): string {
        $concat = "{$reference}{$amountCents}{$currency}{$this->integrityKey}";
        return hash("sha256", $concat);
    }
    public function getTransactionStatus(string $transactionId): string
    {
        $isSandbox = str_starts_with($this->publicKey, 'pub_test_');
        $baseUrl   = $isSandbox
            ? 'https://sandbox.wompi.co/v1'
            : 'https://production.wompi.co/v1';

        $privateKey = config('services.wompi.private_key');

        $response = \Illuminate\Support\Facades\Http::withToken($privateKey)
            ->get("{$baseUrl}/transactions/{$transactionId}");

        if ($response->successful()) {
            return $response->json('data.status', 'ERROR');
        }

        return 'ERROR';
    }

    public function verifyWebhookSignature(
        array $payload,
        string $receivedSignature
    ): bool {
        $eventsKey = config("services.wompi.events_key");
        $transaction = $payload["data"]["transaction"] ?? [];
        $concat =
            ($transaction["id"] ?? "") .
            ($transaction["status"] ?? "") .
            ($transaction["amount_in_cents"] ?? "") .
            ($payload["timestamp"] ?? "") .
            $eventsKey;
        return hash("sha256", $concat) === $receivedSignature;
    }
}
