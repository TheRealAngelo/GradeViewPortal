let timeout;

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Redirect to logout page after inactivity
            window.location.href = "../login.php";
        }, 600000); // (milliseconds)
    }

    
    window.onload = resetTimeout;
    document.onmousemove = resetTimeout;
    document.onkeypress = resetTimeout;
    document.onscroll = resetTimeout;
