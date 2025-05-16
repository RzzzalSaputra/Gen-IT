<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Set a timeout for the operation
            $timeout = config('mail.timeout', 30); // 30 seconds default
            set_time_limit($timeout);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            // Handle various timeout scenarios
            if (strpos($e->getMessage(), 'timed out') !== false || 
                strpos($e->getMessage(), 'timeout') !== false ||
                strpos($e->getMessage(), 'Connection refused') !== false ||
                strpos($e->getMessage(), 'Could not connect') !== false) {
                
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Koneksi ke server email timeout. Silakan coba lagi dalam beberapa saat.']);
            }
            
            // Handle other SMTP errors
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Illuminate\Support\Facades\Log::error('Password reset error: ' . $e->getMessage());
            
            // Handle other unexpected exceptions
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Terjadi kesalahan saat mengirim email. Silakan coba lagi nanti.']);
        }
    }
}
