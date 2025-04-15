// Sélection des éléments HTML
const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');
const goBackButton = document.getElementById('goBackButton');

// Variables globales
const pathStack = [];
const basePath = '/';

// Fonction pour gérer le retour au dossier parent
const goBackPath = () => {
    console.log('État de pathStack avant pop :', pathStack);

    if (pathStack.length === 0) {
        console.log('La pile pathStack est vide. Retour au chemin de base.');
        const initialFiles = getFromLocalStorage('initialFiles');
        if (initialFiles) {
            // Afficher un chemin explicite pour la racine
            parentPathHeader.textContent = `Chemin parent: ${pathStack}`;
            console.log('Chemin parent réinitialisé à la racine.');

            generateTableRows(initialFiles.map(file => ({
                name: file.name,
                webkitRelativePath: file.path
            })), basePath);
        }
        return;
    }

    const parentPath = pathStack.pop();
    console.log('Chemin parent retiré de la pile :', parentPath);

    const parentFiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.startsWith(parentPath) &&
               file.webkitRelativePath.replace(parentPath, '').indexOf('/') === -1;
    });
    console.log('Fichiers filtrés pour le chemin parent :', parentFiles);

    parentPathHeader.textContent = `Chemin parent: ${parentPath}`;
    console.log('Chemin parent mis à jour après retour :', parentPathHeader.textContent);

    generateTableRows(parentFiles, parentPath);
};

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

// Récupérer des données depuis le localStorage
function getFromLocalStorage(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

// Gestion de l'événement "change" sur l'input
fileInput.addEventListener('change', (event) => {
    const files = Array.from(event.target.files);
    saveToLocalStorage('initialFiles', files.map(file => ({
        name: file.name,
        path: file.webkitRelativePath
    })));

    updateParentPath(files);
    generateTableRows(files, files[0]?.webkitRelativePath);
});

// Gestion du bouton "Retour"
goBackButton.addEventListener('click', goBackPath);