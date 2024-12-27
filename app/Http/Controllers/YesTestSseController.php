<?php

namespace App\Http\Controllers;

use App\Models\YesTest;
use Carbon\Carbon;

class YesTestSseController extends Controller
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

            $data = YesTest::orderBy('id','desc')->get()->map(function ($row) {
                $row->is_new = $row->created_at
                    && $row->created_at->gt(Carbon::now()->subMinutes(10));
                return $row;
            });

            echo "event: message\n";
            echo "data: " . json_encode($data) . "\n\n";
            flush();

            sleep(3);
        }
    }
}