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
#PASSWORD_HASH=$(md5 -qs $ITSLEARNING_PASSWORD)
TIMESTAMP=$(date +%s)

curl -s 'https://nehalennia.itslearning.com/index.aspx' \
-H 'Host: nehalennia.itslearning.com' \
-H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:42.0) Gecko/20100101 Firefox/42.0' \
-H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' \
-H 'Accept-Language: en-US,en;q=0.5' \
-H 'Referer: https://nehalennia.itslearning.com/index.aspx' \
-H 'Connection: keep-alive' \
-H 'Content-Type: application/x-www-form-urlencoded' \
--data '__VIEWSTATE=%2FwEPDwULLTE5NTUyODAxMTkPFgIeE1ZhbGlkYXRlUmVxdWVzdE1vZGUCARYCBQVjdGwwMA9kFgQCAg9kFgYCAQ9kFgoCAQ8PFgYeCENzc0NsYXNzBRFoLWhpZ2hsaWdodCBoLW1yMB4EXyFTQgICHgdWaXNpYmxlZ2QWAgIBDxYCHgRUZXh0BSVPbmp1aXN0ZSBnZWJydWlrZXJzbmFhbSBvZiB3YWNodHdvb3JkZAICDxYCHgVjbGFzcwUTY2NsLXJ3Z20tY29sdW1uLTEtMRYCAgEPZBYIZg8WAh8FBQtlbG9naW4taXRlbRYEZg8WBB8FBQhoLWhpZGRlbh4DZm9yBShjdGwwMF9Db250ZW50UGxhY2VIb2xkZXIxX1VzZXJuYW1lX2lucHV0FgJmDxYCHwQFDkdlYnJ1aWtlcnNuYWFtZAIBDw9kFgoeC3BsYWNlaG9sZGVyBQ5HZWJydWlrZXJzbmFhbR4IcmVxdWlyZWQFCHJlcXVpcmVkHgxhdXRvY29tcGxldGUFA29mZh4LYXV0b2NvcnJlY3QFA29mZh4OYXV0b2NhcGl0YWxpemUFA29mZmQCAQ8WAh8FBQtlbG9naW4taXRlbRYEZg8WAh8GBShjdGwwMF9Db250ZW50UGxhY2VIb2xkZXIxX1Bhc3N3b3JkX2lucHV0FgJmDxYCHwQFCldhY2h0d29vcmRkAgEPD2QWCh8HBQpXYWNodHdvb3JkHwgFCHJlcXVpcmVkHwkFA29mZh8KBQNvZmYfCwUDb2ZmZAICDw8WBh8EBQlBYW5tZWxkZW4fAQVSY2NsLWJ1dHRvbiBjY2wtYnV0dG9uLWNvbG9yLWdyZWVuIGl0c2wtbm8tdGV4dC1kZWNvcmF0aW9uIGl0c2wtbmF0aXZlLWxvZ2luLWJ1dHRvbh8CAgJkZAIDDw8WAh8DZxYCHwUFGGgtZHNwLWIgaC1mbnQtc20gaC1wZHQxMGQCBA8WAh8DaBYCAgEPZBYCZg8WAh4LXyFJdGVtQ291bnQC%2F%2F%2F%2F%2Fw9kAgUPFgIfBQUXaC1kc3AtYiBoLWZudC1zbSBoLXBkMTBkAgYPZBYCZg9kFgJmD2QWBgIBDxYCHwQFBk5pZXV3c2QCBQ8WAh8DaGQCBw8UKwACDxYEHgtfIURhdGFCb3VuZGcfDAIBZGQWAmYPZBYCZg8VAwZXZWxrb23UBVdlbGtvbSBpbiBkZSBlbGVrdHJvbmlzY2hlIGxlZXJvbWdldmluZyB2YW4gTmVoYWxlbm5pYS4gVSBrdW50IHppY2ggYWFubWVsZGVuIGRvb3IgdXcgZ2VicnVpa2Vyc25hYW0gZW4gd2FjaHR3b29yZCBpbiB0ZSB2dWxsZW4gZW4gb3AgIkFhbm1lbGRlbiIgdGUga2xpa2tlbi4gTGV0IG9wIDsgaW5sb2duYW1lbiBiZWdpbm5lbiBudSBtZXQgbmVoITxiciAvPjxiciAvPkJpaiBkZSBlZXJzdGUga2VlciBpbmxvZ2dlbiB3b3JkdCBnZXZyYWFnZCBvbSBlZW4gZS1tYWlsYWRyZXMgb3AgdGUgZ2V2ZW4gb2YgdGUgY29udHJvbGVyZW47IGRpdCBhZHJlcyB3b3JkdCBnZWJydWlrdCBvbSBlZW4gd2FjaHR3b29yZCB0ZSBzdHVyZW4gaW5kaWVuIHZlcmdldGVuLiBab3JnIGR1cyBkYXQgZGl0IGUtbWFpbCBhZHJlcyBqdWlzdCBpcy48YnIgLz5MdWt0IGhldCDDqWNodCBuaWV0IG9tIGluIHRlIGxvZ2dlbiwgc3R1dXIgZGFuIGVlbiBtYWlsIG5hYXI6IGVsb0BuZWhhbGVubmlhLm5sIChvLnYudi4gbmFhbSwga2xhcyBlbiBsZWVybGluZ251bW1lcik8YnIgLz48YnIgLz5HZWVmIGlubG9nZ2VnZXZlbnMgbm9vaXQgYWFuIGllbWFuZCBhbmRlcnMsIG9tIG1pc2JydWlrIHRlIHZvb3Jrb21lbiEhPGJyIC8%2BPGJyIC8%2BPGJyIC8%2BPGltZyBzcmMgPSAnaHR0cHM6Ly9maWxlcy5pdHNsZWFybmluZy5jb20vZGF0YS8zOTQvNS9hZmJlZWxkaW5nZW4vYWFubWVsZF9lbG8uanBnJz48YnIgLz4PMjYtOC0yMDE0IDEwOjAwZAIDD2QWAmYPFgIfBQUHaC1obGlzdBYCAgEPFgIfDAIDFgZmD2QWAmYPFQIcaHR0cHM6Ly9leGFtLml0c2xlYXJuaW5nLmNvbQpFeGFtIGxvZ2luZAIBD2QWAmYPFQIgaHR0cDovL2l0c2xlYXJuaW5nbmwuemVuZGVzay5jb20JSGVscCBkZXNrZAICD2QWAmYPFQIRL0NsZWFuQ29va2llLmFzcHgZQ2xlYW4gaXRzbGVhcm5pbmcgY29va2llc2QCCQ9kFgJmDw8WBB8BBSRoLW1ydDEwIGgtbXJiMTAgaC10YS1jIGwtbG9naW5jaG9pY2UfAgICZGQCAw9kFgJmDxUKE3d3dy5pdHNsZWFybmluZy5jb20sY3RsMDBfQ29udGVudFBsYWNlSG9sZGVyMV9uYXRpdmVBbmRMZGFwTG9naW4lY3RsMDBfQ29udGVudFBsYWNlSG9sZGVyMV9vclNlcGFyYXRvcihjdGwwMF9Db250ZW50UGxhY2VIb2xkZXIxX2ZlZGVyYXRlZExvZ2luMmN0bDAwX0NvbnRlbnRQbGFjZUhvbGRlcjFfbmF0aXZlTG9naW5MaW5rQ29udGFpbmVyL2N0bDAwX0NvbnRlbnRQbGFjZUhvbGRlcjFfZmVkZXJhdGVkTG9naW5XcmFwcGVyM2N0bDAwX0NvbnRlbnRQbGFjZUhvbGRlcjFfc2hvd05hdGl2ZUxvZ2luVmFsdWVGaWVsZBNjY2wtcndnbS1jb2x1bW4tMS0yE2NjbC1yd2dtLWNvbHVtbi0xLTIEdHJ1ZWQYAQUnY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSROZXdzJE5ld3NMaXN0DxQrAA5kZGRkZGRkFCsAAWQCAWRkZGYC%2F%2F%2F%2F%2Fw9kn42V2D%2BMbp6gDMXorSzd3lt5xH8%3D&__VIEWSTATEGENERATOR=90059987&__EVENTVALIDATION=%2FwEdAAajNJx7uIS77CgHlKoy9S1z8fB5t%2B9v57KHoifeE6Ej%2B75MFqk64wecfXK5391QIHFYrvJMeIvuXa99Q%2BIq7D1fLSREbUfcpJrCaxK3KzG83F1WhQcZROb4iTuNIbN7%2F%2BGMP5rAPdO2NScxsfskZaG28rSicw%3D%3D&ctl00%24ContentPlaceHolder1%24Username%24input='$ITSLEARNING_USERNAME'&ctl00%24ContentPlaceHolder1%24Password%24input='$ITSLEARNING_PASSWORD'&ctl00%24ContentPlaceHolder1%24nativeLoginButton=Aanmelden&ctl00%24ContentPlaceHolder1%24showNativeLoginValueField=&ctl00%24language_internal%24H=4' \
-c cookies.txt > /dev/null

#curl -s \
#'https://nehalennia.itslearning.com/ProcessLogin.aspx?Username='$ITSLEARNING_USERNAME'&TimeStamp='$TIMESTAMP'&CustomerId=394&Hash='$PASSWORD_HASH'&fromElogin=True' \
#-c cookies.txt > /dev/null
# We krijgen (mogelijk o.a.) de volgende cookie in cookies.txt:
# #HttpOnly_nehalennia.itslearning.com	FALSE	/	FALSE	0	ASP.NET_SessionId	tjgcf2gi5bus3eytusmngybf
# We hebben nu dus een geauthenticeerde SessionId waarmee we een mail kunnen
# versturen vanaf het account van de persoon die ingelogd is met deze SessionId.

COOKIE=$(node regex.js "$(cat cookies.txt | grep 'ASP.NET_SessionId')")

# Test of er goed ingelogd is (debug)
curl -v 'https://nehalennia.itslearning.com/main.aspx?StartUrl=Dashboard%2fDashboard.aspx%3fLocationType%3dHierarchy' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: en-US,en;q=0.5' -H 'Connection: keep-alive' -H 'Cookie: login=CustomerId=394&LanguageId=4&ssl=True; ASP.NET_SessionId='$COOKIE'; ActiveTimestamp='$TIMESTAMP'; OldBrowser=%220%22; sModule=Pool=peta; Pool=peta; OnlineInfo=uce=0&ue=0&ui=0&un=0&ee=0&cee=0&ec=2015-12-03 15:31:32&cec=0000-00-00 00:00:00&nc=2015-12-03 15:29:54&h=5ca2825281516c266838d17dc37e6d7f&lo=2015-12-03 15:34:47&ii=False; ShowNativeLogin=true; DebugCustomerId=394; DebugPersonId=3071; ViewAsPersonId=null; trackNetworkLatency=1449156366623' -H 'Host: nehalennia.itslearning.com' -H 'Referer: https://nehalennia.itslearning.com/DashboardMenu.aspx?LocationType=Hierarchy' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:42.0) Gecko/20100101 Firefox/42.0'

# Send mail
#curl -v \
#'https://nehalennia.itslearning.com/XmlHttp/Api.aspx?Function=MessagingSendMessage&MessageOperationID=1000' \
#-H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' \
#-H 'Accept-Encoding: gzip, deflate' \
#-H 'Accept-Language: en-US,en;q=0.5' \
#-H 'Connection: keep-alive' \
#-H 'Cookie: ActiveTimestamp='$TIMESTAMP'; OldBrowser=%220%22; sModule=Pool=peta; Pool=peta; OnlineInfo=uce=0&ue=0&ui=0&un=0&ee=0&cee=0&ec=2015-11-28 19:27:32&cec=0000-00-00 00:00:00&nc=2015-11-28 19:32:07&h=5ca2825281516c266838d17dc37e6d7f&lo=2015-11-28 19:32:25&ii=False; ShowNativeLogin=true; DebugCustomerId=394; DebugPersonId=3071; ViewAsPersonId=null; ASP.NET_SessionId='$COOKIE'; login=CustomerId=394&LanguageId=4&ssl=True; trackNetworkLatency=1448737540303' \
#-H 'Host: nehalennia.itslearning.com' \
#-H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.11; rv:42.0) Gecko/20100101 Firefox/42.0' \
#-H 'Content-Type: application/x-www-form-urlencoded' \
#--data 'operationId=1000&to=neh123456&cc=&bcc=&subject=testonderwerp&text=%3Cp%3Ehallo+reinier%3C%2Fp%3E%0D%0A&files=&id=533257&messageMeasurement=2&savedTime=Opgeslagen+20%3A27%3A24'
