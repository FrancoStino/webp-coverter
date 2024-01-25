# Comprimi e Converti Immagini in Formato WebP in WordPress

Questo plugin WordPress ti consente di comprimere e convertire automaticamente le immagini caricate nel formato WebP. WebP è un moderno formato di immagine che offre un'eccellente compressione senza compromettere la qualità, il che si traduce in tempi di caricamento del sito web più veloci.

## Requisiti

L'unico requisito per utilizzare questo plugin è avere la libreria `rosell-dk/webp-convert` installata. Puoi aggiornarnala eseguendo il seguente comando con Composer:

```bash
composer require rosell-dk/webp-convert
```
## Installazione

Per utilizzare questo plugin nel tuo sito WordPress, segui questi passaggi:

1. **Scarica il Plugin:** Puoi scaricare il plugin come file ZIP direttamente dal repository GitHub o clonare il repository sul tuo computer locale.

2. **Carica il Plugin nel Child Theme:** Se stai utilizzando un tema child, è consigliabile caricare il plugin nella cartella `functions` del child theme. Assicurati che il tuo child theme sia attivo. Puoi farlo seguendo questi passaggi:

   - Vai alla cartella del tuo child theme, ad esempio `wp-content/themes/nome-child/`.
   - Crea una nuova cartella chiamata `functions` se non esiste già.
   - Carica la directory del plugin nella cartella `functions` del child theme.

3. **Aggiungi il Require nel `functions.php` del Child Theme:** Nel file `functions.php` del tuo child theme, inserisci la seguente linea di codice per includere il plugin:

   ```php
   require ('functions/webp-coverter/compress-and-convert-images-to-webp-format-in-wordpress.php');
   ```

4. **Configurazione Opzionale:** Puoi personalizzare la qualità della conversione WebP regolando l'opzione `quality` nel codice del plugin, se necessario. Valori più alti offrono una migliore qualità ma dimensioni di file più grandi, mentre valori più bassi forniscono una maggiore compressione ma possono ridurre la qualità.

   ```php
   $options = [
       'quality' => 90, // Regola questo valore per controllare il livello di compressione
   ];
