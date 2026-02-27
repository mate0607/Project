// Kategóriák
const categories = [
    { id: 1, name: 'Motor és alkatrészek', icon: '⚙️', count: 324 },
    { id: 2, name: 'Fékrendszer', icon: '🛑', count: 287 },
    { id: 3, name: 'Felfüggesztés', icon: '🚗', count: 256 },
    { id: 4, name: 'Kipufogó rendszer', icon: '🔊', count: 145 },
    { id: 5, name: 'Villamosság', icon: '🔌', count: 312 },
    { id: 6, name: 'Kültéri alkatrészek', icon: '🚙', count: 278 },
    { id: 7, name: 'Beltéri alkatrészek', icon: '🛋️', count: 234 },
    { id: 8, name: 'Hűtő és fűtő rendszer', icon: '🌡️', count: 167 },
    { id: 9, name: 'Olajok és folyadékok', icon: '🛢️', count: 89 },
    { id: 10, name: 'Gumik és felnik', icon: '🛞', count: 156 },
    { id: 11, name: 'Lámpák és világítás', icon: '💡', count: 198 },
    { id: 12, name: 'Szerviz alkatrészek', icon: '🔧', count: 276 }
];

// Termékek
const products = {
    exhausts: [
        { id: 101, name: 'Hátsó kipufogó', price: 24990, originalPrice: 29990, icon: '🔊' },
        { id: 102, name: 'Kipufogócső', price: 15990, icon: '🔊' },
        { id: 103, name: 'Katalizátor', price: 45990, icon: '🔊' },
        { id: 104, name: 'Lefojtó', price: 12990, icon: '🔊' },
        { id: 105, name: 'Első kipufogó', price: 31990, icon: '🔊' },
        { id: 106, name: 'Kipufogó gyűjtő', price: 38990, icon: '🔊' },
        { id: 107, name: 'Kipufogó gumi', price: 2990, icon: '🔊' },
        { id: 108, name: 'Kipufogó tartó', price: 4990, icon: '🔊' },
        { id: 109, name: 'Sport kipufogó', price: 65990, originalPrice: 79990, icon: '🔊' },
        { id: 110, name: 'Kipufogó hangtompító', price: 18990, icon: '🔊' }
    ],
    brakes: [
        { id: 201, name: 'Fékbetét', price: 8990, icon: '🛑' },
        { id: 202, name: 'Féktárcsa', price: 19990, icon: '🛑' },
        { id: 203, name: 'Fékpofa', price: 14990, icon: '🛑' },
        { id: 204, name: 'Fékolaj', price: 3990, icon: '🛑' },
        { id: 205, name: 'Fékcső', price: 7990, icon: '🛑' },
        { id: 206, name: 'Féknyereg', price: 22990, icon: '🛑' },
        { id: 207, name: 'Fékpumpa', price: 35990, icon: '🛑' },
        { id: 208, name: 'ABS szabályozó', price: 42990, icon: '🛑' },
        { id: 209, name: 'Kézifék kábel', price: 6990, icon: '🛑' },
        { id: 210, name: 'Féknyomás érzékelő', price: 8990, icon: '🛑' }
    ],
    specialDeals: [
        { id: 301, name: 'Olajszűrő csomag', price: 5990, originalPrice: 7990, icon: '🛢️' },
        { id: 302, name: 'Légfilter', price: 4990, originalPrice: 6490, icon: '🌬️' },
        { id: 303, name: 'Gyújtógyertya', price: 2990, originalPrice: 3990, icon: '⚡' },
        { id: 304, name: 'Féktárcsa + betét', price: 22990, originalPrice: 28990, icon: '🛑' },
        { id: 305, name: 'Futómű csomag', price: 45990, originalPrice: 59990, icon: '🚗' },
        { id: 306, name: 'Akku + generátor', price: 68990, originalPrice: 84990, icon: '🔋' },
        { id: 307, name: 'Télgumi komplet', price: 89990, originalPrice: 119990, icon: '🛞' },
        { id: 308, name: 'Olajcsomag 5W-30', price: 12990, originalPrice: 16990, icon: '🛢️' }
    ],
    engine: [
        { id: 401, name: 'Hengerfej', price: 89990, icon: '⚙️' },
        { id: 402, name: 'Hengerfej tömítés', price: 12990, icon: '⚙️' },
        { id: 403, name: 'Olajszűrő', price: 3990, icon: '⚙️' },
        { id: 404, name: 'Légfilter', price: 5990, icon: '⚙️' },
        { id: 405, name: 'Üzemanyag szűrő', price: 4990, icon: '⚙️' },
        { id: 406, name: 'Generátor', price: 45990, icon: '⚙️' },
        { id: 407, name: 'Startmotor', price: 38990, icon: '⚙️' },
        { id: 408, name: 'Vízpumpa', price: 19990, icon: '⚙️' },
        { id: 409, name: 'Turbó', price: 129990, icon: '⚙️' },
        { id: 410, name: 'EGR szelep', price: 24990, icon: '⚙️' },
        { id: 411, name: 'Hűtőventilátor', price: 15990, icon: '⚙️' },
        { id: 412, name: 'Hajtáslánc', price: 32990, icon: '⚙️' },
        { id: 413, name: 'Hengerkopás mérő', price: 8990, icon: '⚙️' },
        { id: 414, name: 'Hűtőrács', price: 29990, icon: '⚙️' },
        { id: 415, name: 'Kipufogó gyűjtő', price: 38990, icon: '⚙️' }
    ],
    suspension: [
        { id: 501, name: 'Rugó', price: 12990, icon: '🚗' },
        { id: 502, name: 'Amortizátor', price: 19990, icon: '🚗' },
        { id: 503, name: 'Stabilizátor', price: 8990, icon: '🚗' },
        { id: 504, name: 'Kormánymű', price: 45990, icon: '🚗' },
        { id: 505, name: 'Kormányvég', price: 6990, icon: '🚗' },
        { id: 506, name: 'Gumitömés', price: 3990, icon: '🚗' },
        { id: 507, name: 'Kerékhajlítás', price: 15990, icon: '🚗' },
        { id: 508, name: 'Futómű csap', price: 7990, icon: '🚗' },
        { id: 509, name: 'Lengéscsillapító', price: 22990, icon: '🚗' },
        { id: 510, name: 'Felfüggesztés golyós', price: 4990, icon: '🚗' },
        { id: 511, name: 'Kormányrásegítő', price: 38990, icon: '🚗' },
        { id: 512, name: 'Futómű komplett', price: 89990, icon: '🚗' }
    ],
    electrical: [
        { id: 601, name: 'Akkumulátor', price: 29990, icon: '🔌' },
        { id: 602, name: 'Gyújtótekercs', price: 15990, icon: '🔌' },
        { id: 603, name: 'Gyújtógyertya', price: 2990, icon: '🔌' },
        { id: 604, name: 'Gyújtótrafó', price: 12990, icon: '🔌' },
        { id: 605, name: 'Biztosíték', price: 990, icon: '🔌' },
        { id: 606, name: 'Relé', price: 2990, icon: '🔌' },
        { id: 607, name: 'Kábelköteg', price: 45990, icon: '🔌' },
        { id: 608, name: 'ECU', price: 89990, icon: '🔌' },
        { id: 609, name: 'Szenzor', price: 7990, icon: '🔌' },
        { id: 610, name: 'Generátor', price: 45990, icon: '🔌' },
        { id: 611, name: 'Startmotor', price: 38990, icon: '🔌' },
        { id: 612, name: 'Töltőrendszer', price: 19990, icon: '🔌' }
    ],
    exterior: [
        { id: 701, name: 'Lökhárító', price: 29990, icon: '🚙' },
        { id: 702, name: 'Lámpa', price: 19990, icon: '🚙' },
        { id: 703, name: 'Tükör', price: 12990, icon: '🚙' },
        { id: 704, name: 'Kilincs', price: 5990, icon: '🚙' },
        { id: 705, name: 'Ablaktörlő', price: 7990, icon: '🚙' },
        { id: 706, name: 'Szélvédő', price: 45990, icon: '🚙' },
        { id: 707, name: 'Ajtó', price: 69990, icon: '🚙' },
        { id: 708, name: 'Motorháztető', price: 59990, icon: '🚙' },
        { id: 709, name: 'Csomagtér', price: 49990, icon: '🚙' },
        { id: 710, name: 'Sárhányó', price: 8990, icon: '🚙' },
        { id: 711, name: 'Grill', price: 15990, icon: '🚙' },
        { id: 712, name: 'Bumperszelep', price: 3990, icon: '🚙' }
    ],
    interior: [
        { id: 801, name: 'Ülés', price: 45990, icon: '🛋️' },
        { id: 802, name: 'Kormánykerék', price: 19990, icon: '🛋️' },
        { id: 803, name: 'Műszerfal', price: 69990, icon: '🛋️' },
        { id: 804, name: 'Klíma', price: 39990, icon: '🛋️' },
        { id: 805, name: 'Rádió', price: 29990, icon: '🛋️' },
        { id: 806, name: 'Hangszóró', price: 12990, icon: '🛋️' },
        { id: 807, name: 'Kárpit', price: 8990, icon: '🛋️' },
        { id: 808, name: 'Biztonsági öv', price: 7990, icon: '🛋️' },
        { id: 809, name: 'Tábla', price: 5990, icon: '🛋️' },
        { id: 810, name: 'Kapcsoló', price: 2990, icon: '🛋️' },
        { id: 811, name: 'Vezérlés', price: 15990, icon: '🛋️' },
        { id: 812, name: 'Kárpitozás', price: 19990, icon: '🛋️' }
    ],
    cooling: [
        { id: 901, name: 'Hűtő', price: 29990, icon: '🌡️' },
        { id: 902, name: 'Hűtőventilátor', price: 15990, icon: '🌡️' },
        { id: 903, name: 'Hűtőfolyadék', price: 3990, icon: '🌡️' },
        { id: 904, name: 'Termosztát', price: 5990, icon: '🌡️' },
        { id: 905, name: 'Hűtőcső', price: 7990, icon: '🌡️' },
        { id: 906, name: 'Fűtés', price: 19990, icon: '🌡️' },
        { id: 907, name: 'Klíma', price: 39990, icon: '🌡️' },
        { id: 908, name: 'Kompresszor', price: 45990, icon: '🌡️' },
        { id: 909, name: 'Kondenzátor', price: 22990, icon: '🌡️' },
        { id: 910, name: 'Hőcserélő', price: 17990, icon: '🌡️' }
    ],
    oils: [
        { id: 1001, name: 'Motorolaj 5W-30', price: 6990, icon: '🛢️' },
        { id: 1002, name: 'Motorolaj 10W-40', price: 5990, icon: '🛢️' },
        { id: 1003, name: 'Fékolaj', price: 3990, icon: '🛢️' },
        { id: 1004, name: 'Hidraulikai olaj', price: 4990, icon: '🛢️' },
        { id: 1005, name: 'Sebességváltó olaj', price: 7990, icon: '🛢️' },
        { id: 1006, name: 'Differenciál olaj', price: 6990, icon: '🛢️' },
        { id: 1007, name: 'Fűtőolaj', price: 2990, icon: '🛢️' },
        { id: 1008, name: 'Kenőolaj', price: 1990, icon: '🛢️' },
        { id: 1009, name: 'Szilikontömítő', price: 3990, icon: '🛢️' },
        { id: 1010, name: 'Fékfolyadék', price: 2990, icon: '🛢️' }
    ],
    tires: [
        { id: 1101, name: 'Nyári gumi 195/65 R15', price: 29990, icon: '🛞' },
        { id: 1102, name: 'Téli gumi 205/55 R16', price: 35990, icon: '🛞' },
        { id: 1103, name: 'All-season gumi', price: 32990, icon: '🛞' },
        { id: 1104, name: 'Felnik 16"', price: 49990, icon: '🛞' },
        { id: 1105, name: 'Felnik 17"', price: 59990, icon: '🛞' },
        { id: 1106, name: 'Felnicsavar', price: 2990, icon: '🛞' },
        { id: 1107, name: 'Gumijavító készlet', price: 4990, icon: '🛞' },
        { id: 1108, name: 'Tömlő', price: 3990, icon: '🛞' },
        { id: 1109, name: 'Gumiabroncs', price: 19990, icon: '🛞' },
        { id: 1110, name: 'Felniborítás', price: 8990, icon: '🛞' }
    ],
    lights: [
        { id: 1201, name: 'Első lámpa', price: 19990, icon: '💡' },
        { id: 1202, name: 'Hátsó lámpa', price: 15990, icon: '💡' },
        { id: 1203, name: 'Irányjelző', price: 6990, icon: '💡' },
        { id: 1204, name: 'Fényszóró', price: 22990, icon: '💡' },
        { id: 1205, name: 'Hátsó ködlámpa', price: 8990, icon: '💡' },
        { id: 1206, name: 'Első ködlámpa', price: 12990, icon: '💡' },
        { id: 1207, name: 'Belső világítás', price: 4990, icon: '💡' },
        { id: 1208, name: 'LED izzó', price: 3990, icon: '💡' },
        { id: 1209, name: 'Halogén izzó', price: 2990, icon: '💡' },
        { id: 1210, name: 'Xenon izzó', price: 7990, icon: '💡' }
    ],
    service: [
        { id: 1301, name: 'Szűrőcsomag', price: 12990, icon: '🔧' },
        { id: 1302, name: 'Fékcsomag', price: 29990, icon: '🔧' },
        { id: 1303, name: 'Futómű csomag', price: 45990, icon: '🔧' },
        { id: 1304, name: 'Olajcsere csomag', price: 17990, icon: '🔧' },
        { id: 1305, name: 'Gyújtáscsomag', price: 15990, icon: '🔧' },
        { id: 1306, name: 'Tömítőcsomag', price: 8990, icon: '🔧' },
        { id: 1307, name: 'Szerviz készlet', price: 69990, icon: '🔧' },
        { id: 1308, name: 'Javító készlet', price: 29990, icon: '🔧' },
        { id: 1309, name: 'Tuning csomag', price: 89990, icon: '🔧' },
        { id: 1310, name: 'Karosszéria csomag', price: 59990, icon: '🔧' }
    ]
};

// Márkák
const brands = [
    'BOSCH', 'VALEO', 'MANN', 'CONTINENTAL', 'NGK', 'BREMBO', 
    'DELPHI', 'DENSO', 'HELLA', 'MAHLE', 'PIERBURG', 'SKF',
    'MONROE', 'BILSTEIN', 'KYB', 'SACHS', 'LUK', 'ZF',
    'VDO', 'BERU', 'CHAMPION', 'BOSAL', 'WALKER', 'MAGNETI MARELLI'
];