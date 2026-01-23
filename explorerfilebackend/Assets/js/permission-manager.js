const PermissionManager = (function () {
  "use strict";

  const state = {
    selectedPermissions: new Set(),
    expandedFolders: new Set(),
    expandedPermissionFolders: new Set(),
    treeData: null,
  };

  function countVisibleChildren(tree) {
    let count = 0;
    for (const key in tree) {
      if (!key || key === "." || key === "..") continue;
      if (state.selectedPermissions.has(key)) continue;
      count++;
    }
    return count;
  }

  function buildTreeHTML(tree, parentPath = "") {
    let html = "";

    for (const key in tree) {
      if (!key || key === "." || key === "..") continue;

      const fullPath = key;
      if (!fullPath || fullPath.trim() === "") continue;

      const isFolder = key.endsWith("/");

      if (state.selectedPermissions.has(fullPath)) {
        continue;
      }

      if (isFolder) {
        const children = tree[key];
        const visibleChildCount = countVisibleChildren(children);
        const hasChildren = visibleChildCount > 0;

        const displayName = key
          .replace(/^\/|\/$/g, "")
          .split("/")
          .pop();

        const isRootLevel = parentPath === "";
        const expandedClass = isRootLevel ? "expanded" : "";
        const toggleClass = isRootLevel ? "expanded" : "collapsed";

        const childrenHTML = hasChildren
          ? buildTreeHTML(children, fullPath)
          : "";

        html += `
          <li>
            <div class="tree-item folder" draggable="true" data-path="${fullPath}" data-type="folder">
              ${hasChildren ? `<span class="tree-toggle ${toggleClass}" data-folder-toggle></span>` : "<span class=\"tree-toggle\"></span>"}
              <img src="../../icon/folder2.png" class="tree-icon" alt="dossier">
              <span class="tree-label">${displayName}</span>
            </div>
            ${hasChildren ? `<ul class="file-tree ${expandedClass}">${childrenHTML}</ul>` : ""}
          </li>
        `;
      } else {
        const displayName = key.split("/").pop();

        html += `
          <li>
            <div class="tree-item file" draggable="true" data-path="${fullPath}" data-type="file">
              <span class="tree-toggle"></span>
              <img src="../../icon/file.png" class="tree-icon" alt="fichier">
              <span class="tree-label">${displayName}</span>
            </div>
          </li>
        `;
      }
    }

    return html;
  }

  function getAllItemsInFolder(folderPath) {
    const items = [];

    function searchInTree(subtree, parentPath) {
      for (const key in subtree) {
        if (!key || key === "." || key === "..") continue;

        const fullPath = key;
        if (!fullPath || fullPath.trim() === "") continue;

        const isFolder = key.endsWith("/");

        if (parentPath && parentPath !== "/") {
          const prefix = parentPath.endsWith("/")
            ? parentPath
            : parentPath + "/";
          if (!fullPath.startsWith(prefix)) continue;
        }

        if (isFolder) {
          if (folderPath === "" || fullPath.startsWith(folderPath)) {
            items.push(fullPath);
            searchInTree(subtree[key], fullPath);
          }
        } else {
          if (folderPath === "" || fullPath.startsWith(folderPath)) {
            items.push(fullPath);
          }
        }
      }
    }

    searchInTree(state.treeData, "");
    return items.filter((item) => item && item.trim() !== "");
  }

  function pathsToTree(paths) {
    const tree = {};

    paths.forEach((path) => {
      path = path.replace(/^\//, "");
      if (!path || path.trim() === "") return;

      const isFolder = path.endsWith("/");
      const parts = path
        .split("/")
        .filter((part) => part && part.trim() !== "");

      if (parts.length === 0) return;

      let current = tree;
      parts.forEach((part, index) => {
        const isLast = index === parts.length - 1;

        if (isFolder && isLast) {
          const dirKey = part + "/";
          if (!current[dirKey]) current[dirKey] = {};
        } else if (isLast) {
          current[part] = part;
        } else {
          const dirKey = part + "/";
          if (!current[dirKey]) current[dirKey] = {};
          current = current[dirKey];
        }
      });
    });

    return tree;
  }

  function buildPermissionsTreeHTML(tree, level = 0, parentPath = "") {
    let html =
      "<ul class=\"file-tree\" style=\"padding-left: " + level * 20 + "px;\">";

    for (const key in tree) {
      const isFolder = key.endsWith("/");
      const fullTreePath = parentPath ? parentPath + key : key;
      const originalPath = "/" + fullTreePath;

      if (isFolder) {
        const children = tree[key];
        const hasChildren = Object.keys(children).length > 0;
        const displayName = key.replace(/^\/|\/$/g, "");

        html += `
          <li>
            <div class="tree-item folder" draggable="true" data-path="${originalPath}" data-type="folder">
              ${hasChildren ? "<span class=\"tree-toggle collapsed\" data-permission-toggle></span>" : "<span class=\"tree-toggle\"></span>"}
              <img src="../../icon/folder2.png" class="tree-icon" alt="dossier">
              <span class="tree-label">${displayName}</span>
              <button type="button" class="remove-btn" draggable="false" data-path="${originalPath}" style="margin-left: auto;">✕</button>
            </div>
            ${hasChildren ? buildPermissionsTreeHTML(children, level + 1, fullTreePath) : ""}
          </li>
        `;
      } else {
        const displayName = key.split("/").pop();

        html += `
          <li>
            <div class="tree-item file" draggable="true" data-path="${originalPath}" data-type="file">
              <span class="tree-toggle"></span>
              <img src="../../icon/file.png" class="tree-icon" alt="fichier">
              <span class="tree-label">${displayName}</span>
              <button type="button" class="remove-btn" draggable="false" data-path="${originalPath}" style="margin-left: auto;">✕</button>
            </div>
          </li>
        `;
      }
    }

    html += "</ul>";
    return html;
  }

  function toggleFolder(toggleElement) {
    const treeItem = toggleElement.closest(".tree-item");
    const nextUl = treeItem.nextElementSibling;
    const folderPath = treeItem.dataset.path;

    if (nextUl && nextUl.tagName === "UL") {
      toggleElement.classList.toggle("collapsed");
      toggleElement.classList.toggle("expanded");
      nextUl.classList.toggle("expanded");

      if (toggleElement.classList.contains("expanded")) {
        state.expandedFolders.add(folderPath);
      } else {
        state.expandedFolders.delete(folderPath);
      }
    }
  }

  function togglePermissionFolder(toggleElement) {
    const treeItem = toggleElement.closest(".tree-item");
    const nextUl = treeItem.nextElementSibling;
    const folderPath = treeItem.dataset.path;

    if (nextUl && nextUl.tagName === "UL") {
      toggleElement.classList.toggle("collapsed");
      toggleElement.classList.toggle("expanded");
      nextUl.classList.toggle("expanded");

      if (toggleElement.classList.contains("expanded")) {
        state.expandedPermissionFolders.add(folderPath);
      } else {
        state.expandedPermissionFolders.delete(folderPath);
      }
    }
  }

  function saveCurrentExpandedState() {
    const availableToggles = document.querySelectorAll(
      "#fileTree .tree-toggle[data-folder-toggle].expanded",
    );
    availableToggles.forEach((toggle) => {
      const treeItem = toggle.closest(".tree-item");
      if (treeItem && treeItem.dataset.path) {
        state.expandedFolders.add(treeItem.dataset.path);
      }
    });

    const permissionToggles = document.querySelectorAll(
      ".drop-zone .tree-toggle[data-permission-toggle].expanded",
    );
    permissionToggles.forEach((toggle) => {
      const treeItem = toggle.closest(".tree-item");
      if (treeItem && treeItem.dataset.path) {
        state.expandedPermissionFolders.add(treeItem.dataset.path);
      }
    });
  }

  function restoreExpandedFolders() {
    state.expandedFolders.forEach((folderPath) => {
      const toggleElement = document.querySelector(
        `#fileTree .tree-item[data-path="${CSS.escape(folderPath)}"] .tree-toggle[data-folder-toggle]`,
      );
      if (toggleElement) {
        const treeItem = toggleElement.closest(".tree-item");
        const nextUl = treeItem.nextElementSibling;
        if (nextUl && nextUl.tagName === "UL") {
          toggleElement.classList.remove("collapsed");
          toggleElement.classList.add("expanded");
          nextUl.classList.add("expanded");
        }
      }
    });
  }

  function restorePermissionExpandedFolders() {
    state.expandedPermissionFolders.forEach((folderPath) => {
      const toggleElement = document.querySelector(
        `.drop-zone .tree-item[data-path="${CSS.escape(folderPath)}"] .tree-toggle[data-permission-toggle]`,
      );
      if (toggleElement) {
        const treeItem = toggleElement.closest(".tree-item");
        const nextUl = treeItem.nextElementSibling;
        if (nextUl && nextUl.tagName === "UL") {
          toggleElement.classList.remove("collapsed");
          toggleElement.classList.add("expanded");
          nextUl.classList.add("expanded");
        }
      }
    });
  }

  function attachDragEvents(item) {
    item.addEventListener("dragstart", (e) => {
      const path = item.dataset.path;
      const type = item.dataset.type;

      if (type === "folder") {
        const allItems = getAllItemsInFolder(path);
        e.dataTransfer.setData("text/plain", JSON.stringify(allItems));
        e.dataTransfer.setData("folder-path", path);
      } else {
        e.dataTransfer.setData("text/plain", path);
      }

      e.dataTransfer.effectAllowed = "copy";
      item.classList.add("dragging");
    });

    item.addEventListener("dragend", () => {
      item.classList.remove("dragging");
    });
  }

  function updateAvailableFiles() {
    const treeContainer = document.getElementById("fileTree");

    if (!treeContainer) {
      console.error("fileTree element not found!");
      return;
    }

    saveCurrentExpandedState();
    const treeContent = buildTreeHTML(state.treeData, "");
    const treeHTML = `<ul class="file-tree">${treeContent}</ul>`;

    if (!treeContent.trim()) {
      treeContainer.innerHTML =
        "<p style=\"color: #666; padding: 20px; text-align: center;\">Aucun fichier disponible dans le répertoire</p>";
    } else {
      treeContainer.innerHTML = treeHTML;
    }

    restoreExpandedFolders();
    document
      .querySelectorAll("#fileTree .tree-item[draggable=\"true\"]")
      .forEach(attachDragEvents);
  }

  function updateDropZone() {
    saveCurrentExpandedState();
    const dropZone = document.getElementById("dropZone");
    const permissionsInput = document.getElementById("permissions_input");

    if (!dropZone || !permissionsInput) return;

    // Remove existing click handler to prevent duplicate handlers
    const existingHandler = dropZone._removeClickHandler;
    if (existingHandler) {
      dropZone.removeEventListener("click", existingHandler);
    }

    if (state.selectedPermissions.size === 0) {
      dropZone.innerHTML =
        "<p>Glissez les fichiers ou dossiers ici pour donner l'accès</p>";
    } else {
      const pathsArray = Array.from(state.selectedPermissions);
      const permissionsTree = pathsToTree(pathsArray);
      const html = buildPermissionsTreeHTML(permissionsTree, 0, "");
      dropZone.innerHTML = html;
      restorePermissionExpandedFolders();

      const removeClickHandler = function (e) {
        const btn = e.target.closest(".remove-btn");
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const path = btn.dataset.path;

        if (!path || path.trim() === "") {
          return;
        }

        const normalizedPath = path.trim();
        const toRemove = [];

        if (state.selectedPermissions.has(normalizedPath)) {
          toRemove.push(normalizedPath);
        }

        const prefix = normalizedPath.endsWith("/")
          ? normalizedPath
          : normalizedPath + "/";
        state.selectedPermissions.forEach((p) => {
          if (p && p !== normalizedPath && p.startsWith(prefix)) {
            toRemove.push(p);
          }
        });

        toRemove.forEach((p) => {
          state.selectedPermissions.delete(p);
        });

        const pathParts = normalizedPath.split("/").filter((p) => p && p.trim() !== "");
        let currentPath = "";
        for (let i = 0; i < pathParts.length; i++) {
          currentPath += pathParts[i] + "/";
          const checkPath = "/" + currentPath;

          if (state.selectedPermissions.has(checkPath)) {
            const parentPrefix = checkPath.endsWith("/") ? checkPath : checkPath + "/";
            const hasOtherDescendants = Array.from(state.selectedPermissions).some(
              (p) => p && p !== checkPath && p.startsWith(parentPrefix)
            );

            if (!hasOtherDescendants) {
              state.selectedPermissions.delete(checkPath);
            }
          }
        }

        updateDropZone();
        updateAvailableFiles();
      };

      // Store handler reference for later removal
      dropZone._removeClickHandler = removeClickHandler;
      dropZone.addEventListener("click", removeClickHandler);

      dropZone
        .querySelectorAll(".tree-item[draggable=\"true\"]")
        .forEach(attachDragEvents);
    }

    permissionsInput.value = JSON.stringify(
      Array.from(state.selectedPermissions),
    );
  }

  function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = "copy";
    e.currentTarget.classList.add("drag-over");
  }

  function handleDragLeave(e) {
    e.currentTarget.classList.remove("drag-over");
  }

  function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove("drag-over");

    const data = e.dataTransfer.getData("text/plain");

    try {
      const paths = JSON.parse(data);
      if (Array.isArray(paths)) {
        paths.forEach((path) => {
          if (path && !state.selectedPermissions.has(path)) {
            state.selectedPermissions.add(path);
          }
        });
      }
    } catch {
      if (data && !state.selectedPermissions.has(data)) {
        state.selectedPermissions.add(data);
      }
    }

    updateDropZone();
    updateAvailableFiles();
  }

  function handleClick(e) {
    if (e.target.hasAttribute("data-folder-toggle")) {
      e.preventDefault();
      e.stopPropagation();
      toggleFolder(e.target);
    }
    if (e.target.hasAttribute("data-permission-toggle")) {
      e.preventDefault();
      e.stopPropagation();
      togglePermissionFolder(e.target);
    }
  }

  function init(treeData, existingPermissions) {
    state.treeData = treeData;

    state.selectedPermissions = new Set(existingPermissions);

    const dropZone = document.getElementById("dropZone");
    if (dropZone) {
      dropZone.addEventListener("dragover", handleDragOver);
      dropZone.addEventListener("dragleave", handleDragLeave);
      dropZone.addEventListener("drop", handleDrop);
    }

    document.addEventListener("click", handleClick);

    updateDropZone();
    updateAvailableFiles();
  }

  function getPermissions() {
    return Array.from(state.selectedPermissions);
  }

  return {
    init,
    getPermissions,
    updateDropZone,
    updateAvailableFiles,
  };
})();
