<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;

/**
 * Streams Appointment records over SSE for real-time updates.
 */
class BookingSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // We'll loop for ~30s to avoid infinite script
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Grab all appointments from the existing table
            $appointments = Appointment::all()->map(function ($appt) {
                $appt->is_new = $appt->created_at
                    && $appt->created_at->gt(Carbon::now()->subMinutes(10));
                return $appt;
            });

            echo "event: message\n";
            echo "data: " . json_encode($appointments) . "\n\n";
            flush();

            sleep(3);

            if (connection_aborted()) {
                break;
            }
        }
    }
}