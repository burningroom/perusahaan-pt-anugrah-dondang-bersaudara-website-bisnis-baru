<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected string $found_msg    = "Terkait Telah Berhasil Didapatkan!";
    protected string $created_msg  = "Terkait Telah Berhasil Dibuat!";
    protected string $edited_msg   = "Terkait Telah Berhasil Diubah!";
    protected string $notfound_msg = "Terkait Tidak Ditemukan Di Database...";
    protected string $saved_msg    = "Terkait Berhasil Disimpan!";
    protected string $deleted_msg  = "Terkait Berhasil Dihapus!";

    /**
     * Return a success response with optional title.
     */
    public function sendSuccess(string $message, ?string $title = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'title'   => $title,
            'message' => $message,
        ]);
    }

    /**
     * Return a success response with data.
     */
    public function sendResponse(mixed $data, string $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data ?? [],
        ], $code);
    }

    /**
     * Return an error response.
     */
    public function sendError(string $message, array $errors = [], int $code = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $errors,
        ], $code);
    }
}
