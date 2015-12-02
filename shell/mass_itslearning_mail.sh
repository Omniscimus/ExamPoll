#!/bin/sh
# Dit scriptje stuurt automatisch naar een heleboel mensen een bepaald bericht,
# vanaf de opgegeven account.

ITSLEARNING_USERNAME="neh123456"
ITSLEARNING_PASSWORD="dsafasf"

# Wanneer je verbindt met Itslearning, krijg je een cookie genaamd:
# ASP.NET_SessionId
# Deze cookie bevat een sessiecode.
# Wanneer je inlogt op Itslearning, stuur je die cookie ook, en komt die code
# op de server van Itslearning te staan als een sessie die data mag opvragen
# voor de persoon voor wie hij ingelogd is. Om een mail te sturen, moet je je in
# de POST request waarin je de mail stuurt, authenticeren met dezelfde code.

# Het volgende commando zorgt ervoor dat je inlogt op Itslearning. Omdat we geen
# SessionId aanleveren, krijgen we er een opgestuurd via een cookie, omdat
# de server denkt dat we voor de eerste keer verbinden (wat ook zo is).
# Om in te loggen, moeten we een GET-request sturen met de volgende data:
# - de gebruikersnaam
# - een timestamp
# - een CustomerId, ik denk dat dit de klant (= de school) van het Itslearning
#   bedrijf is, het schijnt altijd 394 te zijn
# - een MD5-hash van het wachtwoord
# - een fromELogin parameter, idk.
PASSWORD_HASH=$(md5 -qs $ITSLEARNING_PASSWORD)
TIMESTAMP=$(date +%s)

curl \
'https://nehalennia.itslearning.com/ProcessLogin.aspx?Username='$ITSLEARNING_USERNAME'&TimeStamp='$TIMESTAMP'&CustomerId=394&Hash='$PASSWORD_HASH'&fromElogin=True' \
-c cookies.txt
# We krijgen (mogelijk o.a.) de volgende cookie in cookies.txt:
# #HttpOnly_nehalennia.itslearning.com	FALSE	/	FALSE	0	ASP.NET_SessionId	tjgcf2gi5bus3eytusmngybf
# We hebben nu dus een geauthenticeerde SessionId waarmee we een mail kunnen
# versturen vanaf het account van de persoon die ingelogd is met deze SessionId.

# W.I.P. aan dit scriptje! :)

# Send mail
curl \
'https://nehalennia.itslearning.com/XmlHttp/Api.aspx?Function=MessagingSendMessage&MessageOperationID=1000' \
-H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' \
-H 'Accept-Encoding: gzip, deflate' \
-H 'Accept-Language: en-US,en;q=0.5' \
-H 'Connection: keep-alive' \
-H 'Cookie: ActiveTimestamp=635843356520266145; OldBrowser=%220%22; sModule=Pool=peta; Pool=peta; OnlineInfo=uce=0&ue=0&ui=0&un=0&ee=0&cee=0&ec=2015-11-28 19:27:32&cec=0000-00-00 00:00:00&nc=2015-11-28 19:32:07&h=5ca2825281516c266838d17dc37e6d7f&lo=2015-11-28 19:32:25&ii=False; ShowNativeLogin=true; DebugCustomerId=394; DebugPersonId=3071; ViewAsPersonId=null; ASP.NET_SessionId=xrvhgjhuw5ymcjzlxjfs1tji; login=CustomerId=394&LanguageId=4&ssl=True; trackNetworkLatency=1448737540303' \
-H 'Host: nehalennia.itslearning.com' \
-H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:42.0) Gecko/20100101 Firefox/42.0' \
-H 'Content-Type: application/x-www-form-urlencoded' \
--data 'operationId=1000&to=neh123456&cc=&bcc=&subject=testonderwerp&text=%3Cp%3Ehallo+reinier%3C%2Fp%3E%0D%0A&files=&id=533257&messageMeasurement=2&savedTime=Opgeslagen+20%3A27%3A24'
