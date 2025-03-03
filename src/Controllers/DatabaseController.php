<?php

namespace Codeshaper\ProjectInstaller\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Ensure this is the correct namespace for your User model
use Codeshaper\ProjectInstaller\Helpers\DatabaseManager;
use Codeshaper\ProjectInstaller\Events\AddingInstallerSuperAdmin;

class DatabaseController extends Controller
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     */
    public function database(Request $request)
    {
        $response = $this->databaseManager->migrateAndSeed();

        if ($response['status'] === 'error') {
            return redirect()->route('LaravelInstaller::environmentWizard')
                ->with(['message' => $response]);
        }

        // Run passport install (if required)
        // $this->databaseManager->passportInstall();

        // Create Super Admin User
        User::create([
            'name' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'can_login' => 1,
            'type' => 'admin',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('LaravelInstaller::final')
            ->with(['message' => $response]);
    }
}
