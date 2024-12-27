<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Carbon\Carbon;

class BlogSseController extends Controller
{
     public function stream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('X-Accel-Buffering: no');

        $startTime = time();
         while (true) {
            if ((time() - $startTime) > 30) {
                echo "event: close\n";
                echo "data: done\n\n";
                flush();
                break;
            }

            $all = Blog::orderBy('id','desc')->get()->map(function ($row) {
                $row->is_new = $row->created_at
                    && $row->created_at->gt(Carbon::now()->subMinutes(10));
                return $row;
            });


            echo "event: message\n";
            echo "data: " . json_encode($all) . "\n\n";
            flush();

             sleep(3);
            if (connection_aborted()) {
                break;
            }
        }
    }
}