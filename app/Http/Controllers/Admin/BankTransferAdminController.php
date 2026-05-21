<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankTransferSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankTransferAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.bank-transfer.index', [
            'bankName' => BankTransferSetting::get('bank_name', ''),
            'accountHolder' => BankTransferSetting::get('account_holder', ''),
            'clabe' => BankTransferSetting::get('clabe', ''),
            'accountNumber' => BankTransferSetting::get('account_number', ''),
            'referenceInstructions' => BankTransferSetting::get('reference_instructions', 'Usa tu número de pedido como referencia'),
            'additionalNotes' => BankTransferSetting::get('additional_notes', ''),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
            'clabe' => 'required|string|size:18',
            'account_number' => 'nullable|string|max:20',
            'reference_instructions' => 'nullable|string|max:500',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        BankTransferSetting::set('bank_name', $request->input('bank_name'));
        BankTransferSetting::set('account_holder', $request->input('account_holder'));
        BankTransferSetting::set('clabe', $request->input('clabe'));
        BankTransferSetting::set('account_number', $request->input('account_number', ''));
        BankTransferSetting::set('reference_instructions', $request->input('reference_instructions', ''));
        BankTransferSetting::set('additional_notes', $request->input('additional_notes', ''));

        return redirect()->route('admin.bank-transfer.index')
            ->with('success', 'Datos bancarios actualizados correctamente.');
    }
}
