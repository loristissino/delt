<?php

return array(

  'Welcome to <i>{name}</i>'=>'Benvenuti su <i>{name}</i>',
  'You can gain experience in bookkeping with the Double Entry method with your firms, listed on the right side.' => 'Puoi fare esperienza nella tenuta della contabilità con il metodo della partita doppia con una delle tue aziende, elencate sulla destra.',
  'You have no firms that you can use to gain experience in bookkeeping with. Go create one.' => 'Non hai aziende che puoi usare per fare esperienza con la contabilità. Creane una.',
  'Bookkeeping' => 'Contabilità',
  'Bookkeeping and accountancy' => 'Gestione contabile',
  'Journal' => 'Libro giornale',
  'Chart of accounts' => 'Piano dei conti',
  'Trial Balance' => 'Situazione contabile',
  'Firm'=>'Azienda',
  'Ledger' => 'Libro mastro',
  'Ledger for account «{name}»' => 'Libro mastro per il conto «{name}»',
  'Statements' => 'Bilancio',
  'Financial Statement' => 'Situazione patrimoniale',
  'Income Statement' => 'Conto economico',
  'Account' => 'Conto',
  'Parent account' => 'Conto di livello superiore',
  'The parent account does not exist.' => 'Il conto di livello superiore non esiste.',
  'The code contains illegal characters.' => 'Il codice contiene caratteri illeciti.',

  'Code' => 'Codice',
  'Name' => 'Nome',
  'Date' => 'Data',
  'Description' => 'Descrizione',
  'Comment' => 'Commento',

  'Nature' => 'Natura',
  'P<!-- nature -->' => 'P',  // conti patrimoniali (stato patrimoniale)
  'E<!-- nature -->' => 'E',  // conti economici (reddito di esercizio)
  'M<!-- nature -->' => 'O',  // conti d'ordine
  'p<!-- nature -->' => 'p',  // transitorio patrimoniale
  'e<!-- nature -->' => 'e',  // transitorio economica
  'r<!-- nature -->' => 'r',  // risultato di esercizio

  'Patrimonial (Asset / Liability / Equity)' => 'Patrimoniale (Attività / Passività / Capitale proprio)',
  'Economic (Profit / Loss)' => 'Economica (Ricavo / Costo)',
  'Memorandum' => 'Conto d\'ordine', 
  'Transitory Patrimonial Account' => 'Conto transitorio patrimoniale',
  'Transitory Economic Account' => 'Conto transitorio economico',
  'Result Account (Net profit / Total loss)' => 'Risultato di esercizio',  
  'Assets'=>'Attività',
  'Liabilities and Equity' => 'Passività e patrimonio',
  'Revenues' => 'Ricavi',  
  'Expenses' => 'Costi',  

  'Ordinary outstanding balance' => 'Eccedenza tipica',
  'Outstanding balance' => 'Eccedenza',
  'D<!-- outstanding balance -->' => 'D',  // "dare"
  'C<!-- outstanding balance -->' => 'A',  // "avere"
  'unset' => 'non impostata',
  'Debit' => 'Dare',
  'Credit' => 'Avere',
  'According to its definition, the account should not have this kind of outstanding balance.' => 'In base alla sua definizione, il conto non dovrebbe avere questo tipo di eccedenza.',
  
  
  'Total Debit' => 'Totale Dare',
  'Total Credit' => 'Totale Avere',
  
  'Localized names' => 'Nomi localizzati',
  
  'Operations' => 'Operazioni',
  'Create new account' => 'Crea nuovo conto',
  'Fix chart' => 'Sistema piano dei conti',
  'Create a new account as child of this one' => 'Crea un nuovo conto come figlio di questo',
  'Delete this account' => 'Elimina questo conto',
  
  
  'Edit'=>'Modifica',
  'Check the debits and the credits.'=>'Controlla gli addebiti e gli accrediti.',
  'New journal post' => 'Nuova registrazione',
  'Save journal post' => 'Registra nel giornale',
  'Add a row' => 'Aggiungi una riga',
  
  'The above outstanding balance is the consolidated algebraic sum of the debits and the credits of the following accounts:' => 'L\'eccedenza qui sopra riportata è la somma algebrica consolidata dei conti sottoindicati:',
  
  'Row {row}: ' => 'Riga {row}: ',
  'the account with code "{code}" is not available.' => 'Il conto con codice "{code}" non è disponibile.',
  'the value "{value}" is not numeric.' => 'il valore "{value}" non è numerico.',
  'the value "{value}" cannot be negative.' => 'il valore "{value}" non può essere negativo.',
  'you cannot have both a debit and a credit.' => 'non è possibile avere sia un addebito sia un accredito.',
  'you must have a debit or a credit.' => 'è necessario un addebito o un accredito.',
  'No amounts specified.' => 'Nessun importo specificato.',
  'The total amount of debits ({debits}) does not match the total amounts of credits ({credits}).' => 'Il totale degli addebiti ({debits}) non corrisponde al totale degli accrediti ({credits}).',
  
  'Fields with <span class="required">*</span> are required.' => 'I campi indicati con <span class="required"> * </span> sono obbligatori.',
  'The rows in which the account field is empty are ignored.' => 'Le righe in cui il campo del conto è vuoto vengono ignorate.',
  'The imbalance is: {amount}.'=> 'Lo sbilancio è: {amount}.',
  'Edit account «{name}»' => 'Modifica del conto «{name}»',
  
  'The children accounts won\'t be deleted, but they will remain orphans.' => 'I conti figli non verranno cancellati, ma resteranno orfani.',
  
  'Edit journal post' => 'Modifica registrazione contabile',
  'Delete' => 'Elimina',
  'Are you sure you want to delete this journal post?' => 'Sei sicuro di voler eliminare questa registrazione contabile?',

  
  'Fork an existing firm' => 'Duplica un\'azienda esistente',
  'Public firms' => 'Aziende pubbliche',
  'Your firms' => 'Le tue aziende', 
  'Create firm' => 'Crea un\'azienda', 
  
  'Closing post' => 'Registrazione di chiusura',
  'Patrimonial closing post' => 'Registrazione di chiusura patrimoniale',
  'Economic closing post' => 'Registrazione di chiusura economica',
  'Memo closing post' => 'Registrazione di chiusura conti d\'ordine',

  'Patrimonial closing' => 'Chiusura patrimoniale',
  'Economic closing' => 'Chiusura economica',
  'Memo closing' => 'Chiusura conti d\'ordine',

  'Please choose the kind of closing you need on the side menu.' => 'Scegli il tipo di chiusura desiderata nel menù a fianco.',
  'Please fix the following errors:' => 'Devi correggere i seguenti errori:',

  'This firm does not seem to have accounts of nature «{nature}» to close.'=> 'Questa azienda non sembra avere conti di natura «{nature}» da chiudere.',
  
  'Reasons' => 'Causali',
  'Create Reason' => 'Crea causale',
  'Create a Reason based on this post' => 'Crea una causale basata su questa registrazione',
  'Delete this post' => 'Elimina questa registrazione',
  'You are going to create a new reason with the following accounts:' => 'Stai per creare una nuova causale con i seguenti conti:',
  'Reason creation' => 'Creazione di causale',
  'The reason has been correctly saved.'=>'La causale è stata correttamente salvata.',
  'The reason could not be saved.'=>'La causale non è stata salvata.',
  
  'Create'=>'Crea',
  
  'Fork the firm «{firm}»' => 'Duplica l\'azienda «{firm}»',
  'Do you want to proceed?' => 'Vuoi procedere?',
  'Yes, please, fork this firm' => 'Sì, crea un duplicato di questa azienda',
  
  'Edit Firm «{name}»' => 'Modifica azienda «{name}»',
  
  'Currency' => 'Valuta',
  'Slug' => 'Slug',
  'Language' => 'Lingua',
  
  'Save' => 'Salva',
  'Export' => 'Esporta',
  'Import' => 'Importa',
  
  'Wonder what a <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="A slug is the part of a URL which identifies a page using human-readable keywords.">slug</a> is?' => 'Ti chiedi che cosa sia uno <a href="http://en.wikipedia.org/wiki/Slug_(web_publishing)#Slug" title="Uno slug è la parte di un URL che identifica una pagina web mediante parole chiave comprensibili da esseri umani.">slug</a>?',
  'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia">ISO 4217 code</a>, like EUR, USD, or GBP' => 'Devi indicare un <a href="http://it.wikipedia.org/wiki/ISO_4217" title="Trova ulteriori informazioni su Wikipedia">codice ISO 4217</a> di tre lettere, come EUR, USD o GBP',

  
  'The information about the firm has been correctly saved.'=>'Le informazioni sull\'azienda sono state correttamente salvate.',
  'The information about the firm could not be saved.' => 'Le informazioni sull\'azienda non sono state salvate.',
  
  'This slug is already in use.' => 'Questo slug è già in uso.',
  'Only lowercase letters, digits and minus sign are allowed.' => 'Solo lettere minuscole, cifre e il segno meno sono ammessi.',
  
  
  'Data to duplicate:' => 'Dati da duplicare:',
  'I agree on the fact that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.' => 'Accetto il fatto che i contenuti dell\'azienda che sto creando saranno disponibili con licenza <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribuzione - Condividi allo stesso modo 3.0 Unported</a>.',
  
  'Chart of Accounts' => 'Piano dei conti',
  'Chart of Accounts and Reasons' => 'Piano dei conti e causali contabili',
  'Chart of Accounts, Reasons and Posts' => 'Piano dei conti, causali contabili, registrazioni contabili',
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
  
  'Row' => 'Riga',
  'The code cannot end with a dot.' => 'Il codice non può finire con un punto.',
  'Total:' => 'Totale',
  
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
  'Sort accounts' => 'Ordina i conti',
  'Sort accounts, debits first' => 'Ordina i conti, mettendo prima gli addebiti',
  
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
  
  
  'The post has been successfully deleted.' => 'La registrazione è stata correttamente eliminata.',
  'you cannot do a debit to this kind of account' => 'non è possibile addebitare questo tipo di conto',
  'you cannot do a credit to this kind of account' => 'non è possibile accreditare questo tipo di conto',
  '(unless the post is marked as adjustment)' => '(a meno che la registrazione non sia contrassegnata come rettifica)',
  'Mark this post as adjustment, thus allowing exceptions in debit/credit checks'=>'Contrassegna questa registrazione come rettifica, ammettendo di conseguenza eccezioni nei controlli',
  
  'Synchronize' => 'Sincronizza',
  'Syncronize accounts from ancestor firms' => 'Sincronizza conti da un\'azienda di cui questa è derivazione',
  
  'Synchronize accounts' => 'Sincronizza conti',
  'You can synchronize your firm\'s chart of accounts with the one of one of the following ancestors:' => 'Puoi sincronizzare il piano dei conti della tua azienda con quelli di una delle seguenti aziende:',
  'New accounts found' => 'Nuovi conti trovati',
  'Changed accounts found' => 'Conti modificati trovati',
  'Select all' => 'Seleziona tutto',
  'Changes' => 'Modifiche',
  'There are no accounts to synchronize.' => 'Non ci sono conti da sincronizzare.',
  
  
  'Clear' => 'Svuota',
  'Delete all journal posts' => 'Elimina tutte le registrazioni contabili',
  'Are you sure you want to delete all journal posts?' => 'Sei sicuro di voler eliminare tutte le registrazioni contabili?',
  'The journal has been successfully cleared.'=> 'Le registrazioni contabili sono state correttamente eliminate.',
  
  'Contact Us'=>'Contattaci',
  'If you have questions about DELT Project or this website, please fill out the following form to contact us. Thank you.'=>'Se hai domande sul progetto DELT o su questo sito, compila il modulo seguente per contattarci. Grazie.',
  'Thank you for contacting us. We will respond to you as soon as possible.'=>'Grazie per averci contattato. Ti risponderemo il più presto possibile.',
  
  'Please enter the letters as they are shown in the image above.'=>'Inserisci le lettere dell\'immagine qui sopra.	',
  'Letters are not case-sensitive.'=>'Non viene tenuta in considerata la differenza tra maiuscole e minuscole.',
  'Submit'=>'Invia',
  'Thank you for contacting us. We will respond to you as soon as possible.'=>'Grazie per averci contattato. Ti risponderemo il più presto possibile.',
  
  'Verification Code'=> 'Codice di verifica',
  'Name'=>'Nome',
  'Email'=>'Email',
  'Subject'=>'Oggetto',
  'Body'=>'Testo',
  'We will use your address only to answer your question.'=>'Useremo il tuo indirizzo di posta elettronica solo per rispondere alla domanda.',
  'Get a new code.'=>'Procurati un codice nuovo',
  
  'Options' => 'Opzioni',
  'License' => 'Licenza',
  
  'The firm is being forked.' =>'Duplicazione dell\'azienda in corso.',
  'The data are being imported.' =>'Importazione dei dati in corso.',
  'Please wait a few seconds...' => 'Aspetta qualche secondo...',
  'Copy of "{name}"' => 'Copia di "{name}"',
);

