# explorerfile

A Windows 98-styled file explorer application with PHP backend, MySQL database, and vanilla JavaScript frontend. Features user authentication and permission-based file access management.

## Features

- **File Explorer**: Browse files and folders with a retro Windows 98 interface
- **User Authentication**: Secure login/registration system
- **Permission Management**: Assign file/folder access to users via drag-and-drop
- **Admin Panel**: Manage user accounts and permissions
- **Responsive UI**: Windows 98 aesthetic with modern functionality
- **RESTful Routes**: Clean, hierarchical routing structure

## Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL
- **Frontend**: Vanilla JavaScript, HTML, CSS
- **Styling**: 98.css (Windows 98 visual recreation)
- **Environment**: Docker/Docker Compose
- **Architecture**: RESTful routing with MVC-like layering

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
     ├── Controllers/
     │   └── AuthController.php      # Authentication handler
     ├── compte/               # User account management
     │   ├── index.php        # User profile page
     │   ├── update.php       # POST: Update user profile
     │   └── password/       # Password management
     │       ├── index.php    # Password change form
     │       └── update.php   # POST: Update password
     ├── admin/               # Admin panel (RESTful routes)
     │   ├── index.php        # Admin dashboard
     │   ├── users/
     │   │   ├── index.php   # User list + inline edit
     │   │   └── update.php  # POST: Update user
     │   └── permissions/    # Permission management
     │       ├── index.php    # Permission editor
     │       └── update.php   # POST: Save permissions
     ├── index.php             # Main file explorer
     ├── affichage.php         # Display logic & table rendering
     ├── connectdb.php         # Database connection
     ├── register.php          # User registration
     ├── login.php             # User login
     ├── logout.php            # User logout
     ├── new/                  # File storage directory
     ├── icon/                 # File/folder icons
     └── style/                # Global CSS styles
```

## Routes

### Public Routes
| Route | Method | Description |
|-------|--------|-------------|
| `/register.php` | GET/POST | User registration |
| `/login.php` | GET/POST | User login |
| `/logout.php` | POST | User logout |

### Protected Routes (User)
| Route | Method | Description |
|-------|--------|-------------|
| `/index.php` | GET | File explorer |
| `/compte/` | GET/POST | User profile page |
| `/compte/password/` | GET/POST | Change password form |

### Protected Routes (Admin)
| Route | Method | Description |
|-------|--------|-------------|
| `/admin/` | GET | Admin dashboard |
| `/admin/users/` | GET/POST | User management |
| `/admin/permissions/` | GET/POST | Permission editor |

**Authentication**: All protected routes require valid session (`$_SESSION['user_id']`). Admin routes require `$_SESSION['isadmin'] == 1`.

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

| Route | Method | Description |
|-------|--------|-------------|
| `/Controllers/AuthController.php?action=register` | POST | User registration |
| `/Controllers/AuthController.php?action=login` | POST | User login |
| `/compte/` | GET | View user profile |
| `/compte/update.php` | POST | Update user profile |
| `/compte/password/` | GET | Password change form |
| `/compte/password/update.php` | POST | Update password |
| `/admin/` | GET | Admin dashboard |
| `/admin/users/` | GET | List users |
| `/admin/users/update.php` | POST | Update user account |
| `/admin/permissions/` | GET | Permission editor |
| `/admin/permissions/update.php` | POST | Save permissions |

## Migration Guide

### Route Changes

**Old Route → New Route**

| Old Route | New Route |
|-----------|-----------|
| `compte/compte.php` | `/compte/` |
| `compte/compteinc.php` | `/compte/update.php` (POST) |
| `compte/edit/editmdp.php` | `/compte/password/` |
| `compte/edit/editmdpinc.php` | `/compte/password/update.php` (POST) |
| `compte/compteadmin.php` | `/admin/` |
| `compte/compteadmininc.php` | `/admin/users/update.php` (POST) |
| `compte/edit/gestioncompte.php` | `/admin/users/` |
| `compte/edit/tableright.php?user_id=X` | `/admin/permissions/?user_id=X` |
| `compte/edit/tablerightinc.php` | `/admin/permissions/update.php` (POST) |

### File Structure Changes

- **Deleted**: `admin.php`, `profile.php` (duplicate files)
- **Deleted**: `compte/edit/` nested directory
- **New**: `/admin/` directory with RESTful routes
- **Reorganized**: `/compte/` for user account management only

## Screenshots

*(Add screenshots here)*

## License

MIT License
