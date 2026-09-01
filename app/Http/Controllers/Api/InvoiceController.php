<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->invoices()->with(['currency', 'items'])->orderBy('issued_at', 'desc')->paginate(15)
        );
    }
}
