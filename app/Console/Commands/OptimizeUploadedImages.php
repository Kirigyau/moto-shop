<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeUploadedImages extends Command
{
    protected $signature = 'images:optimize {--max-width=1280 : Максимальная ширина в пикселях}';

    protected $description = 'Сжимает загруженные PNG/JPEG в storage/app/public (products, hero)';

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('Расширение PHP GD не установлено.');

            return self::FAILURE;
        }

        $maxWidth = max(320, (int) $this->option('max-width'));
        $dirs = ['products', 'hero'];
        $optimized = 0;
        $savedBytes = 0;

        foreach ($dirs as $dir) {
            $path = storage_path('app/public/'.$dir);
            if (! is_dir($path)) {
                continue;
            }

            foreach (File::files($path) as $file) {
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'], true)) {
                    continue;
                }

                $before = $file->getSize();
                $relativeOld = $dir.'/'.$file->getFilename();
                $newRelative = $this->optimizeFile($file->getPathname(), $path, $maxWidth);

                if ($newRelative === null) {
                    continue;
                }

                $newPath = storage_path('app/public/'.$newRelative);
                $after = is_file($newPath) ? filesize($newPath) : $before;
                $this->updateDatabasePaths($relativeOld, $newRelative);

                if ($newRelative !== $relativeOld && is_file($file->getPathname())) {
                    @unlink($file->getPathname());
                }

                $optimized++;
                $savedBytes += max(0, $before - $after);
                $this->line(sprintf(
                    '  %s → %s (%s KB)',
                    $relativeOld,
                    $newRelative,
                    number_format($after / 1024, 0, ',', ' ')
                ));
            }
        }

        if ($optimized === 0) {
            $this->info('Нет изображений для обработки.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Готово: %d файл(ов), сэкономлено ~%s MB.',
            $optimized,
            number_format($savedBytes / 1024 / 1024, 1, ',', ' ')
        ));

        return self::SUCCESS;
    }

    private function optimizeFile(string $source, string $targetDir, int $maxWidth): ?string
    {
        $info = @getimagesize($source);
        if ($info === false) {
            return null;
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($source),
            default => false,
        };

        if ($src === false) {
            return null;
        }

        $width = imagesx($src);
        $height = imagesy($src);

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($maxWidth / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($src);

        $basename = pathinfo($source, PATHINFO_FILENAME);
        $target = $targetDir.DIRECTORY_SEPARATOR.$basename.'.jpg';

        if (! imagejpeg($dst, $target, 82)) {
            imagedestroy($dst);

            return null;
        }

        imagedestroy($dst);

        $dir = basename($targetDir);

        return $dir.'/'.basename($target);
    }

    private function updateDatabasePaths(string $oldRelative, string $newRelative): void
    {
        if ($oldRelative === $newRelative) {
            return;
        }

        Product::query()
            ->where('image', $oldRelative)
            ->update(['image' => $newRelative]);

        HeroSlide::query()
            ->where('image', $oldRelative)
            ->update(['image' => $newRelative]);

        foreach ([$oldRelative, 'storage/'.$oldRelative, '/storage/'.$oldRelative] as $variant) {
            Product::query()->where('image', $variant)->update(['image' => $newRelative]);
            HeroSlide::query()->where('image', $variant)->update(['image' => $newRelative]);
        }
    }
}
