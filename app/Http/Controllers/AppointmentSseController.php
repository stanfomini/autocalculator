<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        // We'll loop for ~30 seconds to keep the demo simple
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Pull all appointments from DB
            $appointments = Appointment::all()->map(function ($appt) {
                $appt->is_new = $appt->created_at
                    && $appt->created_at->gt(Carbon::now()->subMinutes(10));
                return $appt;
            });

            $data = json_encode($appointments);

            // SSE format
            echo "event: message\n";
            echo "data: {$data}\n\n";
            flush();

            sleep(3);

            if (connection_aborted()) {
                break;
            }
        }
    }
}