<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\TicketUser;
use App\Models\User; // Import the User model
use App\Models\Comment; // Import the Comment model
use Illuminate\Support\Facades\Auth;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Using View Composer to share data with the 'layouts.nav' view
        View::composer('layouts.nav', function ($view) {
            $currentUser = auth()->user(); // Get the currently authenticated user
            $tickets = collect(); // Initialize tickets collection
            $notificationMessage = null; // Initialize notification message
            $ticketUserId = null; // Initialize ticket user ID
            $commentdate = null; // Initialize comment date
            $newNotification = null; // Initialize new notification variable

            // Fetch tickets based on user role
            if ($currentUser) {
                $tickets = $this->fetchUserTickets($currentUser); // Fetch tickets for the current user
            }

            // Count unread comments for the current user
            $unreadCommentsCount = Comment::where('user_id', $currentUser->id)
                ->whereNull('read_at')
                ->count();

            // Check for a new comment and create a notification message
            if (session()->has('new_comment')) {
                $comment = session()->get('new_comment'); // Get the new comment from the session
                $notificationMessage = $this->getNotificationMessage($comment, $currentUser);
                
                // Clear the session so it's not repeated
                session()->forget('new_comment');

                $ticketUserId = $comment->ticket_user_id; // Get the ticket user ID
                $commentdate = $comment->created_at; // Get the comment creation date

                // Create a new notification for the relevant user based on the comment
                $this->notifyRelevantUser($comment);

                // Set the new notification message
                $newNotification = $notificationMessage; // Use the generated notification message
            }
            
            // Pass the data to the view
            $view->with(compact('tickets', 'newNotification', 'ticketUserId', 'commentdate', 'unreadCommentsCount'));
        });
    }

    // Method to fetch tickets based on the user's role
    protected function fetchUserTickets($currentUser)
    {
        if ($currentUser->role_id == 7) {
            // Fetch tickets added by users with role 8 using eager loading
            return TicketUser::with(['ticket', 'user'])
                ->whereHas('user', function ($query) {
                    $query->where('role_id', 8);
                })
                ->whereNull('read_at') // Only get unread tickets
                ->get();
        } elseif ($currentUser->role_id == 8) {
            // Fetch tickets added by users with role 7 using eager loading
            return TicketUser::with(['ticket', 'user'])
                ->whereHas('user', function ($query) {
                    $query->where('role_id', 7);
                })
                ->whereNull('read_at') // Only get unread tickets
                ->get();
        }
        return collect(); // Return an empty collection if no tickets found
    }

    // Method to create a notification message based on the comment
    protected function getNotificationMessage($comment, $currentUser)
    {
        return 'New update from ' . $comment->user->name; // Return a message string
    }

    // Method to notify the relevant user based on the comment
    protected function notifyRelevantUser($comment)
    {
        // Find the ticket user the comment belongs to
        $ticketUser = TicketUser::find($comment->ticket_user_id);

        if ($ticketUser) {
            // Determine which user to notify based on ticket user role
            if ($ticketUser->user->role_id == 7) {
                // Notify user with role 8
                $userToNotify = User::where('role_id', 8)->first();
            } elseif ($ticketUser->user->role_id == 8) {
                // Notify user with role 7
                $userToNotify = User::where('role_id', 7)->first();
            }

            // Log or handle notification directly instead of using a notification class
            if ($userToNotify) {
                // Check if the comment was made by the current user
                if ($comment->user_id !== $userToNotify->id) {
                    session()->flash('notification', 'New comment on your ticket from ' . $comment->user->name);
                }
            }
        }
    }

    public function register()
    {
        // Register any application services here
    }
}
