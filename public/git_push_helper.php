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

// 1. Git branch
$results['branch'] = runCmd('git branch --show-current', $repoDir);

// 2. Git status
$results['status_before'] = runCmd('git status --short', $repoDir);

// 3. Git remote check
$results['remote'] = runCmd('git remote -v', $repoDir);

// 4. Set remote if needed
$remoteOut = $results['remote']['stdout'];
if (strpos($remoteOut, 'Sameeralydev/cmslvnewtheme.git') === false) {
    if (empty($remoteOut)) {
        $results['remote_add'] = runCmd("git remote add origin {$targetRepo}", $repoDir);
    } else {
        $results['remote_set'] = runCmd("git remote set-url origin {$targetRepo}", $repoDir);
    }
}

// 5. Git add
$results['add'] = runCmd('git add .', $repoDir);

// 6. Git commit
$results['commit'] = runCmd('git commit -m "Update Chart of Accounts, Details View, and Fee Structure"', $repoDir);

// 7. Git push
$branch = !empty($results['branch']['stdout']) ? $results['branch']['stdout'] : 'main';
$results['push'] = runCmd("git push -u origin {$branch}", $repoDir);

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
