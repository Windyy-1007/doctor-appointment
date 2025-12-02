<?php

class AdminController extends Controller
{
    // Example admin-only route
    public function dashboard(): void
    {
        Auth::requireRole(['admin']);
        $this->render('admin/dashboard');
    }
}
