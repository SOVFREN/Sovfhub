importScripts("https://www.gstatic.com/firebasejs/10.5.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.5.2/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "AIzaSyBdh7itbMdpyRx1QIDWnbIOvoK_71XM1M8",
    authDomain: "sovhub-f64e7.firebaseapp.com",
    projectId: "sovhub-f64e7",
    messagingSenderId: "271137054646",
    appId: "1:271137054646:web:239e243fd599b4047b6b17",
});

const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        body: payload.data.body,
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener("install", (event) => {
    event.waitUntil(self.skipWaiting());
});
