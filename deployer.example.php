<?php

namespace Deployer;

// Include the Laravel & rsync recipes
require 'contrib/rsync.php';
require 'recipe/laravel.php';

set('application', 'Todo-List');
set('ssh_multiplexing', true); // Speeds up deployments

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/.env',
        '/storage/',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);


// Set up a deployer task to copy secrets to the server.
// Since our secrets are stored in Gitlab, we can access them as env vars.
task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

// Production Server
host('134.209.199.89') // Name of the server
    ->setRemoteUser('root') // SSH user
    ->setDeployPath('/var/www/todolist-app'); // Deploy path

// Staging Server
host('134.209.199.89') // Name of the server
    ->setRemoteUser('root') // SSH user
    ->setDeployPath('/var/www/todolist-staging'); // Deploy path

after('deploy:failed', 'deploy:unlock'); // Unlock after failed deploy

desc('Deploy the application');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync', // Deploy code & built assets
    'deploy:secrets', // Deploy secrets
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    // 'php-fpm:restart',
    'artisan:storage:link', // |
    'artisan:view:cache',   // |
    'artisan:config:cache', // |
    'artisan:optimize',     // | Laravel Specific steps
    'artisan:migrate',      // |
    'artisan:queue:restart', // |
    'deploy:symlink',
    'deploy:unlock',
    'deploy:cleanup',
]);