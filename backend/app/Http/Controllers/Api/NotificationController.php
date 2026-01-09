<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Subscribe to push notifications
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $user = $request->user();
        
        // Store subscription in user's push_subscriptions
        $subscriptions = json_decode($user->push_subscriptions ?? '[]', true);
        
        // Check if already subscribed
        $exists = collect($subscriptions)->contains('endpoint', $validated['endpoint']);
        
        if (!$exists) {
            $subscriptions[] = [
                'endpoint' => $validated['endpoint'],
                'keys' => $validated['keys'],
                'created_at' => now()->toISOString(),
            ];
            
            $user->update(['push_subscriptions' => json_encode($subscriptions)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to push notifications',
        ]);
    }

    /**
     * Unsubscribe from push notifications
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $user = $request->user();
        $subscriptions = json_decode($user->push_subscriptions ?? '[]', true);
        
        // Remove subscription
        $subscriptions = array_filter($subscriptions, function ($sub) use ($validated) {
            return $sub['endpoint'] !== $validated['endpoint'];
        });
        
        $user->update(['push_subscriptions' => json_encode(array_values($subscriptions))]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully unsubscribed from push notifications',
        ]);
    }

    /**
     * Send push notification to user
     */
    public function sendPush(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'url' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $user = User::find($validated['user_id']);
        $subscriptions = json_decode($user->push_subscriptions ?? '[]', true);

        if (empty($subscriptions)) {
            return response()->json([
                'success' => false,
                'message' => 'User has no push subscriptions',
            ], 400);
        }

        $payload = json_encode([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'url' => $validated['url'] ?? '/',
            'icon' => $validated['icon'] ?? '/logo-bunda-paud.png',
        ]);

        $sent = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $this->sendWebPush($subscription, $payload);
                $sent++;
            } catch (\Exception $e) {
                Log::error('Push notification failed', [
                    'endpoint' => $subscription['endpoint'],
                    'error' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Push notifications sent: {$sent} success, {$failed} failed",
            'data' => ['sent' => $sent, 'failed' => $failed],
        ]);
    }

    /**
     * Send email notification
     */
    public function sendEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'template' => 'nullable|string|in:default,notification,alert',
        ]);

        try {
            Mail::raw($validated['body'], function ($message) use ($validated) {
                $message->to($validated['to'])
                    ->subject($validated['subject'])
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Email notification failed', [
                'to' => $validated['to'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send notification to all admins
     */
    public function notifyAdmins(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:push,email,both',
        ]);

        $admins = User::whereHas('role', function ($query) {
            $query->whereIn('code', ['super_admin', 'admin']);
        })->where('is_active', true)->get();

        $results = ['push' => 0, 'email' => 0];

        foreach ($admins as $admin) {
            if (in_array($validated['type'], ['push', 'both'])) {
                $subscriptions = json_decode($admin->push_subscriptions ?? '[]', true);
                foreach ($subscriptions as $subscription) {
                    try {
                        $this->sendWebPush($subscription, json_encode([
                            'title' => $validated['title'],
                            'body' => $validated['body'],
                        ]));
                        $results['push']++;
                    } catch (\Exception $e) {
                        // Continue on error
                    }
                }
            }

            if (in_array($validated['type'], ['email', 'both'])) {
                try {
                    Mail::raw($validated['body'], function ($message) use ($admin, $validated) {
                        $message->to($admin->email)
                            ->subject($validated['title']);
                    });
                    $results['email']++;
                } catch (\Exception $e) {
                    // Continue on error
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin notifications sent',
            'data' => $results,
        ]);
    }

    /**
     * Get notification settings for current user
     */
    public function getSettings(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'push_enabled' => !empty(json_decode($user->push_subscriptions ?? '[]', true)),
                'email_enabled' => $user->email_notifications ?? true,
                'subscriptions_count' => count(json_decode($user->push_subscriptions ?? '[]', true)),
            ],
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email_enabled' => 'boolean',
        ]);

        $request->user()->update([
            'email_notifications' => $validated['email_enabled'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated',
        ]);
    }

    /**
     * Send Web Push notification
     */
    private function sendWebPush(array $subscription, string $payload): void
    {
        $vapidPublicKey = config('services.webpush.public_key');
        $vapidPrivateKey = config('services.webpush.private_key');

        if (!$vapidPublicKey || !$vapidPrivateKey) {
            throw new \Exception('VAPID keys not configured');
        }

        // In production, use a library like minishlink/web-push
        // This is a placeholder implementation
        Log::info('Would send web push', [
            'endpoint' => $subscription['endpoint'],
            'payload' => $payload,
        ]);
    }
}
