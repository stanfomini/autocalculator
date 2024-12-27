<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;

/**
 * Streams schedules over SSE so newly created records appear 
 * in real time on /testing1 route across all connected clients.
 */
class ScheduleSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // Keep the loop ~30 seconds for demonstration
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                // Graceful close
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Load all schedules, add "is_new" if created < 10 min
            // Fetch the records in descending order of id so new records are pushed to front.
            $all = Schedule::orderBy('id', 'desc')->get()->map(function($r) {
                $r->is_new = $r->created_at && $r->created_at->gt(Carbon::now()->subMinutes(10));
                return $r;
            });

            echo "event: message\n";
            echo "data: " . json_encode($all) . "\n\n";
            flush();

            sleep(3);

             // check for connection abort.
            if (connection_aborted()) {
                break;
            }
        }
    }
}