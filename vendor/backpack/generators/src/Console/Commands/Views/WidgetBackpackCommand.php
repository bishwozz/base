<?php

namespace Backpack\Generators\Console\Commands\Views;

class WidgetBackpackCommand extends PublishOrCreateViewBackpackCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:widget';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:widget {name?} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack widget';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Widget';

    /**
     * View Namespace.
     *
     * @var string
     */
    protected $viewNamespace = 'widgets';

    /**
     * Stub file name.
     *
     * @var string
     */
    protected $stub = 'widget.stub';

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return resource_path("views/vendor/backpack/base/{$this->viewNamespace}/$name.blade.php");
    }
}
