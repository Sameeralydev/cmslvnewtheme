<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
}

$repoDir = realpath(__DIR__ . '/..');

function runCmd($cmd, $cwd) {
    $descriptors = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    $process = proc_open($cmd, $descriptors, $pipes, $cwd);
    if (is_resource($process)) {
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $return_value = proc_close($process);
        return [
            'cmd' => $cmd,
            'exit_code' => $return_value,
            'stdout' => trim($stdout),
            'stderr' => trim($stderr)
        ];
    }
    return [
        'cmd' => $cmd,
        'exit_code' => -1,
        'stdout' => '',
        'stderr' => 'Failed to open process'
    ];
}

$log = [];

// 1. Fetch remote
$log['fetch'] = runCmd('git fetch origin main', $repoDir);

// 2. Rebase or pull
$log['rebase'] = runCmd('git rebase origin/main', $repoDir);

// If rebase has issue, abort and pull merge
if ($log['rebase']['exit_code'] !== 0) {
    runCmd('git rebase --abort', $repoDir);
    $log['pull'] = runCmd('git pull origin main --no-rebase -X theirs --allow-unrelated-histories -m "Merge branch main"', $repoDir);
}

// 3. Stage any pending files
$log['add'] = runCmd('git add .', $repoDir);

// 4. Commit if needed
$log['commit'] = runCmd('git commit -m "Update Chart of Accounts, Details View, and Fee Structure"', $repoDir);

// 5. Push to remote
$log['push'] = runCmd('git push origin main', $repoDir);

// 6. Final status & log
$log['final_status'] = runCmd('git status', $repoDir);
$log['recent_commits'] = runCmd('git log -n 3 --oneline', $repoDir);

header('Content-Type: application/json');
echo json_encode($log, JSON_PRETTY_PRINT);
