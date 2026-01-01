<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\QueueCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicQueueController extends Controller
{
    public function get(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());

        $counter = QueueCounter::where('date', $date)->first();
        $current = $counter?->current_number ?? 0;

        $queues = Queue::where('date', $date)
            ->orderBy('number')
            ->get(['number', 'status', 'called_at', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'current_number' => $current,
                'queues' => $queues,
            ],
        ]);
    }
}

