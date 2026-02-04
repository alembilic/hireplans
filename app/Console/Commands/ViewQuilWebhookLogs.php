<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewQuilWebhookLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quil:logs 
                            {--lines=50 : Number of lines to display}
                            {--tail : Continuously monitor the log file}
                            {--filter= : Filter logs by keyword}
                            {--level= : Filter by log level (info, debug, warning, error)}
                            {--today : Show only today\'s logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View and monitor Quil webhook logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs/quil-webhooks.log');
        
        // Get today's log file if --today flag is used
        if ($this->option('today')) {
            $date = now()->format('Y-m-d');
            $logPath = storage_path("logs/quil-webhooks-{$date}.log");
        }

        if (!File::exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            $this->info('No Quil webhook logs exist yet.');
            return 1;
        }

        if ($this->option('tail')) {
            $this->info('Monitoring Quil webhook logs (press Ctrl+C to stop)...');
            $this->info('Log file: ' . $logPath);
            $this->newLine();
            
            // Use tail command to monitor the file
            $command = "tail -f " . escapeshellarg($logPath);
            
            if ($filter = $this->option('filter')) {
                $command .= " | grep " . escapeshellarg($filter);
            }
            
            passthru($command);
        } else {
            $this->displayLogs($logPath);
        }

        return 0;
    }

    /**
     * Display logs with formatting.
     */
    private function displayLogs(string $logPath): void
    {
        $lines = $this->option('lines');
        $filter = $this->option('filter');
        $level = $this->option('level');

        // Read last N lines
        $content = shell_exec("tail -n {$lines} " . escapeshellarg($logPath));
        
        if (empty($content)) {
            $this->warn('Log file is empty.');
            return;
        }

        $logLines = explode("\n", trim($content));
        
        // Apply filters
        if ($filter) {
            $logLines = array_filter($logLines, fn($line) => 
                stripos($line, $filter) !== false
            );
        }

        if ($level) {
            $logLines = array_filter($logLines, fn($line) => 
                stripos($line, strtoupper($level)) !== false
            );
        }

        // Display header
        $this->info('=== QUIL WEBHOOK LOGS ===');
        $this->info('File: ' . $logPath);
        $this->info('Lines: ' . count($logLines));
        $this->newLine();

        // Display logs with color coding
        foreach ($logLines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            // Color code based on log level
            if (stripos($line, 'ERROR') !== false) {
                $this->error($line);
            } elseif (stripos($line, 'WARNING') !== false) {
                $this->warn($line);
            } elseif (stripos($line, 'INFO') !== false) {
                $this->info($line);
            } elseif (stripos($line, 'DEBUG') !== false) {
                $this->comment($line);
            } else {
                $this->line($line);
            }
        }

        // Display summary
        $this->newLine();
        $this->displaySummary($logLines);
    }

    /**
     * Display summary statistics.
     */
    private function displaySummary(array $logLines): void
    {
        $stats = [
            'total' => count($logLines),
            'errors' => 0,
            'warnings' => 0,
            'info' => 0,
            'successful' => 0,
            'failed' => 0,
            'duplicates' => 0,
        ];

        foreach ($logLines as $line) {
            if (stripos($line, 'ERROR') !== false) $stats['errors']++;
            if (stripos($line, 'WARNING') !== false) $stats['warnings']++;
            if (stripos($line, 'INFO') !== false) $stats['info']++;
            if (stripos($line, 'completed successfully') !== false) $stats['successful']++;
            if (stripos($line, 'processing failed') !== false) $stats['failed']++;
            if (stripos($line, 'Duplicate webhook') !== false) $stats['duplicates']++;
        }

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Lines', $stats['total']],
                ['Successful Processing', $stats['successful']],
                ['Failed Processing', $stats['failed']],
                ['Duplicate Webhooks', $stats['duplicates']],
                ['Errors', $stats['errors']],
                ['Warnings', $stats['warnings']],
            ]
        );

        // Tips
        $this->newLine();
        $this->info('💡 Useful commands:');
        $this->line('  • View last 100 lines: php artisan quil:logs --lines=100');
        $this->line('  • Monitor in real-time: php artisan quil:logs --tail');
        $this->line('  • Filter by keyword: php artisan quil:logs --filter="error"');
        $this->line('  • Show only errors: php artisan quil:logs --level=error');
        $this->line('  • Today\'s logs only: php artisan quil:logs --today');
    }
}
