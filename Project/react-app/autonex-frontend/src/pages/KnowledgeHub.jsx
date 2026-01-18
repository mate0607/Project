import { useState, useEffect } from "react";

export default function KnowledgeHub() {
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedArticle, setSelectedArticle] = useState(null);

  // Knowledge Hub Data
  const knowledgeBase = [
    // Általános problémák
    {
      id: 1,
      title: "Motor túlhevülése",
      category: "common-issues",
      summary: "A motor hőmérséklete a normál működési tartomány felett emelkedik",
      cost: "$200-$800",
      description: "A motor túlhevülése komoly probléma, amely motorkárosodáshoz vezethet, ha nem foglalkozunk vele azonnal.",
      causes: [
        "Alacsony hűtőfolyadék szint",
        "Hibás termosztát",
        "Törött vízpumpa",
        "Radiátor szivárgás"
      ],
      symptoms: [
        "Hőmérsékleti mutató pirosban",
        "Gőz a motor teréből",
        "Édes illat a motor teréből",
        "Teljesítménykiesés"
      ],
      tips: [
        "Soha ne nyisd meg a radiátorsapkát melegebb motornál",
        "Hideg motornál ellenőrizd a hűtőfolyadék szintet",
        "Szabályos hűtőfolyadék csere megelőzi a lerakódásokat",
        "Győződj meg a radiátorszellőztetőről"
      ]
    },
    {
      id: 2,
      title: "Check Engine fény",
      category: "common-issues",
      summary: "Az műszerfal figyelmeztető lámpa kibocsátási rendszer problémáját jelzi",
      cost: "$100-$500",
      description: "A check engine lámpa egy problémát jelöl, amelyet a járműveid fedezése fel",
      causes: [
        "Laza benzintöltő sapka",
        "Hibás oxigénszenzor",
        "Rossz katalitikus konverter",
        "Motor elkapások"
      ],
      symptoms: [
        "Narancssárga/sárga check engine lámpa",
        "Rövid üresjárat",
        "Rossz üzemanyag-fogyasztás",
        "Nehéz indítás"
      ],
      tips: [
        "Diagnosztikai vizsgálat a kód azonosításához",
        "Szoros vagy cseréld le a benzintöltő sapkát",
        "Ne hagyd figyelmen kívül a figyelmeztető lámpát",
        "Szabályos karbantartás megelőzi a problémákat"
      ]
    },
    {
      id: 3,
      title: "Sebességváltó csúszása",
      category: "common-issues",
      summary: "A motor fordulatszáma nő, de a jármű nem gyorsul megfelelően",
      cost: "$1,500-$3,500",
      description: "A sebességváltó csúszása azt jelenti, hogy a motorod ereje nem jut el megfelelően a kerekekhez.",
      causes: [
        "Alacsony sebességváltó folyadék",
        "Elhasznált sebességváltó szalagok",
        "Hibás nyomatékkonverter",
        "Belső sebességváltó kár"
      ],
      symptoms: [
        "RPM-növekedés sebesség nélkül",
        "Késleltetett gyorsulás",
        "Égő illat",
        "Szokatlan hangok"
      ],
      tips: [
        "Ellenőrizd a sebességváltó folyadékot (pirosnak kell lennie)",
        "Cseréld le a folyadékot az ajánlott időközönként",
        "Kerüld meg a vontatást alacsony folyadékszinttel",
        "Ne hagyd figyelmen kívül a korai figyelmeztető jeleket"
      ]
    },
    {
      id: 4,
      title: "Féktöltés kopása",
      category: "common-issues",
      summary: "Féktöltések elvékonyodnak és csere szükséges",
      cost: "$150-$400",
      description: "A féktöltések fokozatosan elhasználódnak, és rendszeres cserére van szükség a biztonság érdekében.",
      causes: [
        "Normál kopás és elhasználódás",
        "Agresszív fékezési szokások",
        "Rossz féktöltés minőség",
        "Rosszul igazított féknyergek"
      ],
      symptoms: [
        "Fékezés figyelmeztető lámpa",
        "Csikorgás vagy füttyögés",
        "Hosszabb megállási távolságok",
        "Féket puha érzékelsz"
      ],
      tips: [
        "Cseréld le a töltéseket, mielőtt teljesen elhasználódnak",
        "Használj szelíd fékezést, amikor lehetséges",
        "Éves fék ellenőrzés",
        "Cseréld le az elülső és hátsó töltéseket együtt"
      ]
    },

    // Karbantartás
    {
      id: 5,
      title: "Motorolaj csere",
      category: "maintenance",
      summary: "Szabályos motorolaj csere egészséges motort tart és sima futást",
      cost: "$30-$75",
      description: "A szabályos motorolaj csere a legfontosabb karbantartási feladat a motor élettartamának meghosszabbítására.",
      causes: [],
      symptoms: [],
      tips: [
        "Cseréld le az olajat 3,000-7,500 mílionként az olaj típusától függően",
        "Havi szinten ellenőrizd az olajszintet",
        "Használj a gyártó által ajánlott olajmennyiséget",
        "Cseréld le az olajszűrőt minden motorolaj cserénél",
        "Nyomon követd az olajcsere dátumait"
      ]
    },
    {
      id: 6,
      title: "Gumiabroncs forgat",
      category: "maintenance",
      summary: "Mozgass gumiabroncsakat különböző pozíciókba az egyenletes kopásért",
      cost: "$20-$60",
      description: "A gumiabroncs forgatás meghosszabbítja a gumiabroncs élettartamát és javítja a jármű kezelhetőségét az egyenletes futófelület kopása által.",
      causes: [],
      symptoms: [],
      tips: [
        "Forgasd meg a gumiabroncsakat 5,000-7,000 mílionként",
        "Ellenőrizd a gumiabroncs nyomást havi szinten (hideg állapotban)",
        "Tartsd meg a megfelelő szögbeállítást",
        "Cseréld le az összes gumiabroncsakat, amikor az egyik jelentősen elhasználódott",
        "Nyomon követd a futófelület mélységét penny teszt segítségével"
      ]
    },
    {
      id: 7,
      title: "Légszűrő csere",
      category: "maintenance",
      summary: "Tiszta légszűrők biztosítanak hatékony motor teljesítményt",
      cost: "$15-$40",
      description: "A légszűrők megakadályozzák a port és szennyeződéseket a motortól, javítva az üzemanyag-hatékonyságot.",
      causes: [],
      symptoms: [],
      tips: [
        "Cseréld le a motor légszűrőt 12-15 havonta",
        "Cseréld le a kabin légszűrőt 12-15 havonta",
        "Vizsgáld meg a szűrőket vizuálisan a nagy szennyeződésekre",
        "Az eltömődött szűrők csökkentik az üzemanyag-fogyasztást",
        "Használj OEM vagy minőségi utángyártott szűrőket"
      ]
    },
    {
      id: 8,
      title: "Akkumulátor karbantartás",
      category: "maintenance",
      summary: "Tartsd tisztán az akkumulátort és rendszeresen ellenőrizd a csatlakozásokat",
      cost: "$100-$200",
      description: "Az akkumulátor karbantartása biztosítja a megbízható indítást és a megfelelő elektromos rendszer működését.",
      causes: [],
      symptoms: [],
      tips: [
        "Ellenőrizd az akkumulátor termináljait korróziós lerakódásokra",
        "Tisztítsd meg a termináljait szódabikarbónával és vízzel",
        "Cseréld le az akkumulátort 3-5 évente",
        "Tesztelje az akkumulátort tél előtt",
        "Tartsd az akkumulátort feltöltve hideg időben"
      ]
    },

    // OBD kódok
    {
      id: 9,
      title: "P0128 - Hűtőfolyadék hőmérséklete",
      category: "obd-codes",
      summary: "A motor hűtőfolyadékja nem éri el a működési hőmérsékletet",
      cost: "$100-$400",
      description: "Ez a kód azt jelzi, hogy a termosztát vagy hűtési rendszer nem tartja meg a megfelelő hőmérsékletet.",
      causes: [
        "Hibás termosztát",
        "Rossz hűtőfolyadék hőmérséklet szenzor",
        "Alacsony hűtőfolyadék szint",
        "Vízpumpa hiba"
      ],
      symptoms: [
        "Rossz üzemanyag-fogyasztás",
        "Check engine lámpa",
        "Nehéz indítás hidegben",
        "Túlzott kibocsátás"
      ],
      tips: [
        "Ne hagyd figyelmen kívül ezt a kódot",
        "Azonnal diagnosztikai vizsgálat",
        "Először ellenőrizd a hűtőfolyadék szintet",
        "A termosztát csere gyakori javítás"
      ]
    },
    {
      id: 10,
      title: "P0171 - Üzemanyag rendszer túl szegény",
      category: "obd-codes",
      summary: "A motor túl kevés üzemanyagot fut a levegő mennyiségéhez képest",
      cost: "$150-$500",
      description: "Ez a kód azt jelenti, hogy az üzemanyag-levegő keverékeid nem megfelelő.",
      causes: [
        "Hibás oxigénszenzor",
        "Rossz üzemanyag-befecskendezők",
        "Vákuum szivárgás",
        "Meghibásodott üzemanyag-szivattyú"
      ],
      symptoms: [
        "Check engine lámpa",
        "Rossz gyorsulás",
        "Rövid üresjárat",
        "Alacsony üzemanyag-fogyasztás"
      ],
      tips: [
        "Keress vákuum szivárgásokat",
        "Tisztítsd meg vagy cseréld le az üzemanyag-befecskendezőket",
        "Tesztelje az oxigénszenzorokat",
        "Használj minőségi üzemanyagot"
      ]
    },
    {
      id: 11,
      title: "P0301 - 1. henger elkapás",
      category: "obd-codes",
      summary: "Az 1. henger nem működik megfelelően, vibráció és teljesítménykiesés okozva",
      cost: "$200-$600",
      description: "Az elkapás azt jelenti, hogy az üzemanyag-levegő keverék nem gyullad meg megfelelően az adott hengerben.",
      causes: [
        "Elhasznált gyújt gyertyák",
        "Rossz gyújtási tekercs",
        "Üzemanyag-befecskendező problémák",
        "Alacsony tömörítés"
      ],
      symptoms: [
        "Motor vibráció",
        "Check engine lámpa",
        "Rossz gyorsulás",
        "Kemény futás"
      ],
      tips: [
        "Cseréld le a gyújt gyertyákat rendszeresen",
        "Használj helyes típusú gyújt gyertyákat",
        "Ellenőrizd az üzemanyag-befecskendező spray mintáját",
        "Tömörítési vizsgálat szükséges lehet"
      ]
    },
    {
      id: 12,
      title: "P0420 - Katalitikus konverter hatékonysága",
      category: "obd-codes",
      summary: "A katalitikus konverter nem működik hatékonyan",
      cost: "$400-$1,200",
      description: "Ez a kód azt jelzi, hogy a katalitikus konverter nem tisztítja meg a kipufogógázt megfelelően.",
      causes: [
        "Hibás katalitikus konverter",
        "Rossz oxigénszenzor",
        "Motor túl szegény",
        "Kipufogó szivárgás"
      ],
      symptoms: [
        "Check engine lámpa",
        "Rossz üzemanyag-fogyasztás",
        "Csökkent teljesítmény",
        "Rothadó tojás illat"
      ],
      tips: [
        "Először javítsd meg az üzemanyag rendszer problémáit",
        "Használj minőségi üzemanyagot és olajat",
        "Kerüld meg a rövid utakat, amelyek megelőzik a felmelegedést",
        "Cseréld le a konvertert, ha sérült"
      ]
    },

    // További általános problémák
    {
      id: 13,
      title: "Akkumulátor lemerülése",
      category: "common-issues",
      summary: "Az akkumulátor nem képes az autót beindítani",
      cost: "$100-$200",
      description: "Az akkumulátor lemerülése akkor fordul elő, amikor nincs elegendő energia az indítómotor működtetéséhez.",
      causes: [
        "Elöregedett akkumulátor",
        "Rossz töltési rendszer",
        "Felejtett fények",
        "Hideg időjárás"
      ],
      symptoms: [
        "Lassú fordulások az indítás alatt",
        "Kattanó hangok",
        "Az autó nem indul el",
        "Halványabb fények"
      ],
      tips: [
        "Helyesen egyenesítsd az akkumulátor termináljait",
        "Használj jumper kábelt másik autóhoz csatlakoztatva",
        "Cseréld le az akkumulátort 3-5 évente",
        "Elkerüld az autó hosszú ideig történő leállítottságát"
      ]
    },
    {
      id: 14,
      title: "Fékpedál puha érzékelsz",
      category: "common-issues",
      summary: "A fékpedál lenyomásakor szpongiózus vagy puha érzékelsz",
      cost: "$150-$500",
      description: "A puha fékpedál általában azt jelzi, hogy levegő került a féktöltésbe vagy a fékfolyadék alacsony szinten van.",
      causes: [
        "Levegő a féktöltésben",
        "Alacsony fékfolyadék szint",
        "Szivárgó fékcső",
        "Hibás főfékhenger"
      ],
      symptoms: [
        "Puha vagy szpongiózus pedál érzékelsz",
        "Hosszabb fékezési távolságok",
        "Féklámpa világít",
        "Fékfolyadék szintje csökkent"
      ],
      tips: [
        "Azonnal ellenőrizd a fékfolyadék szintjét",
        "Kerüld meg a vezetést, amíg meg nem szerzed",
        "Légtöltés szükséges lehet",
        "Cseréld ki a sérült fékcsövet"
      ]
    },
    {
      id: 15,
      title: "Villanymotor nem működik",
      category: "common-issues",
      summary: "Az elektromos rendszer alapvetően nem működik",
      cost: "$200-$800",
      description: "Az elektromos meghibásodások számos problémát okozhatnak, az indítástól az fényre.",
      causes: [
        "Meghibásodott alternátor",
        "Rossz elektromos vezetékek",
        "Hibás relék vagy biztosítékok",
        "Lemerült akkumulátor"
      ],
      symptoms: [
        "Az autó nem indul el",
        "Fények nem működnek",
        "Műszerfal nem működik",
        "Akusztikus jeleket nem hallunk"
      ],
      tips: [
        "Először ellenőrizd a biztosítékokat",
        "Kerülj rá az akumulátor csatlakozásokra",
        "Tesztelje az alternátort",
        "Ellenőrizd az összes elektromos vezetéket"
      ]
    },

    // További karbantartási tippek
    {
      id: 16,
      title: "Gyújt gyertya csere",
      category: "maintenance",
      summary: "Szokásos gyújt gyertya cserék javítják a motort teljesítményt",
      cost: "$20-$100",
      description: "A gyújt gyertyákat rendszeresen cserélni kell a megfelelő motor gyújtásért és teljesítményért.",
      causes: [],
      symptoms: [],
      tips: [
        "Cseréld le a gyújt gyertyákat 30,000-100,000 mílionként",
        "Használj a gyártó által ajánlott típusokat",
        "Ellenőrizd a gyertya szövetét egyenletes elfogyasztásért",
        "Vékony szürke vagy fehér lerakódások normálisak",
        "Fekete korom azt jelzi, hogy az autó túl gazdag"
      ]
    },
    {
      id: 17,
      title: "Szellőztetőfolyadék csere",
      category: "maintenance",
      summary: "A szellőztetőfolyadék lecserélése megelőzi a motorfagyást és a rozsda kialakulását",
      cost: "$25-$75",
      description: "A szellőztetőfolyadék védi a motort a fagyástól és korróziótól, ezért rendszeresen cserélni kell.",
      causes: [],
      symptoms: [],
      tips: [
        "Cseréld le a szellőztetőfolyadékot 12-15 havonta",
        "Ellenőrizd a szellőztetőfolyadék szintjét havi szinten",
        "Soha ne nyisd meg a radiátorsapkát melegebb motornál",
        "Használj a gyártó által ajánlott típusokat",
        "A régi folyadék eldobása megfelelően"
      ]
    },
    {
      id: 18,
      title: "Fékmester ellenőrzés",
      category: "maintenance",
      summary: "Szabályos fékmester ellenőrzés biztosítja az autó biztonságát",
      cost: "$50-$150",
      description: "A fékmester a fékrendszer lényeges összetevője, amely vizsgálat nélkül meghibásodhat.",
      causes: [],
      symptoms: [],
      tips: [
        "Éves fék ellenőrzés javasolt",
        "Hallgasd meg a szokásostól eltérő hangokat",
        "Teljes fékellenőrzés 60,000 mílionként",
        "Cseréld le a fékfolyadékot a javasolt időközönként",
        "A fékhígító nem helyettesítheti a teljes ellenőrzést"
      ]
    },
    {
      id: 19,
      title: "Biztosítékok és relék",
      category: "maintenance",
      summary: "A biztosítékok és relék ellenőrzése megelőzi az elektromos problémákat",
      cost: "$10-$50",
      description: "A biztosítékok és relék egyszerűen cserélhető alkatrészek, amelyek elektromos problémákat okozhatnak.",
      causes: [],
      symptoms: [],
      tips: [
        "Ismerd meg a biztosítékeloszlást az autódban",
        "Cseréld le az égett biztosítékokat azonosraként",
        "Ne használj magasabb amperós biztosítékokat",
        "Relék rendszeresen tesztelendőek",
        "Tárolj tartalék biztosítékokat az autóban"
      ]
    },

    // További OBD kódok
    {
      id: 20,
      title: "P0300 - Több hengerek elkapása",
      category: "obd-codes",
      summary: "Több henger nem működik megfelelően",
      cost: "$300-$1,000",
      description: "Ez a kód azt jelzi, hogy több henger elkapása történt, amely súlyos problémát jelent.",
      causes: [
        "Elhasznált gyújt gyertyák",
        "Rossz üzemanyag",
        "Motor alapbeállítás hibás",
        "Feszültség probléma"
      ],
      symptoms: [
        "Erős motor vibráció",
        "Check engine lámpa",
        "Teljesítménykiesés",
        "Rossz üzemanyag-fogyasztás"
      ],
      tips: [
        "Azonnal cseréld le a gyújt gyertyákat",
        "Ellenőrizz minden gyújtási tekercset",
        "Diagnosztikai vizsgálat szükséges",
        "Ne vezetess nagyobb sebességgel"
      ]
    },
    {
      id: 21,
      title: "P0500 - Sebesség szenzor",
      category: "obd-codes",
      summary: "A sebességszenzor hibásan működik",
      cost: "$150-$400",
      description: "A sebességszenzor hiba a sebességváltó és sebességmérő problémákat okozhat.",
      causes: [
        "Hibás sebességszenzor",
        "Szenzor vezeték szakadása",
        "Rossz zárása",
        "Szenzor szennyeződése"
      ],
      symptoms: [
        "Sebességmérő nem működik",
        "Sebességváltó problémák",
        "Check engine lámpa",
        "Szokatlan motor viselkedés"
      ],
      tips: [
        "Ellenőrizd a szenzor csatlakozásait",
        "Tisztítsd meg a szennyezett szenzort",
        "Mérj végig az érzékelőt",
        "Szükség esetén cseréld le"
      ]
    },
    {
      id: 22,
      title: "P0133 - Oxigénszenzor",
      category: "obd-codes",
      summary: "Az oxigénszenzor hibásan működik",
      cost: "$200-$400",
      description: "Az oxigénszenzor kritikus a motor vezérléshez és az emissziók csökkentéséhez.",
      causes: [
        "Hibás oxigénszenzor",
        "Szenzor vezeték szakadása",
        "Kiégett szenzor",
        "Szenzor szennyeződése"
      ],
      symptoms: [
        "Check engine lámpa",
        "Rossz üzemanyag-fogyasztás",
        "Motor instabil",
        "Rossz teljesítmény"
      ],
      tips: [
        "Az oxigénszenzor élettartama 80,000 mílió",
        "Cseréld le az elöregedett szenzort",
        "Ellenőrizz a szenzor vezetékeket",
        "Diagnosztikai vizsgálat szükséges"
      ]
    },
    {
      id: 23,
      title: "P0135 - Oxigénszenzor fűtés",
      category: "obd-codes",
      summary: "Az oxigénszenzor fűtési elem hibásan működik",
      cost: "$150-$300",
      description: "Az oxigénszenzor fűtési elem segít gyorsan eléri az üzemi hőmérsékletet.",
      causes: [
        "Hibás fűtési elem",
        "Szenzor vezeték szakadása",
        "Biztosítékot vagy reléeléletlenség"
      ],
      symptoms: [
        "Check engine lámpa",
        "Lassú szenzor válasz",
        "Fogyasztó fogyasztás emelkedik"
      ],
      tips: [
        "Ellenőrizd a biztosítékokat és relét",
        "Tesztelje a fűtési elemet",
        "Szenzor vezetékek megtekintése",
        "Szükség esetén cseréld a szenzort"
      ]
    },
    {
      id: 24,
      title: "P0455 - Szivárgó benzintöltő sapka",
      category: "obd-codes",
      summary: "A benzintöltő sapka eltávozik vagy sérült",
      cost: "$10-$50",
      description: "Egy eltávolt vagy sérült benzintöltő sapka kibocsátási problémákat okozhat.",
      causes: [
        "Elveszett benzintöltő sapka",
        "Sérült benzintöltő sapka",
        "Gyenge szivárgás a szikra"
      ],
      symptoms: [
        "Check engine lámpa",
        "Szag benzin az autóban"
      ],
      tips: [
        "Először szoros vagy cseréld le a sapkát",
        "Olcsó és egyszerű javítás",
        "A hibakód törléséhez újra start",
        "Használj eredeti sapkákat"
      ]
    }
  ];

  const categories = [
    { id: "all", name: "Minden cikk", icon: "📚" },
    { id: "common-issues", name: "Általános problémák", icon: "⚠️" },
    { id: "maintenance", name: "Karbantartás", icon: "🔧" },
    { id: "obd-codes", name: "OBD kódok", icon: "🔍" }
  ];

  // Filter articles based on category and search
  const filteredArticles = knowledgeBase.filter(article => {
    const matchesCategory = selectedCategory === "all" || article.category === selectedCategory;
    const matchesSearch = 
      article.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      article.summary.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  if (selectedArticle) {
    const article = knowledgeBase.find(a => a.id === selectedArticle);
    return (
      <div style={{ maxWidth: '900px', margin: '30px auto', padding: '0 20px' }}>
        <button 
          onClick={() => setSelectedArticle(null)}
          style={{ padding: '8px 16px', marginBottom: '20px', cursor: 'pointer' }}
        >
          ← Vissza
        </button>

        <div className="card" style={{ padding: '24px' }}>
          <h1 style={{ margin: '0 0 16px 0', fontSize: '2rem' }}>{article.title}</h1>
          
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', marginBottom: '20px', padding: '16px', background: 'rgba(15, 23, 42, 0.5)', borderRadius: '8px' }}>
            <div>
              <p style={{ color: '#94A3B8', margin: '0 0 4px 0', fontSize: '0.875rem' }}>Becsült költség</p>
              <p style={{ color: '#22C55E', margin: '0', fontSize: '1.25rem', fontWeight: 'bold' }}>{article.cost}</p>
            </div>
            <div>
              <p style={{ color: '#94A3B8', margin: '0 0 4px 0', fontSize: '0.875rem' }}>Kategória</p>
              <p style={{ color: '#818CF8', margin: '0', fontSize: '1rem', fontWeight: 'bold' }}>
                {categories.find(c => c.id === article.category)?.name}
              </p>
            </div>
          </div>

          <h2 style={{ margin: '20px 0 12px 0' }}>Leírás</h2>
          <p style={{ color: '#CBD5E1', lineHeight: '1.6', marginBottom: '20px' }}>{article.description}</p>

          {article.causes.length > 0 && (
            <>
              <h2 style={{ margin: '20px 0 12px 0' }}>Lehetséges okok</h2>
              <ul style={{ color: '#CBD5E1', marginBottom: '20px', paddingLeft: '20px' }}>
                {article.causes.map((cause, idx) => (
                  <li key={idx} style={{ marginBottom: '8px' }}>{cause}</li>
                ))}
              </ul>
            </>
          )}

          {article.symptoms.length > 0 && (
            <>
              <h2 style={{ margin: '20px 0 12px 0' }}>Tünetek</h2>
              <ul style={{ color: '#CBD5E1', marginBottom: '20px', paddingLeft: '20px' }}>
                {article.symptoms.map((symptom, idx) => (
                  <li key={idx} style={{ marginBottom: '8px' }}>{symptom}</li>
                ))}
              </ul>
            </>
          )}

          <h2 style={{ margin: '20px 0 12px 0' }}>Praktikus tippek</h2>
          <ul style={{ color: '#CBD5E1', paddingLeft: '20px' }}>
            {article.tips.map((tip, idx) => (
              <li key={idx} style={{ marginBottom: '8px' }}>{tip}</li>
            ))}
          </ul>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: '1200px', margin: '30px auto', padding: '0 20px' }}>
      <h1 style={{ margin: '0 0 30px 0' }}>Tudásbázis</h1>

      {/* Keresés */}
      <div style={{ marginBottom: '24px' }}>
        <input
          type="text"
          placeholder="Keress a tudásbázisban..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          style={{
            width: '100%',
            padding: '12px 16px',
            fontSize: '1rem',
            borderRadius: '8px',
            border: '1px solid rgba(148, 163, 184, 0.2)',
            background: 'rgba(15, 23, 42, 0.5)',
            color: '#F1F5F9',
            marginBottom: '16px'
          }}
        />
      </div>

      {/* Kategória navigáció */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(150px, 1fr))', gap: '12px', marginBottom: '24px' }}>
        {categories.map(cat => (
          <button
            key={cat.id}
            onClick={() => setSelectedCategory(cat.id)}
            style={{
              padding: '12px 16px',
              textAlign: 'center',
              background: selectedCategory === cat.id ? '#818CF8' : 'rgba(129, 140, 248, 0.2)',
              border: '1px solid rgba(129, 140, 248, 0.3)',
              color: selectedCategory === cat.id ? '#FFF' : '#818CF8',
              borderRadius: '8px',
              cursor: 'pointer',
              fontSize: '0.9rem',
              fontWeight: '500'
            }}
          >
            {cat.icon} {cat.name}
          </button>
        ))}
      </div>

      {/* Cikkek lista */}
      {filteredArticles.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '40px' }}>
          <p style={{ color: '#94A3B8', fontSize: '1.125rem' }}>Nincs találat a kereséshez "{searchQuery}"</p>
        </div>
      ) : (
        <div style={{ display: 'grid', gap: '12px' }}>
          {filteredArticles.map(article => (
            <div
              key={article.id}
              className="card"
              onClick={() => setSelectedArticle(article.id)}
              style={{ cursor: 'pointer', transition: 'transform 0.2s', padding: '16px' }}
              onMouseEnter={(e) => e.currentTarget.style.transform = 'translateY(-2px)'}
              onMouseLeave={(e) => e.currentTarget.style.transform = 'translateY(0)'}
            >
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', gap: '16px' }}>
                <div style={{ flex: 1 }}>
                  <h3 style={{ margin: '0 0 8px 0', fontSize: '1.125rem' }}>{article.title}</h3>
                  <p style={{ color: '#94A3B8', margin: '0 0 8px 0' }}>{article.summary}</p>
                  <p style={{ color: '#64748B', margin: '0', fontSize: '0.875rem' }}>
                    {categories.find(c => c.id === article.category)?.icon} {categories.find(c => c.id === article.category)?.name}
                  </p>
                </div>
                <div style={{ textAlign: 'right', whiteSpace: 'nowrap' }}>
                  <p style={{ color: '#22C55E', margin: '0', fontWeight: 'bold' }}>{article.cost}</p>
                  <p style={{ color: '#818CF8', margin: '8px 0 0 0', fontSize: '0.875rem' }}>Részletek →</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
