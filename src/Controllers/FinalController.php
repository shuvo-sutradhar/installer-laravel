<?php

namespace Codeshaper\ProjectInstaller\Controllers;

use Illuminate\Routing\Controller;
use Codeshaper\ProjectInstaller\Events\LaravelInstallerFinished;
use Codeshaper\ProjectInstaller\Helpers\EnvironmentManager;
use Codeshaper\ProjectInstaller\Helpers\FinalInstallManager;
use Codeshaper\ProjectInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    function __construct()
    {
        set_time_limit(300);
    }

    /**
     * Update installed file and display finished view.
     *
     * @param \Codeshaper\ProjectInstaller\Helpers\InstalledFileManager $fileManager
     * @param \Codeshaper\ProjectInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \Codeshaper\ProjectInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
