# explorerfile

A Windows 98-styled file explorer application with PHP backend, MySQL database, and vanilla JavaScript frontend. Features user authentication and permission-based file access management.

## Features

- **File Explorer**: Browse files and folders with a retro Windows 98 interface
- **User Authentication**: Secure login/registration system
- **Permission Management**: Assign file/folder access to users via drag-and-drop
- **Admin Panel**: Manage user accounts and permissions
- **Responsive UI**: Windows 98 aesthetic with modern functionality

## Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL
- **Frontend**: Vanilla JavaScript, HTML, CSS
- **Styling**: 98.css (Windows 98 visual recreation)
- **Environment**: Docker/Docker Compose

## Quick Start

```bash
# Start the application
docker-compose up

# Stop the application
docker-compose down

# View logs
docker-compose logs web
```

**Access**: http://localhost:8080/explorerfile/explorerfilebackend/

**Default Credentials**:
- Database: MySQL (root/example)
- See `registerphp/` for user authentication

## Project Structure

```
explorerfile/
├── README.md
├── AGENTS.md                 # Development guidelines
├── docker-compose.yml
├── eslint.config.js
├── script.js                 # Frontend file explorer
│
└── explorerfilebackend/
    ├── Config/
    │   └── paths.php         # Path and URL constants
    ├── Models/
    │   └── Permission.php    # User permissions model
    ├── Services/
    │   ├── FileSystemService.php   # File system operations
    │   └── PermissionService.php   # Permission management logic
    ├── Repositories/
    │   └── PermissionRepository.php
    ├── Views/
    │   └── tableright_view.php     # Permission management UI
    ├── Assets/
    │   ├── js/
    │   │   └── permission-manager.js
    │   └── css/
    ├── index.php             # Main file explorer
    ├── affichage.php         # Display logic & table rendering
    ├── connectdb.php         # Database connection
    ├── registerphp/          # User registration/login
    │   ├── register.php
    │   ├── login.php
    │   └── logininc.php
    ├── compte/               # Account management
    │   ├── compte.php        # User profile
    │   └── compteadmin.php   # Admin user management
    ├── new/                  # File storage directory
    ├── icon/                 # File/folder icons
    └── style/                # Global CSS styles
```

## Architecture

This project follows a simplified MVC-like architecture:

| Layer | Purpose | Examples |
|-------|---------|----------|
| **Models** | Data containers with validation | `Permission.php` |
| **Services** | Business logic and orchestration | `FileSystemService.php`, `PermissionService.php` |
| **Repositories** | Database operations | `PermissionRepository.php` |
| **Views** | HTML templates | `tableright_view.php` |
| **Config** | Constants and paths | `paths.php` |

For detailed coding guidelines, see [AGENTS.md](AGENTS.md).

## Database Schema

### Users Table (`userdata`)
- `id`: INT, Primary Key, Auto Increment
- `username`: VARCHAR(255)
- `password`: VARCHAR(255)
- `isadmin`: TINYINT(1)

### Permissions Table (`permission`)
- `user_id`: INT, Foreign Key to `userdata.id`
- `encodedjson`: JSON, Stores paths array

## Development

### Linting

```bash
# Lint all JS files
npx eslint "**/*.js"

# Fix issues automatically
npx eslint "**/*.js" --fix
```

### Adding New Features

1. Create models in `Models/` for data structures
2. Add services in `Services/` for business logic
3. Create repositories in `Repositories/` for database access
4. Build views in `Views/` as HTML templates
5. Update `Config/paths.php` for new paths

### Code Style

- PHP: camelCase functions, snake_case variables, 2-space indentation
- JavaScript: camelCase, double quotes, ESLint enforced
- CSS: kebab-case classes, camelCase IDs
- Comments: French language preferred

## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `registerphp/register.php` | POST | User registration |
| `registerphp/login.php` | POST | User login |
| `compte/compte.php` | GET | User profile |
| `compte/compteadmin.php` | GET | Admin user management |
| `compte/edit/tableright.php` | GET/POST | Permission management |

## Screenshots

*(Add screenshots here)*

## License

MIT License
