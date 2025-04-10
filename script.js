const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');

function updateParentPath(files) {
    if (files.length > 0) {
        const firstFilePath = files[0].webkitRelativePath;
        if (firstFilePath) {
            const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
        } else {
            parentPathHeader.textContent = 'Aucun chemin disponible';
        }
    } else {
        parentPathHeader.textContent = 'Aucun fichier sélectionné';
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
    }
    return {
        href: `#${pathWithoutFileName}`,
        text: pathWithoutFileName,
        data: pathWithoutFileName
    };
}

// Fonction pour gérer le clic sur un lien
function selectPath(e) {
    console.log(e.target)
    const path = e.target.getAttribute("data-path")
    console.log(fileInput.files)
    const selectedfiles = Array.from(fileInput.files).filter((file)=>{
        return file.webkitRelativePath.includes(path)
    })
    console.log(selectedfiles)
    console.log(path)
    parentPathHeader.textContent = `Chemin parent: ${path}`;
    generateTableRows(selectedfiles, path)
};

function generateTableRows(files, firstFilePath) {
    fileTableBody.innerHTML = '';
    // Convertir files en tableau
    const fileArray = Array.from(files);

    fileArray.forEach((file) => {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        if (file.webkitRelativePath) {

            link.addEventListener('click', selectPath)
            // Appeler la fonction pour générer le lien
            const linkData = generateLinkForFile(file, firstFilePath);
            // Crée le href
            link.href = linkData.href;
            link.textContent = linkData.text;
            link.setAttribute("data-path", linkData.data)
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
        updateParentPath(files);
        // Appeler la fonction pour générer les lignes du tableau
        generateTableRows(files, files[0]?.webkitRelativePath);
    }
);