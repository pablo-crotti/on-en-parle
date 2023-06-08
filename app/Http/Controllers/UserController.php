<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\LoginCredentialsEmail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    private function generateRandomPassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ%&@!#*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    public function getUsers() {
        $users = User::orderBy('name', 'asc')->get();
        return response()->json($users);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return response()->json(['message' => 'Utilisateur supprimé avec succès']);
        }

        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    public function create(Request $request) {
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
        ]);

        $password = $this->generateRandomPassword();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($user->email)->send(new LoginCredentialsEmail($user, $password));
        return response()->json(['message' => 'Utilisateur créé avec succès']);

    }
}
