<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function edit(): View
    {
        $slides = HeroSlide::query()
            ->orderBy('sort_order')
            ->get()
            ->keyBy('sort_order');

        return view('admin.hero-slides.edit', compact('slides'));
    }

    public function update(Request $request): RedirectResponse
    {
        for ($slot = 1; $slot <= HeroSlide::MAX_SLIDES; $slot++) {
            $this->syncSlot($request, $slot);
        }

        return redirect()
            ->route('admin.hero-slides.edit')
            ->with('status', 'Слайдер на главной сохранён.');
    }

    private function syncSlot(Request $request, int $slot): void
    {
        $slide = HeroSlide::query()->where('sort_order', $slot)->first();

        if ($request->boolean("slots.{$slot}.remove")) {
            if ($slide) {
                $this->deleteStoredImage($slide->image);
                $slide->delete();
            }

            return;
        }

        $url = trim((string) $request->input("slots.{$slot}.image", ''));
        $upload = $request->file("slots.{$slot}.image_upload");
        $alt = trim((string) $request->input("slots.{$slot}.alt", '')) ?: null;
        $link = trim((string) $request->input("slots.{$slot}.link", '')) ?: null;

        $newImage = null;

        if ($upload !== null) {
            $request->validate([
                "slots.{$slot}.image_upload" => ['image', 'max:8192'],
            ]);
            $newImage = $upload->store('hero', 'public');
        } elseif ($url !== '') {
            $request->validate([
                "slots.{$slot}.image" => ['string', 'max:2048'],
            ]);
            $newImage = $url;
        }

        if ($slide === null) {
            if ($newImage === null) {
                return;
            }

            HeroSlide::query()->create([
                'sort_order' => $slot,
                'image' => $newImage,
                'alt' => $alt,
                'link' => $link,
            ]);

            return;
        }

        if ($newImage !== null) {
            if ($this->isStoredPath($slide->image) && $slide->image !== $newImage) {
                $this->deleteStoredImage($slide->image);
            }
            $slide->update([
                'image' => $newImage,
                'alt' => $alt,
                'link' => $link,
            ]);

            return;
        }

        $slide->update([
            'alt' => $alt,
            'link' => $link,
        ]);
    }

    private function isStoredPath(string $path): bool
    {
        return ! preg_match('#^https?://#i', $path) && ! str_starts_with($path, '/');
    }

    private function deleteStoredImage(string $path): void
    {
        if ($this->isStoredPath($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
