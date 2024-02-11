<?php
//php artisan make:all Category --path=Admin/

//namespace App\Console\Commands;
namespace Dyalnu\Curdmix\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeAllCommand extends Command
{
    protected $signature = 'make:all {name} {--path=}';

    protected $description = 'Create controller, model, views, and CRUD operations for the given name';

    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->option('path') ?? '';

        // Create controller
        $this->call('make:controller', ['name' => $path . $name.'Controller']);

        // Create model
        $this->call('make:model', ['name' => $path . $name]);

        // Create views folder
        $this->createViewsFolder($path, $name);

        // Create blade templates
        $this->createBladeTemplates($path, $name);

        // Create CRUD methods in controller
        $this->createCRUDMethods($name, $path);

        // Create routes
        $this->createRoutes($name, $path);

        // Create database migration
        $this->createMigration($name);
    }

    protected function createViewsFolder($path, $name)
    {
        $viewsPath = resource_path("views/{$path}{$name}");
        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }
    }

    protected function createBladeTemplates($path, $name)
    {
        $views = ['create', 'edit', 'index', 'show'];
        foreach ($views as $view) {
            $templatePath = resource_path("views/{$path}{$name}/{$view}.blade.php");
            if (!File::exists($templatePath)) {
                File::put($templatePath, 'Template content for ' . $view . ' view');
            }
        }
    }


protected function createCRUDMethods($name, $path)
{
    $controllerPath = app_path("Http/Controllers/{$path}{$name}Controller.php");

    // Read original content of the controller
    $content = file_get_contents($controllerPath);

    // Comments for CRUD operations
    $comments = [
        "// START index",
        "// START create",
        "// START store",
        "// START show",
        "// START edit",
        "// START update",
        "// START destroy",
    ];

    // Check if comments exist in the file
    $hasComments = false;
    foreach ($comments as $comment) {
        if (strpos($content, $comment) !== false) {
            $hasComments = true;
            break;
        }
    }

    // If comments don't exist, add them to the beginning of the file
    if (!$hasComments) {
        // Add comments to the beginning of the file
        $commentsBlock = "";
        foreach ($comments as $comment) {
            $commentsBlock .= "\t\t" . $comment . "\n";
        }
        $newContent = str_replace('{', "{\n\n{$commentsBlock}\n", $content);

        // Save the updated content back to the file
        file_put_contents($controllerPath, $newContent);
    }

    // Check if CRUD methods exist in the file
    $hasCrudMethods = false;
    $crudMethods = [
        $this->getIndexMethod($name),
        $this->getCreateMethod($name),
        $this->getStoreMethod($name),
        $this->getShowMethod($name),
        $this->getEditMethod($name),
        $this->getUpdateMethod($name),
        $this->getDestroyMethod($name),
    ];
    foreach ($crudMethods as $method) {
        if (strpos($content, $method) !== false) {
            $hasCrudMethods = true;
            break;
        }
    }

    // If CRUD methods don't exist, add them to the end of the file
    if (!$hasCrudMethods) {
        // Append CRUD methods to the end of the file
        $newContent = $content . "\n\n" . implode("\n\n", $crudMethods);

        // Save the updated content back to the file
        file_put_contents($controllerPath, $newContent);
    }
}











protected function getIndexMethod($name)
{
    return "
	  public function index()
    {
    // Logic to fetch all {$name}s and pass them to the view
    \${$name}s = {$name}::all();
    return view('{$name}.index', compact('{$name}s'));
	}
";
}

protected function getCreateMethod($name)
{
    return "
    public function create()
    {
        return view('{$name}.create');
    }
";
}

protected function getStoreMethod($name)
{
    return "
    /**
     * Store a newly created {$name} in storage.
     *
     * @param  \\Illuminate\\Http\\Request  \$request
     * @return \\Illuminate\\Http\\RedirectResponse
     */
    public function store(Request \$request)
    {
        \$request->validate([
            // Add validation rules here
        ]);

        {$name}::create(\$request->all());

        return redirect()->route('{$name}.index')
            ->with('success', '{$name} created successfully.');
    }
";
}


protected function getShowMethod($name)
{
    return "
    /**
     * Display the specified {$name}.
     *
     * @param  int  \$id
     * @return \\Illuminate\\Http\\Response
     */
    public function show(\$id)
    {
        \${$name} = {$name}::findOrFail(\$id);
        return view('{$name}.show', compact('{$name}'));
    }
";
}


protected function getEditMethod($name)
{
    return "
    /**
     * Show the form for editing the specified {$name}.
     *
     * @param  int  \$id
     * @return \\Illuminate\\Http\\Response
     */
    public function edit(\$id)
    {
        \${$name} = {$name}::find(\$id);
        return view('{$name}.show', compact('{$name}'));
    }
";
}


protected function getUpdateMethod($name)
{
    return "
    public function update(Request \$request, \$id)
    {
        \$request->validate([
            // Add validation rules here
        ]);

        \$data = \$request->all();
        \${$name} = {$name}::find(\$id);

        if (!\${$name}) {
            return redirect()->route('{$name}.index')
                ->with('error', '{$name} not found.');
        }

        \${$name}->update(\$data);

        return redirect()->route('{$name}.index')
            ->with('success', '{$name} updated successfully.');
    }
";
}


protected function getDestroyMethod($name)
{
    return "
    public function destroy(\$id)
    {
        \$deleted = {$name}::destroy(\$id);

        if(\$deleted) {
            return redirect()->route('{$name}.index')
                ->with('success', '{$name} deleted successfully.');
        } else {
            return redirect()->route('{$name}.index')
                ->with('error', 'Failed to delete {$name}.');
        }
    }
";
}


    protected function createRoutes($name, $path)
    {
        $routeContent = "\nRoute::resource('{$name}', '{$path}{$name}Controller');\n";
        File::append(base_path('routes/web.php'), $routeContent);
    }

    protected function createMigration($name)
    {
        $this->call('make:migration', [
            'name' => "create_{$name}_table",
            '--create' => $name,
        ]);
    }
}
