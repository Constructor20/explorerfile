    <?php
    include '../connectdb.php';

    $sql = "SELECT id, username, email FROM userdata";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php foreach ($users as $user): ?>
        <div class="window">
            <div class="toggle-header">
                <span class="arrow">▶</span>
                <span class="username"><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
            <div class="window-body" style="display: none;">
                <form action="../compte/compteadmininc.php" method="POST">
                    <div class="field-row-stacked">
                        <label>Nom</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" id="username">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    </div>
                    <div class="field-row-stacked">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" id="email">
                    </div>
                    <div class="field-row-stacked">
                        <button type="button" class="button" id="redirectioneditpswd" onclick="redirectionPswd(baseDir)">Modifier votre mot de passe</button>
                    </div>
                    <div class="field-row-stacked">
                        <input type="checkbox" id="showUpdate<?php echo $user['id']?>" onchange="toggleUpdateButtonAdmin(this)">
                        <label for="showUpdate<?php echo $user['id']?>">Je veux modifier mes informations</label>
                    </div>

                    <div class="field-row-stacked" id="updateButtonContainer<?php echo $user['id']?>" style="display: none;">
                        <button type="submit" class="button" name="update_account" data="baba" id="<?php echo $user['id']?>">Mettre à jour</button>
                    </div>
                </form>
                <form action="edit/tableright.php" method="POST">
                    <div class="field-row-stacked">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <button type="submit" class="button" id="redirectionedituser" onclick="redirectioneditUser(baseDir)">Modifier les droits de l'utilisateurs</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
