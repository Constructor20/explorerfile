// Sélection des éléments HTML
const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');
const goBackButton = document.getElementById('goBackButton');

// Variables globales
const pathStack = [];
const tableStack = []; // Pile pour sauvegarder les états du tableau
const basePath = '/';

// Fonction pour gérer le retour au dossier parent
const goBackPath = () => {
    console.log('État de tableStack avant pop :', tableStack);

    if (tableStack.length === 0) {
        console.log('Retour au chemin initial sélectionné :', initialParentPath);
        const initialFiles = getFromLocalStorage('initialFiles');
        if (initialFiles) {
            parentPathHeader.textContent = `Chemin parent: ${initialParentPath}`;

            generateTableRows(initialFiles.map(file => ({
                name: file.name,
                webkitRelativePath: file.path
            })), initialParentPath);
        }
        return;
    }

    // Sinon, on remonte d'un cran
    const previousState = tableStack.pop();
    const { tableState, parentPath } = previousState;

    if (tableState) {
        restoreTableRows(tableState);
    }

    parentPathHeader.textContent = `Chemin parent: ${parentPath || initialParentPath}`;
    console.log('Chemin parent mis à jour après retour :', parentPathHeader.textContent);
};


function restoreTableRows(previousTableState) {
    console.log('Restauration du tableau avec l\'état :', previousTableState);

    fileTableBody.innerHTML = ''; // Réinitialise le tableau

    previousTableState.forEach(rowData => {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        link.textContent = './' + rowData.path || 'Aucun chemin disponible';
        link.setAttribute('data-path', rowData.path);
        link.href = `#${rowData.path}`;
        link.addEventListener('click', (e) => {
            e.preventDefault();
            selectPath(e); // Ajoute l'événement pour gérer le clic
        });

        cellPath.appendChild(link);
        row.appendChild(cellPath);

        const cellName = document.createElement('td');
        cellName.textContent = rowData.name;
        row.appendChild(cellName);

        fileTableBody.appendChild(row);
    });
}

// Fonction pour mettre à jour le chemin parent
function updateParentPath(files) {

    if (!files || files.length === 0 || !files[0].webkitRelativePath) {
        parentPathHeader.textContent = `Chemin parent: ${basePath}`;
        return;
    }

    const firstFilePath = files[0].webkitRelativePath;
    const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
    parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
}
// Fonction pour générer un lien pour un fichier ou un dossier
function generateLinkForFile(file, firstFilePath) {
    let pathWithoutFileName = file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/'));
    const parentFolderName = firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));

    if (parentFolderName === pathWithoutFileName) {
        pathWithoutFileName = '';
    } else {
        pathWithoutFileName = pathWithoutFileName.replace(/^[^\/]+\//, '');
    }

    return {
        href: `#${pathWithoutFileName}`, // Conserve le chemin réel pour la navigation
        text: pathWithoutFileName, // Ajoute un "." uniquement pour l'affichage
        data: pathWithoutFileName // Conserve le chemin réel pour la logique
    };
}

// Fonction pour gérer le clic sur un lien
function selectPath(e) {
    const path = e.target.getAttribute("data-path");
    if (!path) return;

    const currentPath = parentPathHeader.textContent.replace('Chemin parent: ', pathStack).trim();
    pathStack.push(currentPath);

    const selectedFiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.includes(path);
    });

    if (selectedFiles.length === 0) {
        return;
    }

    parentPathHeader.textContent = `Chemin parent: ${path}`;
    generateTableRows(selectedFiles, path);
}

// Fonction pour générer les lignes du tableau
function generateTableRows(files, firstFilePath) {

    fileTableBody.innerHTML = ''; // Réinitialise le tableau

    if (files.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 2;
        cell.textContent = 'Aucun fichier disponible.';
        row.appendChild(cell);
        fileTableBody.appendChild(row);
        return;
    }

    files.forEach((file) => {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        if (file.webkitRelativePath || file.path) {
            const linkData = generateLinkForFile(file, firstFilePath);
            link.href = linkData.href;

            const parentPath = parentPathHeader.textContent.replace('Chemin parent: ', '').trim();
            link.textContent = linkData.data === parentPath ? './' : `./${linkData.data}`;
            link.setAttribute("data-path", linkData.data);
            link.addEventListener('click', (e) => {
                e.preventDefault();
                selectPath(e);
            });
        } else {
            link.textContent = 'Aucun chemin disponible';
        }

        cellPath.appendChild(link);
        row.appendChild(cellPath);

        const cellName = document.createElement('td');
        cellName.textContent = file.name;
        row.appendChild(cellName);

        fileTableBody.appendChild(row);
    });
}

// Sauvegarder des données dans le localStorage
function saveToLocalStorage(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
}

function getFromLocalStorage(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

// Gestion de l'événement "change" sur l'input
let initialParentPath = '/'; // Valeur par défaut

fileInput.addEventListener('change', (event) => {
    const files = Array.from(event.target.files);

    if (files.length > 0) {
        // On récupère le dossier racine du premier fichier
        const firstFilePath = files[0].webkitRelativePath;
        initialParentPath = '/' + firstFilePath.split('/')[0]; // par ex: /Public

        // Sauvegarde dans le localStorage (optionnel)
        saveToLocalStorage('initialParentPath', initialParentPath);

        // Mise à jour du header avec le dossier racine
        parentPathHeader.textContent = `Chemin parent: ${initialParentPath}`;

        saveToLocalStorage('initialFiles', files.map(file => ({
            name: file.name,
            path: file.webkitRelativePath
        })));

        generateTableRows(files, initialParentPath);

        // Sauvegarde de l'état initial
        const initialTableState = files.map(file => ({
            path: file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/')),
            name: file.name
        }));
        tableStack.push({
            tableState: initialTableState,
            parentPath: initialParentPath
        });
    }
});


// Gestion du bouton "Retour"
goBackButton.addEventListener('click', goBackPath);