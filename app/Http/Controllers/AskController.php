<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AskController extends Controller
{
    /**
     * Forward a question to the RAG API and return its answer.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $response = Http::timeout(120)
                ->acceptJson()
                ->post(config('services.rag.url').'/chat', [
                    'question' => $validated['question'],
                    //'session_id' => '1',
                    'session_id' => 'user-'.$request->user()->getKey(),
                ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'The assistant is unavailable right now. Please try again shortly.',
            ], 503);
        }

        if ($response->failed()) {
            return response()->json([
                'error' => 'The assistant could not answer that. Please try again.',
            ], 502);
        }

        return response()->json([
            'answer' => $response->json('answer'),
        ]);
    }
}
