<?php

/*
   ____  __      __    ___  _  _  ___    __   ____     ___  __  __  ___
  (  _ \(  )    /__\  / __)( )/ )/ __)  /__\ (_  _)   / __)(  \/  )/ __)
   ) _ < )(__  /(__)\( (__  )  (( (__  /(__)\  )(    ( (__  )    ( \__ \
  (____/(____)(__)(__)\___)(_)\_)\___)(__)(__)(__)    \___)(_/\/\_)(___/

   @author          Black Cat Development
   @copyright       Black Cat Development
   @link            https://blackcat-cms.org
   @license         http://www.gnu.org/licenses/gpl.html
   @category        CAT_Core
   @package         CAT_Core

*/

include CAT_ENGINE_PATH.'/languages/DE.php';

$LANG = array_merge($LANG,array(
    // --------------- Backend ---------------
    'Close all'         => 'Alle schließen',
    'Content'           => 'Inhalte bearbeiten',
    'Default page'      => 'Einstiegsseite',
    'disabled'          => 'deaktiviert',
    'enabled'           => 'aktiviert',
    'Home'              => 'Dashboard',
    'Keep open'         => 'Offenhalten',
    'Logout'            => 'Abmelden',
    'Open all'          => 'Alle öffnen',
    'Profile'           => 'Benutzerprofil',
    'Recover'           => 'Wiederherstellen',
    'User Profile'      => 'Benutzerprofil',
    'Built-in objects must not be removed' => 'Standard-Objekte können nicht gelöscht werden',
    'Roles_and_permissions' => 'Rollen und Rechte',

    // --------------- Backend -> own profile ---------------
    'profile'           => 'Eigenes Benutzerprofil',
    'dashboard'         => 'Dashboard',
    'administration'    => 'Administrationsbereich',
    'content'           => 'Inhaltsbereich',

    // --------------- Backend -> Session ---------------
    'Close Backend' => 'Backend schließen',
    'Close the Backend and open Homepage (Frontend)' => 'Das Backend schließen und die Hompage öffnen (Frontend)',
    'Do you wish to login again?' => 'Möchten Sie sich neu anmelden?',
    'Login with the given credentials and stay on current page' => 'Mit den angegebenen Daten anmelden und auf der aktuellen Seite bleiben',
    'Remaining session time' => 'Verbleibende Sessionzeit',
    'Session timed out!' => 'Die Session ist abgelaufen!',

    // --------------- Backend -> Menu ---------------
    'Addons'            => 'Erweiterungen',
    'Admintools'        => 'Admin Werkzeuge',
    'Datetime'          => 'Datum &amp; Zeit',
    'Groups'            => 'Gruppen',
    'Headers'           => 'Kopfdateien',
    'Media'             => 'Dateien',
    'Pages'             => 'Seiten',
    'Preferences'       => 'Profil',
    'Roles'             => 'Rollen',
    'Security'          => 'Sicherheit',
    'Settings'          => 'Einstellungen',

    // --------------- Backend -> Dashboard ---------------
    'Add widget'        => 'Widget hinzufügen',
    'Remove widget'     => 'Widget entfernen',
    'Reset Dashboard'   => 'Dashboard zurücksetzen',
    'Do you really want to remove this widget?' => 'Soll dieses Widget wirklich entfernt werden?',
    'Do you really want to reset the Dashboard? All your customization settings will be lost!' => 'Soll das Dashboard wirklich zurückgesetzt werden? Alle persönlichen Einstellungen gehen verloren!',
    'No addable widgets found.' => 'Es wurden keine hinzufügbaren Widgets gefunden.',
    'There are no widgets on your dashboard.' => 'Es befinden sich keine Widgets auf diesem Dashboard.',
    'Use widget setting' => 'Widget-Voreinstellung verwenden',
    'You can add widgets to your dashboard by clicking on the [Add widget] button' => 'Um diesem Dashboard Widgets hinzuzufügen, bitte die [Widget hinzufügen] Schaltfläche verwenden.',

    // --------------- Backend -> Pages ---------------
    'Add page'          => 'Neue Seite',
    'Add section'       => 'Sektion hinzufügen',
    'Add Template CSS'  => 'Template CSS hinzufügen',
    'Actions'           => 'Aktionen',
    'Block number'      => 'Blocknr.',
    'Bootswatch theme'  => 'Bootswatch Theme',
    'Choose an image'   => 'Bild wählen',
    'Collapse all'      => 'Alle einklappen',
    'Date from'         => 'Datum von',
    'Date until'        => 'Datum bis',
    'deleted'           => 'gelöscht',
    'Delete page'       => 'Seite löschen',
    'Delete section'    => 'Abschnitt löschen',
    'draft'             => 'Entwurf',
    'Edit content'      => 'Inhalt bearbeiten',
    'Every day'         => 'Täglich',
    'Expand all'        => 'Alle aufklappen',
    'Header files'      => 'Kopfdateien',
    'hidden'            => 'versteckt',
    'Linked page'       => 'Verknüpfte Seite',
    'Menu title'        => 'Menütitel',
    'Module'            => 'Erweiterung',
    'Move'              => 'Verschieben',
    'No files'          => 'Keine Dateien',
    'no name'           => 'kein Name',
    'No pages yet'      => 'Noch keine Seiten',
    'none'              => 'keine',
    'Page deleted'      => 'Seite gelöscht',
    'Page language'     => 'Seitensprache',
    'Page parent'       => 'Übergeordnete Seite',
    'Page title'        => 'Seitentitel',
    'Page type'         => 'Seitentyp',
    'Period of time'    => 'Zeitspanne',
    'Preview'           => 'Vorschau',
    'private'           => 'privat',
    'public'            => 'öffentlich',
    'registered'        => 'registriert',
    'Relations'         => 'Beziehungen',
    'Template options'  => 'Template-Optionen',
    'Time of day'       => 'Uhrzeit',
    'Time from'         => 'Uhrzeit von',
    'Time until'        => 'Uhrzeit bis',
    'Use jQuery'        => 'jQuery verwenden',
    'Use jQuery UI'     => 'jQuery UI verwenden',
    'Variant'           => 'Variante',
    'Visibility'        => 'Sichtbarkeit',
    'A permalink or permanent link is a URL that is intended to remain unchanged for many years into the future, yielding a hyperlink that is less susceptible to link rot.'
        => 'Ein Permalink ist ein dauerhafter Identifikator in Form einer URL. Bei der Einrichtung eines Permalinks wird angestrebt, die einmal über ihn referenzierten Inhalte dauerhaft und primär über diese URL verfügbar zu machen.',
    'About module variants' => 'Über Modul-Varianten',
    'Add jQuery Plugin' => 'jQuery Plugin hinzufügen',
    'Add explicit Javascript file' => 'Ein bestimmtes Javascript hinzufügen',
    'Add explicit CSS file' => 'Eine bestimmte CSS Datei hinzufügen',
    'Change visibility' => 'Sichtbarkeit ändern',
    'Currently, no extra files are defined for this page.' => 'Zur Zeit sind keine zusätzlichen Dateien für diese Seite konfiguriert.',
    'Do you really want to delete this page?' => 'Soll diese Seite wirklich gelöscht werden?',
    'Do you really want to delete this section?' => 'Soll dieser Abschnitt wirklich <strong>gelöscht</strong> werden?',
    'Do you really want to recover this section?' => 'Soll dieser Abschnitt wirklich wiederhergestellt werden?',
    'Do you really want to unlink the selected page?' => 'Soll diese Seitenbeziehung wirklich entfernt werden?',
    'For example, WYSIWYG sections have variants for multiple columns per row (shown next to each other), accordion, tabs, etc.' => 'Beispielsweise verfügen WYSIWYG-Sektionen über Varianten für mehrere Spalten pro Zeile (nebeneinander dargestellt), Accordion, Tabs, usw.',
    'Icon explanation' => 'Symbolerklärung',
    'If a section shall be visible between two dates, put the start and end date here.' => 'Wenn eine Sektion während einer gewissen Datumsspanne sichtbar sein soll, hier das Start- und Endedatum angeben.',
    "If a section shall be visible between X and Y o'clock every day, put the start and end times here." => 'Wenn eine Sektion nur zwischen X und Y Uhr jeden Tag sichbar sein soll, hier Start- und Ende-Uhrzeit angeben.',
    'If you set visibility to false, the section will <strong>not</strong> be shown. This means, all other settings - like periods of time - are ignored.' => 'Ist die Sichtbarkeit hier deaktiviert, wird diese Sektion <strong>nicht</strong> angezeigt. Alle anderen Einstellungen - z.B. eine Zeitspanne - werden ignoriert.',
    'Menu appearance' => 'Menüzugehörigkeit',
    'Move section to another page' => 'Sektion auf eine andere Seite verschieben',
    'No sections were found for this page' => 'Keine Sektionen für diese Seite gefunden',
    'Please enter max. 55 characters' => 'Bitte maximal 55 Zeichen',
    'Please note that there is a bunch of files that is loaded automatically, so there\'s no need to add them here.' => 'Bitte beachten, dass es eine Reihe von Dateien gibt, die automatisch geladen werden und daher hier nicht verwaltet werden können und müssen.',
    'Please refer to the documentation of each module to learn more about the available variants.' => 'Bitte die Dokumentation des jeweiligen Moduls für Informationen zu den verfügbaren Varianten einsehen.',
    'public - visible for all visitors; registered - visible for configurable groups of visitors; ...' => 'öffentlich - für alle Besucher sichtbar; registriert - für eine einstellbare Gruppe von Besuchern sichtbar; ...',
    'Remove relation' => 'Beziehung entfernen',
    'See this page in the frontend; opens a new tab or browser window' => 'Diese Seite im Frontend ansehen; öffnet einen neuen Browser-Tab oder ein neues Fenster',
    'Select the menu the page belongs to. The menu select depends on the chosen template.' => 'Das Menü wählen, zu dem die Seite gehört. Die Auswahl ist abhängig vom eingestellten Template.',
    'Set language relation' => 'Sprachbeziehung setzen',
    'Set publishing period' => 'Sichtbarkeits-Zeitraum bearbeiten',
    'System default' => 'Standardeinstellung',
    'Target language' => 'Zielsprache',
    'Template variant' => 'Template-Variante',
    'The description should be a nice &quot;human readable&quot; text having 70 up to 156 characters.' => 'Die Beschreibung sollte ein &quot;menschenlesbarer&quot; Text mit mindestens 70 und bis zu 156 Zeichen sein.',
    'The (main) language of the page contents.' => 'Die (hauptsächliche) Sprache der Seiteninhalte.',
    'The (main) type (section) for the page contents.' => 'Haupttyp (Sektion) des Seiteninhalts.',
    'The menu title is used for the navigation menu. Hint: Use short but descriptive titles.' => 'Der Menütitel wird für das Navigationsmenü verwendet. Tipp: Kurze aber aussagekräftige Titel verwenden.',
    'The page is accessible for all visitors and shows up in the navigation by default' => 'Die Seite ist für alle Besucher sichtbar und erscheint üblicherweise auch im Menü',
    'The page is accessible for visitors who know the exact address and can be found by the keyword search, but does not show up in the navigation by default' => 'Die Seite ist sichtbar, wenn man die Adresse kennt, und wird von der Suchfunktion gefunden, erscheint aber nicht im Menü',
    'The page is not accessible in the frontend at all, but can be edited in the backend' => 'Die Seite kann von Besuchern nicht aufgerufen, aber im Backend bearbeitet werden',
    'The page is not ready yet and is not shown in the frontend' => 'Die Seite ist noch nicht fertig und kann von Besuchern nicht aufgerufen werden',
    'The page is only accessible to registered users and is not shown in the navigation for non-registered users' => 'Die Seite ist nur für berechtigte Benutzer sichtbar und erscheint nur im Menü, wenn der Benutzer angemeldet ist',
    'The page is only accessible to registered users; the page shows up in the navigation by default' => 'Die Seite ist nur für berechtigte Benutzer sichtbar; sie erscheint üblicherweise auch im Menü',
    'The page was deleted but can be recovered' => 'Die Seite ist gelöscht, kann aber wiederhergestellt werden',
    'The pages are already linked together' => 'Die Seiten sind bereits miteinander verknüpft',
    'The position of the page in the page tree.' => 'Die Position der Seite im Seitenbaum.',
    'The section was saved successfully' => 'Die Sektion wurde erfolgreich gespeichert',
    'The title should be a nice &quot;human readable&quot; text having 30 up to 55 characters.' => 'Der Seitentitel sollte ein &quot;menschenlesbarer&quot; Text mit mindestens 30 und höchstens 55 Zeichen sein.',
    'There are no linked pages yet' => 'Es sind noch keine Seiten verlinkt',
    'There are no pages in the selected target language available.' => 'In der gewählten Sprache sind keine Seiten vorhanden.',
    'These settings are page based, to manage global settings, goto Settings -> Header files.' => 'Diese Einstellungen sind seitenbasiert, globale Einstellungen können unter Einstellungen -> Kopfdateien vorgenommen werden.',
    'This section is marked as deleted.' => 'Dieser Abschnitt ist als gelöscht markiert.',
    'Use {language_menu()} in your frontend template to show links to the pages listed below.' => 'Das Markup {language_menu()} im Frontend-Template erzeugt Links zu den untenstehenden Seiten.',
    'Variants allow the selection of a specific presentation, possibly combined with specific settings.' => 'Varianten erlauben die Auswahl einer bestimmten Darstellung, eventuell in Kombination mit spezifischen Einstellungen.',
    'Who can view the page' => 'Wer darf die Seite sehen',
    'You can link any page to other pages in different languages that have the same content.' => 'Jede Seite kann mit Seiten in anderen Sprachen, die den gleichen Inhalt haben, verknüpft werden.',
    'You can manage Javascript- and CSS-Files resp. jQuery plugins to be loaded into the page header here.' => 'Hier können Javascript- und CSS-Dateien bzw. jQuery Plugins verwaltet werden, die zusätzlich in den Seitenkopf geladen werden sollen.',
    'You may override the system settings for the template here' => 'Systemweite Template-Einstellung für diese Seite ändern',
    'You may override the system settings for the template variant here' => 'Systemweite Template-Varianten-Einstellung für diese Seite ändern',
    'You may recover it by clicking on the recover icon.' => 'Durch Anklicken des Wiederherstellungs-Icons kann der Abschnitt wiederhergestellt werden.',

    // --------------- Backend -> Addons ---------------
    'Addon name'        => 'Name',
    'Addon type'        => 'Typ',
    'Addon author'      => 'Autor',
    'Addon directory'   => 'Verzeichnisname',
    'Addon description' => 'Kurzbeschreibung',
    'Catalog'           => 'Katalog',
    'Create'            => 'Erstellen',
    'Installed'         => 'Installiert',
    'Languages'         => 'Sprachen',
    'Libraries'         => 'Bibliotheken',
    'Modules'           => 'Erweiterungen',
    'Notinstalled'      => '(Noch) nicht installiert',
    'Page modules'      => 'Seitenmodule',
    'Up to date'        => 'Aktuell',
    'Upgraded'          => 'Aktualisiert',
    'Use Bootstrap'     => 'Bootstrap verwenden',
    'Not (yet) installed' => '(Noch) nicht installiert',
    'The module was created.' => 'Die Erweiterung wurde erstellt.',
    'Type to filter by text...' => 'Zum Filtern tippen...',
    'Update the catalog' => 'Katalog aktualisieren',
    'You cannot uninstall this module as it is protected' => 'Modul ist vor Deinstallation geschützt',
    'Your catalog version' => 'Katalog Version',


    // --------------- Backend -> Roles ---------------
    'New role'          => 'Neue Rolle',
    'Delete role'       => 'Rolle löschen',
    'Permissions'       => 'Rechte',
    'Role ID'           => 'Rollen ID',
    'Title'             => 'Name',
    'Users'             => 'Benutzer',
    'Brief description' => 'Kurze Beschreibung',
    'Do you really want to delete this role?' => 'Wollen Sie diese Rolle wirklich löschen?',
    'Manage role permissions' => 'Rechte bearbeiten',
    'Set permissions for role' => 'Rechte setzen für Rolle',

    // --------------- Backend -> Users ---------------
    'Add users'         => 'Benutzer hinzufügen',
    'active'            => 'aktiv',
    'Backend language'  => 'Backend Sprache',
    'Built in'          => 'Standard (mitgeliefert)',
    'City'              => 'Stadt',
    'Contact'           => 'Kontaktdaten',
    'Country'           => 'Land',
    'Delete user'       => 'Benutzer löschen',
    'Display name'      => 'Anzeigename',
    'Edit user'         => 'Benutzer ändern',
    'eMail address'     => 'eMail Adresse',
    'Home folder'       => 'Homeverzeichnis',
    'Locked by admin'   => 'Vom Administrator gesperrt',
    'Login name'        => 'Loginname',
    'Mobile Phone'      => 'Mobiltelefon',
    'Phone'             => 'Festnetz',
    'Postal'            => 'Postanschrift',
    'Street'            => 'Straße',
    'Tfa enabled'       => 'Zwei-Faktor-Authentifizierung aktiviert',
    'User ID'           => 'Benutzer ID',
    'Username'          => 'Benutzerkennung',
    'ZIP Code'          => 'Postleitzahl',
    'Choose the users you wish to add and click [Save]' => 'Die gewünschten Benutzer auswählen und [Speichern] anklicken',
    'Do you really want to delete this user?' => 'Soll dieser Benutzer wirklich gelöscht werden?',
    'Edit group members' => 'Gruppenmitglieder bearbeiten',
    'This is a built-in-user, so you cannot change it' => 'Systembenutzer; kann nicht geändert werden',
    'Two-Step Authentication disabled' => 'Zwei-Faktor Authentifizierung deaktiviert',
    'Two-Step Authentication enabled' => 'Zwei-Faktor Authentifizierung aktiviert',
    'User is locked by administrator; locked users are not allowed to log in into backend' => 'Benutzer vom Administrator gesperrt; gesperrte Benutzer dürfen sich nicht im Admin-Bereich anmelden',

    // --------------- Backend -> Groups ---------------
    'Add group members' => 'Gruppenmitglieder hinzufügen',
    'Delete group'      => 'Gruppe löschen',
    'Group ID'          => 'Gruppen ID',
    'Click here to edit the group name' => 'Zum Ändern des Gruppennamens hier klicken',
    'Do you really want to remove this group member?' => 'Soll dieses Gruppenmitglied wirklich aus der Gruppe entfernt werden?',
    'Group member successfully removed' => 'Gruppenmitglied erfolgreich entfernt',
    'Do you really want to delete this group?' => 'Soll diese Gruppe wirklich gelöscht werden?',
    'Manage group members' => 'Gruppenmitglieder verwalten',
    'No addable users found' => 'Keine passenden Benutzer gefunden',
    'Remove group member' => 'Gruppenmitglied entfernen',
    'Users of group "Administrators" and users that are already member of this group cannot be added.' => 'Benutzer der Gruppe "Administratoren" und Benutzer, die bereits Mitglied dieser Gruppe sind, können nicht hinzugefügt werden.',

    // --------------- Backend -> Media ---------------
    'All types'         => 'Alle Dateitypen',
    'Bits per sample'   => 'Auflösung',
    'Date'              => 'Datum',
    'Dimensions'        => 'Dimensionen',
    'Filename'          => 'Dateiname',
    'Files'             => 'Dateien',
    'Foldername'        => 'Verzeichnisname',
    'Folders'           => 'Verzeichnisse',
    'Images'            => 'Bilder',
    'Protected'         => 'Geschützt',
    'Resolution X'      => 'Breite in Pixel',
    'Resolution Y'      => 'Höhe in Pixel',
    'Size'              => 'Größe',
    'Unzip'             => 'Entpacken',

    // --------------- Backend -> Permissions ---------------
    'Access to global dashboard' => 'Zugang zum globalen Dashboard',
    'Access to groups'  => 'Zugang zur Gruppenverwaltung',
    'Access to login page' => 'Zugang zur Anmeldeseite',
    'Access to media section' => 'Zugang zur Medienverwaltung',
    'Access to pages'   => 'Zugang zur Seitenverwaltung',
    'Access to permissions' => 'Zugang zur Rechteverwaltung',
    'Access to roles'   => 'Zugang zur Rollenverwaltung',
    'Access to tools'   => 'Zugang zu den Admin-Tools',
    'Access to users'   => 'Zugang zur Benutzerverwaltung',
    'Add new permissions' => 'Neue Rechte anlegen',
    'Add section to page' => 'Sektion zur Seite hinzufügen',
    'Add/remove folder protection (.htaccess)' => 'Verzeichnisschutz hinzfügen/entfernen (.htaccess)',
    'Backend access'    => 'Backend-Zugang',
    'Create a new page' => 'Neue Seite anlegen',
    'Create a new role' => 'Neue Rolle anlegen',
    'Create a new user group' => 'Neue Gruppe anlegen',
    'Create new folder' => 'Verzeichnis anlegen',
    'Create new group'  => 'Neue Gruppe anlegen',
    'Create new users'  => 'Neue Benutzer anlegen',
    'Create root pages (level 0)' => 'Root-Seiten anlegen (Level 0)',
    'Delete files' => 'Medien-Dateien löschen',
    'Delete folder'     => 'Verzeichnis löschen',
    'Delete groups'     => 'Gruppen löschen',
    'Delete pages'      => 'Seiten löschen',
    'Delete roles'      => 'Rollen löschen',
    'Delete section(s) from page' => 'Sektion(en) von der Seite entfernen',
    'Delete social media links globally' => 'Social Media Links global löschen',
    'Delete users'      => 'Benutzer löschen',
    'Edit global social media links' => 'Social Media Links global bearbeiten',
    'Edit group membership' => 'Gruppenmitgliedschaften bearbeiten',
    'Edit intro page'   => 'Einstiegsseite bearbeiten',
    'Edit page settings' => 'Seiteneinstellungen bearbeiten',
    'Edit settings' => 'Einstellungen ändern',
    'Edit social media links on site' => 'Social Media Links der Präsenz (Site) bearbeiten',
    'Edit user data'    => 'Vorhandene Benutzer bearbeiten',
    'Frontend access'   => 'Zugang zum Frontend',
    'Have widgets on the dashboard' => 'Widgets auf dem Dashboard sehen',
    'Install new addons' => 'Addons installieren',
    'List available Admin Tools' => 'Verfügbare Admin Tools sehen',
    'List installed addons' => 'Installierte Addons sehen',
    'Manage group members' => 'Gruppenmitglieder verwalten',
    'Manage role permissions' => 'Rechte in Rollen bearbeiten',
    'Modify existing pages' => 'Vorhandene Seiten bearbeiten',
    'Modify group data' => 'Gruppendaten bearbeiten',
    'Permission'        => 'Berechtigung',
    'Recover a deleted section' => 'Gelöschte Sektion wiederherstellen',
    'Rename and/or move media files' => 'Mediendateien umbenennen oder verschieben',
    'Rename folder'     => 'Verzeichnis umbenennen',
    'See a list of available media files' => 'Liste der Mediendateien sehen',
    'See all users'     => 'Vorhandene Benutzer auflisten',
    'See available admin tools' => 'Vorhandene Admin-Tools auflisten',
    'See available user groups' => 'Vorhandene Gruppen auflisten',
    'See defined permissions' => 'Vorhandene Rechte auflisten',
    'See defined roles' => 'Vorhandene Rollen auflisten',
    'See settings' => 'Einstellungen sehen',
    'See the page tree' => 'Seitenbaum sehen',
    'Set publishing date and time' => 'Veröffentlichungsdatum und -zeit einstellen',
    'Upload media files' => 'Mediendateien hochladen',
    'User can configure his dashboard' => 'Benutzer kann sein Dashboard anpassen',
    'User can edit his profile' => 'Benutzer kann eigenes Profil bearbeiten',
    'View social media links' => 'Social Media Links sehen',

    // --------------- Backend -> Settings ----------
    'Asset paths'       => 'Asset Verzeichnisse',
    'Cc enabled'        => 'Cookie Consent aktiviert',
    'Cookie name'       => 'Cookie-Name',
    'Contact email'     => 'Kontakt Mailadresse',
    'Contact phone'     => 'Kontakt Telefonnummer',
    'Date format'       => 'Datumsformat',
    'Default charset'   => 'Standard Encoding / Charset',
    'Default language'  => 'Standard-Sprache',
    'Default template'  => 'Standard-Template',
    'Default theme'     => 'Standard-Template',
    'Favicon tilecolor' => 'Favicon Hintergrundfarbe',
    'Manage Favicon'    => 'Favicons verwalten',
    'Media directory'   => 'Medien-Verzeichnis',
    'Network'           => 'Netzwerk',
    'Time format'       => 'Uhrzeitformat',
    'Timezone'          => 'Zeitzone',
    'Track visitors'    => 'Besucherzähler aktivieren',
    'Trash enabled'     => 'Seitenmülleimer eingeschaltet',
    'Website brand'     => 'Website Marke / Bezeichnung',
    'Website title'     => 'Seitentitel',
    'Wysiwyg editor'    => 'WYSIWYG Editor',
    'Allowed file types for uploads' => 'Erlaubte Dateitypen für Uploads',
    'Default template variant' => 'Variante',
    'Default theme variant' => 'Variante',
    'Default WYSIWYG-Editor for use in the backend' => 'Standard WYSIWYG Editor',
    'If enabled, deleted pages and sections can be recovered.'
        => 'Eingeschaltet: Seiten und Sektionen können wiederhergestellt werden',
    'If the favicons are generated by the system, this color will be used as the background color.'
        => 'Wenn die Favicons vom System erzeugt werden, wird diese Farbe als Hintergrundfarbe verwendet.',
    "If your server is placed behind a proxy (i.e. if you're using BC for an Intranet), set the name here."
        => 'Wenn sich der Server hinter einem Proxy befindet (z.B. wenn BC für ein Intranet verwendet wird), hier den Namen eintragen.',
    'Manage Cookie Consent' => 'Cookie Consent Einstellungen',
    'Use encrypted sessions' => 'Verschlüsselte Sessions verwenden',
    'The default template is used for every page that does not have a different setting.'
        => 'Das Standard-Template wird für alle Seiten verwendet, die keine abweichende Einstellung haben.',
    'This template has variants.' => 'Dieses Template hat Varianten.',
    'This eMail address may be shown by the frontend template. It may also be used as a default for contact forms.'
        => 'Diese Mailadresse wird möglicherweise vom Frontend Template veröffentlicht. Sie wird auch als Vorgabe für Kontaktformulare verwendet.',

    // ---------- Backend -> Settings -> Favicons ----------
    'android'           => 'Android',
    'apple'             => 'Apple',
    'desktop'           => 'PCs',
    'webapp'            => 'Web Applikationen',
    'windows'           => 'Windows (ab Version 8.0)',
    'Below you can see which Favicon files BlackCat CMS is looking for to populate the page header. A checkmark shows if the file is available.'
        => 'BlackCat CMS sucht nach den unten aufgeführten Dateien, um damit den Seitenkopf zu befüllen. Gefundene Dateien sind anhgehakt.',
    'The CMS will also look for a &quot;browserconfig.xml&quot; file (for Internet Explorer >= 11) and manifest.json (for Web Apps).'
        => 'Das CMS schaut außerdem nach einer &quot;browserconfig.xml&quot; (für Internet Explorer >= 11) und manifest.json (für Web Apps).',
    'While there are several different sizes (for older devices in most cases), we only look for the files with the highest possible pixel rate, as these will still look good when sized down by the device.'
        => 'Obwohl eine Vielzahl unterschiedlicher Größen möglich ist (oft für ältere Geräte), sucht BlackCat CMS nur nach den Dateien mit der jeweils höchsten Auflösung, da diese auch dann gut aussehen, wenn sie vom Gerät herunterskaliert werden.',

    // ---------- Backend -> Settings -> Socialmedia ----------
    'Account' => 'Konto',
    'Add service' => 'Service hinzufügen',
    'Data' => 'Daten',
    'These are the <strong>globally</strong> available social media services.' => 'Dieses sind die <strong>global</strong> verfügbaren Social Media Dienste.',
    'Services added here are available for all sites.' => 'Hier hinzugefügte Dienste sind für alle Sites verfügbar.',
    'Services deleted here will no longer be available on all sites. (!)' => 'Hier gelöschte Dienste sind auf keiner (!) Site mehr verfügbar.',
    'Placeholders' => 'Platzhalter',
    'Will be replaced with the name of the configured service account' => 'Wird mit dem konfigurierten Kontonamen des Service ersetzt',
    'Will be replaced with the URL of the current page' => 'Wird mit der Adresse (URL) der aktuellen Seite ersetzt',
    'Will be replaced with the page title of the current page' => 'Wird mit dem Seitentitel der aktuellen Seite ersetzt',
    'Will be replaced with the description META information of the current page' => 'Wird mit der Beschreibung (META) der aktuellen Seite ersetzt',
    'The services can be configured using the "Social Media Services" Admin Tool.' => 'Die Dienste können mit Hilfe des "Social Media Services" Admin Tools konfiguriert werden.',

    // ---------- Backend -> Sites ----------
    'A site with the same name already exists!' => 'Es existiert bereits eine Präsenz mit diesem Namen!',
    'A site with the same folder name already exists!' => 'Es existiert bereits eine Präsenz mit diesem Unterverzeichnis!',
    'Base URL of the site' => 'Basis-URL der Präsenz',
    'Basedir' => 'Basisverzeichnis',
    'Create site' => 'Webpräsenz erstellen',
    'Edit site' => 'Webpräsenz bearbeiten',
    'New site' => 'Neue Präsenz',
    'No such folder!' => 'Verzeichnis nicht gefunden!',
    'Owner' => 'Besitzer',
    'Please note: You cannot change the folder settings here. This is by design.' => 'Bitte beachten: Die Verzeichnis-Einstellungen können nicht bearbeitet werden.',
    'Site name' => 'Site-Name',
    'Site url' => 'Site-URL',
    'Sites' => 'Präsenzen',
    'Subfolder' => 'Unterverzeichnis',
    'The basedir of the site' => 'Das Basisverzeichnis der Präsenz',
    'The folder [{{folder}}] already exists!' => 'Das Verzeichnis [{{folder}}] existiert bereits!',
    'The name of the subfolder inside Basedir' => 'Der Name des Unterverzeichnisses unterhalb des Basisverzeichnisses',
    'The owner of a site will have admin privileges by default' => 'Der Besitzer einer Site erhält automatisch Adminrechte',
    'The site name may help you to distinguish your sites' => 'Der Site-Name hilft dabei, Sites voneinander zu unterscheiden',

    // ---------- Backend -> Menus ----------
    'Menus of this type' => 'Menüs dieses Typs',
    'Shows "path" to current page' => 'Zeigt den "Pfad" zur aktuellen Seite',
    'Shows all pages that are visible for the current user' => 'Zeigt alle Seiten, die für den aktuellen Besucher sichtbar sind',
    'Shows links to current page in other languages' => 'Zeigt Links zur gleichen Seite in anderen Sprachen',
    'Shows all pages that are visible for the current user and on the same level as the current page' => 'Zeigt alle Seiten, die für den aktuellen Besucher sichtbar und "Nachbarn" (=auf dem gleichen Level) der aktuellen Seite sind',
));