<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Support\StoredImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** @var array<string, string> */
    private array $demoImages;

    public function __construct()
    {
        $this->demoImages = require database_path('data/demo-product-images.php');
    }

    public function run(): void
    {
        $rows = [
            // Мототехника
            ['mototekhnika', 'Кроссовые и эндуро', 'PROMAX', 'gasoline', 'Кроссовый мотоцикл PROMAX MX280', 'promax-mx280', 139_000, 159_900, 'Хит', ['Объём 250 см³', 'Мощность 24 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Лёгкий кроссовый мотоцикл для тренировок и прогулок по бездорожью.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Скутер PROMAX STALKER 240 (49)', 'promax-stalker-240', 129_000, 169_000, 'Хит', ['Объём 200 см³', 'Мощность 18 л.с.', 'Вариатор', 'Двигатель 4T', 'Страна Тайвань'], 'Городской скутер с удобной посадкой и вместительным багажником.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Мопед PROMAX STREET CROSS 150 (49)', 'promax-street-cross-150', 98_900, 159_900, 'Распродажа', ['Объём 150 см³', 'Мощность 14 л.с.', 'КПП полуавтомат', 'Двигатель 4T', 'Страна Тайвань'], 'Универсальный мопед для города и лёгкого бездорожья.'],
            ['mototekhnika', 'Питбайки, кросс, эндуро', 'KAYO', 'gasoline', 'Питбайк КАЙО BASIC TT140 17/14', 'kayo-basic-tt140', 98_990, 109_990, null, ['Объём 140 см³', 'Мощность 13 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Китай'], 'Питбайк для обучения и небольших трасс.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'МаксиСкутер PROMAX-HONDA ADV 250 (49) EFI', 'promax-adv-250', 199_900, 249_000, 'Хит', ['Объём 200 см³', 'Мощность 22 л.с.', 'Вариатор', 'Двигатель 4T', 'Страна Тайвань'], 'Макси-скутер с инжектором и комфортной подвеской.'],
            ['mototekhnika', 'Питбайки, кросс, эндуро', 'IRBIS', 'gasoline', 'Мотоцикл IRBIS TTR 125R 2022', 'irbis-ttr-125r', 99_800, 108_900, null, ['Объём 125 см³', 'Мощность 8 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Россия'], 'Надёжный лёгкий мотоцикл для начинающих.'],
            ['mototekhnika', 'Кроссовые и эндуро', 'PROMAX', 'gasoline', 'Кроссовый мотоцикл PROMAX INFERNO 380', 'promax-inferno-380', 179_900, 239_000, 'Распродажа', ['Объём 300 см³', 'Мощность 29 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Мощный кроссовый мотоцикл для опытных райдеров.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Мопед PROMAX ALPHA TOURIST 150 (49)', 'promax-alpha-tourist-150', 109_800, 129_800, null, ['Объём 150 см³', 'Мощность 14 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Туристический мопед с ветровым стеклом и багажником.'],

            // Экипировка
            ['ekipirovka', 'Шлемы', 'AGV', null, 'Шлем интеграл AGV K1 S', 'agv-k1-s', 18_990, 21_500, null, ['Тип интеграл', 'Вентиляция многоточечная', 'Застёжка микрометр', 'Сертификация ECE 22.06'], 'Спортивный шлем для трека и шоссе с хорошей аэродинамикой.'],
            ['ekipirovka', 'Куртки', 'Alpinestars', null, 'Куртка текстильная Alpinestars T-Jaws V3', 'alpinestars-tjaws-v3', 24_500, null, null, ['Материал полиэстер', 'Защитные вставки CE', 'Сезон весна–осень', 'Соединение с штанами'], 'Текстильная куртка для города и туризма.'],
            ['ekipirovka', 'Перчатки', 'Dainese', null, 'Перчатки кожаные Dainese Mig C2', 'dainese-mig-c2', 7_890, null, null, ['Материал кожа/текстиль', 'Защита тыльной части', 'Сенсорный палец'], 'Универсальные перчатки для дорожной езды.'],
            ['ekipirovka', 'Обувь', 'Alpinestars', null, 'Мотоботы кроссовые Alpinestars Tech 3', 'alpinestars-tech-3', 16_200, 17_900, null, ['Тип кроссовые', 'Защита голеностопа', 'Подошва сцепление'], 'Боты для эндуро и кросса.'],
            ['ekipirovka', 'Защита', 'FOX', null, 'Наколенники FOX Launch D3O', 'fox-launch-knee', 9_450, null, null, ['Защита D3O', 'Размер универсальный', 'Фиксация ремнями'], 'Компактные наколенники для катания по пересечённой местности.'],
            ['ekipirovka', 'Очки', '100%', null, 'Очки мото 100% Strata 2', '100-strata-2', 3_290, null, null, ['Линза прозрачная', 'Пена двойная', 'Ремень с силиконом'], 'Очки для эндуро и мотокросса.'],

            // Запчасти
            ['zapchasti', 'Масла и жидкости', 'Motul', null, 'Масло моторное Motul 7100 4T 10W-40, 1 л', 'motul-7100-10w40', 890, null, null, ['Вязкость 10W-40', 'Стандарт JASO MA2', 'Объём 1 л'], 'Синтетическое масло для четырёхтактных двигателей.'],
            ['zapchasti', 'Зажигание', 'NGK', null, 'Свеча зажигания NGK CR8E', 'ngk-cr8e', 420, null, null, ['Резьба M10', 'Калильное число 8', 'Иридий'], 'Популярная свеча для многих моделей мототехники.'],
            ['zapchasti', 'Привод', 'D.I.D', null, 'Цепь приводная D.I.D 520VX3 118 звеньев', 'did-520vx3-118', 6_790, null, null, ['Шаг 520', '118 звеньев', 'X-кольца'], 'Усиленная цепь для спортивной и туристической эксплуатации.'],
            ['zapchasti', 'Фильтры', 'K&N', null, 'Фильтр масляный K&N KN-204', 'kn-204', 1_250, null, null, ['Тип картридж', 'Многоразовый', 'Высокий поток'], 'Спортивный масляный фильтр премиум-класса.'],
            ['zapchasti', 'Тормоза', 'Brembo', null, 'Тормозные колодки Brembo передние SA', 'brembo-pads-sa', 4_100, null, null, ['Тип спечённые', 'Позиция перед', 'Температурный режим высокий'], 'Колодки для активной езды.'],
            ['zapchasti', 'Электрика', 'Yuasa', null, 'Аккумулятор Yuasa YTX9-BS 12V 8 А·ч', 'yuasa-ytx9-bs', 5_600, null, null, ['Напряжение 12 В', 'Ёмкость 8 А·ч', 'AGM'], 'Необслуживаемый аккумулятор для мотоциклов и скутеров.'],
        ];

        foreach ($rows as $r) {
            $slug = $r[5];
            $product = Product::query()->firstOrNew(['slug' => $slug]);

            $product->fill([
                'category' => $r[0],
                'subcategory' => $r[1],
                'brand' => $r[2],
                'engine_type' => $r[3],
                'title' => $r[4],
                'price' => $r[6],
                'old_price' => $r[7],
                'badge' => $r[8],
                'specs' => $r[9],
                'description' => $r[10],
            ]);

            $product->image = $this->resolveImageForSeed($product, $slug);
            $product->save();
        }
    }

    private function resolveImageForSeed(Product $product, string $slug): string
    {
        $demo = $this->demoImages[$slug] ?? '';
        $current = trim((string) $product->image);

        if (! $product->exists) {
            return $demo;
        }

        if (StoredImage::isUploadedPath($current)) {
            return $current;
        }

        if (StoredImage::isRemoteDemoUrl($current)) {
            return $demo;
        }

        return $current !== '' ? $current : $demo;
    }
}
