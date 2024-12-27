<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSseController extends Controller
{
    public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // Stream indefinitely until client disconnects
        while (true) {
            if (connection_aborted()) {
                break;
            }

            $all = Schedule::orderBy('id','desc')->get()->map(function ($row) {
                $row->is_new = $row->created_at
                    && $row->created_at->gt(Carbon::now()->subMinutes(10));
                return $row;
            });

            echo "event: message\n";
            echo "data: " . json_encode($all) . "\n\n";
            flush();

            sleep(3);
        }
    }
}