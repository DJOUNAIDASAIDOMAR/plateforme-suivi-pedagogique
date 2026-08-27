<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Affiche toutes les notifications
     * de l'utilisateur connecté.
     */
    public function index(): View
    {
        $notifications = Notification::where(
            'id_user',
            Auth::id()
        )
            ->orderByDesc('date_notification')
            ->orderByDesc('id_notification')
            ->get();

        return view(
            'notifications.index',
            compact('notifications')
        );
    }


    /**
     * Marque une notification comme lue.
     */
    public function lire(
        int $idNotification
    ): RedirectResponse {

        $notification = Notification::where(
            'id_user',
            Auth::id()
        )
            ->where(
                'id_notification',
                $idNotification
            )
            ->firstOrFail();

        $notification->update([
            'est_lue' => true,
        ]);

        /*
         * Si la notification possède un lien,
         * on redirige vers la page concernée.
         */
        if ($notification->lien) {

            return redirect(
                $notification->lien
            );
        }

        return redirect()
            ->route('notifications.index');
    }


    /**
     * Marque toutes les notifications
     * de l'utilisateur comme lues.
     */
    public function toutLire(): RedirectResponse
    {
        Notification::where(
            'id_user',
            Auth::id()
        )
            ->where(
                'est_lue',
                false
            )
            ->update([
                'est_lue' => true,
            ]);

        return redirect()
            ->route('notifications.index')
            ->with(
                'success',
                'Toutes les notifications ont été marquées comme lues.'
            );
    }


    /**
     * Supprime une notification.
     */
    public function destroy(
        int $idNotification
    ): RedirectResponse {

        $notification = Notification::where(
            'id_user',
            Auth::id()
        )
            ->where(
                'id_notification',
                $idNotification
            )
            ->firstOrFail();

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with(
                'success',
                'La notification a été supprimée.'
            );
    }


    /**
     * Supprime toutes les notifications
     * de l'utilisateur connecté.
     */
    public function destroyAll(): RedirectResponse
    {
        Notification::where(
            'id_user',
            Auth::id()
        )->delete();

        return redirect()
            ->route('notifications.index')
            ->with(
                'success',
                'Toutes les notifications ont été supprimées.'
            );
    }
}