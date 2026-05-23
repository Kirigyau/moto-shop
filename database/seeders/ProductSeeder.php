<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        $rows = [
            // Мототехника
            ['mototekhnika', 'Кроссовые и эндуро', 'PROMAX', 'gasoline', 'Кроссовый мотоцикл PROMAX MX280', 'promax-mx280', 139_000, 159_900, 'Хит', 'https://picsum.photos/seed/mx280/640/480', ['Объём 250 см³', 'Мощность 24 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Лёгкий кроссовый мотоцикл для тренировок и прогулок по бездорожью.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Скутер PROMAX STALKER 240 (49)', 'promax-stalker-240', 129_000, 169_000, 'Хит', 'https://picsum.photos/seed/stalker/640/480', ['Объём 200 см³', 'Мощность 18 л.с.', 'Вариатор', 'Двигатель 4T', 'Страна Тайвань'], 'Городской скутер с удобной посадкой и вместительным багажником.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Мопед PROMAX STREET CROSS 150 (49)', 'promax-street-cross-150', 98_900, 159_900, 'Распродажа', 'https://picsum.photos/seed/street/640/480', ['Объём 150 см³', 'Мощность 14 л.с.', 'КПП полуавтомат', 'Двигатель 4T', 'Страна Тайвань'], 'Универсальный мопед для города и лёгкого бездорожья.'],
            ['mototekhnika', 'Питбайки, кросс, эндуро', 'KAYO', 'gasoline', 'Питбайк КАЙО BASIC TT140 17/14', 'kayo-basic-tt140', 98_990, 109_990, null, 'https://picsum.photos/seed/kayo140/640/480', ['Объём 140 см³', 'Мощность 13 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Китай'], 'Питбайк для обучения и небольших трасс.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'МаксиСкутер PROMAX-HONDA ADV 250 (49) EFI', 'promax-adv-250', 199_900, 249_000, 'Хит', 'https://picsum.photos/seed/adv250/640/480', ['Объём 200 см³', 'Мощность 22 л.с.', 'Вариатор', 'Двигатель 4T', 'Страна Тайвань'], 'Макси-скутер с инжектором и комфортной подвеской.'],
            ['mototekhnika', 'Питбайки, кросс, эндуро', 'IRBIS', 'gasoline', 'Мотоцикл IRBIS TTR 125R 2022', 'irbis-ttr-125r', 99_800, 108_900, null, 'https://picsum.photos/seed/irbis125/640/480', ['Объём 125 см³', 'Мощность 8 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Россия'], 'Надёжный лёгкий мотоцикл для начинающих.'],
            ['mototekhnika', 'Кроссовые и эндуро', 'PROMAX', 'gasoline', 'Кроссовый мотоцикл PROMAX INFERNO 380', 'promax-inferno-380', 179_900, 239_000, 'Распродажа', 'https://picsum.photos/seed/inferno/640/480', ['Объём 300 см³', 'Мощность 29 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Мощный кроссовый мотоцикл для опытных райдеров.'],
            ['mototekhnika', 'Мопеды и скутеры', 'PROMAX', 'gasoline', 'Мопед PROMAX ALPHA TOURIST 150 (49)', 'promax-alpha-tourist-150', 109_800, 129_800, null, 'https://picsum.photos/seed/tourist/640/480', ['Объём 150 см³', 'Мощность 14 л.с.', 'КПП механика', 'Двигатель 4T', 'Страна Тайвань'], 'Туристический мопед с ветровым стеклом и багажником.'],

            // Экипировка (6)
            ['ekipirovka', 'Шлемы', 'AGV', null, 'Шлем интеграл AGV K1 S', 'agv-k1-s', 18_990, 21_500, null, 'https://picsum.photos/seed/helmet-agv/640/480', ['Тип интеграл', 'Вентиляция многоточечная', 'Застёжка микрометр', 'Сертификация ECE 22.06'], 'Спортивный шлем для трека и шоссе с хорошей аэродинамикой.'],
            ['ekipirovka', 'Куртки', 'Alpinestars', null, 'Куртка текстильная Alpinestars T-Jaws V3', 'alpinestars-tjaws-v3', 24_500, null, null, 'https://picsum.photos/seed/jacket-alp/640/480', ['Материал полиэстер', 'Защитные вставки CE', 'Сезон весна–осень', 'Соединение с штанами'], 'Текстильная куртка для города и туризма.'],
            ['ekipirovka', 'Перчатки', 'Dainese', null, 'Перчатки кожаные Dainese Mig C2', 'dainese-mig-c2', 7_890, null, null, 'https://picsum.photos/seed/gloves-dain/640/480', ['Материал кожа/текстиль', 'Защита тыльной части', 'Сенсорный палец'], 'Универсальные перчатки для дорожной езды.'],
            ['ekipirovka', 'Обувь', 'Alpinestars', null, 'Мотоботы кроссовые Alpinestars Tech 3', 'alpinestars-tech-3', 16_200, 17_900, null, 'https://picsum.photos/seed/boots-tech3/640/480', ['Тип кроссовые', 'Защита голеностопа', 'Подошва сцепление'], 'Боты для эндуро и кросса.'],
            ['ekipirovka', 'Защита', 'FOX', null, 'Наколенники FOX Launch D3O', 'fox-launch-knee', 9_450, null, null, 'https://picsum.photos/seed/knee-fox/640/480', ['Защита D3O', 'Размер универсальный', 'Фиксация ремнями'], 'Компактные наколенники для катания по пересечённой местности.'],
            ['ekipirovka', 'Очки', '100%', null, 'Очки мото 100% Strata 2', '100-strata-2', 3_290, null, null, 'https://picsum.photos/seed/goggles-100/640/480', ['Линза прозрачная', 'Пена двойная', 'Ремень с силиконом'], 'Очки для эндуро и мотокросса.'],

            // Запчасти (6)
            ['zapchasti', 'Масла и жидкости', 'Motul', null, 'Масло моторное Motul 7100 4T 10W-40, 1 л', 'motul-7100-10w40', 890, null, null, 'https://picsum.photos/seed/oil-motul/640/480', ['Вязкость 10W-40', 'Стандарт JASO MA2', 'Объём 1 л'], 'Синтетическое масло для четырёхтактных двигателей.'],
            ['zapchasti', 'Зажигание', 'NGK', null, 'Свеча зажигания NGK CR8E', 'ngk-cr8e', 420, null, null, 'https://picsum.photos/seed/spark-ngk/640/480', ['Резьба M10', 'Калильное число 8', 'Иридий'], 'Популярная свеча для многих моделей мототехники.'],
            ['zapchasti', 'Привод', 'D.I.D', null, 'Цепь приводная D.I.D 520VX3 118 звеньев', 'did-520vx3-118', 6_790, null, null, 'https://picsum.photos/seed/chain-did/640/480', ['Шаг 520', '118 звеньев', 'X-кольца'], 'Усиленная цепь для спортивной и туристической эксплуатации.'],
            ['zapchasti', 'Фильтры', 'K&N', null, 'Фильтр масляный K&N KN-204', 'kn-204', 1_250, null, null, 'https://picsum.photos/seed/filter-kn/640/480', ['Тип картридж', 'Многоразовый', 'Высокий поток'], 'Спортивный масляный фильтр премиум-класса.'],
            ['zapchasti', 'Тормоза', 'Brembo', null, 'Тормозные колодки Brembo передние SA', 'brembo-pads-sa', 4_100, null, null, 'https://picsum.photos/seed/pads-brembo/640/480', ['Тип спечённые', 'Позиция перед', 'Температурный режим высокий'], 'Колодки для активной езды.'],
            ['zapchasti', 'Электрика', 'Yuasa', null, 'Аккумулятор Yuasa YTX9-BS 12V 8 А·ч', 'yuasa-ytx9-bs', 5_600, null, null, '_', ['Напряжение 12 В', 'Ёмкость 8 А·ч', 'AGM'], 'Необслуживаемый аккумулятор для мотоциклов и скутеров.'],
        ];

        $images = [
            'https://images.unsplash.com/photo-1558980664-769d59546b3d?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1449426468159-d96dbf08f19f?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1591637333184-8674264ffa88?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1611432578539-67f4de93319e?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1485965120184-e220f721a03d?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1558981034-0fc0298d1c6f?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1609630875171-b132b1b2f486?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1516934024742-b461fba47600?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1503376768306-4e7341a206ef?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1469037784689-2bc0a9e4a58f?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1471467658195-97edc42e0e56?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1452473768670-0e913f88f44b?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1580894906474-24c70e54eee0?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1483721310020-03333e577078?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&w=640&h=480&q=80',
            'https://images.unsplash.com/photo-1597680751364-4933170387c7?auto=format&w=640&h=480&q=80',
        ];

        foreach ($rows as $i => $r) {
            Product::query()->create([
                'category' => $r[0],
                'subcategory' => $r[1],
                'brand' => $r[2],
                'engine_type' => $r[3],
                'title' => $r[4],
                'slug' => $r[5],
                'price' => $r[6],
                'old_price' => $r[7],
                'badge' => $r[8],
                'image' => $images[$i] ?? $r[9],
                'specs' => $r[10],
                'description' => $r[11],
            ]);
        }
    }
}
