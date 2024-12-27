<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;

/**
 * SSE streaming of the ?bookings? table for real-time updates.
 */
class BookingSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // We'll keep this open for ~30s in a loop
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                // Graceful close event
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Load all bookings, mark ?is_new?
            $bookings = Booking::all()->map(function ($b) {
                $b->is_new = $b->created_at
                    && $b->created_at->gt(Carbon::now()->subMinutes(10));
                return $b;
            });

            // SSE ?message? event
            echo "event: message\n";
            echo "data: " . json_encode($bookings) . "\n\n";
            flush();

            sleep(3);

            if (connection_aborted()) {
                break;
            }
        }
    }
}