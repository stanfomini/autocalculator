<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AppointmentSseController extends Controller
{
    public function stream()
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no'); // some servers need this

        // We?ll keep this simple: re-check the DB every few seconds in a loop.
        // In production, you might use watchers or a better approach.
        // For demonstration, we?ll run the loop for ~30 seconds max.
        $startTime = time();
        while (true) {
            if ((time() - $startTime) > 30) {
                // End the SSE after 30 seconds to prevent infinite loop
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            // Query all appointments
            $appointments = Appointment::all()->map(function($appt) {
                $appt->is_new = $appt->created_at &&
                    $appt->created_at->gt(Carbon::now()->subMinutes(10));
                return $appt;
            });

            // Convert to JSON
            $data = json_encode($appointments);

            // SSE format: send an "message" event
            echo "event: message\n";
            echo "data: {$data}\n\n";
            flush();

            // Wait a few seconds before next push
            sleep(3);

            if (connection_aborted()) {
                break;
            }
        }
    }
}