# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A Windows 98-styled file explorer web application with PHP backend, MySQL database, and vanilla JavaScript frontend. Features user authentication with role-based access control and permission-based file access management.

**Tech Stack**: PHP 8.x, MySQL, vanilla JavaScript, 98.css (Windows 98 visual recreation)

## Development Commands

### Docker Environment

```bash
# Start the application (uses external Docker network)
docker-compose up

# Stop the application
docker-compose down

# View logs
docker-compose logs web
```

**Access**: http://localhost:8001/

**Note**: The Docker setup uses an external network `docker_default` (see `docker-compose.yml`). Ensure this network exists or update the compose file.

### Linting

```bash
# Lint all JavaScript files
npx eslint "**/*.js"

# Fix linting issues automatically
npx eslint "**/*.js" --fix
```

### Database Connection

The application connects to MySQL via `host.docker.internal:3306` with credentials `root/example` (see `explorerfilebackend/connectdb.php`).

## Project Structure

```
explorerfile/                    # Root with frontend visualization
├── script.js                    # Frontend file explorer logic
├── style.css                    # Frontend styles
├── eslint.config.js             # ESLint configuration
├── docker-compose.yml           # Docker setup
├── explorerfilebackend/         # Main PHP application
│   ├── Config/                  # Configuration files
│   │   ├── paths.php            # Path and URL constants
│   │   └── database.php         # Database connection
│   ├── Controllers/             # Request handlers
│   │   └── AuthController.php   # Authentication handler
│   ├── Models/                  # Data models
│   │   ├── Permission.php
│   │   └── User.php
│   ├── Repositories/            # Database access layer
│   │   ├── PermissionRepository.php
│   │   └── UserRepository.php
│   ├── Services/                # Business logic
│   │   ├── AuthService.php
│   │   ├── FileSystemService.php
│   │   └── PermissionService.php
│   ├── Views/                   # Templates
│   │   └── tableright_view.php
│   ├── Assets/                  # Static assets
│   │   ├── js/permission-manager.js
│   │   └── css/tableright.css
│   ├── admin/                   # Admin panel (RESTful routes)
│   │   ├── index.php
│   │   ├── users/
│   │   │   ├── index.php
│   │   │   └── update.php
│   │   └── permissions/
│   │       ├── index.php
│   │       ├── update.php
│   │       └── update_password.php
│   ├── compte/                  # User account management
│   │   ├── index.php
│   │   ├── update.php
│   │   └── password/
│   │       ├── index.php
│   │       └── update.php
│   ├── index.php                # Main file explorer (protected)
│   ├── affichage.php            # Display logic & table rendering
│   ├── login.php                # Login page
│   ├── register.php             # Registration page
│   ├── logout.php               # Logout handler
│   ├── connectdb.php            # Legacy DB connection
│   ├── new/                     # Default file storage directory
│   └── icon/                    # File/folder icons
```

## Architecture

### MVC-like Layering

| Layer | Purpose | Naming Convention |
|-------|---------|-------------------|
| **Models** | Data containers with validation | PascalCase (e.g., `Permission.php`) |
| **Repositories** | Database CRUD operations | `{Entity}Repository.php` |
| **Services** | Business logic and orchestration | `{Name}Service.php` |
| **Views** | HTML templates | `{name}_view.php` |
| **Controllers** | Request handling | `{Name}Controller.php` |
| **Config** | Constants and paths | lowercase.php |

### Route Structure

**RESTful design** with GET for display, POST for actions:

| Route | Access | Purpose |
|-------|--------|---------|
| `/login.php`, `/register.php` | Public | Authentication |
| `/index.php` | User+ | File explorer |
| `/compte/` | User+ | User profile |
| `/compte/password/` | User+ | Change password |
| `/admin/` | Admin only | Admin dashboard |
| `/admin/users/` | Admin only | User management |
| `/admin/permissions/?user_id=X` | Admin only | Permission editor |

**Authentication check** (required on protected pages):
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['isadmin'] !== 1) {
    header('Location: /compte/');
    exit; // For admin-only pages
}
```

### Permission System

User file access permissions are stored as JSON in the `permission` table:
```json
{"paths": ["folder1/", "folder1/file.txt", "folder2/"]}
```

The `FileSystemService::buildTree()` method builds the file hierarchy, and `affichage.php` filters the display based on the user's permissions stored in session.

## Code Style

### PHP
- **Functions**: camelCase (e.g., `findIcon()`, `renderFolderRow()`)
- **Variables**: $snake_case (e.g., `$chemin_complet`)
- **Indentation**: 2 spaces
- **Strings**: Double quotes preferred
- **Comments**: French language preferred
- **Type hints**: Use PHP 8.x features including constructor property promotion
- **Database**: PDO with prepared statements, parameter binding with `PDO::PARAM_INT`

### JavaScript
- **Naming**: camelCase for functions/variables, UPPER_SNAKE_CASE for constants
- **Indentation**: 2 spaces
- **Quotes**: Double quotes (enforced by ESLint)
- **Modules**: Use IIFE pattern for encapsulation (no build system)
- **Selectors**: Use `CSS.escape()` for dynamic path selectors with special characters

### CSS
- **Classes**: kebab-case (e.g., `.box-button`)
- **IDs**: camelCase (e.g., `#goBackButton`)
- Uses 98.css for Windows 98 styling, custom CSS for app-specific styles

## Key Patterns

### Service with Dependency Injection
```php
class PermissionService
{
    public function __construct(
        private PermissionRepository $repository,
        private array $availablePaths = []
    ) {}
}
```

### Model with JSON Serialization
```php
class Permission
{
    public function __construct(private int $userId, private array $paths = []) {}
    public static function fromJson(int $userId, string $json): self { /* ... */ }
    public function toJson(): string { /* ... */ }
}
```

### JavaScript Module Pattern
```javascript
const Module = (function () {
  "use strict";
  const state = { /* ... */ };
  function init() { /* ... */ }
  return { init };
})();
```

## Database Schema

**userdata** table: `id`, `username`, `password` (hashed), `isadmin` (TINYINT)
**permission** table: `user_id` (FK), `encodedjson` (JSON paths array)

## Important Notes

- No package.json - use `npx eslint` directly for linting
- No automated tests - manual browser testing required
- Docker uses external network `docker_default` which must exist
- File upload/storage happens in `explorerfilebackend/new/` directory
- Session-based authentication with `$_SESSION['user_id']` and `$_SESSION['isadmin']`
- Passwords hashed with `password_hash()`, verified with `password_verify()`
