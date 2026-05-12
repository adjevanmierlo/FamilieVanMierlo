cat > (resources / js / app.js) << "EOF";
import "../scss/app.scss";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

function urlBase64ToUint8Array(base64String) {
    const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, "+")
        .replace(/_/g, "/");
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

async function subscribeToPush() {
    if (!("serviceWorker" in navigator) || !("PushManager" in window)) return;

    const permission = await Notification.requestPermission();
    if (permission !== "granted") return;

    const registration = await navigator.serviceWorker.ready;

    try {
        const response = await fetch("/push/vapid-public-key", {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) return;

        const { key } = await response.json();

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(key),
        });

        const p256dh = subscription.getKey("p256dh");
        const auth = subscription.getKey("auth");

        await fetch("/push/subscribe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                )?.content,
            },
            body: JSON.stringify({
                endpoint: subscription.endpoint,
                public_key: p256dh
                    ? btoa(String.fromCharCode(...new Uint8Array(p256dh)))
                    : null,
                auth_token: auth
                    ? btoa(String.fromCharCode(...new Uint8Array(auth)))
                    : null,
                content_encoding: (PushManager.supportedContentEncodings || [
                    "aesgcm",
                ])[0],
            }),
        });
    } catch (e) {
        console.log("Push subscription mislukt:", e);
    }
}

if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
        navigator.serviceWorker.register("/sw.js").then(() => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                subscribeToPush();
            }
        });
    });
}
EOF;
