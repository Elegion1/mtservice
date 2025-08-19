# Comandi per Prenotazioni Fittizie

Questi comandi permettono di gestire prenotazioni fittizie per bloccare auto quando ci sono prenotazioni esterne al sito.

## Comando: `booking:create-dummy`

Crea una prenotazione fittizia per bloccare un'auto.

### Sintassi
```bash
php artisan booking:create-dummy [opzioni]
```

### Opzioni
- `--car-id=ID` - ID dell'auto da bloccare. **Puoi usare questa opzione più volte per bloccare più auto** (es. `--car-id=1 --car-id=2`).
- `--start-date=YYYY-MM-DD` - Data di inizio del blocco.
- `--end-date=YYYY-MM-DD` - Data di fine del blocco.

### Esempi di utilizzo

#### Modalità interattiva (senza parametri)
```bash
php artisan booking:create-dummy
```
Il comando chiederà interattivamente gli ID delle auto (separati da virgola) e le date.

Esempio input interattivo:
```
Inserisci gli ID delle auto da bloccare, separati da virgola (es. 1,2,3)
> 1, 5, 8

Inserisci la data di inizio (YYYY-MM-DD) [2024-08-21]:
> 2024-09-15

Inserisci la data di fine (YYYY-MM-DD) [2024-08-22]:
> 2024-09-17
```

#### Con parametri completi (auto singola)
```bash
php artisan booking:create-dummy --car-id=5 --start-date=2024-08-20 --end-date=2024-08-22
```

#### Con parametri completi (auto multiple)
```bash
php artisan booking:create-dummy --car-id=2 --car-id=3 --start-date=2024-09-10 --end-date=2024-09-12
```

#### Blocco rapido per oggi e domani
```bash
# Blocca le auto 3 e 4 per oggi e domani
php artisan booking:create-dummy \
  --car-id=3 --car-id=4 \
  --start-date=$(date +%Y-%m-%d) \
  --end-date=$(date -d "+1 day" +%Y-%m-%d)
```

## Comando: `booking:manage-dummy`

Gestisce le prenotazioni fittizie esistenti.

### Sintassi
```bash
php artisan booking:manage-dummy {azione} [opzioni]
```

### Azioni disponibili

#### `list` - Visualizza tutte le prenotazioni fittizie
```bash
php artisan booking:manage-dummy list
```

#### `delete` - Elimina una prenotazione fittizia specifica
```bash
php artisan booking:manage-dummy delete --id=123
```
Oppure modalità interattiva:
```bash
php artisan booking:manage-dummy delete
```

#### `cleanup` - Elimina prenotazioni fittizie vecchie
```bash
# Elimina prenotazioni più vecchie di 30 giorni (default)
php artisan booking:manage-dummy cleanup

# Elimina prenotazioni più vecchie di 7 giorni
php artisan booking:manage-dummy cleanup --days=7
```

## Caratteristiche delle Prenotazioni Fittizie

Le prenotazioni fittizie create hanno le seguenti caratteristiche:

- **Codice univoco**: Inizia sempre con `DUMMY-` seguito da un ID univoco
- **Status**: Automaticamente impostato su `confirmed`
- **Payment Status**: Automaticamente impostato su `paid`
- **Identificazione**: Campo `info.is_dummy_booking` = `true`
- **Tracciabilità**: Memorizza il motivo e la data di creazione
- **Dati cliente**: Usa dati fittizi del sistema

## Struttura Dati

### Campo `bookingData`
```json
{
  "type": "noleggio",
  "car_id": 5,
  "date_start": "2024-08-20",
  "time_start": "10:00",
  "date_end": "2024-08-22",
  "time_end": "17:00",
  "pickup_location": "Aeroporto",
  "return_location": "Aeroporto",
  "is_dummy": true,
  "dummy_reason": "Prenotazione Booking.com",
  "created_by_command": true
}
```

### Campo `info`
```json
{
  "is_dummy_booking": true,
  "created_via_command": true,
  "reason": "Prenotazione Booking.com",
  "created_at_timestamp": 1692537600
}
```

## Casi d'uso tipici

### 1. Prenotazione esterna da Booking.com
```bash
php artisan booking:create-dummy \
  --car-id=7 \
  --start-date=2024-08-25 \
  --end-date=2024-08-28 \
  --reason="Prenotazione Booking.com - Ref: BK123456"
```

### 2. Manutenzione programmata
```bash
php artisan booking:create-dummy \
  --car-id=2 \
  --start-date=2024-08-30 \
  --end-date=2024-08-30 \
  --start-time=08:00 \
  --end-time=12:00 \
  --reason="Manutenzione programmata - Tagliando"
```

### 3. Blocco per evento speciale
```bash
php artisan booking:create-dummy \
  --car-id=1 \
  --start-date=2024-09-01 \
  --end-date=2024-09-03 \
  --reason="Riservata per evento aziendale"
```

## Pulizia automatica

Per automatizzare la pulizia delle prenotazioni fittizie vecchie, puoi aggiungere il comando al cron:

```bash
# Nel crontab, esegui ogni domenica alle 02:00
0 2 * * 0 cd /path/to/project && php artisan booking:manage-dummy cleanup --days=30
```

## Note importanti

1. **Validazione**: Il comando valida automaticamente le date e gli orari
2. **Conflitti**: Non verifica conflitti con prenotazioni esistenti (è responsabilità dell'utente)
3. **Backup**: Si consiglia di fare backup prima di eliminazioni massive
4. **Log**: Tutte le operazioni sono tracciate nei log di Laravel
