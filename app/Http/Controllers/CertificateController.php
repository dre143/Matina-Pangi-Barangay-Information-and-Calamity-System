<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Resident;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['resident', 'issuer']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('certificate_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('or_number', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $certificates = $query->latest()->paginate(20);

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new certificate
     */
    public function create()
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('certificates.create', compact('residents'));
    }

    /**
     * Store a newly created certificate
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'certificate_type' => 'required|in:barangay_clearance,certificate_of_indigency,certificate_of_residency,business_clearance,good_moral,travel_permit',
            'purpose' => 'required|string|max:500',
            'or_number' => 'nullable|string|max:50',
            'amount_paid' => 'required|numeric|min:0',
            'valid_until' => 'nullable|date|after:today',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['issued_date'] = now();
        $validated['status'] = 'issued';

        $certificate = Certificate::create($validated);

        return redirect()->route('certificates.show', $certificate)
                        ->with('success', 'Certificate issued successfully!');
    }

    /**
     * Display the specified certificate
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['resident.household.purok', 'issuer']);
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Update certificate status
     */
    public function updateStatus(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'status' => 'required|in:issued,claimed,cancelled',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $certificate->update($validated);

        return redirect()->back()
                        ->with('success', 'Certificate status updated successfully!');
    }

    /**
     * Generate PDF for certificate
     */
    public function generatePdf(Certificate $certificate)
    {
        $certificate->load(['resident', 'issuer']);
        
        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'));
        
        return $pdf->download("certificate-{$certificate->certificate_number}.pdf");
    }

    /**
     * Print certificate
     */
    public function print(Certificate $certificate)
    {
        $certificate->load(['resident', 'issuer']);
        return view('certificates.print', compact('certificate'));
    }
}
