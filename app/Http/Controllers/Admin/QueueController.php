<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\QueueCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    private function resolveDate(Request $request): string
    {
        return $request->query('date', Carbon::today()->toDateString());
    }

    public function list(Request $request)
    {
        $date = $this->resolveDate($request);
        $status = $request->query('status');

        $query = Queue::query()->where('date', $date)->orderBy('number');
        if ($status) {
            $query->where('status', $status);
        }

        $queues = $query->get(['id', 'date', 'number', 'status', 'called_at', 'created_at']);

        $counter = QueueCounter::where('date', $date)->first();
        $current = $counter?->current_number ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $date,
                'current_number' => $current,
                'queues' => $queues,
            ],
        ]);
    }

    public function issue(Request $request)
    {
        $date = $this->resolveDate($request);

        $queue = DB::transaction(function () use ($date) {
            $lastRow = Queue::where('date', $date)
                ->orderByDesc('number')
                ->lockForUpdate()
                ->first(['number']);

            $next = ($lastRow?->number ?? 0) + 1;

            return Queue::create([
                'date' => $date,
                'number' => $next,
                'status' => 'waiting',
            ]);
        });

        return response()->json(['success' => true, 'data' => $queue], 201);
    }

    public function next(Request $request)
    {
        $date = $this->resolveDate($request);
        $adminId = $request->user()->id;

        $result = DB::transaction(function () use ($date, $adminId) {
            $counter = QueueCounter::where('date', $date)->lockForUpdate()->first();

            if (!$counter) {
                $counter = QueueCounter::create([
                    'date' => $date,
                    'current_number' => 0,
                    'updated_by_admin_id' => $adminId,
                ]);
            }

            $nextNumber = $counter->current_number + 1;

            // Naikkan hanya kalau ticket ada (biar "next" tidak loncat ke nomor kosong)
            $ticket = Queue::where('date', $date)->where('number', $nextNumber)->first();

            if (!$ticket) {
                return [
                    'ok' => false,
                    'message' => 'No next queue ticket found',
                    'current_number' => $counter->current_number,
                ];
            }

            $counter->current_number = $nextNumber;
            $counter->updated_by_admin_id = $adminId;
            $counter->save();

            $ticket->status = 'called';
            $ticket->called_at = now();
            $ticket->save();

            return [
                'ok' => true,
                'current_number' => $counter->current_number,
            ];
        });

        if (!$result['ok']) {
            return response()->json(['success' => false, 'message' => $result['message'], 'data' => $result], 409);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function prev(Request $request)
    {
        $date = $this->resolveDate($request);
        $adminId = $request->user()->id;

        $result = DB::transaction(function () use ($date, $adminId) {
            $counter = QueueCounter::where('date', $date)->lockForUpdate()->first();

            if (!$counter) {
                $counter = QueueCounter::create([
                    'date' => $date,
                    'current_number' => 0,
                    'updated_by_admin_id' => $adminId,
                ]);
            }

            if ($counter->current_number === 0) {
                return [
                    'ok' => false,
                    'message' => 'Already at 0',
                    'current_number' => 0,
                ];
            }

            $counter->current_number = $counter->current_number - 1;
            $counter->updated_by_admin_id = $adminId;
            $counter->save();

            return [
                'ok' => true,
                'current_number' => $counter->current_number,
            ];
        });

        if (!$result['ok']) {
            return response()->json(['success' => false, 'message' => $result['message'], 'data' => $result], 409);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }
}

