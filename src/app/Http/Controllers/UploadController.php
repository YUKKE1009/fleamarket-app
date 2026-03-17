<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // 'image' という名前で送られてきたファイルをチェック
        if ($request->hasFile('image')) {
            // storage/app/public/tmp に保存
            $path = $request->file('image')->store('tmp', 'public');

            // 保存したパスを JSON で JavaScript に返却
            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'アップロードに失敗しました'], 400);
    }
}
