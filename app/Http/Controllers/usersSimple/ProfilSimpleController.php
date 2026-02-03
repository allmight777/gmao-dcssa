<?php

namespace App\Http\Controllers\usersSimple;

use App\Http\Controllers\Controller;
use App\Models\Localisation;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilSimpleController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur
     */
    public function view()
    {
        $utilisateur = Auth::user();
        $utilisateur->load('service', 'profil');

        // Récupérer les statistiques de l'utilisateur
        // CORRECTION : Utiliser ID_Demandeur au lieu de demandeur_id
        $demandesTotal = \App\Models\DemandeIntervention::where('ID_Demandeur', $utilisateur->id)->count();
        $demandesEnCours = \App\Models\DemandeIntervention::where('ID_Demandeur', $utilisateur->id)
            ->whereIn('Statut', ['en_attente', 'validee']) // Correction : Statut au lieu de Etat_Demande
            ->count();

        $equipementsTotal = \App\Models\Equipement::where('service_responsable_id', $utilisateur->service_id)
            ->orWhere('localisation_id', $utilisateur->service_id)
            ->count();

        return view('usersSimple.profile.view', compact('utilisateur', 'demandesTotal', 'demandesEnCours', 'equipementsTotal'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function modifier()
    {
        $utilisateur = Auth::user();
        $utilisateur->load('service', 'profil');

        // Récupérer tous les services
        $services = Localisation::where('type', 'service')
            ->orderBy('nom')
            ->get();

        return view('usersSimple.profile.modifier', compact('utilisateur', 'services'));
    }

    /**
     * Mettre à jour les informations personnelles
     */
    public function mettreAJour(Request $request)
    {
        $utilisateur = Auth::user();

        // Validation des données
        $regles = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($utilisateur->id),
            ],
            'telephone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{10,20}$/'],
            'service_id' => ['nullable', 'exists:localisations,id'],
        ];

        $messages = [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone.regex' => 'Le format du numéro de téléphone n\'est pas valide.',
            'service_id.exists' => 'Le service sélectionné n\'existe pas.',
        ];

        $validate = Validator::make($request->all(), $regles, $messages);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        try {
            // Mettre à jour l'utilisateur
            $utilisateur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'grade' => $request->grade,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'service_id' => $request->service_id,
            ]);

            return redirect()->route('user.profile.view')
                ->with('success', 'Votre profil a été mis à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Modifier le mot de passe
     */
    public function modifierMotDePasse(Request $request)
    {
        $utilisateur = Auth::user();

        $regles = [
            'ancien_mot_de_passe' => ['required', 'string'],
            'nouveau_mot_de_passe' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $messages = [
            'ancien_mot_de_passe.required' => 'L\'ancien mot de passe est obligatoire.',
            'nouveau_mot_de_passe.required' => 'Le nouveau mot de passe est obligatoire.',
            'nouveau_mot_de_passe.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'nouveau_mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];

        $validate = Validator::make($request->all(), $regles, $messages);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->ancien_mot_de_passe, $utilisateur->password)) {
            return redirect()->back()
                ->with('error', 'L\'ancien mot de passe est incorrect.')
                ->withInput();
        }

        // Vérifier que le nouveau mot de passe est différent
        if (Hash::check($request->nouveau_mot_de_passe, $utilisateur->password)) {
            return redirect()->back()
                ->with('error', 'Le nouveau mot de passe doit être différent de l\'ancien.')
                ->withInput();
        }

        try {
            // Mettre à jour le mot de passe
            $utilisateur->update([
                'password' => $request->nouveau_mot_de_passe,
            ]);

            return redirect()->route('user.profile.view')
                ->with('success', 'Votre mot de passe a été modifié avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage())
                ->withInput();
        }
    }
}
