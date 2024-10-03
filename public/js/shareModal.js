function openShareModal() {
    const modal = document.getElementById('shareModal');
    const overlay = document.getElementById('overlay');

    modal.style.display = 'block'; 
    overlay.style.display = 'block'; 
    
    setTimeout(() => {
        modal.classList.add('show'); 
    }, 10); 
}

function closeModal() {
    const modal = document.getElementById('shareModal');
    const overlay = document.getElementById('overlay');
    
    modal.classList.remove('show'); 
    
    modal.addEventListener('transitionend', () => {
        if (!modal.classList.contains('show')) { 
            modal.style.display = 'none';
            overlay.style.display = 'none'; 
        }
    }, { once: true }); 
}
function shareOn(platform) {
    const contactName = cardData.name;
    const jobTitle = cardData.job_title;
    const companyName = cardData.company_name;
    const shareText = `${contactName}, ${jobTitle} at ${companyName}\nCheck out this profile card!`;
    const url = window.location.href; 

    if (platform === 'linkedin') {
        const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}&title=${encodeURIComponent(shareText)}`;
        window.open(linkedinUrl, '_blank');
    } else if (platform === 'facebook') {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(shareText)}`;
        window.open(facebookUrl, '_blank');
    } else if (platform === 'twitter') {
        const twitterUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(shareText)}`;
        window.open(twitterUrl, '_blank');
    }

    closeModal(); 
}




