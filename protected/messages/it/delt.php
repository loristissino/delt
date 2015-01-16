<?php

return array(

  'Welcome to <i>{name}</i>'=>'Benvenuti su <i>{name}</i>',
  'Welcome to «{name}»'=>'Benvenuti su «{name}»',
  'You can gain experience in bookkeping and accounting with the Double Entry method with your firms, listed on the right side.' => 'Puoi fare esperienza nella tenuta della contabilità con il metodo della partita doppia con una delle tue aziende, elencate sulla destra.',
  'You have no firms that you can use to gain experience in bookkeeping/accounting with.' => 'Non hai aziende che puoi usare per fare esperienza con la contabilità.',
  'Create one.' => 'Creane una.',
  'Or, even better,' => 'Oppure, ancora meglio,',
  'fork an existing one.' => 'duplicane una esistente.',
  'Bookkeeping/Accounting' => 'Contabilità',
  'Bookkeeping and Accounting' => 'Gestione contabile',
  'Journal' => 'Libro giornale',
  'Chart of accounts' => 'Piano dei conti',
  'General Ledger' => 'Libro mastro',
  'Trial Balance' => 'Situazione contabile',
  'Firm'=>'Azienda',
  'Ledger' => 'Libro mastro',
  'Public View' => 'Visualizzazione pubblica',
  'Ledger for account «{name}»' => 'Libro mastro per il conto «{name}»',
  'Statements' => 'Bilancio',
  'Account' => 'Conto',
  'Parent account' => 'Conto di livello superiore',
  'The parent account does not exist.' => 'Il conto di livello superiore non esiste.',
  'The code contains illegal characters.' => 'Il codice contiene caratteri illeciti.',

  'Code' => 'Codice',
  'Name' => 'Nome',
  'Date' => 'Data',
  'Description' => 'Descrizione',
  'Comment' => 'Commento',
  'Save & Close' => 'Salva & Chiudi',
  'Save & New' => 'Salva & Nuova',
  'There might be some unsaved changes in the form.' => 'Ci potrebbero essere dati non salvati nel modulo.',
  
  'Description / Explanation' => 'Descrizione / Spiegazione',

  'Position' => 'Collocazione',
  'P' => 'C',

  'Unknown' => 'Sconosciuta',
  'This account has not been correctly positioned.'=>'Questo conto non risulta correttamente collocato.',

  'Ordinary outstanding balance' => 'Eccedenza tipica',
  'Outstanding balance' => 'Eccedenza',
  'Ending balance / Balance brought down' => 'Eccedenza finale',
  'Dr.<!-- outstanding balance -->' => 'D',  // "dare"
  'Cr.<!-- outstanding balance -->' => 'A',  // "avere"
  // see http://blog.accountingcoach.com/debit-credit/
  'unset' => 'non impostata',
  'Debit' => 'Dare',
  'Credit' => 'Avere',
  'According to its definition, the account should not have this kind of outstanding balance.' => 'In base alla sua definizione, il conto non dovrebbe avere questo tipo di eccedenza.',
  'No.'=>'N.',
  
  'Total Debit' => 'Totale Dare',
  'Total Credit' => 'Totale Avere',
  
  'Sum'=>'Totale',
  
  'The journal entry «%description%» has been correctly saved.' => 'La registrazione «%description%» è stata salvata.',
  'You can now prepare a new one.' => 'Puoi prepararne una nuova.',
  'Localized names' => 'Nomi localizzati',
  
  
  'Operations' => 'Operazioni',
  'Create new account' => 'Crea nuovo conto',
  'Fix chart' => 'Sistema piano dei conti',
  'Create a new account as child of this one' => 'Crea un nuovo conto come figlio di questo',
  'Delete this account' => 'Elimina questo conto',
  'The parent account, with code «%code%», does not exist.' => 'Il conto di livello superiore, con codice «%code%», non esiste.',
  
  'You are creating an account as a child of «%account%»,'=>'Stai per creare un conto come figlio di «%account%»,',
  'You are making the account a child of «%account%»,'=>'Stai per rendere questo conto figlio di «%account%»,',
  'which currently has the following children:'=>'che al momento ha i seguenti figli:',
  'which has no children at the moment.'=>'che al momento non ha nessun conto figlio.',
  
  'Amounts from closing entries' => 'Importi derivanti da registrazioni di chiusura',
  'included' => 'inclusi',
  'excluded' => 'esclusi',
  'inline' => 'in linea',
  'download' => 'file da scaricare',
  
  'Edit'=>'Modifica',
  'Check the debits and the credits.'=>'Controlla gli addebiti e gli accrediti.',
  'New journal entry' => 'Nuova registrazione',
  'Save journal entry' => 'Registra nel giornale',
  'Add a line' => 'Aggiungi una riga',
  
  'The above outstanding balance is the consolidated algebraic sum of the debits and the credits of the following accounts:' => 'L\'eccedenza qui sopra riportata è la somma algebrica consolidata dei conti sottoindicati:',
  
  'This firm does not have any journal entry yet.'=>'Quest\'azienda non ha ancora nessuna registrazione in partita doppia.',
  'Create a new one now.' => 'Preparane una adesso.', 
  
  'Row {row}: ' => 'Riga {row}: ',
  'the account with code "{code}" is not available (you can add it on the fly to the Chart of Accounts by inserting an exclamation mark at the end of the name, like in "{code}!").' => 'il conto con codice "{code}" non è disponibile (puoi aggiungere da qui il conto al piano dei conti inserendo un punto esclamativo allla fine del nome, come in "{code}!").',
  'the value "{value}" is not numeric.' => 'il valore "{value}" non è numerico.',
  'the value "{value}" cannot be negative.' => 'il valore "{value}" non può essere negativo.',
  'you cannot have both a debit and a credit.' => 'non è possibile avere sia un addebito sia un accredito.',
  'you must have a debit or a credit.' => 'è necessario un addebito o un accredito.',
  'No amounts specified.' => 'Nessun importo specificato.',
  'The total amount of debits ({debits}) does not match the total amounts of credits ({credits}).' => 'Il totale degli addebiti ({debits}) non corrisponde al totale degli accrediti ({credits}).',
  
  'Fields with <span class="required">*</span> are required.' => 'I campi indicati con <span class="required"> * </span> sono obbligatori.',
  'The lines in which the account field is empty are ignored.' => 'Le righe in cui il campo del conto è vuoto vengono ignorate.',
  'The imbalance is: {amount}.'=> 'Lo sbilancio è: {amount}.',
  'Edit account «{name}»' => 'Modifica del conto «{name}»',
  
  'The children accounts won\'t be deleted, but they will remain orphans.' => 'I conti figli non verranno cancellati, ma resteranno orfani.',
  
  'Edit journal entry' => 'Modifica registrazione contabile',
  'Delete' => 'Elimina',
  'Are you sure you want to delete this journal entry?' => 'Sei sicuro di voler eliminare questa registrazione contabile?',

  
  'Fork an existing firm' => 'Duplica un\'azienda esistente',
  'Public firms' => 'Aziende pubbliche',
  'Your firms' => 'Le tue aziende', 
  'Create firm' => 'Crea un\'azienda', 
  'A firm you know the slug of'=> 'Un\'azienda di cui conosci lo slug',
  'Fork' => 'Duplica',
  'Firm not found' => 'Azienda non trovata',
  'Sorry, we could not find a firm with the slug «%slug%».' => 'Purtroppo, non siamo riusciti a trovare un\'azienda con lo slug «%slug%».',
  'Try forking another one.' => 'Prova a duplicarne un\'altra.',
  
  'Closing entry' => 'Registrazione di chiusura',
  'Closing journal entry' => 'Registrazione di chiusura contabile',

  'Please choose the kind of closing you need on the side menu.' => 'Scegli il tipo di chiusura desiderata nel menù a fianco.',
  'Please fix the following errors:' => 'Devi correggere i seguenti errori:',

  'This firm does not seem to have accounts with «{position}» position to close.'=> 'Questa azienda non sembra avere conti con collocazione «{position}» da chiudere.',
  
  'Templates' => 'Causali',
  'Create Template' => 'Crea causale',
  'Create a Template based on this entry' => 'Crea una causale basata su questa registrazione',
  'Delete this entry' => 'Elimina questa registrazione',
  'You are going to create a new template with the following accounts:' => 'Stai per creare una nuova causale con i seguenti conti:',
  'Template creation' => 'Creazione di causale',
  'The template has been correctly saved.'=>'La causale è stata correttamente salvata.',
  'The template could not be saved.'=>'La causale non è stata salvata.',
  
  'Create'=>'Crea',
  
  'Fork the firm «{firm}»' => 'Duplica l\'azienda «{firm}»',
  'Do you want to proceed?' => 'Vuoi procedere?',
  'Yes, please, fork this firm' => 'Sì, crea un duplicato di questa azienda',
  '(If you really need it, you can ask us to be allowed to manage other firms.)'=>'(Se ne hai veramente bisogno, puoi chiederci di essere autorizzato a gestire più aziende.)',
  
  'Edit Firm «{name}»' => 'Modifica azienda «{name}»',
  
  'Currency' => 'Valuta',
  'Slug' => 'Slug',
  'Language' => 'Lingua',
  
  'Save' => 'Salva',
  'Export' => 'Esporta',
  'Import' => 'Importa',
  'Show' => 'Mostra',
  
  'Wonder what a <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="A slug is the part of a URL which identifies a page using human-readable keywords." target="_blank">slug</a> is?' => 'Ti chiedi che cosa sia uno <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="Uno slug è la parte di un URL che identifica una pagina web mediante parole chiave comprensibili da esseri umani." target="_blank">slug</a>?',
  'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia" target="_blank">ISO 4217 code</a>, like EUR, USD, or GBP' => 'Devi indicare un <a href="http://it.wikipedia.org/wiki/ISO_4217" title="Trova ulteriori informazioni su Wikipedia" target="_blank">codice ISO 4217</a> di tre lettere, come EUR, USD o GBP',

  
  'The information about the firm has been correctly saved.'=>'Le informazioni sull\'azienda sono state correttamente salvate.',
  'The information about the firm could not be saved.' => 'Le informazioni sull\'azienda non sono state salvate.',
  
  'This slug is already in use.' => 'Questo slug è già in uso.',
  'Only lowercase letters, digits and minus sign are allowed.' => 'Solo lettere minuscole, cifre e il segno meno sono ammessi.',
  
  
  'Data to duplicate:' => 'Dati da duplicare:',
  'I agree on the fact that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.' => 'Accetto il fatto che i contenuti dell\'azienda che sto creando saranno disponibili con licenza <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribuzione - Condividi allo stesso modo 3.0 Unported</a>.',
  
  'Chart of Accounts' => 'Piano dei conti',
  'Chart of Accounts and Templates' => 'Piano dei conti e causali contabili',
  'Chart of Accounts, Templates, and Journal Entries' => 'Piano dei conti, causali contabili, registrazioni contabili',
  'You must confirm that you accept the license for the contents.' => 'Devi confermare di accettare la licenza per i contenuti.',
  
  'The firm has been successfully forked.' => 'L\'azienda è stata correttamente duplicata.',
  'Data correctly imported.' => 'Dati correttamente importati.',
  
  'Select date from calendar' => 'Seleziona la data dal calendario',
  
  'the account with code "{code}" makes the row a duplicate.' => 'il conto con codice "{code}" rende la riga un duplicato.',
  'the amount of the debit has been computed by difference;' => 'l\'ammontare dell\'addebito è stato calcolato per differenza;',
  'the amount of the credit has been computed by difference;' => 'l\'ammontare dell\'accredito è stato calcolato per differenza;',
  'the amount of the debit has been computed as a balance for the account;' => 'l\'ammontare dell\'addebito è stato calcolato a saldo del conto;',
  'the amount of the credit has been computed as a balance for the account;' => 'l\'ammontare dell\'accredito è stato calcolato a saldo del conto;',
  'it must be checked.' => 'va controllato.',
  'Start typing (code or name) or double-click...'=>'Digita codice o nome, oppure fa\' un doppio clic',
  
  'Row' => 'Riga',
  'The code cannot end with a dot.' => 'Il codice non può finire con un punto.',
  'Total:' => 'Totale:',
  
  'Delete' => 'Elimina',
  'Delete this firm' => 'Elimina questa azienda',
  'Are you sure you want to delete this firm?' => 'Sei sicuro di voler eliminare questa azienda?',
  'The firm has been correctly deleted.' => 'L\'azienda è stata correttamente eliminata.',
  'Create a Firm' => 'Crea un\'azienda',
  'The firm has been successfully created.' => 'L\'azienda è stata correttamente creata.',
  'The language is used for the names of the accounts, not for the user interface' => 'La lingua è usata per i nomi dei conti, non per l\'interfaccia utente',
  
  'The file seems to be invalid.' => 'Il file non appare valido.',
  'Importing data to a firm will erase all current content.' => 'L\'importazione di dati per un\'azienda comporta la cancellazione di tutti i contenuti correnti.',
  
  'Depth'=>'Profondità',
  'Down to Level {number}' => 'Fino al livello {number}',
  'Grandtotal' => 'Totale generale',
  'Aggregate Grandtotal' => 'Totale generale aggregato',
  
  'Move Up' => 'Sposta in su',
  'Move Down' => 'Sposta in giù',
  'Up' => 'Su',
  'Down' => 'Giù',
  
  'Profit/Loss' => 'Utile/Perdita',
  'Select account of profit destination'=>'Seleziona conto di destinazione utile',
  'Select account of loss destination'=>'Seleziona conto di destinazione perdita',
  
  'Raw input' => 'Input grezzo',
  'Switch to raw input mode' => 'Passa alla modalità input grezzo',
  'Text fields' => 'Campi di testo',
  'Switch to text fields mode' => 'Passa alla modalità campi di testo',
  'Load accounts' => 'Carica conti',
  'Load all accounts' => 'Carica tutti i conti',
  'Copy the contents of the text area to a spreadsheet (fields are separated by tabs), and edit the data there (if the text area is empty, you can click on the "Load all accounts" icon above to load all available accounts).' => 'Copia i contenuti dell\'area di testo in un foglio elettronico (i campi sono separati da tabulatori) e modifica lì i dati (se l\'area di testo è vuota, puoi fare clic sull\'icona "Carica i conti" qui sopra per caricare tutti i conti disponibili).',
  'When you are done with the spreadsheet, paste here the three columns (name, debit and credit), and switch to text fields mode.' => 'Quando hai finito con il foglio elettronico, incolla qui le tre colonne (nome, addebito, accredito) e passa alla modalità campi testuali.',
  'Sort postings' => 'Ordina i conti',
  'Sort postings, debits first' => 'Ordina i conti, mettendo prima gli addebiti',
  'Swap debit and credits for the whole journal entry' => 'Scambia gli addebiti con gli accrediti per l\'intera registrazione contabile',
  
  'Image from gravatar.com' => 'Immagine da gravatar.com',
  'This page in other languages:' => 'Questa pagina in altre lingue: ',
  
  'Contact' => 'Contattaci',
  'Home' => 'Pagina principale',
  'About' => 'Questo sito',
  'This page in other languages:'=>'Questa pagina in altre lingue:',
  'Themes' => 'Temi',
  
  'You reached the number of firms maneageble with your account ({number}).' => 'Hai raggiunto il numero massimo di aziende gestibili con il tuo account ({number}).',
  'If you want to create a new one, please delete some of the existing.' => 'Se vuoi crearne una nuova, prima elimina una delle esistenti.',
  'Sorry, you are not allowed to create firms at this time.' => 'Purtroppo, al momento non sei autorizzato a creare aziende.',
  
  'The journal entry has been successfully deleted.' => 'La registrazione è stata correttamente eliminata.',
  'you cannot do a debit to this kind of account' => 'non è possibile addebitare questo tipo di conto',
  'you cannot do a credit to this kind of account' => 'non è possibile accreditare questo tipo di conto',
  '(unless the journal entry is marked as adjustment)' => '(a meno che la registrazione non sia contrassegnata come rettifica)',
  'Mark this journal entry as adjustment, thus allowing exceptions in debit/credit checks'=>'Contrassegna questa registrazione come rettifica, ammettendo di conseguenza eccezioni nei controlli',
  
  'Synchronize' => 'Sincronizza',
  'Syncronize accounts from ancestor firms' => 'Sincronizza conti da un\'azienda di cui questa è derivazione',
  
  'Synchronize accounts' => 'Sincronizza conti',
  'You can synchronize your firm\'s chart of accounts with the one of one of the following ancestors:' => 'Puoi sincronizzare il piano dei conti della tua azienda con quelli di una delle seguenti aziende:',
  'New accounts found' => 'Nuovi conti trovati',
  'Changed accounts found' => 'Conti modificati trovati',
  'Select all' => 'Seleziona tutto',
  'Changes' => 'Modifiche',
  'There are no accounts to synchronize.' => 'Non ci sono conti da sincronizzare.',
  
  'Additional languages' => 'Lingue aggiuntive',
  'You can select other languages to have a multilingual chart of accounts.' => 'Puoi selezionare altre lingue per avere un piano di conti multilingue.',
  'Do you want other languages / locales to be supported?' => 'Vuoi che altre lingue / locali vengano supportati?',
  'The information about the firm is being saved.' => 'Memorizzazione delle informazioni relative all\'azienda in corso.',
  'Just <a href="{url}" target="_blank">drop us a message</a>!'=>'Basta che ci <a href="{url}" target="_blank">invii un messaggio</a>!',
  
  'Clear' => 'Svuota',
  'Delete all journal entries' => 'Elimina tutte le registrazioni contabili',
  'Are you sure you want to delete all journal entries?' => 'Sei sicuro di voler eliminare tutte le registrazioni contabili?',
  'The journal has been successfully cleared.'=> 'Le registrazioni contabili sono state correttamente eliminate.',
  
  'Contact Us'=>'Contattaci',
  'If you have questions about DELT Project or this website, please fill out the following form to contact us. Thank you.'=>'Se hai domande sul progetto DELT o su questo sito, compila il modulo seguente per contattarci. Grazie.',
  'Thank you for contacting us. We will respond to you as soon as possible.'=>'Grazie per averci contattato. Ti risponderemo il più presto possibile.',
  
  'Please enter the letters as they are shown in the image above.'=>'Inserisci le lettere dell\'immagine qui sopra. ',
  'Letters are not case-sensitive.'=>'Non viene tenuta in considerata la differenza tra maiuscole e minuscole.',
  'Submit'=>'Invia',
  'Thank you for contacting us. We will respond to you as soon as possible.'=>'Grazie per averci contattato. Ti risponderemo il più presto possibile.',
  
  'Verification Code'=> 'Codice di verifica',
  'Name'=>'Nome',
  'Email where you want to receive a reply'=>'Email alla quale vuoi ricevere la risposta',
  'Subject'=>'Oggetto',
  'Body'=>'Testo',
  'We will use your address only to answer your question.'=>'Useremo il tuo indirizzo di posta elettronica solo per rispondere alla domanda.',
  'Get a new code.'=>'Procurati un nuovo codice',
  
  'Login with username / email' => 'Entra con nome utente / email',
  'Login with social network account' => 'Entra con account di social network',
  
  'Options' => 'Opzioni',
  'License' => 'Licenza',
  
  'The firm is being forked.' =>'Duplicazione dell\'azienda in corso.',
  'The data are being imported.' =>'Importazione dei dati in corso.',
  'Please wait a few seconds...' => 'Attendi qualche secondo...',
  'Copy of "{name}"' => 'Copia di "{name}"',
  'copy-of-{slug}' => 'copia-di-{slug}',  
  
  'For security reasons, do not use a password that you use on other sites.' => 'Per questioni di sicurezza, non usare una password che usi anche in altri siti.',
  'Your email is going to be used only for passowrd recovery and for important news regarding the website.'=>'Il tuo indirizzo di posta elettronica verrà usato solo per l\'eventuale recupero della password e per l\'invio di notizie importanti riguardanti il sito.',
  'We will not show it in the website nor give it away without your consent.'=>'Non verrà mostrato nel sito web né ceduto ad altri senza il tuo consenso.',
  
  'Trial Balance Export to CSV file' => 'Esportazione situazione contabile su file CSV',
  'Trial Balance Export' => 'Esportazione situazione contabile',
  'Export (CSV)' => 'Esporta (CSV)',
  
  'signed amount' => 'importo con segno',
  'unsigned amount, with type' => 'importo senza segno, con tipo',
  'two columns' => 'due colonne',
  'Type' => 'Tipo',
  'Text delimiter' => 'Delimitatore di testo',
  'Field delimiter' => 'Delimitatore di campo',
  'Character set' => 'Codifica dei caratteri',

  'Share' => 'Condividi',
  'Share the Firm «{name}»' => 'Condivisione azienda «{name}»',
  'This firm does not seem to have accounts of «{position}» position to close.' => 'Questa azienda non sembra avere conti con collocazione «{position}» da chiudere.',
  'This firm is currently shared with another user:|This firm is currently shared with other {n} users:' => 'Questa azienda è correntemente condivisa con un altro utente:|Questa azienda è correntemente condivisa con altri {n} utenti:',

  'This firm is not currently shared with any other user.'=>'Questa azienda non è correntemente condivisa con altri utenti.',
  'You can share it with another user by inviting them with the form below.'=>'Puoi condividerla con un altro utente invitandolo con il modulo qui sotto.',
  'You have been invited to manage the following firms:'=>'Sei stato invitato a gestire le seguenti aziende:',
  'accept'=>'accetta',
  'decline'=>'declina',
  'Accept the invitation to share the management of this firm'=>'Accetta l\'invito a condividere la gestione di questa azienda',
  'Decline the invitation to share the management of this firm'=>'Declina l\'invito a condividere la gestione di questa azienda',
  'Are you sure you want to decline the invitation to share the management of this firm?'=>'Sei sicuro di voler declinare l\'invito a condividere la gestione di questa azienda?',
  "By accepting the invitation, you agree on the following terms:\n\na) the contents of the firm are available under the Creative Commons Attribution-ShareAlike 3.0 Unported License;\n\nb) your name will be listed as an author.\n\nDo you want to accept the invitation to share the management of this firm?" => "Accettando l'invito, ti dichiari d'accordo sulle seguenti condizioni:\n\na) i contenuti dell'azienda sono pubblicati sotto Creative Commons Attribution-ShareAlike 3.0 Unported License;\n\nb) il tuo nome verrà elencato come autore.\n\nVuoi accettare l'invito a condividere la gestione dell'azienda?",
  
  'There must be at least one name for the account.' => 'Ci deve essere almeno un nome per il conto.',
  'You cannot remove the locale (language code). It was put back.' => 'Non puoi rimuovere il locale (codice della lingua). È stato ripristinato.',
  
  'You are now allowed to manage the firm «{firm}».' => 'Adesso sei autorizzato a gestire l\'azienda «{firm}».',
  'You successfully declined the invitation to manage the firm «{firm}».' => 'Hai declinato l\'offerta di condivisione della gestione dell\'azienda «{firm}».',
  'An invitation has been sent to «{username}». When accepted, the firm will be considered shared.' => 'Un invito è stato inviato a «{username}». Quando accettato, l\'azienda verrà considerata condivisa.',
  
  'regenerate' => 'rigenera',
  'randomize' => 'rendi casuale',
  'or' => 'oppure',
  'Click here if you want to regenerate the slug from the name of the firm' => 'Fai clic qui se vuoi rigenerare lo slug a partire dal nome dell\'azienda',
  'Click here if you want to create a random slug, which helps in keeping it somehow a bit more private' => 'Fai clic qui se vuoi far generare uno slug casuale, in modo da rendere l\'azienda un po\' più privata',
  
  'Manage one of your firms'=>'Gestisci una delle tue aziende',
  'Create a new, empty firm'=>'Crea un\'azienda nuova (vuota)',
  'Fork (duplicate) an existing firm'=>'Duplica un\'azienda esistente',
  'Edit your profile\'s settings'=>'Modifica il tuo profilo utente',
  
  'View and edit the Chart of Accounts'=>'Visualizza e modifica il Piano dei conti',
  'View and edit the Journal Entries'=>'Visualizza e modifica il Libro giornale',
  'View the Trial Balance and the Ledger'=>'Visualizza la Situazione contabile e il Libro Mastro',
  'View the Statements'=>'Visualizza il bilancio',
  'Edit firm\'s settings'=>'Modifica le impostazioni dell\'azienda',
  
  'You can copy the following lines and paste them to a spreadsheet.'=>'Puoi copiare il contenuto qui sotto e incollarlo in un foglio elettronico.',
  'Fruition type'=>'Tipo di fruizione',
  
  'Try to set my language for the firm.'=>'Imposta la mia lingua per l\'azienda, se disponibile.',
  
  'The firm «%firm%» has been frozen on <a href="%url%" target="_blank" title="%title%">%date%</a>.'=>'L\'azienda «%firm%» è stata congelata  <a href="%url%" target="_blank" title="%title%">%date%</a>.',
  'The firm «%firm%» is not currently frozen.' => 'L\'azienda «%firm%» al momento non è congelata.',
  'See this timestamp in a different timezone' => 'Vedi questa marca temporale in un fuso orario diverso',
  'Freeze'=>'Congela',
  'Unfreeze'=>'Scongela',
  'You can unfreeze it, if you want.'=>'Puoi scongelarla, se lo desideri.',
  'You can freeze it, if you want. You will not be able to work on it until it gets unfrozen.' => 'Puoi congelarla, se lo desideri. Non potrai apportare modifiche fino a che non verrà scongelata.',
  
  'You successfully freezed the firm «{firm}».' => 'L\'azienda «{firm}» è stata congelata.',
  'You successfully unfreezed the firm «{firm}».' => 'L\'azienda «{firm}» è stata scongelata.',
  'Frozen firm' => 'Azienda congelata',
  
  'Template «%description%»' => 'Causale «%description%»',
  'Delete this template' => 'Elimina questa causale',
  'Are you sure you want to delete this template?' => 'Sei sicuro di voler eliminare questa causale?',
  
  'The template has been correctly deleted.'=>'La causale è stata correttamente eliminata.',
  'The template could not be deleted.'=>'Non è stato possibile eliminare la causale.', 
  
  'Disown' => 'Disconosci',
  'Disown firm' => 'Disconosci azienda',
  'Disown the firm «{name}»' => 'Disconosci l\'azienda «{name}»',
  'You can disown this firm, if you want. It will be left to the other owners.' => 'Puoi disconoscere quest\'azienda, se lo desideri. Verrà lasciata agli altri gestori.', 
  'Configuration' => 'Configurazione',
  'Statements configuration' => 'Configurazione bilancio', 
  'Create new item' => 'Crea nuova voce', 
  'Edit item «{name}»' => 'Modifica voce «{name}»',
  
  'Summary for «{item}»' => 'Chiusura per «{item}»',
  
  'Choose an account' => 'Scegli un conto',
  
  'The Chart of Accounts is empty.' => 'Il piano dei conti è vuoto.',
  'You probably created a new one from scratch instead of forking an existing one.' => 'Probabilmente hai creato un\'azienda nuova anziché duplicarne una esistente.',
  
  'Format'=>'Formato',
  'Statement in pancake format'=>'Prospetto in formato a valori progressivi',
  'Statement in two separate sections'=>'Prospetto in due sezioni separate',
  'Net result' => 'Risultato netto',
  
  'Analyze the transaction' => 'Analizza la scrittura contabile',
  'Transaction analysis' => 'Analisi della scrittura contabile',
  'Transaction analysis is currently disabled. Please save the journal entry first.'=>'L\'analisi della scrittura contabile può essere effettuata solo dopo che essa è stata salvata.',
  'Please note that the transaction analysis is experimental and depends on a consistent chart of accounts.' => 'L\'analisi della scrittura contabile è sperimentale e dipende dalla correttezza dell\'impostazione del piano dei conti.',
  'unexplained entry'=>'scrittura non spiegabile',
  'Transaction analysis is meaningless for closing entries.' => 'L\'analisi non ha significato per le scritture di chiusura.',
  
  'Classification'=>'Classificazione',
  'Change'=>'Variazione',
  'Value'=>'Valore',
  'Contra Account'=>'Conto di rettifica',
  'Increase' => 'Incremento',
  'Decrease' => 'Decremento',
  
  'Please note that when you create a firm from scratch, it will have an empty chart of accounts, and no configuration at all.'=>'Nota che quando crei una nuova azienda da zero, essa non avrà alcuna configurazione né alcun conto nel piano dei conti.',
  'You might prefer to start by {forking} an existing firm (have a look at the standard ones provided).'=>'Potresti preferire iniziare con la  {forking} di un\'azienda esistente (prova a dare un\'occhiata a quelle fornite).',
  'forking (duplicating)'=>'duplicazione (forking)',
  
  'Apply to the selected journal entries:' => 'Applica alle registrazioni contabili selezionate:',
  'Please select the entries you would like to perform this action on!' => 'Seleziona le registrazioni contabili su cui vuoi effettuare l\'operazione!',
  'include' => 'includi',
  'exclude' => 'escludi',
  'delete' => 'elimina',
  'toggle in-statement visibility' => 'commuta visibilità nel bilancio',
  'Include the selected journal entries in computations' => 'Includi le registrazioni selezionate nei calcoli',
  'Exclude the selected journal entries from computations' => 'Escludi le registrazioni selezionate dai calcoli',
  'Delete permanently the selected journal entries' => 'Elimina definitivamente le registrazioni contabili',
  'Toggle the visibility of the selected journal entries in the preparation of the statements' => 'Rendi visibili gli effetti di questa registrazione nel bilancio se non lo sono, o viceversa', 
  
  'Are you sure to perform this action on checked items?' => 'Sei sicuro di voler effettuare l\'operazione sugli elementi selezionati?', 
  'One journal entry has been included. | {n} journal entries have been included.' => 'Una registrazione contabile è stata inclusa. | {n} registrazioni contabili sono state incluse.',
  'One journal entry has been excluded. | {n} journal entries have been excluded.' => 'Una registrazione contabile è stata esclusa. | {n} registrazioni contabili sono state escluse.',
  'No journal entry has been deleted.' => 'Nessuna registrazione contabile è stata eliminata.',
  'One journal entry has been deleted. | {n} journal entries have been deleted.' => 'Una registrazione contabile è stata eliminata. | {n} registrazioni contabili sono state eliminate.',
  'One journal entry has been toggled. | {n} journal entries have been toggled.' => 'Per una registrazione contabile è stata commutata la visibilità. | Per {n} registrazioni contabili è stata commutata la visibilità.',
  
  'With the selected accounts:' => 'Con i conti selezionati:',
  'prepare closing entry' => 'prepara una registrazione di chiusura',
  'prepare snapshot entry' => 'prepara una registrazione con la situazione corrente',
  'Prepare a journal entry that will close the selected accounts' => 'Prepara una registrazione contabile per la chiusura dei conti selezionati',
  'Prepare a journal entry that will open the selected accounts with the current outstanding balance' => 'Prepara una registrazione contabile per l\'apertura dei conti selezionati con l\'eccedenza attuale',
  'Notes' => 'Note',
  'Balance (Dr.)' => 'Eccedenza (D)',
  'Balance (Cr.)' => 'Eccedenza (A)',
  'Journal entry from balances' => 'Registrazione dalle eccedenze',
  'snapshot' => 'situazione corrente',
  'closing' => 'chiusura',
  'Choose' => 'Scegli',
  
  'No journal entry has been modified.' => 'Nessuna registrazione contabile è stata modificata.',
  
  'I understand and I know what I am doing.' => 'Capisco, e sono consapevole di quello che faccio.',
  'The action cannot be undone.' => 'L\'operazione è irreversibile.',
  
  'Sorry, this action has not been implemented yet.' => 'Purtroppo, questa azione non è ancora stata implementata.',
  'Please use the contact form if you want to change your email address.' => 'Contattaci direttamente (tramite il modulo) per cambiare il tuo indirizzo email.',
  
  'Import accounts' => 'Importa conti',
  'Export accounts' => 'Esporta conti',
  'Content' => 'Contenuto',
  'You can copy the contents of the following area into a spreadsheet.'=>'Puoi copiare il contenuto della seguente area di testo in un foglio elettronico.',
  'The format for each line is: name{tab}code{tab}position{tab}balance.'=>'Il formato di ogni riga è: nome{tab}codice{tab}collocazione{tab}eccedenza.',
  'Curious about <a href="{url}" target="_blank">why</a> you have to accept a Creative Commons License?'=>'Ti interessa sapere <a href="{url}" target="_blank">perché</a> devi accettare una licenza Creative Commons?',
  
  'Confused? Perplexed?'=>'Dubbi? Perplessità?',
  'Looking for something different?'=>'Ti aspettavi qualcosa di diverso?',
  'Read more' => 'Approfondisci',
  
  'You can export the data of this firm in the following formats:' => 'Puoi esportare i dati di quest\'azienda nei seguenti formati:',
  'standard JSON-based format used by DELT' => 'formato standard di DELT, basato su JSON',
  'text-based ledger-cli\'s format for transactions' => 'formato di rappresentazione del libro giornale usato da ledger-cli',
  
  'Do you want «%sourceName%» to be a child of «%targetName%»?'=>'Vuoi che «%sourceName%"» diventi figlio di «%targetName%»?',
  'Place the account here?'=>'Conferma spostamento conto',
  'Yes'=>'Sì',
  'Cancel'=>'Annulla',
  'Difference Yet Unexplained' => 'Differenza non ancora spiegata',
  'If modified, the new address appears after validation.'=>'Se modificato, il nuovo indirizzo compare dopo la validazione.',
  
);

