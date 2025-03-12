<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\APIHandler\Auth\AuthHandler;
use App\APIHandler\Client\ClientHandler;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, $setting = null): View
    {
        $client_info_response = ClientHandler::getClientCompleteInfo($request);
        $client_info_response = json_decode($client_info_response->getContent());
        $client_info = null;
        if ($client_info_response && $client_info_response->status === self::OK) {
            $client_info = $client_info_response->data;
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'mindbody_user' => $client_info ? $client_info->Client : null,
            'credit_card' => $client_info ? $client_info->Client->ClientCreditCard : null,
            'setting' => $setting,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255'],
            // 'birth_date' => ['required', 'date'],
        ]);

        $response = AuthHandler::updateClientProfile($request->all());
        $response = json_decode($response->getContent());
        if ($response->status != self::OK) {
            return Redirect::back()->withErrors('message', $response->message);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
