<?php
namespace App\Http\Controllers;
use DB;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class InstallationController extends Controller
{
    public function installations(){
        try {
            // Attempt to establish a database connection
            DB::connection()->getPdo();

            // If the connection is successful, redirect to the home page
            return redirect(route('home'));
        } catch (\Exception $e) {
            // If an exception occurs (indicating a database connection error),
            // proceed to the installation page
            $title = "Installations";
            return view('installations.install', compact('title'));
        }
    }

    public function showFinal()
    {
        return view('installations.installationsFinal');
    }

    public function migrateTables(Request $request)
    {
        // Run the migration command
        Artisan::call('migrate');

        return redirect()->route('home'); // Redirect to the home page or any other appropriate page
    }

    public function installationPost(Request $request){
        // Validate form data
        $validatedData = $request->validate([
            'hostname' => 'required|string',
            'dbport' => 'required|string',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'database_name' => 'required|string',
        ]);

        // Set up database configuration
        $envData = [
            'DB_HOST' => $validatedData['hostname'],
            'DB_PORT' => $validatedData['dbport'],
            'DB_DATABASE' => $validatedData['database_name'],
            'DB_USERNAME' => $validatedData['username'],
            'DB_PASSWORD' => $validatedData['password'],
        ];

        // Write database configuration to .env file
        foreach ($envData as $key => $value) {
            if ($value) {
                // Update or create key in .env file
                file_put_contents(base_path('.env'), $key.'='.$value.PHP_EOL , FILE_APPEND | LOCK_EX);
            }
        }

        // Reload the environment variables
        $file = base_path('.env');

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    putenv("$key=$value");
                }
            }
        }

        // Run migration and seed
        $this->migrateAndSeed();

        // If the migration and seeding are complete, redirect to the final installation page
        return redirect(route('final'));
    }


    protected function migrateAndSeed() {
        try {
            // Migrate database
            Artisan::call('migrate');

            // Seed database
            Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function installationFinal()
    {
        // Now render the final installation view
        $title = 'Installation Successful';
        return view('installations.final', compact('title'));
    }


    public function installationFinalPost(Request $request)
{
    try {
        // Attempt to run migrations to create necessary database tables
        Artisan::call('migrate');

        // Attempt to seed database
        Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CountryTableSeeder']);

        // If migration and seeding are successful, redirect to final page
        return redirect()->route('home')->with('success', 'Installation completed successfully!');
    } catch (\Exception $e) {
        // If an error occurs during migration or seeding, redirect back with error message
        return redirect()->route('home')->with('error', 'An error occurred during installation: ' . $e->getMessage());
    }
}


}
