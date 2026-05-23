<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /** @var array<string, string> */
    public const CATEGORIES = [
        'mototekhnika' => 'Мототехника',
        'ekipirovka' => 'Экипировка',
        'zapchasti' => 'Запчасти',
    ];

    public function home(): View
    {
        $featured = Product::query()
            ->where('category', 'mototekhnika')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $heroSlides = HeroSlide::query()
            ->orderBy('sort_order')
            ->get();

        return view('shop.home', compact('featured', 'heroSlides'));
    }

    public function catalog(string $category, Request $request): View
    {
        if (! isset(self::CATEGORIES[$category])) {
            abort(404);
        }

        $query = Product::query()->where('category', $category);

        $q = trim((string) $request->get('q', ''));
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%');
            });
        }

        if ($request->filled('price_from')) {
            $query->where('price', '>=', max(0, (int) $request->get('price_from')));
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', max(0, (int) $request->get('price_to')));
        }

        $brandsFilter = $request->get('brand', []);
        if (! is_array($brandsFilter)) {
            $brandsFilter = $brandsFilter !== null && $brandsFilter !== '' ? [(string) $brandsFilter] : [];
        }
        $brandsFilter = array_values(array_filter(array_map('strval', $brandsFilter)));
        if ($brandsFilter !== []) {
            $query->whereIn('brand', $brandsFilter);
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory', $request->string('subcategory')->toString());
        }

        if ($category === 'mototekhnika' && $request->filled('engine_type')) {
            $query->where('engine_type', $request->string('engine_type')->toString());
        }

        $sort = (string) $request->get('sort', 'popular');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'title' => $query->orderBy('title'),
            default => $query->orderByDesc('id'),
        };

        $products = $query->paginate(12)->withQueryString();

        $brands = Product::query()
            ->where('category', $category)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $subcategories = Product::query()
            ->where('category', $category)
            ->whereNotNull('subcategory')
            ->where('subcategory', '!=', '')
            ->distinct()
            ->orderBy('subcategory')
            ->pluck('subcategory');

        return view('shop.catalog', [
            'categoryKey' => $category,
            'categoryTitle' => self::CATEGORIES[$category],
            'products' => $products,
            'brands' => $brands,
            'subcategories' => $subcategories,
            'showEngineFilter' => $category === 'mototekhnika',
        ]);
    }

    public function searchSuggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['products' => []]);
        }

        $needle = mb_strtolower($q);
        $like = '%'.addcslashes($q, '%_\\').'%';

        $candidates = Product::query()
            ->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('brand', 'like', $like);
            })
            ->limit(40)
            ->get(['id', 'title', 'slug', 'price', 'image', 'brand']);

        $sorted = $candidates->sortByDesc(function (Product $p) use ($needle) {
            $t = mb_strtolower($p->title);
            $b = mb_strtolower((string) $p->brand);
            $score = 0;
            if (str_starts_with($t, $needle)) {
                $score += 400;
            }
            if (str_contains($t, $needle)) {
                $score += 200;
            }
            if ($b !== '' && str_contains($b, $needle)) {
                $score += 120;
            }
            $score -= min(80, abs(mb_strlen($t) - mb_strlen($needle)));

            return $score;
        })->take(10)->values();

        return response()->json([
            'products' => $sorted->map(fn (Product $p) => [
                'title' => $p->title,
                'url' => route('product.show', $p),
                'price' => number_format($p->price, 0, ',', ' ').' ₽',
                'image' => $p->image_src,
            ]),
        ]);
    }

    public function search(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $products = Product::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where(function ($w) use ($q) {
                    $w->where('title', 'like', '%'.$q.'%')
                        ->orWhere('description', 'like', '%'.$q.'%');
                });
            })
            ->orderBy('category')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('shop.search', compact('products', 'q'));
    }

    public function show(Product $product): View
    {
        return view('shop.product', compact('product'));
    }

    public function pageDelivery(): View
    {
        return view('shop.pages.delivery');
    }

    public function pageInstallment(): View
    {
        return view('shop.pages.installment');
    }

    public function pageWarranty(): View
    {
        return view('shop.pages.warranty');
    }

    public function pageAbout(): View
    {
        return view('shop.pages.about');
    }

    public function pageContacts(): View
    {
        return view('shop.pages.contacts');
    }
}
