<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AnnonceResource;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $annonces = Annonce::orderBy('created_at', 'DESC')->limit(6)->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'La liste de vos annonces',
            'annonces' => AnnonceResource::collection($annonces)
        ]);
    }
}
