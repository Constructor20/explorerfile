const fileInput = document.getElementById('fileInput');
const fileTableBody = document.getElementById('fileTableBody');
const parentPathHeader = document.getElementById('parentpath');

fileInput.addEventListener('change', (event) => {
    const files = event.target.files;

    if (files.length > 0) {
        // Obtenir le chemin parent
        firstFilePath = files[0].webkitRelativePath;
        if (firstFilePath) {
            const parentPathWithoutFileName = '/' + firstFilePath.substring(0, firstFilePath.lastIndexOf('/'));
            parentPathHeader.textContent = `Chemin parent: ${parentPathWithoutFileName}`;
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

            // Ajouter un gestionnaire d'événements pour naviguer dans le dossier
            link.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut du lien
                const targetPath = pathWithoutFileName; // Récupère le chemin cible
                console.log('Chemin cible cliqué :', targetPath);

                // Filtrer les fichiers pour afficher uniquement ceux du dossier cliqué
                const filteredFiles = Array.from(files).filter((file) => {
                    const filePath = file.webkitRelativePath;
                    const fileParentPath = filePath.substring(0, filePath.lastIndexOf('/'));
                    console.log('Chemin parent du fichier :', fileParentPath, '| Chemin cible :', targetPath);
                    return fileParentPath === targetPath; // Vérifie si le chemin parent correspond au chemin cliqué
                });

                console.log('Fichiers filtrés :', filteredFiles);

                // Vérifier si des fichiers ont été trouvés
                if (filteredFiles.length === 0) {
                    console.warn('Aucun fichier trouvé pour le chemin :', targetPath);
                    parentPathHeader.textContent = `Chemin parent: ${targetPath}`;
                    return;
                }

                // Mettre à jour le tableau et le chemin parent
                parentPathHeader.textContent = `Chemin parent: ${targetPath}`;
                fileTableBody.innerHTML = ''; // Réinitialiser le tableau
            });
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