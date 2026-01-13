<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailySalesReport extends Command
{
    protected $signature = 'report:daily-sales';
    protected $description = 'Send daily sales report to admin including revenue and item breakdown';

    public function handle()
    {
        $today = Carbon::today();

        // 1. Fetch Statistics from the Orders table
        $totalRevenue = Order::whereDate('created_at', $today)->sum('total_price');
        $orderCount = Order::whereDate('created_at', $today)->count();

        // 2. Fetch specific items sold today (Eager load product names)
        // We use order_items because one order can have multiple different products
        $itemsSold = OrderItem::with('product')
            ->whereDate('created_at', $today)
            ->get();

        // 3. Prepare the Email Body
        $reportDate = $today->toFormattedDateString();

        $emailBody = "Daily Sales Report: $reportDate\n";
        $emailBody .= "====================================\n\n";
        $emailBody .= "Total Orders: $orderCount\n";
        $emailBody .= "Total Revenue: $" . number_format($totalRevenue, 2) . "\n";
        $emailBody .= "Total Individual Items Sold: " . $itemsSold->sum('quantity') . "\n\n";

        $emailBody .= "Breakdown of Items Sold:\n";
        $emailBody .= "------------------------\n";

        if ($itemsSold->isEmpty()) {
            $emailBody .= "No items were sold today.";
        } else {
            foreach ($itemsSold as $item) {
                $productName = $item->product->name ?? 'Unknown Product';
                $emailBody .= "- $productName: {$item->quantity} unit(s) (@ $" . number_format($item->price_at_purchase, 2) . " each)\n";
            }
        }

        // 4. Send the Mail
        Mail::raw($emailBody, function ($message) use ($reportDate) {
            $message->to('admin@example.com')
                ->subject("Daily Sales Report - $reportDate");
        });

        $this->info("Daily report for $reportDate sent successfully!");
    }
}
