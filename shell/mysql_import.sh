#!/bin/sh
# Importeert stemcodes uit een bestand in een MySQL-database door eerst de codes
# te veranderen naar MySQL-queries. Maakt de database en table aan als ze nog
# niet bestaan.

# Het bestand waarin de gegenereerde stemcodes staan
# (zoals gemaakt door generate_codes.sh)
CODES_FILE="codes.txt"
# Het bestand waarin de MySQL-queries komen te staan
OUTPUT_FILE="codes.sql"
# MySQL-gegevens
MYSQL_HOST="localhost"
MYSQL_PORT="3306"
MYSQL_USER="root"
MYSQL_DATABASE="exampoll"
MYSQL_TABLE="codes"

awk -v table=$MYSQL_TABLE '{for(i=0;i<NF;i++)print "INSERT INTO "table" (code) VALUES (\x27"$i"\x27);";}' $CODES_FILE > $OUTPUT_FILE

echo "Vul het MySQL-wachtwoord in van user '$MYSQL_USER'."
mysql -h $MYSQL_HOST -P $MYSQL_PORT -u $MYSQL_USER -p << EOF
CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE;
USE $MYSQL_DATABASE;
CREATE TABLE IF NOT EXISTS $MYSQL_TABLE (code VARCHAR(32) NOT NULL UNIQUE);
SOURCE $OUTPUT_FILE;
EOF
