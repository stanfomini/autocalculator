<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no'); // For Nginx

        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Retrieve newest first
            $records = Schedule::orderBy('id','desc')->get()->map(function($item) {
                $item->is_new = $item->created_at &&
                                $item->created_at->gt(Carbon::now()->subMinutes(10));
                return $item;
            });

            echo "event: message\n";
            echo "data: " . json_encode($records) . "\n\n";
            flush();

            sleep(3);
            if (connection_aborted()) {
                break;
            }
        }
    }
}