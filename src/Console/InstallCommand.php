<?php

namespace Jagat\Jax\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jaxadmin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Jax for use';

    /**
     * Execute the console command.
     *
     * @author jagat<jagat.kc34@gmail.com>
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'SMartins\PassportMultiauth\Providers\MultiauthServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Jagat\Jax\Providers\JaxServiceProvider']);
    }
}
