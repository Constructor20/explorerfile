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
                    <button type="button" class="button" id="redirectioneditpswd" onclick="redirectionPswd()">Modifier votre mot de passe</button>
                </div>
                <div class="field-row-stacked">
                    <input type="checkbox" id="showUpdate" onchange="toggleUpdateButton(this)">
                    <label for="showUpdate">Je veux modifier mes informations</label>
                </div>

                <div class="field-row-stacked" id="updateButtonContainer" style="display: none;">
                    <button type="submit" class="button" name="update_account">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>
