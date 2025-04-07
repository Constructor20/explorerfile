const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');

fileInput.addEventListener('change', (event) => {

    console.log(event.target);
    const files = event.target.files;
    // Le reset des données
    // fileTableBody.innerHTML = '';
    console.log()

    if (files.length > 0) {
        // Obtenir le chemin parent
        firstFilePath = files[0].webkitRelativePath;
        if (firstFilePath) {
            const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
            console.log(parentPathWithoutFileName)
        } else {
            parentPathHeader.textContent = 'Aucun chemin disponible';
        }
    } 

    for (const file of files) {
        const row = document.createElement('tr');
        const cellPath = document.createElement('td');
        const link = document.createElement('a');

        if (file.webkitRelativePath) {
            // Enlever le nom du fichier à la fin
            let pathWithoutFileName = file.webkitRelativePath.substring(0, file.webkitRelativePath.lastIndexOf('/'));
            // Vérifier si le chemin contient le dossier parent
            const parentFolderName = firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            if (parentFolderName === pathWithoutFileName) {
                // Si le chemin correspond uniquement au dossier parent, remplacer par "/"
                pathWithoutFileName = '/';
            } else {
                // Sinon, enlever le premier segment du chemin
                pathWithoutFileName = pathWithoutFileName.replace(/^[^\/]+\//, '/');
            }
            // Crée le href
            link.href = `#${pathWithoutFileName}`;
            // Remplis le tab du bon nom pour la redirection
            link.textContent = pathWithoutFileName;
        } else {
            // Miskine c'est pas bon
            link.textContent = 'Aucun chemin disponible';
        }

        cellPath.appendChild(link);
        row.appendChild(cellPath);
        
        // crée le tab Fichier
        const cellName = document.createElement('td');
        cellName.textContent = file.name;
        row.appendChild(cellName);


        fileTableBody.appendChild(row);
}

});