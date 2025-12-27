<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Definir las categorías principales y su estructura jerárquica
        $categories = [
            [
                'name' => 'Computadores y Tablets',
                'slug' => 'computadores-y-tablets',
                'children' => [
                    [
                        'name' => 'Notebooks',
                        'slug' => 'notebooks',
                        'children' => [
                            ['name' => 'Notebooks', 'slug' => 'notebooks'],
                            ['name' => 'Notebooks Gamer', 'slug' => 'notebooks-gamer'],
                        ]
                    ],
                    [
                        'name' => 'Tablets y eReaders',
                        'slug' => 'tablets-y-ereaders',
                        'children' => [
                            ['name' => 'Tablets', 'slug' => 'tablets'],
                            ['name' => 'eReaders', 'slug' => 'ereaders'],
                        ]
                    ],
                    [
                        'name' => 'Escritorio',
                        'slug' => 'escritorio',
                        'children' => [
                            ['name' => 'All in One', 'slug' => 'all-in-one'],
                            ['name' => 'Desktop', 'slug' => 'desktop'],
                            ['name' => 'Desktops Gamer', 'slug' => 'desktops-gamer'],
                        ]
                    ],
                    [
                        'name' => 'Servidores',
                        'slug' => 'servidores',
                        'children' => [
                            ['name' => 'Servers', 'slug' => 'servers'],
                            ['name' => 'Discos Servers', 'slug' => 'discos-servers'],
                            ['name' => 'Memorias Servidores', 'slug' => 'memorias-servidores'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios Notebooks',
                        'slug' => 'accesorios-notebooks',
                        'children' => [
                            ['name' => 'Bases Notebooks', 'slug' => 'bases-notebooks'],
                            ['name' => 'Realidad Virtual', 'slug' => 'realidad-virtual'],
                            ['name' => 'Candados y Seguridad', 'slug' => 'candados-y-seguridad'],
                            ['name' => 'Bolsos Notebooks', 'slug' => 'bolsos-notebooks'],
                            ['name' => 'Cargadores Notebooks', 'slug' => 'cargadores-notebooks'],
                            ['name' => 'Baterías de Notebooks', 'slug' => 'baterias-de-notebooks'],
                            ['name' => 'Webcams', 'slug' => 'webcams'],
                            ['name' => 'Adaptadores USB C', 'slug' => 'adaptadores-usb-c'],
                            ['name' => 'Docking Hub USB', 'slug' => 'docking-hub-usb'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios Tablets',
                        'slug' => 'accesorios-tablets',
                        'children' => [
                            ['name' => 'Bolsos Tablets', 'slug' => 'bolsos-tablets'],
                            ['name' => 'Lápices Tablets', 'slug' => 'lapices-tablets'],
                        ]
                    ],
                    [
                        'name' => 'Mouses y Teclados',
                        'slug' => 'mouses-y-teclados',
                        'children' => [
                            ['name' => 'Combos Teclado Mouse', 'slug' => 'combos-teclado-mouse'],
                            ['name' => 'Mousepads', 'slug' => 'mousepads'],
                            ['name' => 'Presentadores', 'slug' => 'presentadores'],
                            ['name' => 'Teclados', 'slug' => 'teclados'],
                            ['name' => 'Mouses', 'slug' => 'mouses'],
                        ]
                    ],
                    [
                        'name' => 'Códigos Digitales',
                        'slug' => 'codigos-digitales',
                        'children' => [
                            ['name' => 'Códigos Microsoft 365', 'slug' => 'codigos-microsoft-365'],
                            ['name' => 'Códigos Xbox', 'slug' => 'codigos-xbox'],
                            ['name' => 'Códigos Windows', 'slug' => 'codigos-windows'],
                        ]
                    ],
                    [
                        'name' => 'Ilustración y Diseño',
                        'slug' => 'ilustracion-y-diseno',
                        'children' => [
                            ['name' => 'Tabletas Digitalizadoras', 'slug' => 'tabletas-digitalizadoras'],
                            ['name' => 'Accesorios Tabletas Digitalizadoras', 'slug' => 'accesorios-tabletas-digitalizadoras'],
                        ]
                    ],
                    [
                        'name' => 'Softwares',
                        'slug' => 'softwares',
                        'children' => [
                            ['name' => 'Licenciamientos', 'slug' => 'licenciamientos'],
                            ['name' => 'Sistemas Operativos', 'slug' => 'sistemas-operativos'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Partes y Piezas Componentes',
                'slug' => 'partes-y-piezas-componentes',
                'children' => [
                    [
                        'name' => 'Procesadores',
                        'slug' => 'procesadores',
                        'children' => [
                            ['name' => 'CPU AMD SAM4', 'slug' => 'cpu-amd-sam4'],
                            ['name' => 'CPU AMD SAM5', 'slug' => 'cpu-amd-sam5'],
                            ['name' => 'CPU Intel S1700', 'slug' => 'cpu-intel-s1700'],
                        ]
                    ],
                    [
                        'name' => 'Placas Madres',
                        'slug' => 'placas-madres',
                        'children' => [
                            ['name' => 'Placas Madres AMD SAM4', 'slug' => 'placas-madres-amd-sam4'],
                            ['name' => 'Placas Madres AMD SAM5', 'slug' => 'placas-madres-amd-sam5'],
                            ['name' => 'Placas Madres Intel S1700', 'slug' => 'placas-madres-intel-s1700'],
                        ]
                    ],
                    [
                        'name' => 'Memorias',
                        'slug' => 'memorias',
                        'children' => [
                            ['name' => 'Memorias PC', 'slug' => 'memorias-pc'],
                            ['name' => 'Memorias Notebook', 'slug' => 'memorias-notebook'],
                            ['name' => 'Lectores de Memorias', 'slug' => 'lectores-de-memorias'],
                            ['name' => 'Pendrives', 'slug' => 'pendrives'],
                            ['name' => 'Memorias Flash', 'slug' => 'memorias-flash'],
                        ]
                    ],
                    [
                        'name' => 'Tarjetas Gráficas',
                        'slug' => 'tarjetas-graficas',
                        'children' => [
                            ['name' => 'Tarjetas Gráficas NVIDIA', 'slug' => 'tarjetas-graficas-nvidia'],
                            ['name' => 'Tarjetas Gráficas Profesionales', 'slug' => 'tarjetas-graficas-profesionales'],
                        ]
                    ],
                    [
                        'name' => 'Almacenamiento',
                        'slug' => 'almacenamiento',
                        'children' => [
                            ['name' => 'Discos Externos', 'slug' => 'discos-externos'],
                            ['name' => 'Enclosure/Cofres', 'slug' => 'enclosure-cofres'],
                            ['name' => 'SSD Externos', 'slug' => 'ssd-externos'],
                            ['name' => 'Discos Duros PC', 'slug' => 'discos-duros-pc'],
                            ['name' => 'Discos SSD', 'slug' => 'discos-ssd'],
                        ]
                    ],
                    [
                        'name' => 'Gabinetes',
                        'slug' => 'gabinetes',
                        'children' => [
                            ['name' => 'Gabinetes con Fuente', 'slug' => 'gabinetes-con-fuente'],
                            ['name' => 'Gabinetes sin Fuente', 'slug' => 'gabinetes-sin-fuente'],
                        ]
                    ],
                    [
                        'name' => 'Fuentes de Poder PSU',
                        'slug' => 'fuentes-de-poder-psu',
                        'children' => []
                    ],
                    [
                        'name' => 'Refrigeración',
                        'slug' => 'refrigeracion',
                        'children' => [
                            ['name' => 'Ventiladores PC', 'slug' => 'ventiladores-pc'],
                            ['name' => 'Refrigeración CPU', 'slug' => 'refrigeracion-cpu'],
                            ['name' => 'Pastas Disipadoras', 'slug' => 'pastas-disipadoras'],
                        ]
                    ],
                    [
                        'name' => 'Ópticos',
                        'slug' => 'opticos',
                        'children' => [
                            ['name' => 'DVD', 'slug' => 'dvd'],
                        ]
                    ],
                    [
                        'name' => 'Sillas Gamer',
                        'slug' => 'sillas-gamer',
                        'children' => []
                    ],
                    [
                        'name' => 'Capturadoras de Video',
                        'slug' => 'capturadoras-de-video',
                        'children' => []
                    ],
                    [
                        'name' => 'Accesorios',
                        'slug' => 'accesorios',
                        'children' => [
                            ['name' => 'Accesorios Desktop', 'slug' => 'accesorios-desktop'],
                            ['name' => 'Cables Desktop', 'slug' => 'cables-desktop'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Smartphones y Wearables',
                'slug' => 'smartphones-y-wearables',
                'children' => [
                    [
                        'name' => 'Smartphones',
                        'slug' => 'smartphones',
                        'children' => []
                    ],
                    [
                        'name' => 'Accesorios Celulares',
                        'slug' => 'accesorios-celulares',
                        'children' => [
                            ['name' => 'Accesorios Celulares', 'slug' => 'accesorios-celulares'],
                            ['name' => 'Cargadores para Automóvil', 'slug' => 'cargadores-para-automovil'],
                            ['name' => 'Cargadores para Casa', 'slug' => 'cargadores-para-casa'],
                            ['name' => 'Baterías Externas', 'slug' => 'baterias-externas'],
                            ['name' => 'Cables', 'slug' => 'cables'],
                            ['name' => 'Carcasas', 'slug' => 'carcasas'],
                            ['name' => 'Láminas Protectoras', 'slug' => 'laminas-protectoras'],
                            ['name' => 'Teclado para Celulares', 'slug' => 'teclado-para-celulares'],
                            ['name' => 'Soportes para Celulares', 'slug' => 'soportes-para-celulares'],
                        ]
                    ],
                    [
                        'name' => 'Banda Ancha Móvil',
                        'slug' => 'banda-ancha-movil',
                        'children' => []
                    ],
                    [
                        'name' => 'Teléfonos Fijos',
                        'slug' => 'telefonos-fijos',
                        'children' => []
                    ],
                    [
                        'name' => 'Wearables',
                        'slug' => 'wearables',
                        'children' => [
                            ['name' => 'Audífonos In Ear', 'slug' => 'audifonos-in-ear'],
                            ['name' => 'Smartband', 'slug' => 'smartband'],
                            ['name' => 'Smartwatches', 'slug' => 'smartwatches'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Gamer y Poder Gráfico',
                'slug' => 'gamer-y-poder-grafico',
                'children' => [
                    ['name' => 'Notebooks Gamer', 'slug' => 'notebooks-gamer'],
                    ['name' => 'Desktops Gamer', 'slug' => 'desktops-gamer'],
                    ['name' => 'Sillas Gamer', 'slug' => 'sillas-gamer'],
                    [
                        'name' => 'Controles',
                        'slug' => 'controles',
                        'children' => [
                            ['name' => 'Joysticks Consolas', 'slug' => 'joysticks-consolas'],
                            ['name' => 'Volantes', 'slug' => 'volantes'],
                        ]
                    ],
                    ['name' => 'Consolas', 'slug' => 'consolas'],
                    ['name' => 'Accesorios Consolas', 'slug' => 'accesorios-consolas'],
                    ['name' => 'Códigos Xbox', 'slug' => 'codigos-xbox'],
                    ['name' => 'Juegos de Consola', 'slug' => 'juegos-de-consola'],
                    ['name' => 'Juegos PC', 'slug' => 'juegos-pc'],
                    [
                        'name' => 'Streamers',
                        'slug' => 'streamers',
                        'children' => [
                            ['name' => 'Webcams', 'slug' => 'webcams'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'UPS, Energía y Energía Solar',
                'slug' => 'ups-energia-y-energia-solar',
                'children' => [
                    ['name' => 'UPS y Reguladores', 'slug' => 'ups-y-reguladores'],
                    ['name' => 'Baterías Externas', 'slug' => 'baterias-externas'],
                    ['name' => 'Linternas', 'slug' => 'linternas'],
                    ['name' => 'Cargadores Notebooks', 'slug' => 'cargadores-notebooks'],
                    ['name' => 'Baterías y Cargadores', 'slug' => 'baterias-y-cargadores'],
                    [
                        'name' => 'Energía Solar',
                        'slug' => 'energia-solar',
                        'children' => [
                            ['name' => 'Focos Energía Solar', 'slug' => 'focos-energia-solar'],
                            ['name' => 'Decorativos Energía Solar', 'slug' => 'decorativos-energia-solar'],
                            ['name' => 'Coolers Solares', 'slug' => 'coolers-solares'],
                            ['name' => 'Paneles Solares', 'slug' => 'paneles-solares'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Accesorios y Periféricos',
                'slug' => 'accesorios-y-perifericos',
                'children' => [
                    [
                        'name' => 'Accesorios Notebooks',
                        'slug' => 'accesorios-notebooks',
                        'children' => [
                            ['name' => 'Bases Notebooks', 'slug' => 'bases-notebooks'],
                            ['name' => 'Realidad Virtual', 'slug' => 'realidad-virtual'],
                            ['name' => 'Candados y Seguridad', 'slug' => 'candados-y-seguridad'],
                            ['name' => 'Bolsos Notebooks', 'slug' => 'bolsos-notebooks'],
                            ['name' => 'Cargadores Notebooks', 'slug' => 'cargadores-notebooks'],
                            ['name' => 'Baterías de Notebooks', 'slug' => 'baterias-de-notebooks'],
                            ['name' => 'Webcams', 'slug' => 'webcams'],
                            ['name' => 'Adaptadores USB C', 'slug' => 'adaptadores-usb-c'],
                            ['name' => 'Docking Hub USB', 'slug' => 'docking-hub-usb'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios Tablets',
                        'slug' => 'accesorios-tablets',
                        'children' => [
                            ['name' => 'Lápices Tablets', 'slug' => 'lapices-tablets'],
                            ['name' => 'Bolsos Tablets', 'slug' => 'bolsos-tablets'],
                        ]
                    ],
                    ['name' => 'Accesorios Consolas', 'slug' => 'accesorios-consolas'],
                    ['name' => 'Accesorios Red', 'slug' => 'accesorios-red'],
                    ['name' => 'Bolsos y Fundas', 'slug' => 'bolsos-y-fundas'],
                    ['name' => 'Capturadoras de Video', 'slug' => 'capturadoras-de-video'],
                    ['name' => 'Herramientas', 'slug' => 'herramientas'],
                    ['name' => 'Mantención Limpieza', 'slug' => 'mantencion-limpieza'],
                    [
                        'name' => 'Adaptadores y Conversores',
                        'slug' => 'adaptadores-y-conversores',
                        'children' => [
                            ['name' => 'Adaptadores PC', 'slug' => 'adaptadores-pc'],
                            ['name' => 'Docking Hub USB', 'slug' => 'docking-hub-usb'],
                            ['name' => 'Adaptadores Bluetooth', 'slug' => 'adaptadores-bluetooth'],
                            ['name' => 'Adaptadores USB C', 'slug' => 'adaptadores-usb-c'],
                        ]
                    ],
                    [
                        'name' => 'Cables y Adaptadores',
                        'slug' => 'cables-y-adaptadores',
                        'children' => [
                            ['name' => 'Cables/Adaptadores Video', 'slug' => 'cables-adaptadores-video'],
                            ['name' => 'Cables de Red', 'slug' => 'cables-de-red'],
                            ['name' => 'Cables Datos USB', 'slug' => 'cables-datos-usb'],
                            ['name' => 'Cables Poder', 'slug' => 'cables-poder'],
                            ['name' => 'Cables Coaxiales', 'slug' => 'cables-coaxiales'],
                            ['name' => 'Cables/Adaptadores de Audio', 'slug' => 'cables-adaptadores-de-audio'],
                        ]
                    ],
                    ['name' => 'Soportes', 'slug' => 'soportes'],
                    [
                        'name' => 'Accesorios Cámaras',
                        'slug' => 'accesorios-camaras',
                        'children' => [
                            ['name' => 'Memorias Flash', 'slug' => 'memorias-flash'],
                            ['name' => 'Lentes', 'slug' => 'lentes'],
                            ['name' => 'Trípodes', 'slug' => 'tripodes'],
                            ['name' => 'Bolsos Cámaras', 'slug' => 'bolsos-camaras'],
                            ['name' => 'Accesorios Cámaras de Acción', 'slug' => 'accesorios-camaras-de-accion'],
                            ['name' => 'Filtros', 'slug' => 'filtros'],
                        ]
                    ],
                    ['name' => 'Organización de Accesorios', 'slug' => 'organizacion-de-accesorios'],
                    [
                        'name' => 'Accesorios Celulares',
                        'slug' => 'accesorios-celulares',
                        'children' => [
                            ['name' => 'Accesorios Celulares', 'slug' => 'accesorios-celulares'],
                            ['name' => 'Cargadores para Automóvil', 'slug' => 'cargadores-para-automovil'],
                            ['name' => 'Cargadores para Casa', 'slug' => 'cargadores-para-casa'],
                            ['name' => 'Baterías Externas', 'slug' => 'baterias-externas'],
                            ['name' => 'Cables', 'slug' => 'cables'],
                            ['name' => 'Carcasas', 'slug' => 'carcasas'],
                            ['name' => 'Láminas Protectoras', 'slug' => 'laminas-protectoras'],
                            ['name' => 'Teclado para Celulares', 'slug' => 'teclado-para-celulares'],
                            ['name' => 'Soportes para Celulares', 'slug' => 'soportes-para-celulares'],
                        ]
                    ],
                    [
                        'name' => 'Mouses y Teclados',
                        'slug' => 'mouses-y-teclados',
                        'children' => [
                            ['name' => 'Combos Teclado Mouse', 'slug' => 'combos-teclado-mouse'],
                            ['name' => 'Mousepads', 'slug' => 'mousepads'],
                            ['name' => 'Presentadores', 'slug' => 'presentadores'],
                            ['name' => 'Teclados', 'slug' => 'teclados'],
                            ['name' => 'Mouses', 'slug' => 'mouses'],
                        ]
                    ],
                    ['name' => 'Accesorios de Drones', 'slug' => 'accesorios-de-drones'],
                    ['name' => 'Accesorios Proyectores', 'slug' => 'accesorios-proyectores'],
                    ['name' => 'Audífonos Headset', 'slug' => 'audifonos-headset'],
                ]
            ],
            [
                'name' => 'Monitores y Proyectores',
                'slug' => 'monitores-y-proyectores',
                'children' => [
                    ['name' => 'Monitores', 'slug' => 'monitores'],
                    ['name' => 'Proyectores', 'slug' => 'proyectores'],
                    [
                        'name' => 'Accesorios Proyectores',
                        'slug' => 'accesorios-proyectores',
                        'children' => [
                            ['name' => 'Lámparas', 'slug' => 'lamparas'],
                            ['name' => 'Telones', 'slug' => 'telones'],
                            ['name' => 'Soportes de Proyectores', 'slug' => 'soportes-de-proyectores'],
                        ]
                    ],
                    ['name' => 'Streaming', 'slug' => 'streaming'],
                ]
            ],
            [
                'name' => 'Almacenamiento y Discos Duros',
                'slug' => 'almacenamiento-y-discos-duros',
                'children' => [
                    ['name' => 'Discos Externos', 'slug' => 'discos-externos'],
                    ['name' => 'SSD Externos', 'slug' => 'ssd-externos'],
                    ['name' => 'Pendrives', 'slug' => 'pendrives'],
                    ['name' => 'Memorias Flash', 'slug' => 'memorias-flash'],
                    ['name' => 'Discos Duros PC', 'slug' => 'discos-duros-pc'],
                    ['name' => 'Discos SSD', 'slug' => 'discos-ssd'],
                    ['name' => 'Enclosure/Cofres', 'slug' => 'enclosure-cofres'],
                    ['name' => 'Discos de Video Vigilancia', 'slug' => 'discos-de-video-vigilancia'],
                    ['name' => 'Fundas Discos Duros', 'slug' => 'fundas-discos-duros'],
                ]
            ],
            [
                'name' => 'Impresoras y Suministros',
                'slug' => 'impresoras-y-suministros',
                'children' => [
                    [
                        'name' => 'Impresoras Hogar y Oficina',
                        'slug' => 'impresoras-hogar-y-oficina',
                        'children' => [
                            ['name' => 'Impresoras Tinta', 'slug' => 'impresoras-tinta'],
                            ['name' => 'Impresoras Láser', 'slug' => 'impresoras-laser'],
                            ['name' => 'Multifuncionales Tinta', 'slug' => 'multifuncionales-tinta'],
                            ['name' => 'Multifuncionales Láser', 'slug' => 'multifuncionales-laser'],
                        ]
                    ],
                    [
                        'name' => 'Impresoras Formatos Especiales',
                        'slug' => 'impresoras-formatos-especiales',
                        'children' => [
                            ['name' => 'Impresoras 3D', 'slug' => 'impresoras-3d'],
                            ['name' => 'Equipos de Corte y Estampado', 'slug' => 'equipos-de-corte-y-estampado'],
                            ['name' => 'POS Impresoras', 'slug' => 'pos-impresoras'],
                            ['name' => 'Rotuladoras', 'slug' => 'rotuladoras'],
                            ['name' => 'Accesorios Impresoras 3D', 'slug' => 'accesorios-impresoras-3d'],
                            ['name' => 'Plotters', 'slug' => 'plotters'],
                        ]
                    ],
                    [
                        'name' => 'Suministros Impresoras',
                        'slug' => 'suministros-impresoras',
                        'children' => [
                            ['name' => 'Cartuchos de Tinta y Botellas para Impresoras', 'slug' => 'cartuchos-de-tinta-y-botellas-para-impresoras'],
                            ['name' => 'Cartuchos de Tinta y Botellas para Plotter', 'slug' => 'cartuchos-de-tinta-y-botellas-para-plotter'],
                            ['name' => 'Toners Impresoras Láser', 'slug' => 'toners-impresoras-laser'],
                            ['name' => 'Tambores Impresoras Láser', 'slug' => 'tambores-impresoras-laser'],
                            ['name' => 'Cintas Rotuladoras', 'slug' => 'cintas-rotuladoras'],
                        ]
                    ],
                    [
                        'name' => 'Otros Suministros Impresión',
                        'slug' => 'otros-suministros-impresion',
                        'children' => [
                            ['name' => 'Accesorios Corte y Estampado', 'slug' => 'accesorios-corte-y-estampado'],
                            ['name' => 'Resinas y Filamentos Impresión 3D', 'slug' => 'resinas-y-filamentos-impresion-3d'],
                            ['name' => 'Accesorios de Impresoras', 'slug' => 'accesorios-de-impresoras'],
                            ['name' => 'Papeles', 'slug' => 'papeles'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Smart TV y Audio',
                'slug' => 'smart-tv-y-audio',
                'children' => [
                    ['name' => 'Smart TV', 'slug' => 'smart-tv'],
                    [
                        'name' => 'Video Audio TV',
                        'slug' => 'video-audio-tv',
                        'children' => [
                            ['name' => 'Barras de Sonido', 'slug' => 'barras-de-sonido'],
                            ['name' => 'Streaming', 'slug' => 'streaming'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios TV y Video',
                        'slug' => 'accesorios-tv-y-video',
                        'children' => [
                            ['name' => 'Soportes de TV', 'slug' => 'soportes-de-tv'],
                            ['name' => 'Cables/Adaptadores Video', 'slug' => 'cables-adaptadores-video'],
                            ['name' => 'Controles TV', 'slug' => 'controles-tv'],
                        ]
                    ],
                    ['name' => 'Antenas TV Digital', 'slug' => 'antenas-tv-digital'],
                    [
                        'name' => 'Equipos de Música',
                        'slug' => 'equipos-de-musica',
                        'children' => [
                            ['name' => 'Audio All in One', 'slug' => 'audio-all-in-one'],
                        ]
                    ],
                    [
                        'name' => 'Parlantes',
                        'slug' => 'parlantes',
                        'children' => [
                            ['name' => 'Parlantes PC', 'slug' => 'parlantes-pc'],
                            ['name' => 'Parlantes Portátiles', 'slug' => 'parlantes-portatiles'],
                        ]
                    ],
                    [
                        'name' => 'Audífonos',
                        'slug' => 'audifonos',
                        'children' => [
                            ['name' => 'Audífonos Call Center', 'slug' => 'audifonos-call-center'],
                            ['name' => 'Audífonos Gamer', 'slug' => 'audifonos-gamer'],
                            ['name' => 'Audífonos Headset', 'slug' => 'audifonos-headset'],
                            ['name' => 'Audífonos In Ear', 'slug' => 'audifonos-in-ear'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios de Audio',
                        'slug' => 'accesorios-de-audio',
                        'children' => [
                            ['name' => 'Adaptadores Bluetooth', 'slug' => 'adaptadores-bluetooth'],
                            ['name' => 'Cables/Adaptadores de Audio', 'slug' => 'cables-adaptadores-de-audio'],
                        ]
                    ],
                    [
                        'name' => 'Comunicación',
                        'slug' => 'comunicacion',
                        'children' => [
                            ['name' => 'Grabadoras de Voz', 'slug' => 'grabadoras-de-voz'],
                            ['name' => 'Micrófonos PC', 'slug' => 'microfonos-pc'],
                        ]
                    ],
                    ['name' => 'Equipos DJ', 'slug' => 'equipos-dj'],
                    [
                        'name' => 'Sonido',
                        'slug' => 'sonido',
                        'children' => [
                            ['name' => 'Micrófonos Dinámicos', 'slug' => 'microfonos-dinamicos'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Oficinas y Punto de Venta',
                'slug' => 'oficinas-y-punto-de-venta',
                'children' => [
                    [
                        'name' => 'Oficinas',
                        'slug' => 'oficinas',
                        'children' => [
                            ['name' => 'Scanners', 'slug' => 'scanners'],
                            ['name' => 'Rotuladoras', 'slug' => 'rotuladoras'],
                            ['name' => 'Calculadoras', 'slug' => 'calculadoras'],
                            ['name' => 'Plastificadoras de Papel', 'slug' => 'plastificadoras-de-papel'],
                            ['name' => 'Trituradoras de Papel', 'slug' => 'trituradoras-de-papel'],
                            ['name' => 'Artículos de Escritorio', 'slug' => 'articulos-de-escritorio'],
                            ['name' => 'Sillas de Oficina', 'slug' => 'sillas-de-oficina'],
                        ]
                    ],
                    [
                        'name' => 'Suministros de Oficina',
                        'slug' => 'suministros-de-oficina',
                        'children' => [
                            ['name' => 'Cartuchos de Tinta y Botellas para Plotter', 'slug' => 'cartuchos-de-tinta-y-botellas-para-plotter'],
                            ['name' => 'Toners Impresoras Láser', 'slug' => 'toners-impresoras-laser'],
                            ['name' => 'Cartuchos de Tinta y Botellas para Impresoras', 'slug' => 'cartuchos-de-tinta-y-botellas-para-impresoras'],
                            ['name' => 'Tambores Impresoras Láser', 'slug' => 'tambores-impresoras-laser'],
                            ['name' => 'Papeles', 'slug' => 'papeles'],
                            ['name' => 'Suministros Plastificadoras (Pouches)', 'slug' => 'suministros-plastificadoras-pouches'],
                            ['name' => 'Cintas Rotuladoras', 'slug' => 'cintas-rotuladoras'],
                        ]
                    ],
                    [
                        'name' => 'Impresoras Hogar y Oficina',
                        'slug' => 'impresoras-hogar-y-oficina',
                        'children' => [
                            ['name' => 'Impresoras Tinta', 'slug' => 'impresoras-tinta'],
                            ['name' => 'Impresoras Láser', 'slug' => 'impresoras-laser'],
                            ['name' => 'Multifuncionales Tinta', 'slug' => 'multifuncionales-tinta'],
                            ['name' => 'Multifuncionales Láser', 'slug' => 'multifuncionales-laser'],
                        ]
                    ],
                    [
                        'name' => 'Conferencias y Reuniones',
                        'slug' => 'conferencias-y-reuniones',
                        'children' => [
                            ['name' => 'Webcams Reuniones', 'slug' => 'webcams-reuniones'],
                            ['name' => 'Webcams', 'slug' => 'webcams'],
                            ['name' => 'Micrófonos PC', 'slug' => 'microfonos-pc'],
                            ['name' => 'Audífonos Call Center', 'slug' => 'audifonos-call-center'],
                            ['name' => 'Cables de Red', 'slug' => 'cables-de-red'],
                            ['name' => 'Conectores de Red', 'slug' => 'conectores-de-red'],
                        ]
                    ],
                    [
                        'name' => 'UPS, Baterías y Cargadores',
                        'slug' => 'ups-baterias-y-cargadores',
                        'children' => [
                            ['name' => 'UPS y Reguladores', 'slug' => 'ups-y-reguladores'],
                            ['name' => 'Baterías Externas', 'slug' => 'baterias-externas'],
                            ['name' => 'Linternas', 'slug' => 'linternas'],
                            ['name' => 'Cargadores Notebooks', 'slug' => 'cargadores-notebooks'],
                            ['name' => 'Baterías y Cargadores', 'slug' => 'baterias-y-cargadores'],
                        ]
                    ],
                    [
                        'name' => 'Puntos de Venta POS',
                        'slug' => 'puntos-de-venta-pos',
                        'children' => [
                            ['name' => 'POS Impresoras', 'slug' => 'pos-impresoras'],
                            ['name' => 'POS Accesorios', 'slug' => 'pos-accesorios'],
                        ]
                    ],
                    [
                        'name' => 'Complementos POS',
                        'slug' => 'complementos-pos',
                        'children' => [
                            ['name' => 'Monitores', 'slug' => 'monitores'],
                            ['name' => 'All in One', 'slug' => 'all-in-one'],
                            ['name' => 'Desktop', 'slug' => 'desktop'],
                            ['name' => 'Teclados', 'slug' => 'teclados'],
                            ['name' => 'Mouses', 'slug' => 'mouses'],
                        ]
                    ],
                    ['name' => 'Baterías Externas', 'slug' => 'baterias-externas'],
                ]
            ],
            [
                'name' => 'Conectividad y Redes',
                'slug' => 'conectividad-y-redes',
                'children' => [
                    ['name' => 'Access Point', 'slug' => 'access-point'],
                    ['name' => 'Extensores de Red', 'slug' => 'extensores-de-red'],
                    ['name' => 'Internet Satelital', 'slug' => 'internet-satelital'],
                    [
                        'name' => 'Routers',
                        'slug' => 'routers',
                        'children' => [
                            ['name' => 'Routers', 'slug' => 'routers'],
                            ['name' => 'Routers Mesh', 'slug' => 'routers-mesh'],
                        ]
                    ],
                    ['name' => 'Switches', 'slug' => 'switches'],
                    ['name' => 'Switch Administrables', 'slug' => 'switch-administrables'],
                    ['name' => 'Tarjetas de Red USB', 'slug' => 'tarjetas-de-red-usb'],
                    ['name' => 'Teléfonos Fijos', 'slug' => 'telefonos-fijos'],
                    [
                        'name' => 'Seguridad',
                        'slug' => 'seguridad',
                        'children' => [
                            ['name' => 'Kits de Vigilancia', 'slug' => 'kits-de-vigilancia'],
                            ['name' => 'Alarmas', 'slug' => 'alarmas'],
                            ['name' => 'Cámaras IP', 'slug' => 'camaras-ip'],
                            ['name' => 'Cámaras Analógicas', 'slug' => 'camaras-analogas'],
                            ['name' => 'Grabadoras DVR y NVR', 'slug' => 'grabadoras-dvr-y-nvr'],
                            ['name' => 'Discos de Video Vigilancia', 'slug' => 'discos-de-video-vigilancia'],
                            ['name' => 'Accesorios Seguridad', 'slug' => 'accesorios-seguridad'],
                        ]
                    ],
                    [
                        'name' => 'Accesorios Red',
                        'slug' => 'accesorios-red',
                        'children' => [
                            ['name' => 'Antenas', 'slug' => 'antenas'],
                            ['name' => 'Conectores de Red', 'slug' => 'conectores-de-red'],
                            ['name' => 'Cables de Red', 'slug' => 'cables-de-red'],
                            ['name' => 'Cables Coaxiales', 'slug' => 'cables-coaxiales'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Video, Fotografía y Drones',
                'slug' => 'video-fotografia-y-drones',
                'children' => [
                    ['name' => 'Cámaras de Acción', 'slug' => 'camaras-de-accion'],
                    ['name' => 'Cámaras Compactas', 'slug' => 'camaras-compactas'],
                    ['name' => 'Cámaras Reflex', 'slug' => 'camaras-reflex'],
                    ['name' => 'Cámaras Mirrorless', 'slug' => 'camaras-mirrorless'],
                    ['name' => 'Cámaras Instantáneas', 'slug' => 'camaras-instantaneas'],
                    [
                        'name' => 'Accesorios Cámaras',
                        'slug' => 'accesorios-camaras',
                        'children' => [
                            ['name' => 'Memorias Flash', 'slug' => 'memorias-flash'],
                            ['name' => 'Lentes', 'slug' => 'lentes'],
                            ['name' => 'Trípodes', 'slug' => 'tripodes'],
                            ['name' => 'Bolsos Cámaras', 'slug' => 'bolsos-camaras'],
                            ['name' => 'Accesorios Cámaras de Acción', 'slug' => 'accesorios-camaras-de-accion'],
                            ['name' => 'Filtros', 'slug' => 'filtros'],
                        ]
                    ],
                    ['name' => 'Accesorios de Drones', 'slug' => 'accesorios-de-drones'],
                ]
            ],
            [
                'name' => 'Hogar y Seguridad',
                'slug' => 'hogar-y-seguridad',
                'children' => [
                    [
                        'name' => 'Seguridad',
                        'slug' => 'seguridad',
                        'children' => [
                            ['name' => 'Kits de Vigilancia', 'slug' => 'kits-de-vigilancia'],
                            ['name' => 'Alarmas', 'slug' => 'alarmas'],
                            ['name' => 'Cámaras IP', 'slug' => 'camaras-ip'],
                            ['name' => 'Cámaras Analógicas', 'slug' => 'camaras-analogas'],
                            ['name' => 'Grabadoras DVR y NVR', 'slug' => 'grabadoras-dvr-y-nvr'],
                            ['name' => 'Discos de Video Vigilancia', 'slug' => 'discos-de-video-vigilancia'],
                            ['name' => 'Accesorios Seguridad', 'slug' => 'accesorios-seguridad'],
                        ]
                    ],
                    [
                        'name' => 'Smart Home Domótica',
                        'slug' => 'smart-home-domotica',
                        'children' => [
                            ['name' => 'Hubs y Kits Smart Home', 'slug' => 'hubs-y-kits-smart-home'],
                            ['name' => 'Iluminación y Enchufes Inteligentes', 'slug' => 'iluminacion-y-enchufes-inteligentes'],
                            ['name' => 'Asistentes de Voz', 'slug' => 'asistentes-de-voz'],
                            ['name' => 'Otros Smart Home Domótica', 'slug' => 'otros-smart-home-domotica'],
                            ['name' => 'Salud y Bienestar Smart Health', 'slug' => 'salud-y-bienestar-smart-health'],
                        ]
                    ],
                    [
                        'name' => 'Aires Acondicionados',
                        'slug' => 'aires-acondicionados',
                        'children' => [
                            ['name' => 'Aire Acondicionado Portátil', 'slug' => 'aire-acondicionado-portatil'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Automóvil, GPS y Outdoor',
                'slug' => 'automovil-gps-y-outdoor',
                'children' => [
                    ['name' => 'GPS Deportivo', 'slug' => 'gps-deportivo'],
                    [
                        'name' => 'Automóvil',
                        'slug' => 'automovil',
                        'children' => [
                            ['name' => 'Soportes para Celulares', 'slug' => 'soportes-para-celulares'],
                            ['name' => 'Monitoreo de Vehículos', 'slug' => 'monitoreo-de-vehiculos'],
                            ['name' => 'Hidrolavadoras', 'slug' => 'hidrolavadoras'],
                            ['name' => 'Cargadores para Automóvil', 'slug' => 'cargadores-para-automovil'],
                            ['name' => 'Receptores Bluetooth Automóvil', 'slug' => 'receptores-bluetooth-automovil'],
                        ]
                    ],
                    ['name' => 'Drones', 'slug' => 'drones'],
                    ['name' => 'Scooters Eléctricos', 'slug' => 'scooters-electricos'],
                ]
            ],
            [
                'name' => 'Electrónica y Robótica',
                'slug' => 'electronica-y-robotica',
                'children' => [
                    [
                        'name' => 'Electrónica',
                        'slug' => 'electronica',
                        'children' => [
                            ['name' => 'Interfaz de Usuario', 'slug' => 'interfaz-de-usuario'],
                            ['name' => 'Componentes Electrónicos', 'slug' => 'componentes-electronicos'],
                            ['name' => 'Sensores', 'slug' => 'sensores'],
                            ['name' => 'Actuadores', 'slug' => 'actuadores'],
                            ['name' => 'Placas de Desarrollo', 'slug' => 'placas-de-desarrollo'],
                            ['name' => 'Accesorios Electrónica', 'slug' => 'accesorios-electronica'],
                            ['name' => 'Kits de Electrónica', 'slug' => 'kits-de-electronica'],
                        ]
                    ],
                    [
                        'name' => 'Robótica',
                        'slug' => 'robotica',
                        'children' => [
                            ['name' => 'Robótica', 'slug' => 'robotica'],
                            ['name' => 'Accesorios Robótica', 'slug' => 'accesorios-robotica'],
                        ]
                    ],
                ]
            ],
        ];

        // Crear las categorías recursivamente
        $this->createCategoriesRecursively($categories);
    }

    /**
     * Función recursiva para crear categorías con estructura jerárquica
     */
    private function createCategoriesRecursively(array $categories, ?int $parentId = null, int $level = 0): void
    {
        foreach ($categories as $index => $categoryData) {
            // Crear la categoría principal o subcategoría
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'parent_id' => $parentId,
                'is_active' => true,
                'position' => $index,
            ]);

            // Si tiene hijos, llamar recursivamente
            if (isset($categoryData['children']) && count($categoryData['children']) > 0) {
                $this->createCategoriesRecursively($categoryData['children'], $category->id, $level + 1);
            }
        }
    }
}