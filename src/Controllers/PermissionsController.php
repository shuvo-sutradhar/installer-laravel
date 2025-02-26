<?php

namespace SdTech\ProjectInstaller\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use SdTech\ProjectInstaller\Helpers\PermissionsChecker;
use Exception;

class PermissionsController extends Controller
{
    protected PermissionsChecker $permissions;
    protected ?string $token;
    protected ?string $envUrl;

    public function __construct(PermissionsChecker $checker)
    {
        $this->permissions = $checker;
        $this->token = config('installer.env_path.env_token');
        $this->envUrl = config('installer.env_path.env_url_path');
    }

    /**
     * Display the permissions check page.
     */
    public function permissions()
    {
        $permissions = $this->permissions->check(config('installer.permissions'));
        return view('vendor.installer.permissions', compact('permissions'));
    }

    public function verify()
    {
        $permissions = $this->permissions->check(config('installer.permissions'));

        if (config('installer.checkPurchaseCode')) {
            return view('vendor.installer.verify', compact('permissions'));
        }
        return redirect()->route('LaravelInstaller::environment');
    }

    public function codeVerifyProcess(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'purchase_code' => 'required|string',
            'profile_name' => 'required|string',
        ], [
            'purchase_code.required' => __('Purchase code field is required.'),
            'profile_name.required' => __('Profile name field is required.'),
        ])->validate();

        if (config('installer.demoPurchaseCodeAllow') && config('installer.demoPurchaseCode') == $validated['purchase_code']) {
            $check = $this->checkDemoPurchaseCode($validated['purchase_code']);
        } else {
            $check = $this->checkEnvatoPurchaseCode($validated['purchase_code']);
        }

        if (!$check['success']) {
            return redirect()->back()->with(['message' => $check['message']]);
        }
        return redirect()->route('LaravelInstaller::environment')->with('message', $check['message']);
    }

    // Check demo purchase code
    private function checkDemoPurchaseCode(string $purchaseCode): array
    {
        return [
            'success' => config('installer.demoPurchaseCode') === $purchaseCode,
            'message' => config('installer.demoPurchaseCode') === $purchaseCode
                ? __('Purchase code verified successfully.')
                : __('Invalid code')
        ];
    }

    // Check Envato purchase code
    private function checkEnvatoPurchaseCode(string $purchaseCode): array
    {
        try {
            $token = $this->token;
            $o = $this->verifyPurchase($purchaseCode, $token);

            if (is_object($o)) {
                $this->verifyMessages($purchaseCode);
                return ['success' => true, 'message' => __('Purchase code verified successfully.')];
            }
            return ['success' => false, 'message' => __('Invalid purchase code or user has not purchased any of your items.')];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function verifyPurchase(string $code, string $token)
    {
        $verify_obj = $this->getPurchaseData($code, $token);

        return ($verify_obj && is_object($verify_obj) && isset($verify_obj->{"verify-purchase"}->item_name))
            ? $verify_obj->{"verify-purchase"}
            : null;
    }

    private function getPurchaseData(string $code, string $token)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-type' => 'application/json',
            ])->get($this->envUrl . $code . '.json', ['code' => $code]);

            return $response->successful() ? $response->object() : false;
        } catch (Exception $e) {
            return false;
        }
    }

    private function verifyMessages(string $envPhraseKey)
    {
        Cookie::queue('addenvparkey', $envPhraseKey);
    }
}
