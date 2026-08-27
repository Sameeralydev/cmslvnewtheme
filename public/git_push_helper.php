<?php

$repoDir = realpath(__DIR__ . '/..');
$targetRepo = 'https://github.com/Sameeralydev/cmslvnewtheme.git';

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

$results = [];

// 1. Git pull --rebase
$results['pull'] = runCmd('git pull --rebase origin main', $repoDir);

// If pull rebase had conflicts or failed, try git pull origin main --no-rebase
if ($results['pull']['exit_code'] !== 0) {
    runCmd('git rebase --abort', $repoDir);
    $results['pull_merge'] = runCmd('git pull origin main --no-rebase -X theirs --allow-unrelated-histories -m "Merge remote changes"', $repoDir);
}

// 2. Git add
$results['add'] = runCmd('git add .', $repoDir);

// 3. Git commit if any changes
$results['commit'] = runCmd('git commit -m "Update Chart of Accounts, Details View, and Fee Structure"', $repoDir);

// 4. Git push
$results['push'] = runCmd('git push -u origin main', $repoDir);

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
