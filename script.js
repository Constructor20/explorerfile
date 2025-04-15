const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');
const goBackButton = document.getElementById('goBackButton');

const pathStack = [];
let oldparentpath = '/';
const basePath = '/';

const goBackPath = () => {

    // Si la pile est vide, récupérer les fichiers initiaux depuis localStorage
    if (pathStack.length === 0) {
        console.log('La pile pathStack est vide. Retour au chemin de base.');
        const initialFiles = getFromLocalStorage('initialFiles');
        if (initialFiles) {
            parentPathHeader.textContent = `Chemin parent: ${basePath}`;
            updateParentPath(initialFiles); // Mettre à jour le chemin parent
            generateTableRows(initialFiles.map(file => ({
                name: file.name,
                webkitRelativePath: file.path
            })), basePath);
        }
        return;
    }

    const parentPath = pathStack.pop();

    // Filtrer les fichiers pour n'afficher que ceux du dossier parent
    const parentFiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.startsWith(parentPath) &&
               file.webkitRelativePath.replace(parentPath, '').indexOf('/') === -1;
    });

    parentPathHeader.textContent = `Chemin parent: ${parentPath}`;
    // Mettre à jour le tableau
    generateTableRows(parentFiles, parentPath);
};

goBackButton.addEventListener('click', goBackPath);

function updateParentPath(files) {

    if (!files || files.length === 0 || !files[0].webkitRelativePath) {
        parentPathHeader.textContent = `Chemin parent: ${basePath}`;
        console.log(basePath)
        return;
    }

    const firstFilePath = files[0].webkitRelativePath;

    if (firstFilePath) {
        const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
        parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
        console.log(parentPathHeader)
    } else {
        parentPathHeader.textContent = `Chemin parent: ${basePath}`;
    }
}

// Fonction pour générer un lien pour un fichier ou un dossier
function generateLinkForFile(file, firstFilePath) {
    let pathWithoutFileName = file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/'));
    const parentFolderName = firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));


    if (parentFolderName === pathWithoutFileName) {
        pathWithoutFileName = '/';
    } else {
        pathWithoutFileName = pathWithoutFileName.replace(/^[^\/]+\//, '/');
    } return {
        href: `#${pathWithoutFileName}`,
        text: pathWithoutFileName,
        data: pathWithoutFileName
    };
}

// Fonction pour gérer le clic sur un lien
function selectPath(e) {
    const path = e.target.getAttribute("data-path");
    if (!path) return;

    // Sauvegarder le chemin actuel dans la pile
    const currentPath = parentPathHeader.textContent.replace('Chemin parent: ', '').trim();
    pathStack.push(currentPath);
    console.log('Chemin actuel sauvegardé dans pathStack :', currentPath);
    console.log('État de pathStack après push :', pathStack);

    // Filtrer les fichiers pour le chemin sélectionné
    const selectedfiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.includes(path);
    });
    console.log('Fichiers sélectionnés pour le chemin :', selectedfiles);

    // Vérifiez si des fichiers ont été trouvés
    if (selectedfiles.length === 0) {
        console.log('Aucun fichier trouvé pour le chemin sélectionné.');
        return;
    }

    // Mettre à jour le chemin parent
    parentPathHeader.textContent = `Chemin parent: ${path}`;
    console.log('Chemin parent mis à jour :', parentPathHeader.textContent);

    // Générer le tableau
    generateTableRows(selectedfiles, path);
}
// Il affiche bien le /Public mais pas de tab



function generateTableRows(files, firstFilePath) {
    console.log('Fichiers reçus pour générer les lignes du tableau :', files);
    console.log('Chemin de base utilisé pour générer les liens :', firstFilePath);

    fileTableBody.innerHTML = ''; // Réinitialise le tableau

    if (files.length === 0) {
        console.log('Aucun fichier à afficher dans le tableau.');
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 2;
        cell.textContent = 'Aucun fichier disponible.';
        row.appendChild(cell);
        fileTableBody.appendChild(row);
        return;
    }

    files.forEach((file) => {
        const row = document.createElement('tr'); // Crée une nouvelle ligne
        const cellPath = document.createElement('td'); // Colonne pour le chemin
        const link = document.createElement('a'); // Lien pour le chemin

        if (file.webkitRelativePath || file.path) {
            const linkData = generateLinkForFile(file, firstFilePath);
            link.href = linkData.href;

            const parentPath = parentPathHeader.textContent.replace('Chemin parent: ', '').trim();
            if (linkData.data === parentPath) {
                link.textContent = './'; // Affiche "./" si le chemin parent correspond
            } else {
                link.textContent = '.' + linkData.text; // Texte par défaut
            }

            console.log('Lien généré pour le fichier :', linkData);

            link.setAttribute("data-path", linkData.data);
            link.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut du lien
                selectPath(e); // Appelle la fonction pour gérer la navigation
            });
        } else {
            link.textContent = 'Aucun chemin disponible';
        }

        cellPath.appendChild(link); // Ajoute le lien à la cellule
        row.appendChild(cellPath); // Ajoute la cellule à la ligne

        const cellName = document.createElement('td');
        cellName.textContent = file.name;
        row.appendChild(cellName); // Ajoute la cellule à la ligne

        fileTableBody.appendChild(row); // Ajoute la ligne au tableau
    });
}

function saveToLocalStorage(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
}

function getFromLocalStorage(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

// Gestion de l'événement "change" sur l'input
fileInput.addEventListener('change', (event) => {
    const files = Array.from(event.target.files);

    // Sauvegarder les fichiers dans localStorage
    saveToLocalStorage('initialFiles', files.map(file => ({
        name: file.name,
        path: file.webkitRelativePath
    })));

    // Mettre à jour le chemin parent
    updateParentPath(files);

    // Appeler la fonction pour générer les lignes du tableau
    generateTableRows(files, files[0]?.webkitRelativePath);
});