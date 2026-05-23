<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ShopController;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = ShopController::CATEGORIES;
        $category = $request->query('category');

        $query = Product::query()->orderByDesc('id');

        if (is_string($category) && isset($categories[$category])) {
            $query->where('category', $category);
        } else {
            $category = null;
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products', 'category', 'categories'));
    }

    public function create(Request $request): View
    {
        $product = new Product;
        $category = $request->query('category');

        if (is_string($category) && isset(ShopController::CATEGORIES[$category])) {
            $product->category = $category;
        } else {
            $category = null;
        }

        return view('admin.products.create', compact('product', 'category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $base = $this->validatedBase($request);
        if (! $request->hasFile('image_upload') && trim((string) ($base['image'] ?? '')) === '') {
            return back()->withErrors(['image' => 'Укажите ссылку на изображение или загрузите файл.'])->withInput();
        }

        $image = $this->resolveImage($request, trim((string) ($base['image'] ?? '')));
        $slugSource = ($base['slug'] ?? '') !== '' ? $base['slug'] : $base['title'];
        $slug = $this->uniqueSlug(Str::slug($slugSource));

        Product::query()->create([
            'category' => $base['category'],
            'subcategory' => $base['subcategory'] ?: null,
            'brand' => $base['brand'] ?: null,
            'engine_type' => $base['engine_type'] ?: null,
            'title' => $base['title'],
            'slug' => $slug,
            'price' => $base['price'],
            'old_price' => $base['old_price'],
            'badge' => $base['badge'] ?: null,
            'image' => $image,
            'specs' => $this->parseSpecs($request->string('specs_raw')->toString()),
            'description' => $base['description'] ?: null,
        ]);

        return redirect()
            ->route('admin.products.index', ['category' => $base['category']])
            ->with('status', 'Товар создан.');
    }

    public function edit(Product $product): View
    {
        $specsRaw = is_array($product->specs) ? implode("\n", $product->specs) : '';

        return view('admin.products.edit', compact('product', 'specsRaw'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if (trim((string) $request->input('slug')) === '') {
            $request->merge(['slug' => $product->slug]);
        }

        $base = $this->validatedBase($request);
        $image = $this->resolveImage($request, trim((string) ($base['image'] ?? '')), $product->image);

        $slug = $this->uniqueSlug(Str::slug($base['slug']), $product->id);

        $product->update([
            'category' => $base['category'],
            'subcategory' => $base['subcategory'] ?: null,
            'brand' => $base['brand'] ?: null,
            'engine_type' => $base['engine_type'] ?: null,
            'title' => $base['title'],
            'slug' => $slug,
            'price' => $base['price'],
            'old_price' => $base['old_price'],
            'badge' => $base['badge'] ?: null,
            'image' => $image,
            'specs' => $this->parseSpecs($request->string('specs_raw')->toString()),
            'description' => $base['description'] ?: null,
        ]);

        return redirect()
            ->route('admin.products.index', ['category' => $base['category']])
            ->with('status', 'Товар обновлён.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $category = $product->category;
        $product->delete();

        return redirect()
            ->route('admin.products.index', ['category' => $category])
            ->with('status', 'Товар удалён.');
    }

    /**
     * @return array{category: string, subcategory: string, brand: string, engine_type: string, title: string, slug: string, price: int, old_price: ?int, badge: string, image: string, description: string}
     */
    private function validatedBase(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'in:mototekhnika,ekipirovka,zapchasti'],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'engine_type' => ['nullable', 'in:gasoline,electric'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'old_price' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'badge' => ['nullable', 'string', 'max:64'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'image', 'max:8192'],
            'description' => ['nullable', 'string', 'max:20000'],
            'specs_raw' => ['nullable', 'string', 'max:20000'],
        ]);
    }

    /**
     * @return list<string>
     */
    private function parseSpecs(string $raw): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                $out[] = $line;
            }
        }

        return $out;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'tovar';
        $candidate = $slug;
        $i = 2;
        while (Product::query()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $slug.'-'.$i;
            $i++;
        }

        return $candidate;
    }

    private function resolveImage(Request $request, string $urlField, ?string $previous = null): string
    {
        if ($request->hasFile('image_upload')) {
            return $request->file('image_upload')->store('products', 'public');
        }

        if ($urlField !== '') {
            return $urlField;
        }

        return $previous ?? '';
    }
}
