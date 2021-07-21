Úlohy:

1.	Vytvorte webový „portál“, ktorý bude pozostávať z troch stránok: 
A.	Na prvej stránke bude zobrazená predpoveď počasia pre miesto, z ktorého si návšteník pozerá vašu stránku. Pokiaľ nebude možné nájsť predpoveď počasia pre dané miesto, tak predpoveď sa zobrazí pre najbližšie mesto, pre ktoré je predpoveď k dispozícii. 
B.	Na druhej stránke budú zobrazené tieto údaje:
•	IP adresa návštevníka danej stránky, 
•	GPS súradnice zodpovedajúceho miesta, 
•	mesto, v rámci ktorého sa dané súradnice nachádzajú (ak sa toto mesto nedá lokalizovať, tak sa vypíše reťazec typu „mesto sa nedá lokalizovať alebo sa nachádzate na vidieku“), 
•	štát, ktorému daná IP adresa prislúcha, 
•	hlavné mesto tohoto štátu. 
C.	Na tretej stránke budú zobrazené nasledujúce štatistické údaje:
•	počet návštevníkov vášho portálu, pričom títo návštevníci budú rozdelení na základe štátov, z ktorých podľa svojej IP adresy pochádzajú. Tieto údaje uveďte prehľadne do tabuľky, v ktorej bude uvedená vlajka daného štátu, meno tohto štátu a počet návštevníkov z tohoto štátu. Za unikátnu návštevu sa považuje 1 návšteva z 1 IP adresy počas 1 dňa. 
•	v prípade kliknutia na daný štát sa otvorí podobná tabuľka, kde sa budú zobrazovať informácie o počtoch návštev z miest daného štátu. Neidentifikované mestá sa budú spočítavať do kolonky „nelokalizované mestá a vidiek“. 
•	mapa s bodkami, odkiaľ pochádzali návštevníci vášho portálu (realizácia mapy môže byť spravená napr. cez Google mapy, OpenStreet mapy, resp. inú vami vybranú alternatívu). 
•	informácia, v ktorom čase koľko ľudí navštívilo váš portál. Vyhodnocujte časové pásma medzi 6:00-15:00, 15:00-21:00, 21:00-24:00, 24:00-6:00. Berte do úvahy lokálny čas daného užívateľa (t.j. ak sa na stránku pozrie Bratislavčan o 19:00 a človek z New Yorku svojho lokálneho času tiež o 19:00, tak napriek tomu, že medzi týmito dvoma mestami je časový posun 6 hodín, tak sa to bude považovať za rovnaký lokálny čas). 
•	informácia o tom, ktorá z vašich troch stránok bola najčastejšie navštevovaná. 
2.	Pri vytváraní zadania máte možnosť používať všetky v rámci PHP preberané technológie (CURL, rôzne API,..) a všetky dostupné informačné zdroje (napr. wikipédiu, ...). Adresy pre API si kľudne môžete vymieňať prostredníctvom diskusného fóra, discordu, komentárov. Na zobrazovanie vlajok môžete použiť napríklad: http://www.geonames.org/flags/x/de.gif. Názov obrázka by mal byť totožný s ISO kódom danej krajiny, ktorý sa používa aj v mailových adresách. 
3.	Počíta sa s tým, že počítadlo prístupov si vytvoríte sami, t.j. v tomto prípade využitie nejakej služby na Internete nie je povolené.
4.	Pri prvej návšteve stránky si vyžiadajte od užívateľa súhlas, že na stránke sa môže spracovávať jeho IP adresa a GPS súradnice. Ak tento súhlas užívateľ neudelí, tak mu vypíše oznam, že nemôže mať sprístupnený obsah stránky.
5.	Kvôli otestovaniu funkcionality vašej web stránky môžete na simulovanie návštev z iných štátov použiť napríklad niektorú z web stránok zverejnených tu: https://www.fossmint.com/free-proxy-for-anonymous-web-browsing/ 
6.	Všetky použité API uveďte do technickej dokumentácie.
7.	Pri vypracovaní tohoto zadania je už možné použiť PHP framework (nie je to povinné).
