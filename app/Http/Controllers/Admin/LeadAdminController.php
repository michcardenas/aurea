<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query();

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead): View
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead eliminado.');
    }

    public function exportCsv(): StreamedResponse
    {
        $leads = Lead::latest()->get();

        return response()->streamDownload(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Nombre', 'Email', 'Fuente', 'Fecha']);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->source,
                    $lead->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 'leads-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
