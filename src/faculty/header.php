<div class="header-left">
        <img src="../../assets/images/SVA_logo.png" alt="ADD LOGO HERE">
        <h1>Faculty Dashboard</h1> <!-- I WANT TO CHANGE h1 but I need to find good font pa, iz too big (thats !what she said)-->
</div>
<div class="header-right">
        <!--INSERT MODAL CODE-->
        <div class="header-right">
    <button id="showSettingButt" onClick="showSettings()"><img src="../../assets/icons/icons8-whitePerson-90.png" alt="Settings"></button>

    <!-- Settings Modal -->
    <div id="settingModal" class="settings-modal">
        <div class="modal-content">
            <div class="modal-content-title">
                <h6>Settings</h6>
            </div>
            <div class="modal-content-selects">
                <p id="switchSY">Switch-SY</p>
                <p id="editPassword">Password</p>
            </div>  
        </div>
    </div>

    <!-- Switch-SY Modal -->
    <div id="switchSYModal" class="settings-modal">
        <div class="modal-content">
            <div class="modal-content-title">
                <h6 style="margin-bottom: 1rem;">Switch School Year</h6>
            </div>
            <div class="modal-content-selects">
                <form method="POST" action="switch_sy.php">
                    <label for="school_year">Select School Year:</label>
                    <select id="school_year" name="school_year" required>
                        <?php
                        $school_years = $conn->query("SELECT id, CONCAT(year_start, '-', year_end) AS school_year FROM school_year ORDER BY year_start DESC");
                        while ($row = $school_years->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['school_year']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Switch</button>
                </form>
            </div>
        </div>
    </div>

    <!-- update_password Modal -->
    <div id="editPasswordModal" class="settings-modal">
        <div class="modal-content">
            <div class="modal-content-title">
                <h6>Edit Password</h6>
            </div>
            <div class="modal-content-selects">
                <form method="POST" action="update_password.php">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
    <!-- FUNCTION NIYA -->
    <script>
        function toggleModal(modalId, show) {
            const modal = document.getElementById(modalId);
            if (show) {
                modal.classList.add("show");
            } else {
                modal.classList.remove("show");
                if (modalId === "editPasswordModal") {
                    resetEditPasswordForm();
                }
            }
        }

        function resetEditPasswordForm() {
            const form = document.querySelector("#editPasswordModal form");
            if (form) {
                form.reset();
            }
        }

        document.getElementById("showSettingButt").onclick = () => toggleModal("settingModal", true);
        document.getElementById("switchSY").onclick = () => {
            toggleModal("settingModal", false);
            toggleModal("switchSYModal", true);
        };

        document.getElementById("editPassword").onclick = () => {
            toggleModal("settingModal", false);
            toggleModal("editPasswordModal", true);
        };

        window.onclick = (event) => {
            if (event.target.classList.contains("settings-modal")) {
                event.target.classList.remove("show");
                // Reset the form fields if the modal is being closed
                if (event.target.id === "editPasswordModal") {
                    resetEditPasswordForm();
                }
            }
        };
    </script>
        <!--END OF MODAL CODE-->
        <!-- This shit is for right side of header, like pfp and shit --> 
        <h1><?php echo htmlspecialchars($faculty_name); ?></h1>
</div>