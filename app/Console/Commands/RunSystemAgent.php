<?php

namespace App\Console\Commands;

use App\Jobs\SystemAgent\ProcessReminderJob;
use App\Models\Reminder;
use Illuminate\Console\Command;

class RunSystemAgent extends Command
{
    protected $signature = 'system-agent:run {--reminder-id=} {--all-due}';

    protected $description = 'Run the System Agent for a reminder or all due reminders';

    public function handle()
    {
        $id = $this->option('reminder-id');
        $all = $this->option('all-due');

        if ($id) {
            ProcessReminderJob::dispatch((int) $id);
            $this->info("Dispatched ProcessReminderJob for reminder {$id}");

            return 0;
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

            $this->info('Dispatched due reminders');

            return 0;
        }

        $this->info('Specify --reminder-id or --all-due');

        return 1;
    }
}
