<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\StoreRequest;
use App\Http\Requests\Contacts\UpdateRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = ContactResource::collection($request->user()->contacts()->get());

        return view('contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);
        
        $contact = new ContactResource($contact);

        return view('contacts.show', compact('contact'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(StoreRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $contact = Contact::firstOrCreate(
            ['user_id' => $user->id, 'owner_id' => auth()->user()->id],
            ['name' => $request->name]
        );

        return redirect(route('contacts.show', new ContactResource($contact)));
    }

    public function update(UpdateRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);
        
        $contact->update(['name' => $request->name]);

        return response()->json(['contact' => new ContactResource($contact)]);
    }

    public function destroy(Request $request, Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect(route('contacts.index'));
    }
}
