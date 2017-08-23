<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 2017-08
 */

namespace Runner\FastdSeeder;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class SeederServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        if ('prod' !== config()->get('environment')) {
            $consoles = config()->get('consoles', []);
            $consoles[] = SeederConsole::class;
            config()->set('consoles', $consoles);
        }
    }
}
