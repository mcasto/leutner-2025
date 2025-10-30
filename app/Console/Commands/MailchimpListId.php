<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MailchimpMarketing\ApiClient;

class MailchimpListId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:list-id
                            {--save : Save the first list ID to .env file}
                            {--api-key= : Mailchimp API key}
                            {--server= : Mailchimp server prefix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find Mailchimp List IDs and optionally save to .env';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = $this->getApiKey();
        $server = $this->getServerPrefix();

        if (!$apiKey || !$server) {
            $this->error('❌ Mailchimp credentials not found.');
            $this->info('Please set MAILCHIMP_APIKEY and MAILCHIMP_SERVER in your .env file');
            $this->info('or use --api-key and --server options.');
            return 1;
        }

        $lists = $this->getMailchimpLists($apiKey, $server);

        if (empty($lists)) {
            $this->error('❌ No lists found or unable to connect to Mailchimp.');
            return 1;
        }

        $this->displayLists($lists);

        if ($this->option('save')) {
            $this->saveListIdToEnv($lists);
        }

        return 0;
    }

    /**
     * Get API key from option or .env
     */
    private function getApiKey(): ?string
    {
        return $this->option('api-key') ?: env('MAILCHIMP_KEY');
    }

    /**
     * Get server prefix from option or .env
     */
    private function getServerPrefix(): ?string
    {
        return $this->option('server') ?: env('MAILCHIMP_SERVER');
    }

    /**
     * Fetch lists from Mailchimp
     */
    private function getMailchimpLists(string $apiKey, string $server): array
    {
        $mailchimp = new ApiClient();
        $mailchimp->setConfig([
            'apiKey' => $apiKey,
            'server' => $server,
        ]);

        try {
            $response = $mailchimp->lists->getAllLists();
            return $response->lists;
        } catch (\Exception $e) {
            $this->error("❌ Mailchimp API Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Display lists in a formatted table
     */
    private function displayLists(array $lists): void
    {
        $this->info('🎯 Found ' . count($lists) . ' Mailchimp List(s)');
        $this->line('');

        $tableData = [];
        foreach ($lists as $index => $list) {
            $tableData[] = [
                '#' => $index + 1,
                'Name' => $list->name,
                'ID' => $list->id,
                'Members' => $list->stats->member_count,
                'Created' => $this->formatDate($list->date_created),
            ];
        }

        $this->table(
            ['#', 'List Name', 'List ID', 'Members', 'Created'],
            $tableData
        );

        $this->line('');
        $this->info('💡 To use a list ID in your code:');
        $this->line('   - Copy the "ID" value from above');
        $this->line('   - Add to your .env: MAILCHIMP_LIST_ID=your_list_id_here');
        $this->line('   - Or use the --save option to automatically save the first list');
    }

    /**
     * Save the first list ID to .env file
     */
    private function saveListIdToEnv(array $lists): void
    {
        if (empty($lists)) {
            $this->error('No lists available to save.');
            return;
        }

        $firstList = $lists[0];
        $listId = $firstList->id;
        $listName = $firstList->name;

        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('.env file not found!');
            return;
        }

        $envContent = file_get_contents($envPath);

        // Check if MAILCHIMP_LIST_ID already exists
        if (preg_match('/^MAILCHIMP_LIST_ID=.*/m', $envContent)) {
            // Update existing
            $envContent = preg_replace(
                '/^MAILCHIMP_LIST_ID=.*/m',
                "MAILCHIMP_LIST_ID={$listId}",
                $envContent
            );
            $action = 'updated';
        } else {
            // Add new
            $envContent .= "\nMAILCHIMP_LIST_ID={$listId}";
            $action = 'added';
        }

        if (file_put_contents($envPath, $envContent)) {
            $this->info("✅ Successfully {$action} MAILCHIMP_LIST_ID={$listId} to .env");
            $this->info("   List: {$listName}");

            // Clear config cache
            $this->call('config:clear');
        } else {
            $this->error('❌ Failed to write to .env file');
        }
    }

    /**
     * Format date for display
     */
    private function formatDate(string $date): string
    {
        return \Carbon\Carbon::parse($date)->format('M j, Y');
    }
}
