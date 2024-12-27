<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;

/**
 * SSE controller for real-time updates from schedules table
 */
class ScheduleSseController extends Controller
{
    public function stream()
    {
        // SSE Headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // Keep SSE open for ~30 seconds
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                // Graceful close
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            $schedules = Schedule::all()->map(function ($row) {
                $row->is_new = $row->created_at
                    && $row->created_at->gt(Carbon::now()->subMinutes(10));
                return $row;
            });

            echo "event: message\n";
            echo "data: " . json_encode($schedules) . "\n\n";
            flush();

            sleep(3);

            if (connection_aborted()) {
                break;
            }
        }
    }
}