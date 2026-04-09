<?php namespace App\Jobs;
use App\Domains\Cart\Models\Cart;
use App\Mail\AbandonedCartMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
class SendAbandonedCartEmails implements ShouldQueue
{
    use Dispatchable, Queueable;
    public function handle(): void
    {
        Cart::query()
            ->whereNotNull("email")
            ->whereNull("converted_at")
            ->where("updated_at", "<", now()->subHour())
            ->whereDoesntHave("remindersSent")
            ->with("items.product")
            ->each(
                fn($cart) => Mail::to($cart->email)->queue(
                    new AbandonedCartMail($cart)
                )
            );
    }
}
