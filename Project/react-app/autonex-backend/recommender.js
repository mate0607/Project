function recommend({ category, description }) {
  const text = (description || "").toLowerCase();
  const cat = (category || "").toLowerCase();

  const rules = [
    {
      match: () => cat === "brake" || /fék|csikorog|pedál|fékez/.test(text),
      service: "Fékrendszer átvizsgálás + fékbetét/féktárcsa ellenőrzés",
      explanation: "Fék jellegű tüneteknél elsőként biztonsági okból fékvizsgálat javasolt.",
      min: 8000,
      max: 45000,
    },
    {
      match: () => cat === "engine" || /check engine|rángat|erőtlen|dadog|motor/.test(text),
      service: "OBD diagnosztika + motor hibakód kiolvasás",
      explanation: "Motor jellegű panaszoknál a hibakód kiolvasás gyorsan szűkíti a hibát.",
      min: 7000,
      max: 20000,
    },
    {
      match: () => cat === "suspension" || /kopog|futómű|kanyar|ráz|zörög/.test(text),
      service: "Futómű átvizsgálás (szilent, gömbfej, stabpálca)",
      explanation: "Kopogás/rázás gyakran futómű kopás; rázópad + vizsgálat ajánlott.",
      min: 10000,
      max: 50000,
    },
    {
      match: () => cat === "electric" || /nem indul|akku|indít|indítómotor|generátor|tölt/.test(text),
      service: "Akkuteszt + töltőrendszer ellenőrzés",
      explanation: "Indítási gondoknál az akku/generátor ellenőrzés tipikus első lépés.",
      min: 5000,
      max: 25000,
    },
    {
    match: () => cat === "cooling" || /túlmelegszik|hűtővíz|vízpumpa|hengerfej|hűtő/.test(text),
    service: "Hűtőrendszer diagnosztika",
    explanation: "Melegedésnél tipikus a hűtőfolyadék hiány, termosztát vagy vízpumpa gond.",
    min: 8000,
    max: 60000,
  },
  {
    match: () => cat === "transmission" || /váltó|sebesség|kuplung|csúszik|recseg/.test(text),
    service: "Váltó/kuplung vizsgálat",
    explanation: "Recsegés vagy csúszás esetén a váltó és kuplung hibáit kell kizárni.",
    min: 15000,
    max: 120000,
  },
  {
    match: () => cat === "aircon" || /klíma|nem fúj|hideg|meleg levegő|gáz/.test(text),
    service: "Klímarendszer ellenőrzés + töltés",
    explanation: "A klíma hibáinak 80%-a gázhiány vagy tömítetlenség.",
    min: 12000,
    max: 35000,
  },
  {
    match: () => cat === "exhaust" || /kipufogó|hangos|füst|dpf|katalizátor/.test(text),
    service: "Kipufogó rendszer átvizsgálás",
    explanation: "Füstölés, hangos kipufogó vagy hibakód sokszor DPF/katalizátor gond.",
    min: 10000,
    max: 150000,
  },
  {
    match: () => cat === "tyres" || /gumi|kerék|defekt|nyomás|rezgés/.test(text),
    service: "Kerék és guminyomás ellenőrzés + centrírozás",
    explanation: "Rezgés 90% eséllyel kiegyensúlyozatlanság vagy sérült gumi miatt van.",
    min: 3000,
    max: 15000,
  },
  {
    match: () => cat === "fluids" || /olaj|folyik|szivárog|csöpög|szivárgás/.test(text),
    service: "Folyadékszivárgás felderítése + olajszint ellenőrzés",
    explanation: "Olaj- vagy hűtőfolyadék szivárgás hosszú távon súlyos motorkárt okozhat.",
    min: 5000,
    max: 45000,
  },
  {
    match: () => cat === "fuel" || /üzemanyag|benzin|diesel|tanksapka|üzemanyagpumpa|injektort/.test(text),
    service: "Üzemanyag rendszer átvizsgálása + injektortisztítás",
    explanation: "Üzemanyag rendszer hibája gyenge teljesítményt és fogyasztást okozhat.",
    min: 8000,
    max: 35000,
  },
  {
    match: () => cat === "steering" || /kormányzás|kormány|nehéz|könnyű|rezeg|rugalmas/.test(text),
    service: "Kormányzási rendszer átvizsgálása",
    explanation: "Kormányzási problémák biztonsági kockázatot jelentenek és haladéktalanul vizsgálatra szorulnak.",
    min: 10000,
    max: 50000,
  },
  {
    match: () => cat === "battery" || /akkumulátor|akku|nem indul|gyenge|lemerült/.test(text),
    service: "Akkumulátor kapacitás teszt + csere ha szükséges",
    explanation: "Az akkumulátor leromlása idővel természetes folyamat, amit érdemes proaktívan figyelni.",
    min: 15000,
    max: 50000,
  },
  {
    match: () => cat === "alternator" || /generátor|töltés|dinamó|elektromos/.test(text),
    service: "Alternátor kimenet teszt + szinttartó vizsgálat",
    explanation: "Az alternátor hiányos működése az akkumulátor gyors lemerüléshez vezet.",
    min: 20000,
    max: 60000,
  },
  {
    match: () => cat === "starter" || /indítómotor|indító|nem indul|morgás/.test(text),
    service: "Indítómotor átvizsgálása + megkerülő teszt",
    explanation: "Indítómotor hibája az indítási gondok klasszikus oka.",
    min: 15000,
    max: 55000,
  },
  {
    match: () => cat === "lights" || /fény|nem világít|világítás|lámpa|halogen|led/.test(text),
    service: "Világítási rendszer átvizsgálása + izzó/led csere",
    explanation: "Meghibásodott fények biztonsági kockázatot jelentenek és azonnal javítandók.",
    min: 2000,
    max: 15000,
  },
  {
    match: () => cat === "wipers" || /ablaktörlő|törlő|viszkető|csíkoz/.test(text),
    service: "Ablaktörlő szalag csere + folyékony töltés",
    explanation: "Meghibásodott ablaktörlő csökkenti a látási viszonyokat és biztonsági kockázatot jelent.",
    min: 2000,
    max: 8000,
  },
  {
    match: () => cat === "air-conditioning" || /légkondicionálás|klíma|nem fúj|hideg|meleg levegő|gáz/.test(text),
    service: "Klímarendszer ellenőrzés + töltés",
    explanation: "A klíma hibáinak 80%-a gázhiány vagy tömítetlenség.",
    min: 12000,
    max: 35000,
  },
  {
    match: () => cat === "interior" || /belső|üléshuzat|műanyag|szövet|belső rész|puhtítás/.test(text),
    service: "Belső felületek tisztítása + kisebb javítások",
    explanation: "A belső rész tisztántartása növeli a jármű értékét és kellemességét.",
    min: 5000,
    max: 25000,
  },
  {
    match: () => cat === "exterior" || /külső|karosszéria|festék|rozsdafoltas|karcolt|külső rész/.test(text),
    service: "Külső felületek átvizsgálása + polírozás/festés szükség esetén",
    explanation: "A külső részek megfelelő kezelése megakadályozza a rozsdásodást és megőrzi az értéket.",
    min: 8000,
    max: 60000,
  },
  ];

  return rules.find(r => r.match()) || {
    service: "Általános állapotfelmérés + diagnosztika",
    explanation: "Nem volt egyértelmű egyezés, ezért általános átvizsgálás javasolt.",
    min: 12000,
    max: 40000,
  };
}

module.exports = { recommend };