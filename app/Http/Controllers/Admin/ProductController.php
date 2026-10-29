<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('badge', 'like', "%{$s}%");
            });
        }

        $products = $query->orderBy('category')->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'category' => ['required', 'in:tabungan,deposito,kredit'],
            'badge' => ['nullable', 'string', 'max:100'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cta_text' => ['required', 'string', 'max:100'],
            'cta_type' => ['required', 'in:whatsapp,form_pengajuan,form'],
            'ketentuan' => ['nullable'],
            'syarat' => ['nullable'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $destPath = public_path('uploads/products');
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }

        if ($request->hasFile('image_file')) {
            $img = $request->file('image_file');
            $filename = time() . '_prod_' . Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $img->getClientOriginalExtension();
            $img->move($destPath, $filename);
            $validated['image'] = '/uploads/products/' . $filename;
        }

        if ($request->hasFile('hero_image_file')) {
            $hero = $request->file('hero_image_file');
            $filename = time() . '_hero_' . Str::slug(pathinfo($hero->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $hero->getClientOriginalExtension();
            $hero->move($destPath, $filename);
            $validated['hero_image'] = '/uploads/products/' . $filename;
        }

        $validated['slug'] = Str::slug($request->input('name'));
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = Str::slug($request->input('name')) . '-' . $counter++;
        }

        $validated['ketentuan'] = $this->parseList($request->input('ketentuan'));
        $validated['syarat'] = $this->parseList($request->input('syarat'));
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$validated['name']}' berhasil ditambahkan!");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'category' => ['required', 'in:tabungan,deposito,kredit'],
            'badge' => ['nullable', 'string', 'max:100'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'hero_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'cta_text' => ['required', 'string', 'max:100'],
            'cta_type' => ['required', 'in:whatsapp,form_pengajuan,form'],
            'ketentuan' => ['nullable'],
            'syarat' => ['nullable'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $destPath = public_path('uploads/products');
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }

        if ($request->hasFile('image_file')) {
            $img = $request->file('image_file');
            $filename = time() . '_prod_' . Str::slug(pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $img->getClientOriginalExtension();
            $img->move($destPath, $filename);
            $validated['image'] = '/uploads/products/' . $filename;
        }

        if ($request->hasFile('hero_image_file')) {
            $hero = $request->file('hero_image_file');
            $filename = time() . '_hero_' . Str::slug(pathinfo($hero->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $hero->getClientOriginalExtension();
            $hero->move($destPath, $filename);
            $validated['hero_image'] = '/uploads/products/' . $filename;
        }

        $validated['ketentuan'] = $this->parseList($request->input('ketentuan'));
        $validated['syarat'] = $this->parseList($request->input('syarat'));
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $request->input('sort_order', 0);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$name}' telah berhasil dihapus.");
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update(['is_active' => !$product->is_active]);

        $statusText = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk '{$product->name}' berhasil {$statusText}.");
    }

    private function parseList(mixed $input): array
    {
        if (is_array($input)) {
            return array_values(array_filter(array_map('trim', $input)));
        }

        if (is_string($input)) {
            $lines = explode("\n", str_replace("\r", "", $input));
            return array_values(array_filter(array_map('trim', $lines)));
        }

        return [];
    }
}
