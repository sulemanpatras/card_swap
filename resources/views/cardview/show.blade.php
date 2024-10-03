    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Card</title>
    <script src="{{ asset('js/shareModal.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #f1f1f1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.card {
    width: 90%;
    max-width: 350px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
}

.card-header {
    background-size: cover;
    background-position: center;
    height: 100px;
}

.card-header img {
    width: 80px;
    height: 80px;
    border: 3px solid #fff;
    margin-top: 73px;
    z-index: 1;
    margin-right: 210px;
}


.card-body {
    padding: 20px;
}

.card-body h2 {
    margin-top:56px;
    font-size: 1.5rem; 
    color: #000000;
    display: flex;
}

.card-body p {
    color: #000;
    font-size: 0.9rem;
    margin-top: 11px;    
    margin-right: 187px; 
    margin-bottom: 10px; 
    margin-left: -12px;  
}

.card-body strong {
    margin-left: -55px;
}

.contact-info {
    margin-top: 20px;
    margin-bottom: 20px;
}

.contact-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    margin-bottom: 15px;
    background-color: #F9F9F9;
}

.contact-item i {
    color: #666;
    cursor: pointer;
}

.contact-item span {
    flex-grow: 1;
    margin-left: 10px;
    text-align: left;
    font-size: 0.9rem;
}

.save-btn {
    width: 100%;
    max-width: 200px;
    padding: 12px;
    background-color: #232323;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 0.3s;
    cursor: pointer;
    text-decoration: none;
}

.save-btn svg {
    margin-right: 5px; 
    margin-left: -4px; 
}

.save-btn i {
    margin-right: 5px;
}


.save-btn:hover {
    background-color: #444;
}


.button-container {
    display: flex;
    align-items: flex-start; 
    justify-content: center; 
    gap: 10px; 
}

.bi.bi-share-fill {
    margin-left: 7px;
    margin-top: 8px;
    height: 33px;
    cursor: pointer;

}


.gender {
    display: flex;
    align-items: center; 
    opacity: 0.6;
    padding-left: 13px;
    gap:4px;
}

.not-specified {
    white-space: nowrap; 
}

.share-contact{
    white-space: nowrap; 
    margin-top:-20px;

}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgb(209, 209, 209);
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    transition: opacity 0.3s ease, transform 0.3s ease;
    opacity: 0;
    z-index: 1000;
}

.modal.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.modal-content {
    position: relative; 
}

.modal-content button {
    margin-top: -108px;
    position: absolute;
    margin-bottom: 16px;
    background: #ffffff;
    border: none;
    border-radius: 18px;
    font-size: 27px;
    cursor: pointer;
    margin-left: 85px;
}


.card-footer {
    background-color: #ffffff;
    padding: 15px;
    text-align: left;
}

.card-footer p {
    font-size: 0.9rem;
    color: #000000;
    margin-bottom: 10px;
    margin-left: 10px;
}

.social-icons {
    display: flex;
    justify-content: flex-start; 
    gap: 8px;
    margin-top: 20px;
    margin-left: 45px;
}

.social-icons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; 
}

.social-icons p {
    flex: 1 0 20%; 
    max-width: 20%; 
    text-align: center; 
}

.social-icons i {
    font-size: 20px;
    color: #333;
    cursor: pointer;
}


.social-icons i:hover {
    color: #000;
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff; 
    padding: 30px;
    border-radius: 10px; 
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); 
    transition: opacity 0.3s ease, transform 0.3s ease;
    opacity: 0;
    z-index: 1000;
}

.modal.show {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1.05); 
}


.modal-content h2 {
    margin: 0;
    font-size: 24px; 
    color: #161616;
}

.modal-content p {
    font-size: 16px; 
    color: #555; 
    margin: 10px 0 20px 0; 
}


.close-icon {
    position: absolute;
    top: -30px;
    right: -30px;
    cursor: pointer;
    color: black;
}

.share-icons {
    display: flex;
    gap: 33px; 
    margin-top: 20px; 
}

.share-icons svg {
    cursor: pointer; 
    width: 32px; 
    height: 32px;
}

.name-preferred {
    display: flex; 
    align-items: center; 
    white-space: nowrap;
}

.save-icon{
    border-radius: 16px; 
    margin-right:10px;
}

.job-container {
    display: flex;
    flex-direction: column;
    align-items: baseline;
    justify-content: center;
}


.job-title {
    font-size: 18px; 
    text-align: center; 
    color: #000000;
    margin-bottom: 5px; 
    word-wrap: break-word;
}

.company-name {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    color: #000000;
    word-wrap: break-word; 
}

.link-website{
    text-decoration: none;
    color:#000;
}

.blur {
    filter: blur(5px);
    transition: filter 0.3s ease;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px); 
    display: none; 
    z-index: 999; 
}

@media screen and (max-width: 768px) {
    .card-body h2 {
        font-size: 1.2rem;
    }
    .card-body p, .contact-item span, .card-footer p {
        font-size: 0.8rem;
    }
    .save-btn {
        max-width: 150px;
        font-size: 0.9rem;
    }
}

@media screen and (max-width: 480px) {
    .card {
        width: 100%;
        max-width: 320px; 
    }
    .card-header img {
        width: 70px;
        height: 70px;
    }
    .social-icons i {
        font-size: 18px;
    }
}

    </style>
</head>
<body>
    
<div class="card">
    <div class="card-header" style="background-image: url('{{ $cardData['cover_photo'] }}'); ">

        @if ( $cardData['card_image'] )
        <img src="{{ $cardData['card_image'] }}" alt="Card Image" style="max-width: 300px;">
    @endif
    
    </div>
    <div class="card-body">
        <h2>{{ $cardData['name'] }}</h2>
        <p class="gender">
            <span class="not-specified">
                {{ $cardData['pronoun'] }}
            </span>
            
            @if(!empty($cardData['preferred_name']))
            <span class="name-preferred">|  </span>
            @endif
            <span class="name-preferred">{{$cardData['preferred_name']}}</span>
            
            @if(!empty($cardData['preferred_name']))
                <span class="name-preferred">Preferred</span>
            @endif
        </p>
        
        
        
        <div class="job-container">
            <div class="job-title">{{ $cardData['job_title'] }} at</div>
            <div class="company-name">{{ $cardData['company_name'] }}</div>
        </div>
        
                   
        <div class="contact-info">
            @foreach ($cardData['card_details'] as $detail)
                @if($detail['type'] === 'contact' && $detail['title'] === 'phone')
                    <div class="contact-item">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="26" height="26" rx="5" fill="black"/>
                            <path d="M20.2222 13.0001C20.2218 11.0847 19.4608 9.24794 18.1065 7.89359C16.7521 6.53925 14.9153 5.77821 13 5.77783V7.22228C14.1426 7.22254 15.2595 7.56146 16.2096 8.19622C17.1597 8.83098 17.9003 9.7331 18.3379 10.7886C18.6284 11.4897 18.7778 12.2412 18.7778 13.0001H20.2222ZM5.77777 11.5556V7.9445C5.77777 7.75295 5.85386 7.56925 5.9893 7.43381C6.12475 7.29837 6.30845 7.22228 6.49999 7.22228H10.1111C10.3026 7.22228 10.4863 7.29837 10.6218 7.43381C10.7572 7.56925 10.8333 7.75295 10.8333 7.9445V10.8334C10.8333 11.0249 10.7572 11.2086 10.6218 11.3441C10.4863 11.4795 10.3026 11.5556 10.1111 11.5556H8.66666C8.66666 13.088 9.27539 14.5576 10.3589 15.6411C11.4425 16.7247 12.9121 17.3334 14.4444 17.3334V15.8889C14.4444 15.6974 14.5205 15.5137 14.656 15.3783C14.7914 15.2428 14.9751 15.1667 15.1667 15.1667H18.0555C18.2471 15.1667 18.4308 15.2428 18.5662 15.3783C18.7017 15.5137 18.7778 15.6974 18.7778 15.8889V19.5001C18.7778 19.6916 18.7017 19.8753 18.5662 20.0107C18.4308 20.1462 18.2471 20.2223 18.0555 20.2223H14.4444C9.65827 20.2223 5.77777 16.3418 5.77777 11.5556Z" fill="white"/>
                            <path d="M17.0033 11.3419C17.2213 11.8675 17.3334 12.431 17.3333 13.0001H16.0333C16.0334 12.6017 15.955 12.2072 15.8026 11.8392C15.6502 11.4711 15.4268 11.1367 15.1451 10.855C14.8634 10.5733 14.529 10.3499 14.1609 10.1975C13.7929 10.045 13.3984 9.96665 13 9.96675V8.66675C13.857 8.66679 14.6948 8.92097 15.4074 9.39713C16.1199 9.8733 16.6753 10.5501 17.0033 11.3419Z" fill="white"/>
                            </svg>
                        <span>{{ $detail['value'] }}</span>
                        <a href="tel:{{ $detail['value'] }}" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_417_1175)">
                                <path d="M11 6C11.2652 6 11.5196 6.10536 11.7071 6.29289C11.8946 6.48043 12 6.73478 12 7C12 7.26522 11.8946 7.51957 11.7071 7.70711C11.5196 7.89464 11.2652 8 11 8H5V19H16V13C16 12.7348 16.1054 12.4804 16.2929 12.2929C16.4804 12.1054 16.7348 12 17 12C17.2652 12 17.5196 12.1054 17.7071 12.2929C17.8946 12.4804 18 12.7348 18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11ZM20 3C20.2652 3 20.5196 3.10536 20.7071 3.29289C20.8946 3.48043 21 3.73478 21 4V9C21 9.26522 20.8946 9.51957 20.7071 9.70711C20.5196 9.89464 20.2652 10 20 10C19.7348 10 19.4804 9.89464 19.2929 9.70711C19.1054 9.51957 19 9.26522 19 9V6.414L10.707 14.707C10.5184 14.8892 10.2658 14.99 10.0036 14.9877C9.7414 14.9854 9.49059 14.8802 9.30518 14.6948C9.11977 14.5094 9.0146 14.2586 9.01233 13.9964C9.01005 13.7342 9.11084 13.4816 9.293 13.293L17.586 5H15C14.7348 5 14.4804 4.89464 14.2929 4.70711C14.1054 4.51957 14 4.26522 14 4C14 3.73478 14.1054 3.48043 14.2929 3.29289C14.4804 3.10536 14.7348 3 15 3H20Z" fill="black"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_417_1175">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                        </a>
                    </div>
                @endif
        
                @if($detail['type'] === 'contact' && $detail['title'] === 'email')
                    <div class="contact-item">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="26" height="26" rx="5" fill="black"/>
                            <g clip-path="url(#clip0_417_1193)">
                            <path d="M20.2845 12.477V8.21837C20.2845 7.43762 19.6457 6.79883 18.865 6.79883H7.50865C6.7279 6.79883 6.09621 7.43762 6.09621 8.21837L6.08911 16.7356C6.08911 17.5164 6.7279 18.1551 7.50865 18.1551H15.3161V14.6063C15.3161 13.4281 16.2672 12.477 17.4454 12.477H20.2845ZM13.1868 13.1868L7.50865 9.63791V8.21837L13.1868 11.7672L18.865 8.21837V9.63791L13.1868 13.1868Z" fill="white"/>
                            <path d="M20.2845 15.3161V18.1552C20.2845 18.9359 19.6457 19.5747 18.865 19.5747C18.0842 19.5747 17.4454 18.9359 17.4454 18.1552V14.9612C17.4454 14.7625 17.6016 14.6063 17.8003 14.6063C17.999 14.6063 18.1552 14.7625 18.1552 14.9612V18.1552H19.5747V14.9612C19.5747 14.4906 19.3878 14.0393 19.055 13.7065C18.7222 13.3737 18.2709 13.1868 17.8003 13.1868C17.3297 13.1868 16.8784 13.3737 16.5456 13.7065C16.2128 14.0393 16.0259 14.4906 16.0259 14.9612V18.1552C16.0259 19.7238 17.2964 20.9942 18.865 20.9942C20.4336 20.9942 21.704 19.7238 21.704 18.1552V15.3161H20.2845Z" fill="white"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_417_1193">
                            <rect width="17.0345" height="17.0345" fill="white" transform="translate(5.37933 5.37927)"/>
                            </clipPath>
                            </defs>
                            </svg>                      
                            
                            <span>{{ $detail['value'] }}</span>
                        <a href="mailto:{{ $detail['value'] }}" target="_blank">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_417_1175)">
                                <path d="M11 6C11.2652 6 11.5196 6.10536 11.7071 6.29289C11.8946 6.48043 12 6.73478 12 7C12 7.26522 11.8946 7.51957 11.7071 7.70711C11.5196 7.89464 11.2652 8 11 8H5V19H16V13C16 12.7348 16.1054 12.4804 16.2929 12.2929C16.4804 12.1054 16.7348 12 17 12C17.2652 12 17.5196 12.1054 17.7071 12.2929C17.8946 12.4804 18 12.7348 18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11ZM20 3C20.2652 3 20.5196 3.10536 20.7071 3.29289C20.8946 3.48043 21 3.73478 21 4V9C21 9.26522 20.8946 9.51957 20.7071 9.70711C20.5196 9.89464 20.2652 10 20 10C19.7348 10 19.4804 9.89464 19.2929 9.70711C19.1054 9.51957 19 9.26522 19 9V6.414L10.707 14.707C10.5184 14.8892 10.2658 14.99 10.0036 14.9877C9.7414 14.9854 9.49059 14.8802 9.30518 14.6948C9.11977 14.5094 9.0146 14.2586 9.01233 13.9964C9.01005 13.7342 9.11084 13.4816 9.293 13.293L17.586 5H15C14.7348 5 14.4804 4.89464 14.2929 4.70711C14.1054 4.51957 14 4.26522 14 4C14 3.73478 14.1054 3.48043 14.2929 3.29289C14.4804 3.10536 14.7348 3 15 3H20Z" fill="black"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_417_1175">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>             
                            
                            </a>
                    </div>
                @endif
                @if($detail['type'] === 'social' && $detail['title'] === 'website')
                @php
		                    $url = $detail['value'];
		                    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		                        $url = "https://" . $url;
		                    }
		                @endphp
                    <div class="contact-item">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="26" height="26" rx="5" fill="black"/>
                            <path d="M13 20.1864C11.5787 20.1864 10.1893 19.7649 9.00746 18.9753C7.82566 18.1856 6.90456 17.0633 6.36064 15.7501C5.81671 14.437 5.6744 12.992 5.95169 11.598C6.22898 10.2039 6.91342 8.92345 7.91846 7.91841C8.9235 6.91337 10.204 6.22893 11.598 5.95164C12.9921 5.67435 14.437 5.81667 15.7501 6.36059C17.0633 6.90451 18.1857 7.82561 18.9753 9.00742C19.765 10.1892 20.1864 11.5786 20.1864 13C20.1843 14.9053 19.4265 16.732 18.0793 18.0792C16.732 19.4265 14.9053 20.1843 13 20.1864ZM13 7.74324C12.4117 8.43184 11.9521 9.22073 11.6433 10.0722H14.3595C14.1837 9.59071 13.9608 9.12778 13.694 8.69012C13.4888 8.35619 13.2567 8.03953 13 7.74324ZM11.2374 11.6692C11.1056 12.5515 11.1056 13.4485 11.2374 14.3308H14.764C14.8958 13.4485 14.8958 12.5515 14.764 11.6692H11.2374ZM7.41058 13C7.41057 13.4485 7.46487 13.8953 7.57228 14.3308H9.62307C9.51215 13.4471 9.51215 12.5529 9.62307 11.6692H7.57228C7.46487 12.1046 7.41057 12.5515 7.41058 13ZM13 18.2567C13.5884 17.5681 14.048 16.7792 14.3568 15.9278H11.6419C11.8177 16.4092 12.0406 16.8722 12.3073 17.3098C12.5122 17.6437 12.7438 17.9604 13 18.2567ZM16.3756 14.3308H18.4264C18.6421 13.4567 18.6421 12.5433 18.4264 11.6692H16.377C16.4879 12.5529 16.4879 13.4471 16.377 14.3308H16.3756ZM17.7577 15.9278H16.0416C15.8018 16.7327 15.4549 17.5017 15.0102 18.2141C16.1509 17.7714 17.1159 16.9687 17.759 15.9278H17.7577ZM10.9898 18.2141C10.5451 17.5017 10.1982 16.7327 9.95844 15.9278H8.24102C8.88412 16.9687 9.8492 17.7714 10.9898 18.2141ZM8.24102 10.0722H9.95844C10.1982 9.26728 10.5451 8.49827 10.9898 7.78583C9.8492 8.22857 8.88412 9.03128 8.24102 10.0722ZM15.0102 7.78583C15.4549 8.49827 15.8018 9.26728 16.0416 10.0722H17.759C17.1159 9.03128 16.1509 8.22857 15.0102 7.78583Z" fill="white"/>
                            </svg>
                            <span>
                                <a href="{{ $url }}" target="_blank" class="link-website">{{ $url }}</a>
                            </span>
                             <a href="{{$url}}" target="_blank">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_417_1175)">
                                    <path d="M11 6C11.2652 6 11.5196 6.10536 11.7071 6.29289C11.8946 6.48043 12 6.73478 12 7C12 7.26522 11.8946 7.51957 11.7071 7.70711C11.5196 7.89464 11.2652 8 11 8H5V19H16V13C16 12.7348 16.1054 12.4804 16.2929 12.2929C16.4804 12.1054 16.7348 12 17 12C17.2652 12 17.5196 12.1054 17.7071 12.2929C17.8946 12.4804 18 12.7348 18 13V19C18 19.5304 17.7893 20.0391 17.4142 20.4142C17.0391 20.7893 16.5304 21 16 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V8C3 7.46957 3.21071 6.96086 3.58579 6.58579C3.96086 6.21071 4.46957 6 5 6H11ZM20 3C20.2652 3 20.5196 3.10536 20.7071 3.29289C20.8946 3.48043 21 3.73478 21 4V9C21 9.26522 20.8946 9.51957 20.7071 9.70711C20.5196 9.89464 20.2652 10 20 10C19.7348 10 19.4804 9.89464 19.2929 9.70711C19.1054 9.51957 19 9.26522 19 9V6.414L10.707 14.707C10.5184 14.8892 10.2658 14.99 10.0036 14.9877C9.7414 14.9854 9.49059 14.8802 9.30518 14.6948C9.11977 14.5094 9.0146 14.2586 9.01233 13.9964C9.01005 13.7342 9.11084 13.4816 9.293 13.293L17.586 5H15C14.7348 5 14.4804 4.89464 14.2929 4.70711C14.1054 4.51957 14 4.26522 14 4C14 3.73478 14.1054 3.48043 14.2929 3.29289C14.4804 3.10536 14.7348 3 15 3H20Z" fill="black"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_417_1175">
                                    <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                    </svg>           
                            
                            </a>
                    </div>
                @endif
            @endforeach
        </div>
        
        
   
        </div>

        <div class="button-container">
            <a class="save-btn" href="{{ route('download.contact', ['card' => $cardData['id']]) }}" download="contact.vcf">
                <img src="{{ asset('image/download.png') }}" class="save-icon">
                Save Contact
            </a>
            

            <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg" class="bi bi-share-fill" viewBox="0 0 16 16" onclick="openShareModal()">
                <circle cx="19.5" cy="19.5" r="19.5" fill="#232323"/>
                <path d="M23.5345 30.2586C22.6939 30.2586 21.9795 29.9644 21.3911 29.3761C20.8028 28.7877 20.5086 28.0732 20.5086 27.2327C20.5086 27.1151 20.517 26.993 20.5338 26.8666C20.5506 26.7402 20.5758 26.6269 20.6095 26.5267L13.4987 22.3914C13.2129 22.6435 12.8935 22.8412 12.5405 22.9844C12.1875 23.1276 11.8177 23.1989 11.431 23.1982C10.5905 23.1982 9.87606 22.9041 9.28769 22.3157C8.69933 21.7273 8.40515 21.0129 8.40515 20.1724C8.40515 19.3319 8.69933 18.6174 9.28769 18.0291C9.87606 17.4407 10.5905 17.1465 11.431 17.1465C11.8177 17.1465 12.1875 17.2181 12.5405 17.3614C12.8935 17.5046 13.2129 17.7019 13.4987 17.9534L20.6095 13.8181C20.5758 13.7172 20.5506 13.6039 20.5338 13.4782C20.517 13.3524 20.5086 13.2304 20.5086 13.112C20.5086 12.2715 20.8028 11.5571 21.3911 10.9687C21.9795 10.3804 22.6939 10.0862 23.5345 10.0862C24.375 10.0862 25.0894 10.3804 25.6778 10.9687C26.2661 11.5571 26.5603 12.2715 26.5603 13.112C26.5603 13.9526 26.2661 14.667 25.6778 15.2554C25.0894 15.8437 24.375 16.1379 23.5345 16.1379C23.1478 16.1379 22.778 16.0666 22.425 15.9241C22.072 15.7815 21.7526 15.5838 21.4668 15.331L14.356 19.4664C14.3896 19.5672 14.4149 19.6809 14.4317 19.8073C14.4485 19.9337 14.4569 20.0554 14.4569 20.1724C14.4569 20.2894 14.4485 20.4114 14.4317 20.5385C14.4149 20.6656 14.3896 20.7789 14.356 20.8784L21.4668 25.0138C21.7526 24.7616 22.072 24.5643 22.425 24.4217C22.778 24.2792 23.1478 24.2075 23.5345 24.2069C24.375 24.2069 25.0894 24.5011 25.6778 25.0894C26.2661 25.6778 26.5603 26.3922 26.5603 27.2327C26.5603 28.0732 26.2661 28.7877 25.6778 29.3761C25.0894 29.9644 24.375 30.2586 23.5345 30.2586Z" fill="white"/>
                </svg>
                
            
        </div>
        
 <div id="overlay" class="overlay" style="display:none;"></div>
<div id="shareModal" class="modal" style="display:none;">
    <div class="modal-content" id="modalContent">
        <h2 class="share-contact">Share Contact</h2>
        <div class="share-icons"> 
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16" onclick="shareOn('twitter')">
                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
              </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16" onclick="shareOn('linkedin')">
                <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16" onclick="shareOn('facebook')">
                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
            </svg>
        </div>
        <button type="button" class="close" onclick="closeModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
       
    </div>
</div>
<script>
    const cardData = @json($cardData);
</script>


       <div class="card-footer">
            <p>Let's Get Social</p>
            
            <div class="social-icons">
                @foreach ($cardData['card_details'] as $detail)
    @php
        $url = $detail['value'];
        if (strpos($url, 'http') !== 0) {
            $url = 'https://' . $url;
        }
    @endphp

    @if ($detail['title'] === 'social-link' && strpos($url, 'facebook') !== false)
        <a href="{{ $url }}" target="_blank">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 10C1 5.02944 5.02944 1 10 1H40C44.9706 1 49 5.02944 49 10V40C49 44.9706 44.9706 49 40 49H10C5.02944 49 1 44.9706 1 40V10Z" stroke="#232323" stroke-width="2"/>
                <path d="M21.021 15.7975V19.9195H18V24.9595H21.021V39.9385H27.222V24.961H31.3845C31.3845 24.961 31.7745 22.5445 31.9635 19.9015H27.2475V16.4545C27.2475 15.94 27.9225 15.247 28.5915 15.247H31.9725V10H27.3765C20.8665 10 21.021 15.0445 21.021 15.7975Z" fill="black"/>
            </svg>
        </a>
    @elseif ($detail['type'] === 'social' && strpos($url, 'instagram') !== false)
        <a href="{{ $url }}" target="_blank">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 10C1 5.02944 5.02944 1 10 1H40C44.9706 1 49 5.02944 49 10V40C49 44.9706 44.9706 49 40 49H10C5.02944 49 1 44.9706 1 40V10Z" stroke="#232323" stroke-width="2"/>
                <path d="M18.7 10H31.3C36.1 10 40 13.9 40 18.7V31.3C40 33.6074 39.0834 35.8203 37.4518 37.4518C35.8203 39.0834 33.6074 40 31.3 40H18.7C13.9 40 10 36.1 10 31.3V18.7C10 16.3926 10.9166 14.1797 12.5482 12.5482C14.1797 10.9166 16.3926 10 18.7 10ZM18.4 13C16.9678 13 15.5943 13.5689 14.5816 14.5816C13.5689 15.5943 13 16.9678 13 18.4V31.6C13 34.585 15.415 37 18.4 37H31.6C33.0322 37 34.4057 36.4311 35.4184 35.4184C36.4311 34.4057 37 33.0322 37 31.6V18.4C37 15.415 34.585 13 31.6 13H18.4ZM32.875 15.25C33.3723 15.25 33.8492 15.4475 34.2008 15.7992C34.5525 16.1508 34.75 16.6277 34.75 17.125C34.75 17.6223 34.5525 18.0992 34.2008 18.4508C33.8492 18.8025 33.3723 19 32.875 19C32.3777 19 31.9008 18.8025 31.5492 18.4508C31.1975 18.0992 31 17.6223 31 17.125C31 16.6277 31.1975 16.1508 31.5492 15.7992C31.9008 15.4475 32.3777 15.25 32.875 15.25ZM25 17.5C26.9891 17.5 28.8968 18.2902 30.3033 19.6967C31.7098 21.1032 32.5 23.0109 32.5 25C32.5 26.9891 31.7098 28.8968 30.3033 30.3033C28.8968 31.7098 26.9891 32.5 25 32.5C23.0109 32.5 21.1032 31.7098 19.6967 30.3033C18.2902 28.8968 17.5 26.9891 17.5 25C17.5 23.0109 18.2902 21.1032 19.6967 19.6967C21.1032 18.2902 23.0109 17.5 25 17.5ZM25 20.5C23.8065 20.5 22.6619 20.9741 21.818 21.818C20.9741 22.6619 20.5 23.8065 20.5 25C20.5 26.1935 20.9741 27.3381 21.818 28.182C22.6619 29.0259 23.8065 29.5 25 29.5C26.1935 29.5 27.3381 29.0259 28.182 28.182C29.0259 27.3381 29.5 26.1935 29.5 25C29.5 23.8065 29.0259 22.6619 28.182 21.818C27.3381 20.9741 26.1935 20.5 25 20.5Z" fill="black"/>
            </svg>
        </a>
    @elseif ($detail['type'] === 'social' && strpos($url, 'linkedin') !== false)
        <a href="{{ $url }}" target="_blank">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 10C1 5.02944 5.02944 1 10 1H40C44.9706 1 49 5.02944 49 10V40C49 44.9706 44.9706 49 40 49H10C5.02944 49 1 44.9706 1 40V10Z" stroke="#232323" stroke-width="2"/>
                <g clip-path="url(#clip0_417_1213)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.0341 15.0614C16.5727 14.4871 16.8689 13.72 16.8627 12.9229C16.8349 12.1491 16.5224 11.4156 15.9891 10.8723C15.4558 10.329 14.7421 10.017 13.9938 10C13.2432 10.0166 12.5268 10.3279 11.9899 10.8706C11.4529 11.4134 11.1355 12.147 11.1021 12.9229C11.1127 13.7192 11.4188 14.4809 11.957 15.0505C12.4952 15.62 13.2244 15.9538 13.9938 16C14.7625 15.9613 15.4864 15.6347 16.0341 15.0614ZM38 37.4999H32.7626V26.6703C32.7626 25.1013 32.709 22.9478 30.5686 22.9478C28.3784 22.9478 28.0485 24.6813 28.0485 26.5646V37.4999H22.7628V19.4999H27.7896V21.4897H27.8621C28.4461 20.4193 29.5013 19.5277 31.1361 19.4998C35.5584 19.4998 38 21.9831 38 26.0498V37.4999ZM16.7624 19.4999H11.7876V37.4999H16.7624V19.4999Z" fill="black"/>
                </g>
                <defs>
                <clipPath id="clip0_417_1213">
                <rect width="27" height="27" fill="white" transform="translate(11 10)"/>
                </clipPath>
                </defs>
            </svg>
        </a>
    @endif
@endforeach

            </div>
            
            

                
                
            </div>
        </div>
        <br>

       
    

            {{-- <h2 style="margin-left:16px;margin-right:10px;">QR Code:</h2>
                  <img src="{{ $cardData['qr_code_image'] }}" alt="QR Code"> --}}
</body>
</html>