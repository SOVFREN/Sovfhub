import {
    initializeApp
} from 'https://www.gstatic.com/firebasejs/10.5.2/firebase-app.js'

import {
    getAnalytics
} from 'https://www.gstatic.com/firebasejs/10.5.2/firebase-analytics.js'

import {
    getAuth
} from 'https://www.gstatic.com/firebasejs/10.5.2/firebase-auth.js'
import {
    getFirestore
} from 'https://www.gstatic.com/firebasejs/10.5.2/firebase-firestore.js'
import {
    getMessaging, getToken
} from 'https://www.gstatic.com/firebasejs/10.5.2/firebase-messaging.js'


const firebaseConfig = {
    apiKey: $('.web_push_service_variables > .apiKey').text(),
    authDomain: $('.web_push_service_variables > .authDomain').text(),
    projectId: $('.web_push_service_variables > .projectId').text(),
    messagingSenderId: $('.web_push_service_variables > .messagingSenderId').text(),
    appId: $('.web_push_service_variables > .appId').text(),
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

const firebase_requestPermission = async () => {
    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
        console.log('Notification permission granted.');
    } else {
        console.log('Notification permission denied.');
    }
};

const firebase_getDeviceToken = async () => {
    try {
        const token = await getToken(messaging, {
            serviceWorkerRegistration: firebase_sw_reg
        });
        add_push_subscriber(token, 'firebase');
    } catch (error) {
        console.error('Error getting device token:', error);
    }
};

window.addEventListener('load', function() {
    setTimeout(function() {
        if (firebase_sw_reg !== undefined) {
            firebase_requestPermission()
            .then(() => {
                firebase_getDeviceToken();
            });
        }
    }, 3000);
});