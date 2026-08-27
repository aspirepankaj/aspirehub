<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:websites {--dry-run : Preview imports without saving to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import client websites from WHMCS tblclients and tblhosting SQL dumps';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN MODE: No changes will be written to the database.');
        }

        $clientsFile = base_path('firstdb/tblclients.sql');
        $hostingFile = base_path('firstdb/tblhosting.sql');
        $productsFile = base_path('firstdb/tblproducts.sql');

        if (!file_exists($clientsFile)) {
            $this->error("Clients SQL dump not found at: {$clientsFile}");
            return Command::FAILURE;
        }

        if (!file_exists($hostingFile)) {
            $this->error("Hosting SQL dump not found at: {$hostingFile}");
            return Command::FAILURE;
        }

        if (!file_exists($productsFile)) {
            $this->error("Products SQL dump not found at: {$productsFile}");
            return Command::FAILURE;
        }

        // Phase 1: Parse tblproducts.sql to map product details
        $this->info('Phase 1: Parsing tblproducts.sql...');
        $products = []; // id => [name, color]
        
        $productsHandle = fopen($productsFile, 'r');
        $productCols = [];
        $prodIdIdx = null;
        $prodNameIdx = null;
        $prodColorIdx = null;

        while (($line = fgets($productsHandle)) !== false) {
            $line = trim($line);

            if (preg_match('/INSERT INTO `?tblproducts`?\s*\(([^)]+)\)\s*VALUES/i', $line, $matches)) {
                $productCols = array_map(fn($col) => trim(str_replace('`', '', $col)), explode(',', $matches[1]));
                $prodIdIdx = array_search('id', $productCols);
                $prodNameIdx = array_search('name', $productCols);
                $prodColorIdx = array_search('color', $productCols);
                continue;
            }

            if (str_starts_with($line, '(')) {
                $values = $this->parseSqlValues($line);
                if ($values) {
                    $id = intval($values[$prodIdIdx !== false ? $prodIdIdx : 0] ?? 0);
                    $name = trim($values[$prodNameIdx !== false ? $prodNameIdx : 3] ?? '');
                    $color = trim($values[$prodColorIdx !== false ? $prodColorIdx : 95] ?? '');

                    if ($id && $name) {
                        $products[$id] = [
                            'name' => html_entity_decode($name, ENT_QUOTES | ENT_HTML5),
                            'color' => !empty($color) ? $color : $this->getRandomColor()
                        ];
                    }
                }
            }
        }
        fclose($productsHandle);
        $this->info('Parsed ' . count($products) . ' products from tblproducts.sql.');

        // Truncate tables before inserting if not a dry-run
        if (!$dryRun) {
            $this->info('Truncating old websites, pivot, and service types tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('adspv_website_plan')->truncate();
            DB::table('adspv_website_service_type')->truncate();
            DB::table('adspv_websites')->truncate();
            DB::table('adspv_service_types')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('Tables truncated successfully.');

            // Insert parsed products as Service Types
            $this->info('Inserting products into service_types...');
            foreach ($products as $id => $data) {
                DB::table('adspv_service_types')->insert([
                    'id' => $id,
                    'name' => $data['name'],
                    'color' => $data['color'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            $this->info('Inserted ' . count($products) . ' service types.');
        }

        // Phase 2: Parse tblclients.sql to map WHMCS Client ID to Email
        $this->info('Phase 2: Parsing tblclients.sql...');
        $whmcsClients = []; // whmcs_id => email
        
        $clientsHandle = fopen($clientsFile, 'r');
        $clientCols = [];
        $emailIdx = null;
        $idIdx = null;

        while (($line = fgets($clientsHandle)) !== false) {
            $line = trim($line);
            
            if (preg_match('/INSERT INTO `?tblclients`?\s*\(([^)]+)\)\s*VALUES/i', $line, $matches)) {
                $clientCols = array_map(fn($col) => trim(str_replace('`', '', $col)), explode(',', $matches[1]));
                $emailIdx = array_search('email', $clientCols);
                $idIdx = array_search('id', $clientCols);
                continue;
            }

            if (str_starts_with($line, '(')) {
                $values = $this->parseSqlValues($line);
                if ($values) {
                    $id = $values[$idIdx !== false ? $idIdx : 0] ?? null;
                    $email = $values[$emailIdx !== false ? $emailIdx : 5] ?? null;
                    
                    if ($id && $email) {
                        $whmcsClients[intval($id)] = trim(strtolower($email));
                    }
                }
            }
        }
        fclose($clientsHandle);
        $this->info('Parsed ' . count($whmcsClients) . ' clients from tblclients.sql.');

        // Phase 3: Map WHMCS client IDs to local client IDs
        $this->info('Phase 3: Mapping WHMCS clients to local database...');
        $mappedClients = []; // whmcs_id => local_client_id
        $unmappedEmails = [];

        foreach ($whmcsClients as $whmcsId => $email) {
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
            if ($user) {
                $client = Client::where('user_id', $user->id)->first();
                if ($client) {
                    $mappedClients[$whmcsId] = $client->id;
                } else {
                    $unmappedEmails[] = "{$email} (User exists but not as Client)";
                }
            } else {
                $unmappedEmails[] = "{$email} (User email not found in DB)";
            }
        }

        $this->info('Successfully mapped ' . count($mappedClients) . ' clients.');
        if (count($unmappedEmails) > 0) {
            $this->warn('Could not map ' . count($unmappedEmails) . ' client emails.');
        }

        // Phase 4: Parse tblhosting.sql and extract websites & service type pivots
        $this->info('Phase 4: Parsing tblhosting.sql and extracting website profiles...');
        
        $hostingHandle = fopen($hostingFile, 'r');
        $hostingCols = [];
        
        $userIdIdx = null;
        $domainIdx = null;
        $regdateIdx = null;
        $statusIdx = null;
        $usernameIdx = null;
        $passwordIdx = null;
        $notesIdx = null;
        $packageIdIdx = null;

        $stats = [
            'total_rows_parsed' => 0,
            'ignored_empty_domain' => 0,
            'ignored_unmapped_client' => 0,
            'websites_created' => 0,
            'websites_updated' => 0,
            'pivots_created' => 0,
        ];

        // Track created website IDs for duplicates: [local_client_id][normalized_domain] => local_website_id
        $createdWebsites = [];

        while (($line = fgets($hostingHandle)) !== false) {
            $line = trim($line);

            if (preg_match('/INSERT INTO `?tblhosting`?\s*\(([^)]+)\)\s*VALUES/i', $line, $matches)) {
                $hostingCols = array_map(fn($col) => trim(str_replace('`', '', $col)), explode(',', $matches[1]));
                
                $userIdIdx = array_search('userid', $hostingCols);
                $domainIdx = array_search('domain', $hostingCols);
                $regdateIdx = array_search('regdate', $hostingCols);
                $statusIdx = array_search('domainstatus', $hostingCols);
                $usernameIdx = array_search('username', $hostingCols);
                $passwordIdx = array_search('password', $hostingCols);
                $notesIdx = array_search('notes', $hostingCols);
                $packageIdIdx = array_search('packageid', $hostingCols);
                continue;
            }

            if (str_starts_with($line, '(')) {
                $values = $this->parseSqlValues($line);
                if ($values) {
                    $stats['total_rows_parsed']++;

                    $whmcsUserId = intval($values[$userIdIdx !== false ? $userIdIdx : 1] ?? 0);
                    $rawDomain = trim($values[$domainIdx !== false ? $domainIdx : 6] ?? '');
                    $regdate = trim($values[$regdateIdx !== false ? $regdateIdx : 5] ?? '');
                    $domainstatus = trim($values[$statusIdx !== false ? $statusIdx : 16] ?? 'Active');
                    $username = trim($values[$usernameIdx !== false ? $usernameIdx : 17] ?? '');
                    $password = trim($values[$passwordIdx !== false ? $passwordIdx : 18] ?? '');
                    $notes = trim($values[$notesIdx !== false ? $notesIdx : 19] ?? '');
                    $packageId = intval($values[$packageIdIdx !== false ? $packageIdIdx : 3] ?? 0);

                    // Skip empty or invalid domains
                    if (empty($rawDomain) || !str_contains($rawDomain, '.')) {
                        $stats['ignored_empty_domain']++;
                        continue;
                    }

                    // Skip unmapped clients
                    if (!isset($mappedClients[$whmcsUserId])) {
                        $stats['ignored_unmapped_client']++;
                        continue;
                    }

                    $localClientId = $mappedClients[$whmcsUserId];

                    // Clean and normalize domain name
                    $domain = rtrim(trim($rawDomain), '/');
                    $normalized = str_replace(['http://', 'https://', 'www.'], '', $domain);
                    $normalized = strtolower($normalized);

                    if (empty($normalized) || str_contains($normalized, ' ') || str_starts_with($normalized, '.')) {
                        $stats['ignored_empty_domain']++;
                        continue;
                    }

                    // Determine standard status (Active vs Inactive)
                    $mappedStatus = in_array(strtolower($domainstatus), ['active', 'completed']) ? 'Active' : 'Inactive';

                    // Generate valid URL format
                    $url = $domain;
                    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $url = 'https://' . $url;
                    }

                    // Generate clean Site Name
                    $siteName = ucfirst(explode('.', $normalized)[0] ?? 'Website');

                    // Regdate parse
                    $createdAt = now();
                    if (!empty($regdate) && $regdate !== '0000-00-00') {
                        try {
                            $createdAt = \Carbon\Carbon::parse($regdate);
                        } catch (\Exception $e) {
                            // fallback
                        }
                    }

                    $websiteId = null;

                    // If website doesn't exist yet, insert
                    if (!isset($createdWebsites[$localClientId][$normalized])) {
                        $stats['websites_created']++;

                        if ($dryRun) {
                            $this->line("Would create Website: [Client: {$localClientId}] {$siteName} | URL: {$url} | Status: {$mappedStatus}");
                            $websiteId = 'mock_id_' . $stats['websites_created'];
                        } else {
                            $newWebsite = Website::create([
                                'client_id' => $localClientId,
                                'site_name' => $siteName,
                                'url' => $url,
                                'status' => $mappedStatus,
                                'admin_username' => !empty($username) ? $username : null,
                                'admin_password' => !empty($password) ? $password : null, // will be auto-encrypted
                                'notes' => !empty($notes) ? $notes : null,
                                'added_by' => 1,
                                'created_at' => $createdAt,
                                'updated_at' => $createdAt
                            ]);
                            $websiteId = $newWebsite->id;
                        }
                        $createdWebsites[$localClientId][$normalized] = $websiteId;
                    } else {
                        // Website already exists, fetch ID and optionally merge credentials/notes
                        $websiteId = $createdWebsites[$localClientId][$normalized];
                        $stats['websites_updated']++;

                        if (!$dryRun) {
                            $existingWeb = Website::find($websiteId);
                            if ($existingWeb) {
                                $updatedData = [];
                                if (empty($existingWeb->admin_username) && !empty($username)) {
                                    $updatedData['admin_username'] = $username;
                                }
                                if (empty($existingWeb->admin_password) && !empty($password)) {
                                    $updatedData['admin_password'] = $password;
                                }
                                if (empty($existingWeb->notes) && !empty($notes)) {
                                    $updatedData['notes'] = $notes;
                                }
                                // If current row is active, ensure website is marked Active
                                if ($existingWeb->status !== 'Active' && $mappedStatus === 'Active') {
                                    $updatedData['status'] = 'Active';
                                }
                                if (!empty($updatedData)) {
                                    $existingWeb->update($updatedData);
                                }
                            }
                        }
                    }

                    // Map Service Type (Product Package) pivot relation
                    if ($packageId && isset($products[$packageId])) {
                        $stats['pivots_created']++;

                        if ($dryRun) {
                            $this->line("  -> Would associate Service Type ID: {$packageId} ({$products[$packageId]['name']})");
                        } else {
                            // Check if relationship already exists
                            $pivotExists = DB::table('adspv_website_service_type')
                                ->where('website_id', $websiteId)
                                ->where('service_type_id', $packageId)
                                ->exists();

                            if (!$pivotExists) {
                                DB::table('adspv_website_service_type')->insert([
                                    'website_id' => $websiteId,
                                    'service_type_id' => $packageId,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }
            }
        }
        fclose($hostingHandle);

        $this->info("\n--- Import Summary ---");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total hosting rows parsed', $stats['total_rows_parsed']],
                ['Ignored (empty / invalid domains)', $stats['ignored_empty_domain']],
                ['Ignored (unmapped client ID)', $stats['ignored_unmapped_client']],
                [$dryRun ? 'Websites to create' : 'Websites created successfully', $stats['websites_created']],
                ['Websites mapped with additional rows', $stats['websites_updated']],
                [$dryRun ? 'Pivots to associate' : 'Service Type pivots associated', $stats['pivots_created']]
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Generates a modern curated HSL/Hex color for services.
     */
    protected function getRandomColor()
    {
        $colors = ['#4f46e5', '#10b981', '#f59e0b', '#0ea5e9', '#8b5cf6', '#f43f5e', '#14b8a6', '#d946ef'];
        return $colors[array_rand($colors)];
    }

    /**
     * Parses a line of SQL values handling strings, quotes, escapes, and commas.
     */
    protected function parseSqlValues($line)
    {
        $line = trim($line);
        if (str_starts_with($line, '(')) {
            $line = rtrim($line, ',;');
            $line = substr($line, 1, -1); // remove outer ( and )
            
            $values = [];
            $current = '';
            $inQuote = false;
            $escaped = false;
            $len = strlen($line);
            
            for ($i = 0; $i < $len; $i++) {
                $char = $line[$i];
                if ($escaped) {
                    $current .= $char;
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === '\'') {
                    $inQuote = !$inQuote;
                } elseif ($char === ',' && !$inQuote) {
                    $values[] = trim($current);
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
            $values[] = trim($current);
            
            return array_map(function($val) {
                $val = trim($val);
                if (str_starts_with($val, "'") && str_ends_with($val, "'")) {
                    $val = substr($val, 1, -1);
                }
                return str_replace(["\\'", "\\\""], ["'", "\""], $val);
            }, $values);
        }
        return null;
    }
}
