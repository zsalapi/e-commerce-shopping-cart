<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLowStockEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $product) {}

    public function handle(): void
    {
        // In a real app, you'd fetch the admin email from config or DB
        Mail::to('admin@example.com')->send(new \App\Mail\LowStockWarning($this->product));
    }
}
