# Warehouse Manager

Laravel application with Inertia.js and Vue.js for modern warehouse management.

## Tech Stack

- **Backend:** Laravel 10+
- **Frontend:** Vue.js 3 + Inertia.js
- **Database:** MySQL/PostgreSQL
- **Styling:** Tailwind CSS
- **Build Tool:** Vite

## Installation

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 16+
- npm/yarn

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/warehouse-manager.git
   cd warehouse-manager
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=warehouse_manager
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seed database**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```
   
   For development:
   ```bash
   npm run dev
   ```

8. **Start the application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## Development

### Start development server
```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

### Database commands
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Run migrations only
php artisan migrate

# Run seeders only
php artisan db:seed

# Create new migration
php artisan make:migration create_table_name

# Create new seeder
php artisan make:seeder TableNameSeeder
```

### Commands
```bash
# Create Item
php artisan items:create


```

## Project Structure

```
├── app/
│   ├── Http/Controllers/     # Laravel controllers
│   ├── Models/              # Eloquent models
│   └── ...
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/     # Vue components
│   │   ├── Layouts/       # Inertia layouts
│   │   ├── Pages/         # Inertia pages
│   │   └── app.js         # Main JS entry point
│   └── css/               # Stylesheets
├── routes/
│   └── web.php            # Web routes
└── ...
```

## Features

- User authentication and authorization
- Modern SPA experience with server-side routing
- Responsive design with Tailwind CSS
- Real-time updates
- Database seeding for development

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).