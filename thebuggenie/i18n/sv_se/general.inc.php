<?php			
			
	if (!defined('THEBUGGENIE_PATH')) exit();		
			
	TBGContext::getI18n()->setCharset('iso-8859-1');		
	setlocale(LC_ALL, array('sv_SE@euro', 'sv_SE', 'se'));
			
	$strings['Released under the MPL 1.1 only. Read the license at %link_to_MPL%'] = 'Released under the MPL 1.1 only. Read the license at %link_to_MPL%';
	$strings['Welcome, %username%'] = 'V�lkommen, %username%';
	$strings['Frontpage'] = 'Startsida';
	$strings['Report an issue'] = 'L�gg till �rende';
	$strings['Continue reporting'] = 'L�gg till �rende';
	$strings['Configuration center'] = 'Kontrollpanel';
	$strings['About'] = 'Om';
	$strings['My account'] = 'Mitt konto';
	$strings['Places'] = 'Platser';
	$strings['Settings'] = 'Inst�llningar';
	$strings['Common actions'] = 'Vanliga �tg�rder';
	$strings['View / edit my details'] = 'Visa / redigera mina uppgifter';
	$strings['Change my status to'] = '�ndra min status till';
	$strings['Never mind'] = 'Ingen fara';
	$strings['Log out'] = 'Logga ut';
	$strings['Switch back to original user'] = 'V�xla tillbaka till ursprungliga anv�ndare';
	$strings['Close menu'] = 'St�ng menyn';
	$strings['Close this menu'] = 'St�ng menyn';
	$strings['Logga in / Registrera'] = 'Logga in / Registrera';
	$strings['Login'] = 'Logga in';
	$strings['The Bug Genie online help'] = 'BUGS 2 onlinehj�lpen';
	$strings['Project overview'] = 'Produkt�versikt';
	$strings['There are no projects.'] = 'Det finns inga produkter.';
	$strings['Before you can report issues, there needs to be at least one project.'] = 'Innan du kan anm�la fr�gor, det m�ste finnas minst en produkt.';
	$strings['Click here to go to project management'] = 'Klicka h�r f�r att g� till Project Management';
	$strings['Continue'] = 'Forts�tt';
	$strings['Thank you!'] = 'Tack!';
	$strings['Check'] = 'Check';
	$strings['None'] = 'Ingen';
	$strings['Delete'] = 'Ta bort';
	$strings['Yes'] = 'Ja';
	$strings['No'] = 'Nej';
	$strings['Ok'] = 'Ok';
	$strings['OK'] = 'OK';
	$strings['Done'] = 'Gjord';
	$strings['Edit'] = 'Redigera';
	$strings['Cancel'] = 'Avbryt';
	$strings['Show'] = 'Visa';
	$strings['Hide'] = 'D�lj';
	$strings['Remove'] = 'Flytta';
	$strings['Close'] = 'N�ra';
	$strings['Change'] = '�ndra';
	$strings['Set'] = 'Ange';
	$strings['Add'] = 'L�gg till';
	$strings['Add this'] = 'L�gg till denna';
	$strings['Save'] = 'Spara';
	$strings['Saved'] = 'Sparade';
	$strings['%save% or %cancel%'] = '%save% eller %cancel%';
	$strings['Update'] = 'Uppdatera';
	$strings['Name:'] = 'Namn:';
	$strings['Description:'] = 'Beskrivning:';
	$strings['Description'] = 'Beskrivning';
	$strings['People you know'] = 'M�nniskor som du k�nner';
	$strings['Friends'] = 'V�nner';
	$strings['Real name: %real_name%'] = 'Fullst�ndigt namn: %real_name%';
	$strings['People see you as: %buddy_name%'] = '�vriga ser dig som: %buddy_name%';
	$strings['You are currently: %user_state%'] = 'Du �r f�r n�rvarande: %user_state%';
	$strings['Homepage: %link_to_homepage%'] = 'Hemsida: %link_to_homepage%';
	$strings['Documentation: %link_to_documentation%'] = 'Dokumentation: %link_to_documentation%';
	$strings['Lead by: %user_or_team%'] = 'Leds av: %user_or_team%';
	$strings['QA by: %user_or_team%'] = 'Transaktionsansvar: %user_or_team%';
	$strings['QA Manager: %user_or_team%'] = 'Kvalitetsansvarig: %user_or_team%';
	$strings['No homepage provided'] = 'Ingen hemsida angiven';
	$strings['No URL provided'] = 'Ingen angiven URL';
	$strings['Open issues assigned to you'] = '�ppna fr�gor som du';
	$strings['No issues are assigned to you'] = 'Inga fr�gor �r f�r att du';
	$strings['Open issues assigned to your teams'] = '�ppna fr�gor till dina lag';
	$strings['No issues are assigned to this team'] = 'Inga fr�gor �r f�r att det h�r laget';
	$strings['Please enter a username and password'] = 'V�nligen ange ett anv�ndarnamn och l�senord';
	$strings['This account belongs to a scope that is not active'] = 'Det h�r kontot tillh�r en omfattning som inte �r aktiva';
	$strings['Your account has not been activated yet'] = 'Ditt konto har inte aktiverats �nnu';
	$strings['Your account has been suspended'] = 'Ditt konto har st�ngts av';
	$strings['No such login'] = 'Inga s�dana inloggning';
	$strings['No such user'] = 'Ingen s�dan anv�ndare';
	$strings['This username does not exist'] = 'Anv�ndarnamnet finns inte';
	$strings['Friendly name: %buddy_name%'] = 'kortnamn: %buddy_name%';
	$strings['This user is currently: %user_state%'] = 'Denna anv�ndare �r f�r n�rvarande: %user_state%';
	$strings['Email-address is hidden'] = 'E-post-kandidat �r dolda';
	$strings['Email address: %email_address%'] = 'E-postkandidat: %email_address%';
	$strings['Email address not provided'] = 'E-postkandidat inte angiven';
	$strings['Last seen: %time%'] = 'S�gs senast: %time%';
	$strings['Currently: %user_state%'] = 'Status: %user_state%';
	$strings['Become friends'] = 'Bli v�nner';
	$strings['Don\'t be friends any more'] = 'Inte vara v�nner l�ngre';
	$strings['%username% is now your friend'] = '%username% �r nu din v�n';
	$strings['%username% is no longer your friend'] = '%username% �r inte l�ngre din v�n';
	$strings['%username% is already your friend'] = '%username% �r redan din v�n';
	$strings['Click "Close menu" to close this menu.'] = 'Klicka p� "St�ng menyn" f�r att st�nga menyn.';
	$strings['View details'] = 'Visa information';
	$strings['Temporarily switch to this user'] = 'Tillf�lligt byta till den h�r anv�ndaren';
	$strings['Warning:'] = 'Varning!';
	$strings['You have already switched user once. Switching again clears the original user information, and you will have to log out and back in again to return to your original user.'] = 'Du har redan bytt anv�ndarprofil en g�ng. Genom att logga ut s� raderas den ursprungliga anv�ndarinformationen, och du tvingas logga ut och in igen f�r att �terg� till den ursprungliga anv�ndaren.';
	$strings['Unknown team'] = 'Ok�nt team';
	$strings['Please log in to view this page'] = 'Logga in f�r att visa den h�r sidan';
	$strings['You do not have access to this page'] = 'Du har inte tillg�ng till den h�r sidan';
	$strings['You do not have access to this module'] = 'Du har inte tillg�ng till denna modul';
	$strings['Please confirm'] = 'Bekr�fta';
	$strings['Are you sure you want to delete this item?'] = '�r du s�ker p� att du vill ta bort denna post?';
	$strings['No planned release'] = 'Inga planerade release';
	$strings['Planned release'] = 'Planerad release';
	$strings['You do not have access to this project'] = 'Du har inte tillg�ng till denna produkt';
	$strings['Enabled'] = 'Aktiverat';
	$strings['Enable'] = 'M�jligg�ra';
	$strings['Disabled'] = 'Funktionshindrade';
	$strings['Disable'] = 'Inaktivera';
	$strings['No description available'] = 'Ingen beskrivning tillg�nglig';
	$strings['Select a project'] = 'V�lj en produkt';
	$strings['Found these users'] = 'Hittade dessa anv�ndare';
	$strings['click on a user for more options'] = 'klicka p� en anv�ndare f�r fler alternativ';
	$strings['No users found'] = 'Inga anv�ndare funna';
	$strings['Users found'] = 'Anv�ndare hittade';
	$strings['Please enter at least two characters to search'] = 'Ange minst tv� tecken f�r att s�ka';
	$strings['Your changes has been saved'] = 'Dina �ndringar har sparats';
	$strings['Released %release_date%'] = '%Release_date% sl�ppt';
	$strings['Planned release %release_date%'] = 'Planerat avslut %release_date%';
	$strings['No release date set'] = 'Ej tidsbest�mt';
	$strings['Ver: %version_number%'] = 'Version: %version_number%';
	$strings['Search for'] = 'S�k';
	$strings['Search'] = 'S�k';
	$strings['Find'] = 'Hitta';
	$strings['Add this'] = 'L�gg till denna';
	$strings['Administered by:'] = 'Administreras av:';
	$strings['Email:'] = 'E-post:';
	$strings['Registered %timestamp%'] = 'Registrerad %timestamp%';
	$strings['Posted automatically on behalf of %username%'] = 'Postades automatiskt p� uppdrag av %username%';
	$strings['Posted by %username%'] = 'Upplagd av %username%';
	$strings['edited by %username%'] = 'redigerad av %username%';
	$strings['Today'] = 'Idag';
	$strings['Yesterday'] = 'Ig�r';
	$strings['Tomorrow'] = 'Imorgon';
	$strings['Please confirm that you want to delete this comment'] = 'Bekr�fta att du vill ta bort den h�r kommentaren';
	$strings['The comment was deleted'] = 'Kommentaren har raderats';
	$strings['Confirm'] = 'Bekr�fta';
	$strings['Open'] = '�ppen';
	$strings['Closed'] = 'St�ngt';
	$strings['State'] = 'Status'; //"Open" or "closed", not US states
	$strings['Project'] = 'produkt';
	$strings['Issue type'] = '�rendeslag';
	$strings['Edition(s)'] = 'Version';
	$strings['Not determined'] = 'Inte best�md';
	$strings['Category'] = 'Kategori';
	$strings['Resolution'] = '�tg�rd';
	$strings['Status'] = 'Status';
	$strings['Vote'] = 'Omr�stning';
	$strings['Items attached to this issue'] = 'Bilagor till detta �rende';
	$strings['Attach a link to this issue'] = 'Bifoga en l�nk till detta �rende';
	$strings['Descr.:'] = 'Beskrivning:'; // "Description" abbreviated
	$strings['URL:'] = 'URL:';
	$strings['Please wait, loading list'] = 'V�nta. Laddar lista';
	$strings['Severity'] = 'prioritet';
	$strings['Click here to add a comment'] = 'Klicka h�r f�r att l�gga till en kommentar';
	$strings['Add a comment'] = 'L�gg till en kommentar';
	$strings['Visibility'] = 'Synlighet';
	$strings['This comment is visible to everyone'] = 'Denna kommentar �r synligt f�r alla';
	$strings['This comment is visible only to the developers / staff'] = 'Denna kommentar �r endast synlig f�r administrat�rer';
	$strings['Comment'] = 'Kommentera';
	$strings['Post comment'] = 'L�gg upp kommentar';
	$strings['Title'] = 'Titel';
	$strings['Untitled comment'] = 'Ej namngiven kommentar';
	$strings['Group:'] = 'Grupp:';
	$strings['Team:'] = 'Team:';
	$strings['Nothing entered.'] = 'Ingenting angivet.';
	$strings['There are no available categories'] = 'Det finns inga tillg�ngliga kategorier';
	$strings['There are no available builds'] = 'Inga tillg�ngliga kandidater';
	$strings['There are no available components'] = 'Det finns inga tillg�ngliga komponenter';
	$strings['This issue does not affect any editions'] = 'Problemet p�verkar inte alla versioner';
	$strings['There are no available editions'] = 'Inga tillg�ngliga versioner';
	$strings['System'] = 'System'; // the name of the "system" user, which will be displayed in some places
	$strings['Everyone'] = 'Alla';
	$strings['Enter any user detail to search for, and press "Find" to look up the user(s).'] = 'Ange anv�ndarinfo att s�ka efter och tryck "S�k" f�r att hitta anv�ndaren.';
	$strings['Find'] = 'Hitta';
	$strings['Scheduled: %date%'] = 'Planerat: %date%';
	$strings['Closed issues: %number_of_closed% of %number_of_issues% assigned to this milestone'] = 'Avslutade �renden: %number_of_closed% av %number_of_issues% kan h�nf�ras till detta delm�l';
	$strings['View details about this milestone'] = 'Visa detaljer om detta delm�l';
	$strings['There are no milestones for this project.'] = 'Det finns inga delm�l f�r denna produkt.';
	$strings['You are now in "print friendly" mode'] = 'Du �r nu i "utskiftsv�nligt" l�ge';
	$strings['Switch back to normal mode'] = 'Byt tillbaka till normall�ge';
	$strings['Logged in as %username%'] = 'Inloggad som %username%';
	$strings['No project specified'] = 'Ingen produkt som anges';
	$strings['You have to specify a project'] = 'Du m�ste ange en produkt';
	$strings['Roadmap for %project_name%'] = 'Plan f�r %project_name%';
	$strings['Here is an overview of the current roadmap for this project.'] = 'H�r �r en plan�versikt f�r detta produkt.';
	$strings['This project has no public roadmap.'] = 'Denna produkt har ingen offentlig f�rdplan.';
	$strings['The roadmap may be visible if you are logged in.'] = 'F�rdplanen kan visas om du �r inloggad';
	$strings['This project has no roadmap.'] = 'Denna produkt har ingen plan.';
	$strings['This milestone has no planned schedule'] = 'Delm�l har ej fastst�llts';
	$strings['Scheduled release date: %date%'] = 'Planerat Utgivningsdatum:% datum%';
	$strings['There are no issues assigned to this milestone'] = 'Det finns inga �renden som h�r till detta delm�l';
	$strings['Estimated time to complete this milestone: %time%'] = 'Ber�knad tid f�r att slutf�ra denna delm�l:%time%';
	$strings['Time to complete is not estimated'] = 'Tidsram saknas f�r m�luppfyllelse';
	$strings['Estimated time to reach the completion of this milestone: %time%'] = 'Ber�knad tid f�r att n� slutf�randet av detta delm�l: %time%';
	$strings['Time to reach completion is not estimated'] = 'Utf�randetid �r inte fastst�lld';
	$strings['There are no components'] = 'Det finns inga komponenter';
	$strings['There are no builds'] = 'Det finns inga kandidater';
	$strings['View roadmap'] = 'Visa m�ls�ttning';
	$strings['Not scheduled yet'] = 'Inte planerat �nnu';
	$strings['Please try again'] = 'F�rs�k igen';
	$strings['There are no items attached to this issue'] = 'Det finns inga objekt som l�ggs p� denna fr�ga';
	$strings['You cannot upload files bigger than %max_size% MB'] = 'Du kan inte ladda upp filer st�rre �n %max_size% MB';
	$strings['Could not determine filetype'] = 'Det gick inte att fastst�lla filtyp';
	$strings['This filetype is not allowed'] = 'Denna filtyp �r inte till�tet';
	$strings['An error occured when saving the file'] = 'Ett fel uppstod n�r filen skulle sparas';
	$strings['The file was not uploaded correctly'] = 'Filen har inte lagts upp korrekt';
	$strings['The upload was interrupted, please try again'] = 'Uppladdningen avbr�ts, f�rs�k igen';
	$strings['No file was uploaded'] = 'Ingen fil har lagts upp';
	$strings['There was an error with your upload: %error%'] = 'Det uppstod ett fel med din l�gga upp: %error%';
	$strings['Next'] = 'N�sta';
	$strings['Back'] = 'Tillbaka';
	$strings['You are not allowed to upload files'] = 'Det �r inte till�tet att ladda upp filer';
			
	// Help related		
	$strings['BUGS 2 help'] = 'BUGS 2 hj�lp';
	$strings['welcome'] = 'V�lkommen!';
	$strings['prefix'] = 'produktsprefix';
	$strings['config_projects'] = 'produkter, upplagor, kandidater och komponenter';
	$strings['permissions'] = 'Beh�righeter';
			
?>