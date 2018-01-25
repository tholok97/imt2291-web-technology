# Lab tasks (taken from task-description. in norwegian)

1. Ta utgangspunkt i koden i uke4_lab. I classes/User.php finnes klassen User. Legg til metode addUser i denne klassen, metoden skal ta i mot brukernavn og passord og annen brukerrelatert informasjon. Bruk e-post som brukernavn, annen informasjon som kan være aktuelt å lagre er navn (fornavn/etternavn), kallenavn, avatarbilde, hjemmeside etc. Returner som minimum status=ok og id=ny brukerid ved suksess og status=fail og errorMessage=hensiktsmessig feilbeskjed ved feil. Bruk DB.php klassen fra denne ukens forelesningsnotater for å sende med databaseforbindelse til konstruktoren for klassen.
2. Bruk password_hash for hashing av passord.
3. Skriv klassen UserTest med metoden testCreateUser for å teste om en bruker kan opprettes.
4. Oppdater klasser User, legg til metoden login. Denne metoden skal ta to parametre, brukernavn og passord. Sjekk om brukeren finnes i databasen. Dersom brukeren finnes så returneres status=ok og brukerdetaljene lagres i objektet. Dersom brukeren ikke finnes så returneres status=fail med informasjon om hvorvidt det er brukeren som ikke finnes eller om det er passordet som er feil.
5. Oppdater UserTest for å teste om du kan logge inn.
6. Oppdater klassen User, legg til en metode for å slette en bruker gitt brukerid til brukeren som skal slettes. Også her skal det returneres status=ok ved suksess, og status=fail dersom noe går galt.
7. Oppdater testene i UserTest så brukeren som opprettes slettes når testen er ferdig (i slutten av hver test.) Sjekk at brukeren kan slettes.
8. Oppdater klaser User så loginstatus lagres ved hjelp av en sessjonsvariabel. Når et objekt av klassen opprettes skal loginstatus automatisk oppdateres. Ta også i mot `$_POST` i konstruktoren og la sjekk for innlogging/utlogging skje automatisk i konstruktoren. 
9. Legg til en metode isLoggedIn som returnerer true dersom brukeren er logged inn og false dersom brukeren ikke er logget inn.
10. I koden i uke4_lab finnes et utgangspunkt for å teste den User klassen du nå har i bruk på en nettside. Utvid denne testen slik at du fyller ut brukernavn/passord og logger på. Utvid denne testen slik at du i FunctionalTests/setup oppretter en ny  bruker med random brukernavn/passord. Da vare på brukernavn og passord. Skriv så en test som fyller ut dette brukernavnet og passord og sjekker at en kan logge på vie en nettside.

Videre er det naturlig å legge til funksjonalitet for persistent login og teste dette, både på unit test nivå og via en nettside (integrasjonstesting.) Dette vil inngå som en del av det første prosjektet.

# Running project

* Depends on PHP and MySQL. An export of the database schema is indcluded.
* Run `composer install` to get dependencies installed

# Running tests

* Useful alias for running all tests: `alias t='./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/UserTest.php'`
* In the functional test you have to specify you own base url to run the test on
* Test relies on there existing a user with the username 'testuser' and password 'testpassword' in the live database
