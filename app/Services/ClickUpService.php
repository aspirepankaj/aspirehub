<?php

namespace App\Services;

use App\Modules\CRM\ClickUp\Models\ClickUpFolder;
use App\Modules\CRM\ClickUp\Models\ClickUpSpace;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClickUpService
{
    protected string $baseUrl;
    protected string $apiToken;
    protected string $teamId;

    public function __construct()
    {
        $this->baseUrl  = config('services.clickup.base_url', 'https://api.clickup.com/api/v2');
        $this->apiToken = config('services.clickup.api_token');
        $this->teamId   = config('services.clickup.team_id');
    }

    /**
     * Get HTTP client initialized with ClickUp auth headers
     */
    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiToken,
            'Accept'        => 'application/json',
        ])->timeout(15);
    }

    /**
     * Fetch Spaces from ClickUp API
     */
    public function fetchSpaces(): array
    {
        $url = "{$this->baseUrl}/team/{$this->teamId}/space";
        $response = $this->client()->get($url);

        if ($response->successful()) {
            return $response->json('spaces') ?? [];
        }

        Log::error('ClickUp API Error fetching spaces', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        throw new \Exception("ClickUp API Space Fetch Failed: " . ($response->json('err') ?? $response->body()));
    }

    /**
     * Fetch Folders for a given Space ID from ClickUp API
     */
    public function fetchFolders(string $spaceId): array
    {
        $url = "{$this->baseUrl}/space/{$spaceId}/folder";
        $response = $this->client()->get($url);

        if ($response->successful()) {
            return $response->json('folders') ?? [];
        }

        Log::error("ClickUp API Error fetching folders for space {$spaceId}", [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return [];
    }

    /**
     * Sync all ClickUp Spaces & Folders into Local Database
     */
    public function syncAll(): array
    {
        $now = Carbon::now();
        $syncedSpacesCount = 0;
        $syncedFoldersCount = 0;

        $spaces = $this->fetchSpaces();

        foreach ($spaces as $spaceData) {
            $spaceId = (string) $spaceData['id'];

            // Upsert Space
            ClickUpSpace::updateOrCreate(
                ['id' => $spaceId],
                [
                    'name'      => trim($spaceData['name'] ?? 'Unnamed Space'),
                    'color'     => $spaceData['color'] ?? null,
                    'archived'  => (bool) ($spaceData['archived'] ?? false),
                    'synced_at' => $now,
                ]
            );
            $syncedSpacesCount++;

            // Fetch & Upsert Folders for this space
            $folders = $this->fetchFolders($spaceId);

            foreach ($folders as $folderData) {
                $folderId = (string) $folderData['id'];

                $existingFolder = ClickUpFolder::find($folderId);

                ClickUpFolder::updateOrCreate(
                    ['id' => $folderId],
                    [
                        'clickup_space_id' => $spaceId,
                        'name'             => trim($folderData['name'] ?? 'Unnamed Folder'),
                        'task_count'       => (int) ($folderData['task_count'] ?? 0),
                        'archived'         => (bool) ($folderData['archived'] ?? false),
                        // Preserve existing mapping if already set
                        'client_id'        => $existingFolder->client_id ?? null,
                        'website_id'       => $existingFolder->website_id ?? null,
                        'synced_at'        => $now,
                    ]
                );
                $syncedFoldersCount++;
            }
        }

        return [
            'synced_spaces'  => $syncedSpacesCount,
            'synced_folders' => $syncedFoldersCount,
            'synced_at'      => $now->format('M d, Y H:i:s'),
        ];
    }

    /**
     * Fetch all ClickUp tasks for a specific Client by their assigned folders (Parallel Async Requests)
     */
    public function fetchClientTasks(int $clientId): array
    {
        $folders = ClickUpFolder::where('client_id', $clientId)->get();

        if ($folders->isEmpty()) {
            return [];
        }

        // 1. Fetch Lists for all assigned folders concurrently (Parallel HTTP Pool)
        $listResponses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($folders) {
            foreach ($folders as $folder) {
                $pool->as('folder_' . $folder->id)->withHeaders([
                    'Authorization' => $this->apiToken,
                    'Accept'        => 'application/json',
                ])->timeout(10)->get("{$this->baseUrl}/folder/{$folder->id}/list");
            }
        });

        // Collect all list targets
        $listTargets = [];
        foreach ($folders as $folder) {
            $folderId = (string) $folder->id;
            $res = $listResponses['folder_' . $folderId] ?? null;

            if ($res && $res->successful()) {
                $lists = $res->json('lists') ?? [];
                foreach ($lists as $listData) {
                    $listId = (string) $listData['id'];
                    $listName = trim($listData['name'] ?? 'Tasks List');
                    $listTargets[] = [
                        'list_id'     => $listId,
                        'list_name'   => $listName,
                        'folder_id'   => $folderId,
                        'folder_name' => $folder->name,
                    ];
                }
            }
        }

        if (empty($listTargets)) {
            return [];
        }

        // 2. Fetch Tasks for all lists concurrently (Parallel HTTP Pool)
        $taskResponses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($listTargets) {
            foreach ($listTargets as $target) {
                $pool->as('list_' . $target['list_id'])->withHeaders([
                    'Authorization' => $this->apiToken,
                    'Accept'        => 'application/json',
                ])->timeout(10)->get("{$this->baseUrl}/list/{$target['list_id']}/task", [
                    'include_closed' => 'true',
                    'subtasks'       => 'true',
                ]);
            }
        });

        // 3. Process all task responses
        $allTasks = [];
        foreach ($listTargets as $target) {
            $res = $taskResponses['list_' . $target['list_id']] ?? null;

            if ($res && $res->successful()) {
                $rawTasks = $res->json('tasks') ?? [];

                foreach ($rawTasks as $t) {
                    $statusName = strtolower($t['status']['status'] ?? 'open');
                    $statusType = strtolower($t['status']['type'] ?? '');

                    $assignees = array_map(function ($a) {
                        return [
                            'id'             => $a['id'] ?? null,
                            'username'       => $a['username'] ?? $a['email'] ?? 'User',
                            'email'          => $a['email'] ?? '',
                            'profilePicture' => $a['profilePicture'] ?? null,
                            'initials'       => $a['initials'] ?? null,
                            'color'          => $a['color'] ?? null,
                        ];
                    }, $t['assignees'] ?? []);

                    $allTasks[] = [
                        'id'           => (string) $t['id'],
                        'name'         => trim($t['name'] ?? 'Untitled Task'),
                        'description'  => Str::limit(strip_tags($t['text_content'] ?? $t['description'] ?? ''), 120),
                        'status'       => $t['status']['status'] ?? 'open',
                        'status_color' => $t['status']['color'] ?? '#87909c',
                        'url'          => $t['url'] ?? "https://app.clickup.com/t/{$t['id']}",
                        'assignees'    => $assignees,
                        'folder_id'    => $target['folder_id'],
                        'folder_name'  => $target['folder_name'],
                        'list_id'      => $target['list_id'],
                        'list_name'    => $target['list_name'],
                        'created_timestamp' => isset($t['date_created']) ? (int) $t['date_created'] : 0,
                        'due_date'          => isset($t['due_date']) && $t['due_date'] ? date('M d, Y', (int) ($t['due_date'] / 1000)) : null,
                        'date_created'      => isset($t['date_created']) && $t['date_created'] ? date('M d, Y', (int) ($t['date_created'] / 1000)) : null,
                    ];
                }
            }
        }

        // Group tasks by folder_id, sort each folder's tasks by latest created_timestamp, and cap at max 50 per folder
        $tasksByFolder = [];
        foreach ($allTasks as $task) {
            $fId = $task['folder_id'];
            $tasksByFolder[$fId][] = $task;
        }

        $finalTasks = [];
        foreach ($tasksByFolder as $fId => $fTasks) {
            usort($fTasks, function ($a, $b) {
                return ($b['created_timestamp'] ?? 0) <=> ($a['created_timestamp'] ?? 0);
            });
            $sliced = array_slice($fTasks, 0, 50);
            $finalTasks = array_merge($finalTasks, $sliced);
        }

        // Overall sort by latest created_timestamp
        usort($finalTasks, function ($a, $b) {
            return ($b['created_timestamp'] ?? 0) <=> ($a['created_timestamp'] ?? 0);
        });

        return $finalTasks;
    }
}
