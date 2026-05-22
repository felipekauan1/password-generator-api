<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePasswordRequest;
use App\Models\GeneratedPassword;

class PasswordController extends Controller
{
    public function generate(GeneratePasswordRequest $request)
    {
        $length = $request->input('length');
        $uppercase = $request->input('uppercase');
        $lowercase = $request->input('lowercase');
        $numbers = $request->input('numbers');
        $symbols = $request->input('symbols');

        $possibleChars = '';

        if ($uppercase == true) {
            $possibleChars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($lowercase == true) {
            $possibleChars .= 'abcdefghijklmnopqrstuvwxyz';
        }

        if ($numbers == true) {
            $possibleChars .= '0123456789';
        }

        if ($symbols == true) {
            $possibleChars .= '!@#$%^&*';
        }

        do {
            $possibleChars = str_repeat($possibleChars, 2);
        } while (strlen($possibleChars) < $length);

        // Transforma a string em um array de letras
        $letterArray = str_split($possibleChars);

        // Embaralha o array
        shuffle($letterArray);
        
        // Junta o array de volta em uma string
        $passwordGenerated = implode('', $letterArray);

        // Retorna a string com a quantidade de caracteres escolhida
        $passwordGenerated = substr($passwordGenerated, 0, $length);

        $record = GeneratedPassword::create([
            'password' => $passwordGenerated,
            'length' => $length,
            'uppercase' => $uppercase,
            'lowercase' => $lowercase,
            'numbers' => $numbers,
            'symbols' => $symbols,
        ]);

        return response()->json([
            'message' => 'Senha criada com sucesso!',
            'password' => $record,
        ], 201);
    }

    public function index()
    {
        $passwords = GeneratedPassword::all();

        return response()->json([
            'passwords' => $passwords,
        ]);
    }

    public function destroy(GeneratedPassword $password)
    {
        $password->delete();

        return response()->json([
            'message' => 'Senha deletada com sucesso!',
        ]);
    }
}
