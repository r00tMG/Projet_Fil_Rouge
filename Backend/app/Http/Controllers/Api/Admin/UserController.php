<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\Annonce;
use App\Models\Demande;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    function __construct()
    {
        /*$this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);*/
    }

    public function getUsers()
    {
        $user = auth()->user();
        //dd($user);
        if (!$user)
        {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => "Unauthorized",
            ]);
        }
        $users = User::where('id','!=',$user->id)->get();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => "Ceci est la liste des utilisateurs",
            'storage' => asset('storage'),
            'users' => UserResource::collection($users)
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        //dd($user);
        if (!$user)
        {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => "Unauthorized",
            ]);
        }
        $users = User::orderBy('id','DESC')->get();

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => "Ceci est la liste des utilisateurs",
            'storage' => asset('storage'),
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'photo_profile' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,svg'],
            'password' => 'required|same:password_confirmation',
            'roles' => 'required'
        ]);
        //dd($validated->fails());
        logger($validated->fails());
        if ($validated->fails())
        {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => "Erreur de requête",
                'errors' => $validated->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        //dd($input);
        if ($request->hasFile('photo_profile')){
            $input['photo_profile'] = $request->file('photo_profile')->store('profiles');
        }

        $user = User::create($input);
        logger(!Role::where('name', $request->input('roles'))->exists());

        if (!Role::where('name', $request->input('roles'))->exists()) {
            return response()->json(['error' => 'Role does not exist'], 400);
        }
        //dd($request->input('roles'));
        $user->assignRole(explode(',', $request->input('roles')));
        //dd($user);
        logger('check assign role', [$user]);

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => "L'utilisateur a été créé avec succés",
            'storage' => asset('storage'),
            '_user' => $user,
            'user' => new UserResource($user)
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): \Illuminate\Http\JsonResponse
    {
        $userConnect = auth()->user();
        //dd($userConnect);
        if (!$userConnect)
        {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => "Unauthorized",
            ]);
        }
        //dd($user === null);
        if ($user === null)
        {
            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => "Aucun utilisateur n'a été trouvé",
            ]);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => "Cette requête a réussi",
            'storage' => asset('storage'),
            'user' => new UserResource($user)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
       // dd($user);
        logger('check get user', [$user]);
        if ($user == null)
        {
            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => "Aucun utilisateur n'a été trouvé",
            ]);
        }
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'photo_profile' => ['required','file','mimes:jpeg,png,jpg,gif,svg'],
            'password' => 'required|same:password_confirmation',
            'roles' => 'required'
        ]);
        //dd($validated->fails());775187134
        logger('Validation checked',[$validated->fails()]);
        if ($validated->fails())
        {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => "Erreur de requête",
                'errors' => $validated->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        //dd($input);
        if ($request->hasFile('photo_profile')){
            $input['photo_profile'] = $request->file('photo_profile')->store('updates');
        }
        logger('Photo Profile', $input['photo_profile']);

        $user->update($input);
        //dd($user);

        logger(!Role::where('name', $request->input('roles'))->exists());

        if (!Role::where('name', $request->input('roles'))->exists()) {
            return response()->json(['error' => 'Role does not exist'], 400);
        }
        //dd($request->input('roles'));
        $user->assignRole(explode(',', $request->input('roles')));
        //dd($user);
        logger('check assign role', [$user]);

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => "L'utilisateur a été modifié avec succés",
            'storage' => asset('storage'),
            '_user' => $user,
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id == null) {
            return response()->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => "Aucun utilisateur n'a été trouvé",
            ]);
        }

        $user = User::find($id);

        $user->orders()->delete();
        logger('orders deleted');

        Demande::where('user_id', $id)->delete();
        logger('demandes deleted');

        $user->annonces()->delete();
        logger('annonces deleted');

        $user->profile()->delete();
        logger('profile deleted');

        $user->delete();
        logger('user deleted');

        return response()->json([
            'status' => Response::HTTP_NO_CONTENT,
            'message' => "Votre utilisateur a été supprimé",
        ]);
    }


}
