# WebHome Project - Calendar

## Installation

```sh
composer install
```

```sh
bower install
npm install
gulp dist
```


/*

Matin
    -> En semaine : Le jour mm : 8h à 9h et 12h à 14h
    -> WE : rien

Série de 1  Nuit
    -> L,Ma,Me,Je :
        -> Le jour mm :        16h à 18h
        -> Le lendemain :       8h à 9h

Série de 3 Nuits
    -> Ma,Me,Je    => Jour de début Ma
        -> Le jour mm :        16h à 18h      (avant de commencer la nuit)
        -> Pendant la série :   8h à 9h, 12h à 14h et 15h à 17h (Après Nuit 1, Nuit 2)
        -> Après la série :     8h à 9h, 12h à 14h (après Nuit 3)

    -> Ve,Sa,Di     => Jour de début Ve
        -> La jour mm :        16h à 18h      (avant de commencer la nuit)
        -> Après la série :     8h à 9h, 12h à 14h (après Nuit 3)

*/