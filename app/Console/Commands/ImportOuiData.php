<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ImportOuiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:oui';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import IEEE OUI data into the database';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'http://standards-oui.ieee.org/oui/oui.csv';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->body();
            $lines = explode("\n", $data);

            foreach ($lines as $line) {
                $fields = str_getcsv($line);
                $registry = $fields[0] ?? '';
                $assignment = $fields[1] ?? '';
                $orgName = $fields[2] ?? '';
                $orgAddress = $fields[3] ?? '';

                if (!empty($registry) && !empty($assignment) && !empty($orgName) && !empty($orgAddress)) {
                    DB::table('ieee_oui_data')->insert([
                        'registry' => $registry,
                        'assignment' => $assignment,
                        'organization_name' => $orgName,
                        'organization_address' => $orgAddress,
                    ]);
                }
            }

            $this->info('IEEE OUI data imported successfully.');
        } else {
            $this->error('Failed to fetch IEEE OUI data.');
        }

    }
}
