<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivreRequest;
use App\Http\Requests\UpdateLivreRequest;
use App\Models\Livre;
use Illuminate\Support\Facades\File;




class LivreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $livres = Livre::all();
        return $this->customJsonResponse("Liste des livres", $livres);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivreRequest $request)
    {
        $livre = new Livre();
        $livre->fill($request->validated());
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $livre->image = $image->store('livres', 'public');
        }
        $livre->save();
        return $this->customJsonResponse("Livre créé avec succès", $livre, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Livre $livre)
    {
        return $this->customJsonResponse("Livre récupéré avec succès", $livre);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livre $livre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivreRequest $request, Livre $livre)
    {
        $livre->fill($request->validated());
        if ($request->hasFile('image')) {

            if (File::exists(public_path("storage/" . $livre->image))) {
                File::delete(public_path($livre->image));
            }
            $image = $request->file('image');
            $livre->image = $image->store('livres', 'public');
        }
        // dd($livre->image);
        if ($livre->quantite > 0) {
            $livre->update(['disponible' => true]);
        }
        $livre->update();
        return $this->customJsonResponse("Livre modifié avec succès", $livre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livre $livre)
    {
        $livre->delete();
        return $this->customJsonResponse("Livre supprimé avec succès", null, 200);
    }
    public function restore($id)
    {
        $livre = Livre::onlyTrashed()->where('id', $id)->first();
        $livre->restore();
        return $this->customJsonResponse("Livre restauré avec succès", $livre);
    }
    public function forceDelete($id)
    {
        $livre = Livre::onlyTrashed()->where('id', $id)->first();
        $livre->forceDelete();
        return $this->customJsonResponse("Livre supprimé définitivement", null, 200);
    }
    public function trashed()
    {
        $livres = Livre::onlyTrashed()->get();
        return $this->customJsonResponse("Livres archivés", $livres);
    }
}
