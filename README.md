# Warehouse Manager

**Nota introduttiva:**  
Questo progetto è stato sviluppato come parte di una **code challenge a tempo limitato**.  
Il suo scopo principale era dimostrare capacità di progettazione, gestione di ruoli e flussi core di un gestionale, più che implementare tutte le funzionalità avanzate o test/REST API completi.

---

## Tech Stack
- **Backend:** Laravel 12  
- **Frontend:** Vue.js 3 + Inertia.js  
- **Database:** MySQL  
- **Styling:** Tailwind CSS  
- **Build Tool:** Vite  

---

## Setup

### Prerequisites
- PHP 8.1+  
- Composer  
- Node.js 16+  
- npm / yarn  

### Installation
```bash
git clone https://github.com/your-username/warehouse-manager.git
cd warehouse-manager

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Aggiorna il file `.env` con le credenziali del tuo database:  
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warehouse_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Esegui migrazioni e seeding:  
```bash
php artisan migrate:fresh --seed
npm run dev
php artisan serve
```

Visita [http://localhost:8000](http://localhost:8000) per usare l'app.

---

## Project Structure
```
├── app/
│   ├── Http/Controllers/     # Laravel controllers
│   ├── Models/               # Eloquent models
├── database/
│   ├── migrations/           
│   └── seeders/              
├── resources/
│   ├── js/
│   │   ├── Components/       
│   │   ├── Layouts/          
│   │   ├── Pages/            
│   │   └── app.js            
│   └── css/                  
├── routes/
│   └── web.php               
```

---

## Features implemented
- **Authentication & Role management** (admin / user)  
- **Admin panel**:  
  - User management  
  - Inventory management (create/update items)  
  - Approve/decline user requests  
- **User panel**:  
  - Request multiple items from inventory  
  - Track status of requests (pending / approved / declined)  
- **UI/UX** con design (Tailwind CSS)  
- **Database seeding** con dati di esempio per demo rapida  

---

## Limitations (time-constrained)
- **REST APIs non implementate**  
- **Test Pest limitati**: alcuni funzionano, altri danno errori, copertura incompleta  
- Controlli avanzati (es. date) non completamente implementati  

---

## Possible extensions
Se avessi avuto più tempo, avrei aggiunto:  
- REST API documentate 
- Test completi Pest + e2e con Cypress  
- Ruoli e permessi più granulari (oltre admin/user)  
- Miglioramenti UX/UI con componenti riutilizzabili  
- Logiche di validazione più complete  

---

## Approach
Ho quindi dato priorità a:  
- Core business logic (role management, approval/declination workflow)  
- Flusso funzionale e chiaro tra pannelli admin e user  
- Struttura del codice leggibile e facilmente estendibile  

Le funzionalità incomplete (API, test avanzati) riflettono una scelta di priorità e non una mancanza di capacità: sono aree in cui sono motivato a migliorare rapidamente.

---

## License
MIT

