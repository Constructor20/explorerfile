const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');

// Fonction pour générer un lien pour un fichier ou un dossier
function generateLinkForFile(file, firstFilePath) {
    let pathWithoutFileName = file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/'));
    const parentFolderName = firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
    if (parentFolderName === pathWithoutFileName) {
        pathWithoutFileName = '/';
    } else {
        pathWithoutFileName = pathWithoutFileName.replace(/^[^\/]+\//, '/');
    }
    return {
        href: `#${pathWithoutFileName}`,
        text: pathWithoutFileName
    };
}

// Fonction pour gérer le clic sur un lien
function handleLinkClick(link, files, targetPath) {
    link.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le comportement par défaut du lien
        console.log('Chemin cible cliqué :', targetPath);

        // Filtrer les fichiers pour afficher uniquement ceux du dossier cliqué
        const filteredFiles = Array.from(files).filter((file) => {
            const filePath = file.webkitRelativePath;
            if (!filePath.startsWith(targetPath)) return false; // Vérifie si le chemin commence par le chemin cible
        
            const relativePath = filePath.substring(targetPath.length + 1); // Chemin relatif après le chemin cible
            return !relativePath.includes('/'); // Exclut les fichiers dans les sous-dossiers
        });

        console.log('Fichiers filtrés :', filteredFiles);

        // Vérifier si des fichiers ont été trouvés
        if (filteredFiles.length === 0) {
            console.warn('Aucun fichier trouvé pour le chemin :', targetPath);
            parentPathHeader.textContent = `Chemin parent: ${targetPath}`;
            fileTableBody.innerHTML = '<tr><td colspan="2">Aucun fichier ou dossier trouvé.</td></tr>';
            return;
        }

        // Appeler la fonction pour générer les lignes du tableau
        generateTableRows(filteredFiles, targetPath);
    });
}

// Fonction pour mettre à jour le tableau
function updateTable(filteredFiles, targetPath) {
    parentPathHeader.textContent = `Chemin parent: ${targetPath}`;
    fileTableBody.innerHTML = ''; // Réinitialiser le tableau

    filteredFiles.forEach((file) => {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        // Vérifier si c'est un sous-dossier ou un fichier
        const filePath = file.webkitRelativePath;
        const relativePath = filePath.substring(targetPath.length + 1);
        const pathSegments = relativePath.split('/').filter(Boolean); // Diviser en segments et supprimer les vides
        const isDirectory = pathSegments.length === 1 && relativePath.endsWith('/'); // Vérifie si c'est un sous-dossier direct

        if (isDirectory) {
            const folderName = pathSegments[0];
            link.href = `#${targetPath}/${folderName}`;
            link.textContent = folderName;

            // Ajouter un gestionnaire d'événements pour naviguer dans le sous-dossier
            handleLinkClick(link, filteredFiles, `${targetPath}/${folderName}`);
        } else {
            link.textContent = file.name;
        }

        cellPath.appendChild(link);
        row.appendChild(cellPath);

        // Ajouter une colonne pour le nom du fichier
        const cellName = document.createElement('td');
        cellName.textContent = file.name;
        row.appendChild(cellName);

        fileTableBody.appendChild(row);
    });
}

function generateTableRows(files, firstFilePath) {
    // Convertir files en tableau
    const fileArray = Array.from(files);

    fileArray.forEach((file) => {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        if (file.webkitRelativePath) {
            // Appeler la fonction pour générer le lien
            const linkData = generateLinkForFile(file, firstFilePath);

            // Crée le href
            link.href = linkData.href;
            link.textContent = linkData.text;

            // Ajouter un gestionnaire d'événements pour naviguer dans le dossier
            handleLinkClick(link, fileArray, linkData.href.substring(1)); // Passer le chemin sans le "#"
        } else {
            link.textContent = 'Aucun chemin disponible';
        }

        cellPath.appendChild(link);
        row.appendChild(cellPath);

        // Crée une cellule pour le nom du fichier
        const cellName = document.createElement('td');
        cellName.textContent = file.name;
        row.appendChild(cellName);

        fileTableBody.appendChild(row);
    });
}


// Gestion de l'événement "change" sur l'input
fileInput.addEventListener('change', (event) => {
    const files = event.target.files;
    console.log(files)
    if (files.length > 0) {
        const firstFilePath = files[0].webkitRelativePath;
        if (firstFilePath) {
            const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
        } else {
            parentPathHeader.textContent = 'Aucun chemin disponible';
        }

        // Appeler la fonction pour générer les lignes du tableau
        generateTableRows(files, firstFilePath);
    }
});