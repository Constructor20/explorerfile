const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');
const goBackButton = document.getElementById('goBackButton');

const pathStack = [];
let oldparentpath = '/'; 

const goBackPath = () => {
    // Si la pile est vide, récupérer les fichiers initiaux depuis localStorage
    if (pathStack.length === 0) {
        const initialFiles = getFromLocalStorage('initialFiles');
        if (initialFiles) {
            parentPathHeader.textContent = `Chemin parent: /`;
            generateTableRows(initialFiles.map(file => ({
                name: file.name,
                webkitRelativePath: file.path
            })), '/');
        }
        return;
    }

    // Récupérer le dernier chemin de la pile
    const parentPath = pathStack.pop();

    // Filtrer les fichiers pour n'afficher que ceux du dossier parent
    const parentFiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.startsWith(parentPath) &&
               file.webkitRelativePath.replace(parentPath, '').indexOf('/') === -1;
    });

    // Mettre à jour le chemin parent avec l'ancien chemin
    parentPathHeader.textContent = `Chemin parent: ${parentPath}`;

    // Mettre à jour le tableau
    generateTableRows(parentFiles, parentPath);
};

goBackButton.addEventListener('click', goBackPath);

function updateParentPath(files) {
    if (files.length > 0) {
        const firstFilePath = files[0].webkitRelativePath;
        if (firstFilePath) {
            const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
            const oldparentpath = parentPathWithoutFileName
        } else {
            parentPathHeader.textContent = 'Aucun chemin disponible';
        }
    }
}

// Fonction pour générer un lien pour un fichier ou un dossier
function generateLinkForFile(file, firstFilePath) {
    let pathWithoutFileName = file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/'));
    const parentFolderName = firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
    console.log(parentFolderName)
    console.log(pathWithoutFileName)


    if (parentFolderName === pathWithoutFileName) {
        pathWithoutFileName = '/';
    } else {
        pathWithoutFileName = pathWithoutFileName.replace(/^[^\/]+\//, '/');
        // console.log(pathWithoutFileName)
    } return {
        href: `#${pathWithoutFileName}`,
        text: pathWithoutFileName,
        data: pathWithoutFileName
    };
}

// Fonction pour gérer le clic sur un lien
function selectPath(e) {
    const path = e.target.getAttribute("data-path")

    oldparentpath = parentPathHeader.textContent.replace('Chemin parent: ', '').trim();

    const selectedfiles = Array.from(fileInput.files).filter((file) => {
        return file.webkitRelativePath.includes(path)
    })
    parentPathHeader.textContent = `Chemin parent: ${path}`;
    generateTableRows(selectedfiles, path)
};


function generateTableRows(files, firstFilePath) {
    fileTableBody.innerHTML = ''; // Réinitialise le tableau

    files.forEach((file) => {
        const row = document.createElement('tr'); // Crée une nouvelle ligne
        const cellPath = document.createElement('td'); // Colonne pour le chemin
        const link = document.createElement('a'); // Lien pour le chemin

        if (file.webkitRelativePath || file.path) {
            const linkData = generateLinkForFile(file, firstFilePath);
            link.href = linkData.href;

            // Vérifie si le chemin parent correspond au chemin d'accès
            const parentPath = parentPathHeader.textContent.replace('Chemin parent: ', '').trim();
            if (linkData.data === parentPath) {
                link.textContent = './'; // Affiche "./" si le chemin parent correspond
            } else {
                link.textContent = '.' + linkData.text; // Texte par défaut
            }

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

        // Crée une cellule pour le nom du fichier
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