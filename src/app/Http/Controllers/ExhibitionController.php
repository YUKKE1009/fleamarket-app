<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ★Storageを追加

class ExhibitionController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        // 1. 画像パスの初期値
        $imagePath = null;

        // 2. パスの決定：ファイルが直接アップされたか、一時保存(tmp)があるか
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('item_images', 'public');
        } elseif ($request->temp_img_url) {
            $oldPath = $request->temp_img_url;
            $newPath = str_replace('tmp/', 'item_images/', $oldPath);

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $newPath);
                $imagePath = $newPath;
            }
        }

        if (!$imagePath) {
            return back()->withErrors(['image_url' => '商品画像をアップロードしてください'])->withInput();
        }

        // 3. 商品情報の保存（image_url に必ず値が入る状態で実行される）
        $item = Item::create([
            'seller_id'    => $user->id,
            'condition_id' => $request->condition_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'image_url'    => $imagePath,
            'brand'        => $request->brand,
        ]);

        $item->categories()->attach($request->category_ids);

        return redirect()->route('item.index')->with('message', '商品を出品しました');
    }
}
