<?php

return array(

  'Welcome to <i>{name}</i>'=>'Benvenuti su <i>{name}</i>',
  'You can gain experience in bookkeping with the Double Entry method with your firms, listed on the right side.' => 'Puoi fare esperienza nella tenuta della contabilità con il metodo della partita doppia con una delle tue aziende, elencate sulla destra.',
  
  'Bookkeeping' => 'Contabilità',
  'Bookkeeping and accountancy' => 'Gestione contabile',
  'Journal' => 'Libro giornale',
  'Chart of accounts' => 'Piano dei conti',
  'Trial Balance' => 'Situazione contabile',
  'Ledger' => 'Libro mastro',
  'Account' => 'Conto',
  'Parent account' => 'Conto di livello superiore',

  'Code' => 'Codice',
  'Name' => 'Nome',
  'Date' => 'Data',
  'Description' => 'Descrizione',

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
  'You must provide a three-letter <a href="http://en.wikipedia.org/wiki/ISO_4217" title="Find more on Wikipedia">ISO 4217 code</a>' => 'Devi indicare un <a href="http://it.wikipedia.org/wiki/ISO_4217" title="Trova ulteriori informazioni su Wikipedia">codice ISO 4217</a> di tre lettere',

  
  'The information about the firm has been correctly saved.'=>'Le informazioni sull\'azienda sono state correttamente salvate.',
  'The information about the firm could not be saved.' => 'Le informazioni sull\'azienda non sono state salvate.',
  
  'This slug is already in use.' => 'Questo slug è già in uso.',
  'Only lowercase letters, digits and minus sign are allowed.' => 'Solo lettere minuscole, cifre e il segno meno sono ammessi.',
  
  
  'Data to duplicate:' => 'Dati da duplicare:',
  'I understand that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.' => 'Sono consapevole del fatto che i contenuti dell\'azienda che sto creando saranno disponibili con licenza <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribuzione - Condividi allo stesso modo 3.0 Unported</a>.',
  
  'Chart of Accounts' => 'Piano dei conti',
  'Chart of Accounts and Reasons' => 'Piano dei conti e causali contabili',
  'Chart of Accounts, Reasons and Posts' => 'Piano dei conti, causali contabili, registrazioni contabili',
  'You must confirm that you accept the license for the contents.' => 'Devi confermare di accettare la licenza per i contenuti.',
  
  'The firm has been successfully forked.' => 'L\'azienda è stata correttamente duplicata.',
  'Data correctly imported.' => 'Dati correttamente importati',
  
  'Select date from calendar' => 'Seleziona la data dal calendario',
  
);

