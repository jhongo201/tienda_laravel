<?php 

use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendAbandonedCartEmails;
use App\Jobs\ExpirePendingOrders; 

// Carritos abandonados — cada hora 
Schedule::job(new SendAbandonedCartEmails)->hourly(); 

// Expirar órdenes pending > 30 minutos 
Schedule::job(new ExpirePendingOrders)->everyFifteenMinutes();
