<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vehicle Types, Brands & Models
    |--------------------------------------------------------------------------
    | Cascading data: vehicle_type → brand → models
    | Used by the sale creation/edit forms for search-as-you-type suggestions.
    */

    'types' => [
        'Autó' => [
            'Abarth' => ['124 Spider', '500', '595', '695', 'Grande Punto', 'Punto Evo'],
            'Alfa Romeo' => ['147', '156', '159', '166', '4C', 'Brera', 'Giulia', 'Giulietta', 'GT', 'GTV', 'MiTo', 'Spider', 'Stelvio', 'Tonale'],
            'Audi' => ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'e-tron', 'e-tron GT', 'Q2', 'Q3', 'Q4 e-tron', 'Q5', 'Q7', 'Q8', 'R8', 'RS3', 'RS4', 'RS5', 'RS6', 'RS7', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'TT'],
            'BMW' => ['1-es', '2-es', '2-es Active Tourer', '2-es Gran Coupé', '3-as', '4-es', '5-ös', '6-os', '7-es', '8-as', 'i3', 'i4', 'i5', 'i7', 'iX', 'iX1', 'iX3', 'M2', 'M3', 'M4', 'M5', 'M8', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'Z4'],
            'Chevrolet' => ['Aveo', 'Camaro', 'Captiva', 'Corvette', 'Cruze', 'Epica', 'Malibu', 'Orlando', 'Spark', 'Trax'],
            'Citroën' => ['Berlingo', 'C1', 'C2', 'C3', 'C3 Aircross', 'C4', 'C4 Cactus', 'C4 Picasso', 'C5', 'C5 Aircross', 'C5 X', 'C6', 'DS3', 'DS4', 'DS5', 'Jumpy', 'SpaceTourer', 'ë-C4'],
            'Cupra' => ['Ateca', 'Born', 'Formentor', 'Leon', 'Tavascan'],
            'Dacia' => ['Dokker', 'Duster', 'Jogger', 'Logan', 'Sandero', 'Spring'],
            'DS' => ['DS 3', 'DS 3 Crossback', 'DS 4', 'DS 5', 'DS 7', 'DS 9'],
            'Fiat' => ['500', '500C', '500L', '500X', '600e', 'Bravo', 'Dobló', 'Fiorino', 'Grande Punto', 'Linea', 'Panda', 'Punto', 'Punto Evo', 'Qubo', 'Tipo'],
            'Ford' => ['B-Max', 'C-Max', 'EcoSport', 'Edge', 'Explorer', 'Fiesta', 'Focus', 'Fusion', 'Galaxy', 'Ka', 'Kuga', 'Mondeo', 'Mustang', 'Mustang Mach-E', 'Puma', 'Ranger', 'S-Max', 'Tourneo Connect', 'Transit Connect'],
            'Honda' => ['Accord', 'City', 'Civic', 'CR-V', 'CR-Z', 'e', 'HR-V', 'Insight', 'Jazz', 'ZR-V'],
            'Hyundai' => ['Accent', 'Bayon', 'Elantra', 'Getz', 'i10', 'i20', 'i30', 'i40', 'Ioniq', 'Ioniq 5', 'Ioniq 6', 'ix20', 'ix35', 'Kona', 'Santa Fe', 'Tucson', 'Veloster'],
            'Jaguar' => ['E-Pace', 'F-Pace', 'F-Type', 'I-Pace', 'XE', 'XF', 'XJ'],
            'Jeep' => ['Avenger', 'Cherokee', 'Compass', 'Grand Cherokee', 'Renegade', 'Wrangler'],
            'Kia' => ['Carens', 'Ceed', 'EV6', 'EV9', 'Niro', 'Optima', 'Picanto', 'ProCeed', 'Rio', 'Sorento', 'Soul', 'Sportage', 'Stinger', 'Stonic', 'Venga', 'XCeed'],
            'Land Rover' => ['Defender', 'Discovery', 'Discovery Sport', 'Freelander', 'Range Rover', 'Range Rover Evoque', 'Range Rover Sport', 'Range Rover Velar'],
            'Lexus' => ['CT', 'ES', 'GS', 'IS', 'LC', 'LX', 'NX', 'RC', 'RX', 'UX'],
            'Mazda' => ['2', '3', '5', '6', 'CX-3', 'CX-30', 'CX-5', 'CX-60', 'MX-30', 'MX-5'],
            'Mercedes-Benz' => ['A-osztály', 'B-osztály', 'C-osztály', 'CLA', 'CLS', 'E-osztály', 'EQA', 'EQB', 'EQC', 'EQE', 'EQS', 'G-osztály', 'GLA', 'GLB', 'GLC', 'GLE', 'GLS', 'S-osztály', 'SL', 'SLC', 'Sprinter', 'V-osztály', 'Vito'],
            'Mini' => ['Clubman', 'Convertible', 'Cooper', 'Countryman', 'Paceman'],
            'Mitsubishi' => ['ASX', 'Colt', 'Eclipse Cross', 'L200', 'Lancer', 'Outlander', 'Pajero', 'Space Star'],
            'Nissan' => ['Ariya', 'Juke', 'Leaf', 'Micra', 'Navara', 'Note', 'Pathfinder', 'Pulsar', 'Qashqai', 'Townstar', 'X-Trail'],
            'Opel' => ['Adam', 'Astra', 'Combo', 'Corsa', 'Crossland', 'Grandland', 'Insignia', 'Karl', 'Meriva', 'Mokka', 'Vivaro', 'Zafira'],
            'Peugeot' => ['108', '2008', '206', '207', '208', '3008', '301', '308', '4008', '5008', '508', 'Bipper', 'Partner', 'Rifter', 'e-208', 'e-2008'],
            'Porsche' => ['718 Boxster', '718 Cayman', '911', 'Cayenne', 'Macan', 'Panamera', 'Taycan'],
            'Renault' => ['Captur', 'Clio', 'Espace', 'Fluence', 'Grand Scénic', 'Kadjar', 'Kangoo', 'Koleos', 'Laguna', 'Mégane', 'Mégane E-Tech', 'Scénic', 'Talisman', 'Twingo', 'Zoe'],
            'Seat' => ['Alhambra', 'Altea', 'Arona', 'Ateca', 'Ibiza', 'Leon', 'Mii', 'Tarraco', 'Toledo'],
            'Škoda' => ['Citigo', 'Enyaq', 'Fabia', 'Kamiq', 'Karoq', 'Kodiaq', 'Octavia', 'Rapid', 'Roomster', 'Scala', 'Superb', 'Yeti'],
            'Smart' => ['ForFour', 'ForTwo'],
            'SsangYong' => ['Korando', 'Musso', 'Rexton', 'Tivoli', 'Torres', 'XLV'],
            'Subaru' => ['BRZ', 'Crosstrek', 'Forester', 'Impreza', 'Legacy', 'Levorg', 'Outback', 'Solterra', 'WRX', 'XV'],
            'Suzuki' => ['Across', 'Alto', 'Baleno', 'Ignis', 'Jimny', 'S-Cross', 'Splash', 'Swift', 'SX4', 'SX4 S-Cross', 'Vitara', 'Swace'],
            'Tesla' => ['Model 3', 'Model S', 'Model X', 'Model Y'],
            'Toyota' => ['Auris', 'Avensis', 'Aygo', 'Aygo X', 'bZ4X', 'C-HR', 'Camry', 'Corolla', 'Corolla Cross', 'GR86', 'Highlander', 'Hilux', 'Land Cruiser', 'Mirai', 'ProAce', 'ProAce City', 'RAV4', 'Supra', 'Yaris', 'Yaris Cross'],
            'Volkswagen' => ['Amarok', 'Arteon', 'Beetle', 'Caddy', 'CC', 'e-Golf', 'Golf', 'ID.3', 'ID.4', 'ID.5', 'ID.7', 'Jetta', 'Passat', 'Polo', 'Scirocco', 'Sharan', 'T-Cross', 'T-Roc', 'Taigo', 'Tiguan', 'Touareg', 'Touran', 'Up!'],
            'Volvo' => ['C30', 'C40', 'C70', 'EX30', 'EX90', 'S40', 'S60', 'S80', 'S90', 'V40', 'V50', 'V60', 'V70', 'V90', 'XC40', 'XC60', 'XC70', 'XC90'],
        ],

        'Motor' => [
            'Aprilia' => ['Dorsoduro', 'RS 125', 'RS 660', 'RSV4', 'Shiver', 'SR GT', 'Tuareg 660', 'Tuono'],
            'Benelli' => ['502C', 'Leoncino', 'TRK 502', 'TRK 702'],
            'BMW' => ['C 400', 'F 750 GS', 'F 800 GS', 'F 850 GS', 'F 900 R', 'F 900 XR', 'G 310 GS', 'G 310 R', 'K 1600', 'M 1000 RR', 'R 1250 GS', 'R 1250 RT', 'R 1300 GS', 'R NineT', 'S 1000 R', 'S 1000 RR', 'S 1000 XR'],
            'Ducati' => ['Diavel', 'Hypermotard', 'Monster', 'Multistrada', 'Panigale', 'Scrambler', 'Streetfighter', 'SuperSport'],
            'Harley-Davidson' => ['Breakout', 'CVO', 'Fat Bob', 'Fat Boy', 'Heritage', 'Iron 883', 'Low Rider', 'Nightster', 'Road Glide', 'Road King', 'Softail', 'Sport Glide', 'Sportster S', 'Street Bob', 'Street Glide'],
            'Honda' => ['Africa Twin', 'CB 125R', 'CB 500F', 'CB 500X', 'CB 650R', 'CB 750 Hornet', 'CBR 500R', 'CBR 600RR', 'CBR 650R', 'CRF 300L', 'Forza', 'Gold Wing', 'NC 750X', 'NT 1100', 'PCX', 'Rebel', 'X-ADV'],
            'Husqvarna' => ['701 Enduro', '701 Supermoto', 'Norden 901', 'Svartpilen', 'Vitpilen'],
            'Indian' => ['Chief', 'Challenger', 'FTR', 'Scout', 'Springfield', 'Super Chief'],
            'Kawasaki' => ['ER-6', 'KLE 500', 'Ninja 400', 'Ninja 650', 'Ninja ZX-6R', 'Ninja ZX-10R', 'Versys 650', 'Versys 1000', 'Vulcan', 'W800', 'Z400', 'Z650', 'Z900', 'Z H2', 'ZX-4RR'],
            'KTM' => ['125 Duke', '200 Duke', '390 Adventure', '390 Duke', '690 Enduro', '690 SMC R', '790 Adventure', '790 Duke', '890 Adventure', '890 Duke', '1290 Super Adventure', '1290 Super Duke R', 'RC 390'],
            'Moto Guzzi' => ['California', 'Stelvio', 'V7', 'V85 TT', 'V100 Mandello'],
            'Royal Enfield' => ['Classic 350', 'Continental GT', 'Himalayan', 'Hunter 350', 'Interceptor 650', 'Meteor 350', 'Super Meteor 650'],
            'Suzuki' => ['Burgman', 'DR-Z400', 'GSX-R600', 'GSX-R750', 'GSX-R1000', 'GSX-S750', 'GSX-S1000', 'Hayabusa', 'SV650', 'V-Strom 650', 'V-Strom 800', 'V-Strom 1050'],
            'Triumph' => ['Bonneville', 'Daytona', 'Rocket 3', 'Scrambler', 'Speed Triple', 'Speed Twin', 'Street Triple', 'Thruxton', 'Tiger 660', 'Tiger 800', 'Tiger 900', 'Tiger 1200', 'Trident 660'],
            'Yamaha' => ['FZ', 'MT-03', 'MT-07', 'MT-09', 'MT-10', 'NMAX', 'R1', 'R3', 'R6', 'R7', 'T-Max', 'Ténéré 700', 'Tracer 7', 'Tracer 9', 'XSR 700', 'XSR 900', 'YZF-R125'],
        ],

        'Kis teherautó' => [
            'Citroën' => ['Berlingo', 'Jumper', 'Jumpy', 'Nemo'],
            'Fiat' => ['Dobló Cargo', 'Ducato', 'Fiorino', 'Scudo', 'Talento'],
            'Ford' => ['Courier', 'Custom', 'Ranger', 'Transit', 'Transit Connect', 'Transit Courier', 'Transit Custom'],
            'Iveco' => ['Daily'],
            'MAN' => ['TGE'],
            'Mercedes-Benz' => ['Citan', 'eSprinter', 'Sprinter', 'Vito'],
            'Nissan' => ['Interstar', 'NV200', 'NV300', 'NV400', 'Primastar', 'Townstar'],
            'Opel' => ['Combo Cargo', 'Movano', 'Vivaro'],
            'Peugeot' => ['Boxer', 'Expert', 'Partner'],
            'Renault' => ['Express', 'Kangoo', 'Master', 'Trafic'],
            'Toyota' => ['Hilux', 'ProAce', 'ProAce City'],
            'Volkswagen' => ['Amarok', 'Caddy', 'Crafter', 'Transporter'],
        ],

        'Mezőgazdasági gép' => [
            'Case IH' => ['Farmall', 'Luxxum', 'Maxxum', 'Optum', 'Puma', 'Vestrum'],
            'Claas' => ['Arion', 'Atos', 'Axion', 'Elios', 'Nexos', 'Xerion'],
            'Deutz-Fahr' => ['5D', '5G', '6G', '7G', '8280 TTV', '9340 TTV', 'Agrofarm', 'Agrotron'],
            'Fendt' => ['200 Vario', '300 Vario', '500 Vario', '700 Vario', '900 Vario', '1000 Vario', '211 Vario'],
            'John Deere' => ['5M', '6M', '6R', '7R', '8R', '8RT', '9R'],
            'Kubota' => ['L', 'M', 'M5', 'M6', 'M7', 'MK5000'],
            'Massey Ferguson' => ['3700', '4700', '5700', '6700', '7700', '8700'],
            'New Holland' => ['T4', 'T5', 'T6', 'T7', 'T8', 'T9'],
            'Steyr' => ['Expert CVT', 'Kompakt', 'Multi', 'Profi CVT', 'Terrus CVT'],
            'Zetor' => ['Crystal', 'Forterra', 'Hortus', 'Major', 'Proxima', 'Utilix'],
        ],

        'Egyéb' => [
            'Egyéb' => ['Egyéb'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Body Types per Vehicle Type
    |--------------------------------------------------------------------------
    */

    'body_types' => [
        'Autó' => ['Sedan', 'Kombi', 'Hatchback', 'SUV', 'Kupé', 'Kabrió', 'Pickup', 'Egyéb'],
        'Motor' => ['Naked', 'Sport', 'Túra', 'Enduro', 'Chopper/Cruiser', 'Supermoto', 'Robogó', 'Egyéb'],
        'Kis teherautó' => ['Furgon', 'Platós', 'Duplakabinos', 'Dobozos', 'Hűtős', 'Egyéb'],
        'Mezőgazdasági gép' => ['Traktor', 'Kombájn', 'Rakodó', 'Egyéb'],
        'Egyéb' => ['Egyéb'],
    ],
];
