# AGENTS.md - Development Guidelines

## Project Overview

File explorer application with PHP backend, vanilla JavaScript frontend, and MySQL database.
Features: user authentication, permission-based file access (stored as JSON), Windows 98 retro UI.

## Development Commands

### Docker (Primary Environment)

```bash
# Start application
docker-compose up

# Stop application
docker-compose down

# Access logs
docker-compose logs web
```

### Linting

```bash
# Lint JavaScript files
npx eslint script.js explorerfilebackend/script.js

# Lint all JS files
npx eslint "**/*.js"

# Fix linting issues automatically
npx eslint "**/*.js" --fix
```

**ESLint Configuration (eslint.config.js):**

```javascript
module.exports = [
  {
    files: ["**/*.js"],
    languageOptions: {
      ecmaVersion: "latest",
      sourceType: "script",
      globals: {
        console: "readonly",
        document: "readonly",
        window: "readonly",
        localStorage: "readonly",
        JSON: "readonly",
        Set: "readonly",
        CSS: "readonly",
      },
    },
    rules: {
      quotes: ["error", "double"],
      semi: ["error", "always"],
      "no-unused-vars": "warn",
      "no-console": "off",
    },
  },
];
```

### Testing

No automated test suite currently configured. Manual testing via browser required.

## Code Style Guidelines

### PHP

**Naming Conventions:**

- Functions: camelCase (e.g., `findIcon()`, `renderFolderRow()`)
- Variables: $snake_case (e.g., `$chemin`, `$chemin_complet`, `$chemin_url`)
- Constants: UPPER_SNAKE_CASE (not extensively used)
- Files: lowercase with underscores (e.g., `tablerightinc.php`)

**Formatting:**

- 2-space indentation
- Double quotes for strings
- No trailing whitespace
- One blank line between functions

**Imports/Includes:**

- Use `include` or `require` for PHP files
- Include `Config/database.php` for database connections
- Use relative paths: `require __DIR__ . '/../Config/database.php'`

**Type Handling:**

- Use PHP 8.x type hints for parameters and return types
- Constructor property promotion for clean class definitions
- Type casting: `(int) $_SESSION['user_id']`
- Use `??` null coalescing operator for optional values
- Strict JSON handling with `JSON_THROW_ON_ERROR`

**Error Handling:**

```php
try {
    $conn = new PDO("mysql:host=host.docker.internal;port=3306;dbname=explorerfile;charset=utf8", "root", "example");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Erreur connexion BDD : " . $e->getMessage());
    die("Erreur de connexion à la base de données");
}
```

**Database:**

- Use PDO with prepared statements
- Parameter binding: `$stmt->bindValue(':id', $userId, PDO::PARAM_INT)`
- Always sanitize input before queries
- Use `password_hash()` and `password_verify()` for passwords

### JavaScript

**Naming Conventions:**

- Functions: camelCase (e.g., `goBackPath`, `selectPath`)
- Variables: camelCase (e.g., `fileInput`, `pathStack`)
- Constants: UPPER_SNAKE_CASE (e.g., `basePath`)
- Event handlers: descriptive names (e.g., `toggleDeconnectionButton`)

**Formatting:**

- 2-space indentation
- Double quotes for strings (enforced by ESLint)
- Use `const` by default, `let` only when reassignment needed
- Arrow functions for callbacks

**Imports:**

- No build system/module system - vanilla JS
- Scripts loaded via `<script src="script.js"></script>`
- Use external libraries via CDN (e.g., 98.css)

**DOM Manipulation:**

```javascript
// Select elements at top of file
const fileInput = document.getElementById("fileInput");
const fileTableBody = document.getElementById("fileTableBody");

// Create elements properly
const row = document.createElement("tr");
const cell = document.createElement("td");
cell.textContent = "value";
row.appendChild(cell);
```

**Event Handling:**

```javascript
element.addEventListener("click", (e) => {
  e.preventDefault();
  // handler logic
});
```

**Data Storage:**

- Use localStorage for client-side persistence

```javascript
function saveToLocalStorage(key, data) {
  localStorage.setItem(key, JSON.stringify(data));
}

function getFromLocalStorage(key) {
  const data = localStorage.getItem(key);
  return data ? JSON.parse(data) : null;
}
```

**JavaScript Module Pattern:**

- Use IIFE (Immediately Invoked Function Expression) for module encapsulation
- Revealing module pattern with exposed API
- State management object pattern
- Event delegation for dynamic elements

```javascript
const PermissionManager = (function () {
  "use strict";

  const state = {
    selectedPermissions: new Set(),
    expandedFolders: new Set(),
    treeData: null,
  };

  function init(treeData, existingPermissions) {
    state.treeData = treeData;
    state.selectedPermissions = new Set(existingPermissions);
    // initialization logic
  }

  function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = "copy";
  }

  function handleDrop(e) {
    e.preventDefault();
    // drop handling logic
  }

  return {
    init,
    getPermissions,
    updateDropZone,
    updateAvailableFiles,
  };
})();
```

**Dynamic Selectors:**

- Use `CSS.escape()` for dynamic path selectors
- Handle special characters in attribute selectors

```javascript
const toggleElement = document.querySelector(
  `#fileTree .tree-item[data-path="${CSS.escape(folderPath)}"] .tree-toggle`,
);
```

### HTML

**Structure:**

- HTML5 doctype
- Include 98.css: `<link rel="stylesheet" href="https://unpkg.com/98.css">`
- Custom styles: `<link rel="stylesheet" href="style.css">`

**Conventions:**

- French labels for UI (e.g., "Sélectionner un dossier")
- IDs: camelCase (e.g., `fileInput`, `parentpath`)
- Classes: lowercase and kebab-case (e.g., `.button`, `.table`)
- Inline styles for specific element positioning (acceptable for Windows 98 theme)

### CSS

**Naming:**

- Classes: lowercase and kebab-case (e.g., `.box-button`, `.dropzone`)
- IDs: camelCase (e.g., `#goBackButton`, `#parentpath`)

**Formatting:**

- 2-space indentation
- Shorthand properties where appropriate
- Transitions on hover states

**Style Organization:**

- Use 98.css for base Windows 98 styling
- Custom styles in style.css for app-specific styling
- Responsive considerations: use flexbox, media queries if needed

## Architecture Patterns

### File Structure

```
explorerfile/                  # Frontend (file selection + visualization)
explorerfilebackend/           # Backend PHP application
  - Config/                  # Application configuration files
    - paths.php              # Path and URL constants
  - Models/                  # Data models and entities
    - Permission.php         # User permissions model
  - Services/                # Business logic layer
    - FileSystemService.php  # File system operations
    - PermissionService.php  # Permission management logic
  - Repositories/            # Data access layer
    - PermissionRepository.php
  - Views/                   # View templates
    - tableright_view.php
  - Assets/                  # Static assets
    - js/                    # JavaScript modules
      - permission-manager.js
    - css/                   # Component-specific styles
  - Controllers/              # Request handlers
    - AuthController.php      # Authentication logic
  - compte/                  # User account management ONLY
    - index.php             # User profile page
    - update.php            # POST: Update user profile
    - password/             # Password management
      - index.php          # Password change form
      - update.php         # POST: Update password
  - admin/                   # Admin panel (NEW - RESTful routes)
    - index.php            # Admin dashboard
    - users/               # User management
      - index.php         # User list + inline edit
      - update.php        # POST: Update user
    - permissions/         # Permission management
      - index.php         # Permission editor
      - update.php        # POST: Save permissions
  - index.php                # Main file explorer
  - affichage.php            # Display logic & table rendering
  - connectdb.php            # Database connection
  - register.php             # User registration
  - login.php                # User login
  - logout.php               # User logout
  - new/                     # Default file directory
  - icon/                    # File/folder icons
  - style/                   # Global styles
```

### MVC-like Architecture

**Layer Responsibilities:**

- **Models**: Pure data containers with validation and conversion methods
- **Services**: Business logic, orchestration, and tree operations
- **Repositories**: Database operations, CRUD for single entities
- **Views**: HTML templates with minimal PHP logic
- **Config**: Global constants and path definitions

**File Naming:**

- Models: Singular, PascalCase (e.g., `Permission.php`)
- Services: Descriptive, Service suffix (e.g., `FileSystemService.php`)
- Repositories: Entity name + Repository suffix (e.g., `PermissionRepository.php`)
- Views: Descriptive, `_view.php` suffix (e.g., `tableright_view.php`)
- Config: Descriptive, `.php` extension (e.g., `paths.php`)

**Model Pattern:**

```php
class Permission
{
    public function __construct(
        private int $userId,
        private array $paths = []
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public static function fromJson(int $userId, string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return new self($userId, $data['paths'] ?? []);
    }

    public function toJson(): string
    {
        return json_encode(['paths' => $this->paths], JSON_THROW_ON_ERROR);
    }
}
```

**Service Pattern with Dependency Injection:**

```php
class PermissionService
{
    public function __construct(
        private PermissionRepository $repository,
        private array $availablePaths = []
    ) {}

    public function getExistingPermissions(int $userId): Permission
    {
        $permission = $this->repository->findByUserId($userId);
        return $permission ?? new Permission($userId, []);
    }

    public function savePermissions(Permission $permission): bool
    {
        return $this->repository->save($permission);
    }
}
```

**Repository Pattern:**

```php
class PermissionRepository
{
    public function __construct(private PDO $db) {}

    public function findByUserId(int $userId): ?Permission
    {
        $sql = "SELECT encodedjson FROM permission WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $json = $stmt->fetchColumn();
        return $json ? Permission::fromDatabaseJson($userId, $json) : null;
    }

    public function save(Permission $permission): bool
    {
        $json = $permission->toDatabaseJson();
        return $this->exists($permission->getUserId())
            ? $this->update($permission->getUserId(), $json)
            : $this->insert($permission->getUserId(), $json);
    }

    private function exists(int $userId): bool { /* ... */ }
    private function update(int $userId, string $json): bool { /* ... */ }
    private function insert(int $userId, string $json): bool { /* ... */ }
}
```

**Configuration Pattern:**

```php
// Config/paths.php
define('ROOT_DIR', dirname(__DIR__));
define('BASE_DIR', ROOT_DIR . '/new');
define('ICON_DIR', ROOT_DIR . '/icon');
define('STYLE_DIR', ROOT_DIR . '/style');

define('ICON_URL', '/explorerfilebackend/icon');
define('STYLE_URL', '/explorerfilebackend/style');
define('ASSETS_URL', '/explorerfilebackend/Assets');

define('FOLDER_ICON', ICON_URL . '/folder2.png');
define('FILE_ICON', ICON_URL . '/file.png');
```

### Route Architecture

**RESTful Design:**
- **User Routes**: `/compte/` - Profile, password management
- **Admin Routes**: `/admin/` - User management, permissions
- **GET**: Display forms/pages
- **POST**: Process form submissions

**Route Patterns:**
- `{resource}/` - Display resource (GET)
- `{resource}/update.php` - Update resource (POST)
- Nested resources: `/admin/permissions/?user_id={id}`

**Authentication Middleware:**
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
if ($_SESSION['isadmin'] !== 1) {
    header('Location: /compte/');
    exit;
}
```

**Form Action Examples:**
```php
// User profile update
<form action="update.php" method="POST">

// Admin user update
<form action="update.php" method="POST">

// Permission editor
<form action="permissions/" method="GET">
    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
</form>

// Permission save
<form action="update.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
    <input type="hidden" name="permissions" value="">
</form>
```

### Permission System

User file access stored as JSON in `permission` table:

```json
{ "paths": ["folder1", "file.txt", "subfolder"] }
```

Decoded and filtered in `affichage.php` via `path()` function.

### Session Management

```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
```

## Comments & Documentation

- Comments primarily in French (project is French-language)
- Minimal comments - code should be self-explanatory
- TODO notes in French (e.g., `// XXX` marker for completed tasks)

## Security

- Never commit credentials (use environment variables in production)
- Validate all user input before database operations
- Use password_hash() for storing passwords
- Check session user_id before allowing access
- Admin checks: `if($_SESSION['isadmin'] == 1)`

## Plan Mode

- Make the plan extremely concise, Sacrifice grammar for the sake of concision
- At the end of each plan, gime me a list of unresolved questions to answer, if any.
