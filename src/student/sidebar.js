function vmChange(){
    const hoverVm = document.getElementById("hoverVm");
    const vmIcon = document.getElementById("vmIcon");

    hoverVm.addEventListener('mouseenter', ()=>{
        vmIcon.classList.toggle('fadeOut');
        setTimeout(() =>{
            vmIcon.src = '../../assets/icons/icons8-whiteAbout-64.png';
            vmIcon.classList.remove('fadeOut');
        }, 40);
    });

    hoverVm.addEventListener('mouseleave', ()=>{

        vmIcon.classList.toggle('fadeOut'); 
        setTimeout(() =>{
            vmIcon.src = '../../assets/icons/icons8-about-64.png';
            vmIcon.classList.remove('fadeOut');
        }, 40);
        
    });
};

function dashboardChange(){
    const hoverDashboard = document.getElementById("hoverDashboard");
    const dashboardIcon = document.getElementById("dashboardIcon");

    hoverDashboard.addEventListener('mouseenter', ()=>{
        dashboardIcon.classList.toggle('fadeOut');
        setTimeout(() =>{
            dashboardIcon.src = '../../assets/icons/icons8-whiteDashboard-96.png';
            dashboardIcon.classList.remove('fadeOut');
        }, 40);
    });

    hoverDashboard.addEventListener('mouseleave', ()=>{

        dashboardIcon.classList.toggle('fadeOut'); 
        setTimeout(() =>{
            dashboardIcon.src = '../../assets/icons/icons8-dashboard-96.png';
            dashboardIcon.classList.remove('fadeOut');
        }, 40);
        
    });
};

function gradeChange(){
    const hoverGrade = document.getElementById("hoverGrade");
    const gradeIcon = document.getElementById("gradeIcon");

    hoverGrade.addEventListener('mouseenter', ()=>{
        gradeIcon.classList.toggle('fadeOut');
        setTimeout(() =>{
            gradeIcon.src = '../../assets/icons/icons8-whiteAssessment-100.png';
            gradeIcon.classList.remove('fadeOut');
        }, 40);
    });

    hoverGrade.addEventListener('mouseleave', ()=>{

        gradeIcon.classList.toggle('fadeOut'); 
        setTimeout(() =>{
            gradeIcon.src = '../../assets/icons/icons8-assessment-100.png';
            gradeIcon.classList.remove('fadeOut');
        }, 40);
        
    });
};

function announcementChange(){
    const hoverAnnouncement = document.getElementById("hoverAnnouncement");
    const announcementIcon = document.getElementById("announcementIcon");

    hoverAnnouncement.addEventListener('mouseenter', ()=>{
        announcementIcon.classList.toggle('fadeOut');
        setTimeout(() =>{
            announcementIcon.src = '../../assets/icons/icons8-whiteAnnouncement-100.png';
            announcementIcon.classList.remove('fadeOut');
        }, 40);
    });

    hoverAnnouncement.addEventListener('mouseleave', ()=>{

        announcementIcon.classList.toggle('fadeOut'); 
        setTimeout(() =>{
            announcementIcon.src = '../../assets/icons/icons8-announcement-100.png';
            announcementIcon.classList.remove('fadeOut');
        }, 40);
        
    });
};

function setActiveLink() { 
    const links = document.querySelectorAll('a.bar'); 
    const currentPath = window.location.pathname; 
// ill figure this out pa kay ga ano sa mga icons

    /*const activeImages = {
        'vmIcon': '../../assets/icons/icons8-whiteAbout-64.png',
        'dashboardIcon': '../../assets/icons/icons8-whiteDashboard-96.png',
        'gradeIcon': '../../assets/icons/icons8-whiteAssessment-100.png',
        'announcementIcon': '../../assets/icons/icons8-whiteAnnouncement-100.png'
    };*/

    links.forEach(function(link) { 
        const linkPath = new URL(link.href).pathname; 
        if (linkPath === currentPath) { 
            link.classList.add('active'); 

            /*const img = link.querySelector('img');
            if (img && activeImages[img.id]) {
                img.src = `../../assets/icons/${activeImages[img.id]}`;
            }*/
            
        } 
    }); 
} 

document.addEventListener('DOMContentLoaded', setActiveLink);
    