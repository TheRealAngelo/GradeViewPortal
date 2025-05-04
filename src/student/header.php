<div class="header-left"> <!--  class="header-left" -->
        <img src="../../assets/images/SVA_logo.png" alt="ADD LOGO HERE">
        <h1>Student Dashboard</h1> <!-- I WANT TO CHANGE h1 but I need to find good font pa, iz too big (thats !what she said)-->
</div>

<div class="header-right">
        <button id="showSettingButt" onClick="showSettings()"><img src="../../assets/icons/icons8-whitePerson-90.png" alt="Settings"></button>

        <div id="settingModal" class="settings-modal">
            <div class="modal-content">
                <h6 style="margin-bottom: 0.25rem; border-bottom: 1px solid rgba(0, 0, 0, 0.2); width: 100%; padding: 0.5rem 0.25rem">Settings</h6>
                <p>Switch-SY</p>
                <p>Password</p>
                <!-- Add your settings form or content here -->
                </div>
        </div>

        <script>
            const showSettingButt = document.getElementById("showSettingButt");
            const settingModal = document.getElementById("settingModal"); 

            showSettingButt.onclick = function showSettings(){
                settingModal.classList.toggle("show")
            };

            window.onclick = function(event){
                if(event.target == settingModal){
                    settingModal.classList.remove("show");
                }
            }
        </script>

<!-- This shit is for right side of header, like pfp and shit --> 
        <h1><?php echo htmlspecialchars($student_name); ?></h1>
</div>
