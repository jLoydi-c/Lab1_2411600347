<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    // marks one notification as read and goes to that product
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('products.show', $notification->data['product_id']);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}