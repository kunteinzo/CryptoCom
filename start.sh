#!/bin/bash

curr="$(pwd)"


function inkotlin() {
    cd kotlin
    ./gradlew run
    cd $curr
    echo "In Kotlin Done"
    echo
}

function inphp() {
    cd php
    php src/test.php
    cd $curr
    echo
    echo "In PHP Done"
    echo
}

function inpython() {
    cd python
    source .venv/bin/activate
    python main.py
    cd $curr
    echo "In Python Done"
}

echo "Run in"

select n in All Kotlin PHP Python
do
    case $n in
        All) inkotlin && inphp && inpython && exit;;

        Kotlin) inkotlin && exit;;

        PHP) inphp && exit;;

        Python) inpython && exit;;

        *) exit;;
    esac
done



