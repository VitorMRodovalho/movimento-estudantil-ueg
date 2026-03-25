<?php
//zOOm Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Image Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
| Date: February, 2005                                                |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2005 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Mambo. It's the most feature-rich gallery component    |
|              for Mambo! For documentation and a detailed list       |
|              of features, check the zOOm homepage:                  |
|              http://zoom.ummagumma.nl                               |
| Filename: germani.php  (Du)                                         |
| Version: 2.5.beta2 v2                                               |
| Translation: Per Lasse Baasch - www.skycube.net & www.bsekrank.de   |
| also Updated by S. Wienert and mamboGTT.de (2.1.4RC3)    		      |
| ..if you make updates.. please inform me... thanks                  |
-----------------------------------------------------------------------
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_ISO","iso-8859-1");
define("_ZOOM_PICK","W&auml;hle ein Album");
define("_ZOOM_DELETE","L&ouml;schen");
define("_ZOOM_BACK","Zur&uuml;ck");
define("_ZOOM_MAINSCREEN","Hauptseite");
define("_ZOOM_BACKTOGALLERY","Zur&uuml;ck zum Album");
define("_ZOOM_INFO_DONE","Fertig!");
define("_ZOOM_TOOLTIP", "zOOm ToolTipp");
define("_ZOOM_WARNING", "zOOm Warnung!");

//Gallery admin page
if ($zoom->_isAdmin || $zoom->_isUser){
define("_ZOOM_ADMINSYSTEM","Admin System");
define("_ZOOM_USERSYSTEM","Benutzer System");
define("_ZOOM_ADMIN_TITLE","Fotogalerie Admin System");
define("_ZOOM_USER_TITLE","Fotogalerie Benutzer System");
define("_ZOOM_CATSMGR","Album Manager");
define("_ZOOM_CATSMGR_DESCR","Erstelle neue Alben f&uuml;r Deine neuen Bilder, erstelle und l&ouml;sche sie hier im Album Manager.");
define("_ZOOM_NEW","Neues Album");
define("_ZOOM_DEL","Album l&ouml;schen");
define("_ZOOM_MEDIAMGR","Media Manager");
define("_ZOOM_MEDIAMGR_DESCR","ändern, löschen, suchen nach neuen Bildern automatisch oder manuell.");
define("_ZOOM_UPLOAD","Datei(en) hochladen");
define("_ZOOM_EDIT","Album bearbeiten");
define("_ZOOM_ADMIN_CREATE","Datenbank erzeugen");
define("_ZOOM_ADMIN_CREATE_DESCR","Erstelle die ben&ouml;tigten Datenbanktabellen, dann kannst Du beginnen, das Album zu benutzen.");
define("_ZOOM_HD_PREVIEW","Preview");
define("_ZOOM_HD_CHECKALL","Ausw&auml;hlen/Abw&auml;hlen alle");
define("_ZOOM_HD_CREATEDBY","Erstellt von");
define("_ZOOM_HD_AFTER","Einf&uuml;gen nach");
define("_ZOOM_HD_HIDEMSG","Verberge 'keine Bilder' Text");
define("_ZOOM_HD_NAME","Name Album");
define("_ZOOM_HD_DIR","Verzeichnis");
define("_ZOOM_HD_NEW","Neues Album");
define("_ZOOM_HD_SHARE","Dieses Album freigeben");
define("_ZOOM_TOPLEVEL","Top Zugriff");
define("_ZOOM_HD_UPLOAD","Datei hochladen");
define("_ZOOM_A_ERROR_ERRORTYPE","Fehlertyp");
define("_ZOOM_A_ERROR_IMAGENAME","Bildname");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> nicht verfügbar");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> nicht verfügbar");
define("_ZOOM_A_ERROR_NOTINSTALLED","nicht installiert");
define("_ZOOM_A_ERROR_CONFTODB","Fehler beim speichern der Konfiguration in die Datenbank!");
define("_ZOOM_A_MESS_NOT_SHURE","* Wenn du dir nicht sicher bist, benutze default \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Note: \"Safe Mode\" ist aktiv, deswegen könnte es sein, dass man keine großen Datei hochladen kann!<br />Du solltest in das Admininterface wechseln und dort zu FTP wechseln.");
define("_ZOOM_A_MESS_SAFEMODE2","Note: \"Safe Mode\" ist aktiv, deswegen könnte es sein, dass man keine großen Datei hochladen kann!<br />zOOm erfordert das aktivieren des FTP-Mode im Admininterface.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Verarbeitung der Datei...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Konnte folgende url nicht öffnen:");
define("_ZOOM_A_MESS_PARSE_URL","Syntaxanalyse \"%s\" für Datei... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Wenn du hier eine graue Box siehst oder Probleme beim hochladen hast, <br /> könnte es sein, dass du eine neuere java run-time installieren musst. Gehe zu <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> und lade die neueste Version herunter.");
define("_ZOOM_SETTINGS","Einstellungen");
define("_ZOOM_SETTINGS_DESCR","Zeige und &auml;ndere alle m&ouml;glichen Konfigurationseinstellungen hier.");
define("_ZOOM_SETTINGS_TAB1","System");
define("_ZOOM_SETTINGS_TAB2","Layout");
define("_ZOOM_SETTINGS_TAB4","Zugriffsrechte");
define("_ZOOM_SETTINGS_TAB5","Safe Mode");
define("_ZOOM_SETTINGS_CONVTYPE","Konvertierungsart");
define("_ZOOM_SETTINGS_AUTODET","Auto-Erkennung: ");
define("_ZOOM_SETTINGS_IMGPATH","Pfad zu Bild-Dateien:");
define("_ZOOM_SETTINGS_TTIMGPATH","Pfad zu Bilder ist ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Bild Konvertierungseinstellungen:");
define("_ZOOM_SETTINGS_IMPATH","Pfad zu ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," oder NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Pfad zu FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg ist erforderlich um Thumbnails für Videos zu erstellen.<br />Unterstützte Formate: ");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Pfad zu PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, welches teil vom Xpdf Packet ist, wird benötigt für PDF Dateien.");
define("_ZOOM_SETTINGS_MAXSIZE","Bild max. Gr&ouml;ße: ");
define("_ZOOM_SETTINGS_THUMBSETTINGS","Thumbnail Einstellungen:");
define("_ZOOM_SETTINGS_QUALITY","NetPBM und GD2 JPEG Qualit&auml;t: ");
define("_ZOOM_SETTINGS_SIZE","Thumbnail max. Gr&ouml;ße: ");
define("_ZOOM_SETTINGS_TEMPNAME","Tempor&auml;rer Name: ");
define("_ZOOM_SETTINGS_AUTONUMBER","Automatische Bildbenennung (z.B. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Tempor&auml;re Beschreibung: ");
define("_ZOOM_SETTINGS_TITLE","Album Titel:");
define("_ZOOM_SETTINGS_SUBCATSPG","Anzahl der Spalten in (Unter-)Alben");
define("_ZOOM_SETTINGS_COLUMNS","Anzahl der Thumbnail-Spalten");
define("_ZOOM_SETTINGS_THUMBSPG","Thumbs pro Seite");
define("_ZOOM_SETTINGS_CMTLENGTH","Kommentare max. L&auml;nge");
define("_ZOOM_SETTINGS_CHARS","Zeichen");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Präfix für Albumtitel");
define("_ZOOM_SETTINGS_COMMENTS","Kommentare");
define("_ZOOM_SETTINGS_POPUP","PopUp-Bilder");
define("_ZOOM_SETTINGS_CATIMG","Zeige Kategorie Bild");
define("_ZOOM_SETTINGS_SLIDESHOW","Diashow");
define("_ZOOM_SETTINGS_ZOOMLOGO","Zeige zOOm-Logo");
define("_ZOOM_SETTINGS_SHOWHITS","Zeige Anzahl der Zugriffe");
define("_ZOOM_SETTINGS_READEXIF","Zeige EXIF-Daten");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Dieses Feature erlaubt EXIF und oder IPTC daten anzuschauen, ohne eine Installation vom EXIF Modul in PHP.");
define("_ZOOM_SETTINGS_READID3","Lese mp3 ID3-Informationen");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Dieses Feature zeigt erweiterte ID3 v1.1 und v2.0 Informationen beim anzeigen/ abspielen einer MP3.");
define("_ZOOM_SETTINGS_RATING","Bewertung");
define("_ZOOM_SETTINGS_CSS","Stylesheet (CSS) Popup Fenster");
define("_ZOOM_SETTINGS_USERUPL","Erlaube Benutzern das Hochladen:");
define("_ZOOM_SETTINGS_ACCESSLVL","Zugriffsebene: ");
define("_ZOOM_SETTINGS_SUCCESS","Einstellungen erfolgreich aktualisiert!");
define("_ZOOM_SETTINGS_ZOOMING","Bildvergr&ouml;ßerung");
define("_ZOOM_SETTINGS_ORDERBY","Thumbnail Sortierungsmethode; Reihenfolge nach");
define("_ZOOM_SETTINGS_CATORDERBY","(Unter-)Alben Sortierung; sortiert nach");
define("_ZOOM_SETTINGS_DATE_ASC","Datum, aufsteigend");
define("_ZOOM_SETTINGS_DATE_DESC","Datum, absteigend");
define("_ZOOM_SETTINGS_FLNM_ASC","Dateiname, aufsteigend");
define("_ZOOM_SETTINGS_FLNM_DESC","Dateiname, absteigend");
define("_ZOOM_SETTINGS_NAME_ASC","Name, aufsteigend");
define("_ZOOM_SETTINGS_NAME_DESC","Name, absteigend");
define("_ZOOM_SETTINGS_LBTOOLTIP","Eine Lightbox ist wie ein Warenkorb, Benutzer können Bilder sammeln und dann als ZIP Datei herunterladen.");
define("_ZOOM_SETTINGS_SHOWNAME","Zeige Name");
define("_ZOOM_SETTINGS_SHOWDESCR","Zeige Beschreibung");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Zeige Schl&uuml;sselw&ouml;rter");
define("_ZOOM_SETTINGS_SHOWDATE","Zeige Datum");
define("_ZOOM_SETTINGS_SHOWFILENAME","Zeige Dateiname");
define("_ZOOM_SETTINGS_METABOX","Zeige Box mit Medien-Info im Album");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Unbenutzt erhöht dies die Geschwindigkeit des Fotoalbums. Effektiv für große Datenbanken.");
define("_ZOOM_SETTINGS_ECARDS","E-Cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","E-Cards G&uuml;ltigkeit");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","Eine Woche");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","Zwei Wochen");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","Einen Monat");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","Drei Monate");
define("_ZOOM_SETTINGS_SHOWSEARCH","Such-Feld auf ALLEN Seiten");
define("_ZOOM_SETTINGS_ALLOWCREATE","Benutzern erlauben, ein Album zu erstellen");
define("_ZOOM_SETTINGS_ALLOWDEL","Benutzern erlauben, ein freigegebenes Album zu l&ouml;schen");
define("_ZOOM_SETTINGS_ALLOWEDIT","Benutzern erlauben, ein Album (freigegeben) zu &auml;ndern");
define("_ZOOM_SETTINGS_SETMENUOPTION","Zeige 'Hochladen'-Link im Benutzermen&uuml;");
define("_ZOOM_SETTINGS_USEFTP","Nutze FTP mode?");
define("_ZOOM_SETTINGS_FTPHOST","Host Name");
define("_ZOOM_SETTINGS_FTPUNAME","Benutzer Name");
define("_ZOOM_SETTINGS_FTPPASS","Passwort");
define("_ZOOM_SETTINGS_FTPWARNING","Warnung: Passwort ist nicht sicher gespeichert!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Verzeichnis auf dem Host");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Bitte den Pfad von Mambo zu FTP hier eingeben. WICHTIG: Ende <b>ohne</b> einen Slash oder Backslash!");
define("_ZOOM_YES","Ja");
define("_ZOOM_NO","Nein");
define("_ZOOM_SAVE","Speichern");
define("_ZOOM_MOVEFILES","Bilder verschieben");
define("_ZOOM_MOVEFILES_DESCR","ein Bild von Album zu Album verschieben.");
define("_ZOOM_BUTTON_MOVE","verschieben");
define("_ZOOM_MOVEFILES_STEP1","Schritt 1: W&auml;hle das Ausgangsalbum");
define("_ZOOM_MOVEFILES_STEP2","Schritt 2: W&auml;hle die Bilder, die Du verschieben m&ouml;chtest");
define("_ZOOM_MOVEFILES_STEP3","Schritt 3: W&auml;hle das Zielalbum und verschiebe die Bilder");
define("_ZOOM_ALERT_MOVEOK","Bild(er) erfolgreich verschoben!");
define("_ZOOM_OPTIMIZE","Tabellen optimieren");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Gallery benutzt viele Tabellen, daher entstehen einige &uuml;bersch&uuml;ssige Daten. Klicke hier, um sie zu entfernen.");
define("_ZOOM_OPTIMIZE_SUCCESS","zOOm Media Galerie Tabellen optimiert!");
define("_ZOOM_UPDATE","Update zOOm Media Galerie");
define("_ZOOM_UPDATE_DESCR","Neue Features zuf&uuml;gen, l&ouml;se Probleme und Bugs! Gehe zu <a href=\"http://zoom.ummagumma.nl\" target=\"_blank\">zoom.ummagumma.nl</a> f&uuml;r die letzten Updates!");
define("_ZOOM_UPDATE_XMLDATE","Datum des letzten Updates");
define("_ZOOM_UPDATE_PACKAGE","Update ZIP-Datei: ");
define("_ZOOM_CREDITS","&Uuml;ber zOOm Gallery & Credits");

//Image actions
define("_ZOOM_UPLOAD_SINGLE","einzelne (ZIP-)Datei");
define("_ZOOM_UPLOAD_MULTIPLE","mehrere Dateien");
define("_ZOOM_UPLOAD_DRAGNDROP","Drag n Drop");
define("_ZOOM_UPLOAD_SCANDIR","durchsuche Verzeichnis");
define("_ZOOM_UPLOAD_INTRO","Dr&uuml;cke den <b>Durchsuchen</b>-Button, um ein Foto zum Hochladen auszuw&auml;hlen.");
define("_ZOOM_UPLOAD_STEP1","1. W&auml;hle die Anzahl der Dateien, die Du hochladen willst: ");
define("_ZOOM_UPLOAD_STEP2","2. W&auml;hle das Album, in das Du die Dateien ablegen willst: ");
define("_ZOOM_UPLOAD_STEP3","3. Benutze den Durchsuchen-Button, um Bilder von Deinem Computer auszuw&auml;hlen");
define("_ZOOM_SCAN_STEP1","Schritt 1: W&auml;hle einen Ort, der nach Bildern durchsucht werden soll...");
define("_ZOOM_SCAN_STEP2","Schritt 2: W&auml;hle die Bilder, die Du hochladen willst...");
define("_ZOOM_SCAN_STEP3","Schritt 3: zOOm verarbeitet die Bilder, die Du ausgew&auml;hlt hast...");
define("_ZOOM_SCAN_STEP1_DESCR","Der Ort kann eine URL oder ein Verzeichnis auf dem Server sein.<br>&nbsp;  Tipp: FTP, Bilder in ein Verzeichnis hochladen und hier den Pfad angeben!");
define("_ZOOM_SCAN_STEP2_DESCR1","Verarbeitung");
define("_ZOOM_SCAN_STEP2_DESCR2","als ein lokales Verzeichnis");
define("_ZOOM_FORMCREATE_NAME","Name");
define("_ZOOM_FORM_IMAGEFILE","Bild");
define("_ZOOM_FORM_IMAGEFILTER","&Uuml;nterst&uuml;tzte Bildarten");
define("_ZOOM_FORM_INGALLERY","Im Album");
define("_ZOOM_FORM_SETFILENAME","Setze Bildnamen gleich dem Orginal-Dateinamen.");
define("_ZOOM_FORM_LOCATION","Ort");
define("_ZOOM_BUTTON_SCAN","&Uuml;bermittle URL oder Verzeichnis");
define("_ZOOM_BUTTON_UPLOAD","Hochladen");
define("_ZOOM_BUTTON_EDIT","Bearbeiten");
define("_ZOOM_BUTTON_CREATE","Erstellen");
define("_ZOOM_CONFIRM_DEL","Diese Option wird ein Album komplett enfernen, inklusive Inhalt!\\nWillst Du wirklich fortfahren?");
define("_ZOOM_CONFIRM_DELMEDIUM","Du bist dabei, dieses Bild komplett zu enfernen!\\nWillst Du wirklich fortfahren?");
define("_ZOOM_ALERT_DEL","Album ist gel&ouml;scht!");
define("_ZOOM_ALERT_NOCAT","Kein Album ausgew&auml;hlt!");
define("_ZOOM_ALERT_NOMEDIA","Nichts ausgewählt!");
define("_ZOOM_ALERT_EDITOK","Albumfelder erfolgreich bearbeitet!");
define("_ZOOM_ALERT_NEWGALLERY","Neues Album erstellt.");
define("_ZOOM_ALERT_NONEWGALLERY","Album nicht erstellt!!");
define("_ZOOM_ALERT_EDITIMG","Bildeigenschaften erfolgreich bearbeitet");
define("_ZOOM_ALERT_DELPIC","Bild ist gel&ouml;scht!");
define("_ZOOM_ALERT_NODELPIC","Bild kann nicht gel&ouml;scht werden!");
define("_ZOOM_ALERT_NOPICSELECTED","Kein Bild ausgew&auml;hlt.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Keine Bilder ausgew&auml;hlt.");
define("_ZOOM_ALERT_UPLOADOK","Bild erfolgreich hochgeladen!");
define("_ZOOM_ALERT_UPLOADSOK","Bilder erfolgreich hochgeladen!");
define("_ZOOM_ALERT_WRONGFORMAT","Falsches Bildformat.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Falsches Format.");
define("_ZOOM_ALERT_MOVEFAILURE","Fehler beim Verschieben der Datei.");
define("_ZOOM_ALERT_IMGERROR","Fehler beim Ver&auml;ndern des Bildes/ Erstellung Thumbnail.");
define("_ZOOM_ALERT_PCLZIPERROR","Fehler beim Entpacken des Archivs.");
define("_ZOOM_ALERT_INDEXERROR","Fehler beim Indizieren des Dokuments.");
define("_ZOOM_ALERT_IMGFOUND","Bild(er) gefunden.");
define("_ZOOM_INFO_CHECKCAT","Bitte w&auml;hle erst ein Album, bevor Du den Hochladen-Button benutzt!");
define("_ZOOM_BUTTON_ADDIMAGES","Bilder zuf&uuml;gen");
define("_ZOOM_BUTTON_REMIMAGES","Bilder entfernen");
define("_ZOOM_INFO_PROCESSING","Verabeitung der Bilder:");
define("_ZOOM_ITEMEDIT_TAB1","Eigenschaften");
define("_ZOOM_ITEMEDIT_TAB2","Mitglieder");
define("_ZOOM_USERSLIST_LINE1",">>W&auml;hle Mitglieder f&uuml;r diesen Bereich<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Allg. Zugriff<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Nur f&uuml;r Mitglieder<<");
define("_ZOOM_PUBLISHED","Ver&ouml;ffentlicht");
define("_ZOOM_SHARED","Dieses Album freigeben");
define("_ZOOM_ROTATE","Drehe das Bild um 90 Grad");
define("_ZOOM_CLOCKWISE","im Uhrzeigersinn");
define("_ZOOM_CCLOCKWISE","gegen den Uhrzeigersinn");
}

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","Diashow:");
define("_ZOOM_PREV_IMG","vorheriges Bild");
define("_ZOOM_NEXT_IMG","n&auml;chstes Bild");
define("_ZOOM_FIRST_IMG","erstes Bild");
define("_ZOOM_LAST_IMG","letztes Bild");
define("_ZOOM_PLAY","abspielen");
define("_ZOOM_STOP","stop");
define("_ZOOM_RESET","zur&uuml;cksetzen");
define("_ZOOM_FIRST","Erstes");
define("_ZOOM_LAST","Letztes");
define("_ZOOM_PREVIOUS","Vorherige");
define("_ZOOM_NEXT","N&auml;chste");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Schnellsuche...");
define("_ZOOM_ADVANCED_SEARCH","Erweiterte Suche");
define("_ZOOM_SEARCH_KEYWORD","Suche nach Schl&uuml;sselwort");
define("_ZOOM_IMAGES","Bild(er)");
define("_ZOOM_IMGFOUND","gefunden - Du bist auf Seite");
define("_ZOOM_IMGFOUND2","von");
define("_ZOOM_SUBGALLERIES","Unter-Album");
define("_ZOOM_ALERT_COMMENTOK","Dein Kommentar wurde erfolgreich gespeichert!");
define("_ZOOM_ALERT_COMMENTERROR","Du hast dieses Bild bereits kommentiert!");
define("_ZOOM_ALERT_VOTE_OK","Deine Bewertung wurde gespeichert! Danke!");
define("_ZOOM_ALERT_VOTE_ERROR","Du hast dieses Bild bereits bewertet!");
define("_ZOOM_WINDOW_CLOSE","Schließen");
define("_ZOOM_NOPICS","Keine Bilder im Album");
define("_ZOOM_PROPERTIES","Eigenschaften");
define("_ZOOM_COMMENTS","Kommentare");
define("_ZOOM_NO_COMMENTS","Keine Kommentare bisher.");
define("_ZOOM_YOUR_NAME","Name");
define("_ZOOM_ADD","hinzuf&uuml;gen");
define("_ZOOM_NAME","Name");
define("_ZOOM_DATE","Datum hinzugef&uuml;gt");
define("_ZOOM_DESCRIPTION","Beschreibung");
define("_ZOOM_IMGNAME","Name");
define("_ZOOM_FILENAME","Dateiname");
define("_ZOOM_CLICKDOCUMENT","(zum &Ouml;ffnen auf Dateinamen klicken)");
define("_ZOOM_KEYWORDS","Schl&uuml;sselw&ouml;rter");
define("_ZOOM_HITS","Zugriffe");
define("_ZOOM_CLOSE","Schließen");
define("_ZOOM_NOIMG", "Keine Bilder gefunden!");
define("_ZOOM_NONAME", "Du must einen Namen angeben!");
define("_ZOOM_NOCAT", "Keine Kategorien gew&auml;hlt!");
define("_ZOOM_EDITPIC", "Bild bearbeiten");
define("_ZOOM_SETCATIMG","Als Albumbild festlegen");
define("_ZOOM_SETPARENTIMG","Als Albumbild des dar&uuml;berliegenden Albums festlegen");
define("_ZOOM_PASS","Passwort");
define("_ZOOM_PASS_REQUIRED","Dieses Album ben&ouml;tigt ein Passwort.<br>Bitte gib das Passwort ein<br>und dr&uuml;cke den Weiter-Button. Danke!");
define("_ZOOM_PASS_BUTTON","Weiter");
define("_ZOOM_PASS_GALLERY","Passwort");
define("_ZOOM_PASS_INNCORRECT","Passwort falsch");

//Lightbox
define("_ZOOM_LIGHTBOX","Lightbox");
define("_ZOOM_LIGHTBOX_GALLERY","Lightboxe dieses Album!");
define("_ZOOM_LIGHTBOX_ITEM","Lightbox diese Datei!");
define("_ZOOM_LIGHTBOX_VIEW","Lightbox anschauen");
define("_ZOOM_YOUR_LIGHTBOX","Dein Lightbox Inhalt:");
define("_ZOOM_LIGHTBOX_ZIPBTN","ZIP-Datei erstellen");
define("_ZOOM_LIGHTBOX_CATS","Alben");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Titel & Beschreibung");
define("_ZOOM_ACTION","Ausf&uuml;hren");
define("_ZOOM_LIGHTBOX_ADDED","Datei erfolgreich in Deine Lightbox hinzugef&uuml;gt!");
define("_ZOOM_LIGHTBOX_NOTADDED","Fehler beim Einf&uuml;gen in die Lightbox!");
define("_ZOOM_LIGHTBOX_EDITED","Datei erfolgreich editiert!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Fehler beim Editieren der Datei!");
define("_ZOOM_LIGHTBOX_DEL","Datei erfolgreich aus der Lightbox entfernt!");
define("_ZOOM_LIGHTBOX_NOTDEL","Fehler beim Enfernen der Datei aus der Lightbox!");
define("_ZOOM_LIGHTBOX_NOZIP","Du hast schon eine ZIP-Datei Deiner Lightbox erstellt!");
define("_ZOOM_LIGHTBOX_PARSEZIP","F&uuml;ge Bilder aus Album ein...");
define("_ZOOM_LIGHTBOX_DOZIP","Erstelle ZIP-Datei...");
define("_ZOOM_LIGHTBOX_DLHERE","Du kannst nun die Lightbox herunterladen");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Zeige/ verberge EXIF-Info");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","gerade spielt:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Klicke hier zum abspielen.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Zeige/ verberge ID3-tag Informationen");
define("_ZOOM_ID3_LENGTH","Länge");
define("_ZOOM_ID3_TITLE","Titel");
define("_ZOOM_ID3_ARTIST","Interpret");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","Jahr");
define("_ZOOM_ID3_COMMENT","Kommentar");
define("_ZOOM_ID3_GENRE","Genre");

//rating
define("_ZOOM_RATING","Bewertung");
define("_ZOOM_NOTRATED","Keine Bewertungen verf&uuml;gbar!");
define("_ZOOM_VOTE","Stimme");
define("_ZOOM_VOTES","Stimmen");
define("_ZOOM_RATE0","schlecht");
define("_ZOOM_RATE1","nicht so toll");
define("_ZOOM_RATE2","befriedigend");
define("_ZOOM_RATE3","gut");
define("_ZOOM_RATE4","sehr gut");
define("_ZOOM_RATE5","perfekt!");

//special
define("_ZOOM_TOPTEN","Die Besten 10");
define("_ZOOM_LASTSUBM","Zuletzt &uuml;bermittelt");
define("_ZOOM_LASTCOMM","Zuletzt kommentiert");
define("_ZOOM_SEARCHRESULTS","Suchergebnisse");
define("_ZOOM_TOPRATED","Am Besten bewertet");

//ecard
define("_ZOOM_ECARD_SENDAS","Sende dieses Bild als E-Card an einen Freund!");
define("_ZOOM_ECARD_YOURNAME","Dein Name");
define("_ZOOM_ECARD_YOUREMAIL","Deine E-Mail Addresse");
define("_ZOOM_ECARD_FRIENDSNAME","Name Deines Freundes");
define("_ZOOM_ECARD_FRIENDSEMAIL","E-Mail Addresse Deines Freundes");
define("_ZOOM_ECARD_MESSAGE","Nachricht");
define("_ZOOM_ECARD_SENDCARD","Sende E-Card");
define("_ZOOM_ECARD_SUCCESS","Deine E-Card wurde erfolgreich versandt.");
define("_ZOOM_ECARD_CLICKHERE","Klicke hier um die E-Card zu sehen!");
define("_ZOOM_ECARD_ERROR","Fehler beim Versenden.");
define("_ZOOM_ECARD_TURN","R&uuml;ckseite ansehen");
define("_ZOOM_ECARD_TURN2","Vorderseite ansehen");
define("_ZOOM_ECARD_SENDER","An Dich gesandt von:");
define("_ZOOM_ECARD_SUBJ","Du hast eine E-Card erhalten von:");
define("_ZOOM_ECARD_MSG1","hat Dir eine E-Card geschickt von");
define("_ZOOM_ECARD_MSG2","Klicke auf den Link darunter, um Deine persönliche E-Card zu sehen");
define("_ZOOM_ECARD_MSG3","Bitte nicht auf diese E-Mail antworten, da diese automatisch erstellt wurde!");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','zOOm Installation versuch das Bilderverzeichnis zu erstellen "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','fertig!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','fehlgeschlagen!');
define ('_ZOOM_INSTALL_MESS1','zOOm Image Gallery wurde erfolgreich installiert.<br>Du kannst nun deine Fotos und Alben veröffentlichen!');
define ('_ZOOM_INSTALL_MESS2','Anmerkung: Das erste, was du jetzt tun solltest ist, in das Componenten Menü zu wechseln.<br>Suche nach dem Eintrag "zOOm Media Gallery Admin", anklicken und in die <br>Einstellungen wechseln.');
define ('_ZOOM_INSTALL_MESS3','Hier kannst du alle Parameter für zOOm ändern.');
define ('_ZOOM_INSTALL_MESS4','Vergiss nicht ein Album zu erstellen');
define ('_ZOOM_INSTALL_MESS_FAIL1','zOOM Gallery konnte nicht erfolgreich installiert werden!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Folgende Verzeichnisse müssen noch erstellt werden und benötigen die Rechte "0777":<br />'
. '"images/zoom"<br />'
. '/components/com_zoom/images"<br />'
. '"/components/com_zoom/admin"<br />'
. '"/components/com_zoom/classes"<br />'
. '"/components/com_zoom/images"<br />'
. '"/components/com_zoom/images/admin"<br />'
. '"/components/com_zoom/images/filetypes"<br />'
. '"/components/com_zoom/images/rating"<br />'
. '"/components/com_zoom/images/smilies"<br />'
. '"/components/com_zoom/language"<br />'
. '"/components/com_zoom/tabs"');
define ('_ZOOM_INSTALL_MESS_FAIL3','Wenn du diese Verzeichnisse eingerichtet hast, gehe zu <br /> "Components -> zOOm Media Gallery" und ändere deine Einstellungen.');


//Module Language
define("_ZOOM_M_config","Module");
define("_ZOOM_M_method","Anzeige Methode");
define("_ZOOM_M_all","alle");
define("_ZOOM_M_random","Zufall");
define("_ZOOM_M_newest","neueste");
define("_ZOOM_M_hits","Zugriffe");
define("_ZOOM_M_votes","Stimmen");
define("_ZOOM_M_count","Anzahl der Bilder:");
define("_ZOOM_M_lastup","Letztes Update:");
define("_ZOOM_M_admin_count","Zeige Anzahl der Bilder:");
define("_ZOOM_M_admin_lastup","Zeige letztes Update:");
define("_ZOOM_M_admin_cats","Zeige Albumname:");
define("_ZOOM_M_admin_meth","Zeige Methode:");
define("_ZOOM_M_admin_df","Datumformat (j M, H:i):");
?>
