<?php

namespace App\Http\Controllers;

use App\Services\EmiCalculatorService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalculatorController extends Controller
{
    public function emi(Request $request, EmiCalculatorService $emi): View
    {
        $result = null;

        if ($request->isMethod('post') || $request->filled('principal')) {
            $data = $request->validate([
                'principal' => ['required', 'numeric', 'min:1'],
                'rate'      => ['required', 'numeric', 'min:0', 'max:50'],
                'tenure'    => ['required', 'integer', 'min:1', 'max:480'],
            ]);

            $result = $emi->compute(
                (float) $data['principal'],
                (float) $data['rate'],
                (int) $data['tenure'],
            );
        }

        return view('tools.emi', [
            'result' => $result,
            'input'  => $request->only(['principal', 'rate', 'tenure']),
        ]);
    }
}
