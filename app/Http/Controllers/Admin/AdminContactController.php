<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminContactController extends Controller
{
    /*
     * List all contacts
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): \Inertia\Response
    {
        $contacts = Contact::query();

        if (!$request->has('created_at')) {
            $contacts->orderBy('created_at', 'desc');
        }

        if ($request->has('search') && $request->search !== null) {
            $contacts->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $contacts->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('subject') && $request->subject !== null) {
            $contacts->orderBy('subject', $request->subject === 'desc' ? 'desc' : 'asc');
        }

        $contacts = $contacts->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Contact/List', [
            'contacts' => $contacts->items(),
            'pagination' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'per_page' => $contacts->perPage(),
                'total' => $contacts->total(),
            ],
            'filters' => $request->all(),
        ]);
    }

    /*
     * Show a contact
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id): \Inertia\Response
    {
        $contact = Contact::where('id', $id)->firstOrFail();

        return Inertia::render('Admin/Contact/Show', [
            'contact' => $contact,
        ]);
    }
}
