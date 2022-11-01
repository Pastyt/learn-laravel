<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        return view('listings.index', ['listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(4)]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', ['listing' => $listing]);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $form_fields = $request->validate(
            [
                'title'    => 'required',
                'company'  => ['required', Rule::unique('listings', 'company')],
                'location' => 'required',
                'website' => 'required',
                'email' => ['required','email'],
                'tags' => 'required',
                'description' => 'required',
            ]
        );

        if ($request->hasFile('logo')) {
            $form_fields['logo'] = $request->file('logo')?->store('logos','public');
        }

        $form_fields['user_id'] = auth()->id();

        Listing::create($form_fields);

        return redirect('/')->with('message','Listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit',['listing' => $listing]);
    }

    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        $form_fields = $request->validate(
            [
                'title'    => 'required',
                'company'  => 'required',
                'location' => 'required',
                'website' => 'required',
                'email' => ['required','email'],
                'tags' => 'required',
                'description' => 'required',
            ]
        );

        if ($request->hasFile('logo')) {
            $form_fields['logo'] = $request->file('logo')?->store('logos','public');
        }

        $listing->update($form_fields);

        return back()->with('message','Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        $listing->delete();
        return redirect('/')->with('message','Listing deleted successfully!');
    }

    public function manage()
    {
        return view('listings.manage',['listings' => auth()->user()->listings()->get()]);
    }
}
