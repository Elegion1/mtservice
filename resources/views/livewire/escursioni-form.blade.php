<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="container mx-auto my-3 p-5 border rounded shadow">
        <form>
            <h1>Prenota escursione</h1>
            <div class="mb-3 row">
                <div class="col-12 col-md-6">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="col-12 col-md-6">
                    <label for="surname" class="form-label">Cognome</label>
                    <input type="text" class="form-control" id="surname">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="email" class="form-label">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                </div>
                <div class="col-6">
                    <label for="phone" class="form-label">Numero di telefono</label>
                    <input type="tel" class="form-control" id="phone" aria-describedby="emailHelp">
                </div>
                
    
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3 row">
                        <div class="col-4">
                            <label for="surname" class="form-label">Passeggeri</label>
                            <input type="number" class="form-control" id="surname">
                        </div>
                        <div class="col-8 d-flex justify-content-around align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Sola Andata
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"
                                    checked>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Andata e ritorno
                                </label>
                            </div>
                        </div>                    
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
            
                            <label for="arrivaldate">Andata</label>
                            <input type="datetime-local" class="form-control" id="arrivaldate">
            
                        </div>
                        <div class="col-6">
                            <label for="departdate">Ritorno</label>
                            <input type="datetime-local" class="form-control" id="departdate">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-primary">Prenota</button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-12">
                        <label for="message">Inserisci un messaggio</label>
                        <textarea class="form-control" id="message" rows="7"
                        placeholder="N° volo arrivo 
    Orario di arrivo 
    Provenienza 
    Orario di partenza
    N° bagagli 
    Indirizzo destinazione o nominativo struttura 
    Se si necessita di seggiolino o alzata bambini."></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
</div>
