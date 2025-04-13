document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const announcementForm = document.getElementById('announcementForm');
    const gradesForm = document.getElementById('gradesForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (!username || !password) {
                event.preventDefault();
                alert('Please fill in both username and password.');
            }
        });
    }

    if (announcementForm) {
        announcementForm.addEventListener('submit', function(event) {
            const announcementText = document.getElementById('announcementText').value;

            if (!announcementText) {
                event.preventDefault();
                alert('Announcement text cannot be empty.');
            }
        });
    }

    if (gradesForm) {
        gradesForm.addEventListener('submit', function(event) {
            const gradeInput = document.getElementById('gradeInput').value;

            if (!gradeInput || isNaN(gradeInput)) {
                event.preventDefault();
                alert('Please enter a valid grade.');
            }
        });
    }
});