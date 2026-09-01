<?php

namespace App\Http\Controllers;

use App\Jobs\SystemAgent\ProcessReminderJob;
use App\Models\Reminder;
use Illuminate\Http\Request;

class SystemAgentController extends Controller
{
    public function run(Request $request)
    {
        $reminderId = $request->input('reminder_id');
        $all = $request->boolean('all_due');

        if ($reminderId) {
            ProcessReminderJob::dispatch((int) $reminderId);

            return response()->json(['status' => 'dispatched', 'reminder_id' => $reminderId]);
        }

        if ($all) {
            $now = now();
            $query = Reminder::where('enabled', true)
                ->where(function ($q) use ($now) {
                    $q->whereNotNull('cron_expression')
                        ->orWhere(function ($q2) use ($now) {
                            $q2->whereNotNull('run_at')->where('run_at', '<=', $now);
                        });
                });

            $query->cursor()->each(function (Reminder $reminder) {
                ProcessReminderJob::dispatch($reminder->id);
            });

            return response()->json(['status' => 'dispatched_all_due']);
        }

        return response()->json(['error' => 'missing reminder_id or all_due flag'], 422);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'run_at' => 'nullable|date',
            'cron_expression' => 'nullable|string',
            'channel' => 'nullable|string',
            'enabled' => 'nullable|boolean',
        ]);

        $reminder = Reminder::create(array_merge($data, ['enabled' => $data['enabled'] ?? false]));

        return response()->json($reminder, 201);
    }

    public function index(Request $request)
    {
        $query = Reminder::query();
        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        return response()->json($query->paginate(25));
    }
}
