<?php
namespace Deployer;
require 'recipe/laravel.php';

// Config
set('current_path', '{{deploy_path}}/current');
set('repository', 'git@github.com:ambivalent-axiom/goodday-assig.git');
set('git_tty', true);
set('keep_releases', 3);

// Shared files/dirs between deploys
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('production')
    ->setHostname('dev.ambax.io')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '/var/www/html/goodday')
    ->set('branch', 'main')
    ->set('env_file', '.env.production')
    ->set('shared_files', ['.env']);

task('env:copy', function() {
    $envFile = get('env_file');
    writeln("Using env file: " . $envFile);
    run("cp /home/deployer/{$envFile} {{release_path}}/.env");
    writeln("Environment file copied successfully");
});

task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

// Custom task for artisan commands
task('artisan:optimize', function () {
    cd('{{release_path}}');
    run('php artisan optimize');
});

// Make sure environment is set up before caching config
before('artisan:config:cache', 'env:copy');

// Hooks
after('deploy:failed', 'deploy:unlock');
after('deploy:vendors', 'build');

// Main deploy task list
desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:migrate',
    'artisan:optimize',
    'deploy:publish',
]);
