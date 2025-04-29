let timeout;

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            window.location.href = "../logout.php";
        }, 600000); // (milliseconds)
    }

    
    window.onload = resetTimeout;
    document.onmousemove = resetTimeout;
    document.onkeypress = resetTimeout;
    document.onscroll = resetTimeout;
