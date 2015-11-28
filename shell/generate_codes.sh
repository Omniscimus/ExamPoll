#!/bin/sh
# Genereert een gegeven aantal stemcodes en schrijft ze in een bestand. Als het
# bestand al bestaat, worden de codes aan het eind van het bestand
# bijgeschreven.

# Hoeveel codes moeten er in het bestand geschreven worden?
NUMBER_OF_CODES=10
# In welk bestand moeten de codes geschreven worden?
FILE="codes.txt"

echo "Codes genereren in $FILE..."
INITIAL_LC_CTYPE=$LC_CTYPE
export LC_CTYPE=C
for((i=0; $i<$NUMBER_OF_CODES; i=$i+1))
do
# Succes met dit brute-forcen, er zijn 62^32 mogelijkheden
echo $(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1) >> $FILE
done
export LC_CTYPE=$INITIAL_LC_CTYPE
