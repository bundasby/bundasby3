// Push Notification Service
// Handles browser push notifications and email notifications

class NotificationService {
  constructor() {
    this.vapidPublicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY || ''
    this.isSupported = 'Notification' in window && 'serviceWorker' in navigator
    this.permission = 'default'
  }

  // Check if notifications are supported
  checkSupport() {
    return this.isSupported
  }

  // Request notification permission
  async requestPermission() {
    if (!this.isSupported) {
      console.warn('Push notifications are not supported')
      return false
    }

    try {
      const permission = await Notification.requestPermission()
      this.permission = permission
      return permission === 'granted'
    } catch (error) {
      console.error('Error requesting notification permission:', error)
      return false
    }
  }

  // Get current permission status
  getPermission() {
    if (!this.isSupported) return 'unsupported'
    return Notification.permission
  }

  // Subscribe to push notifications
  async subscribe() {
    if (!this.isSupported) return null

    try {
      const registration = await navigator.serviceWorker.ready
      
      const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey)
      })

      // Send subscription to server
      await this.sendSubscriptionToServer(subscription)
      
      return subscription
    } catch (error) {
      console.error('Error subscribing to push notifications:', error)
      return null
    }
  }

  // Unsubscribe from push notifications
  async unsubscribe() {
    try {
      const registration = await navigator.serviceWorker.ready
      const subscription = await registration.pushManager.getSubscription()
      
      if (subscription) {
        await subscription.unsubscribe()
        await this.removeSubscriptionFromServer(subscription)
      }
      
      return true
    } catch (error) {
      console.error('Error unsubscribing:', error)
      return false
    }
  }

  // Send subscription to server
  async sendSubscriptionToServer(subscription) {
    try {
      const response = await fetch('/api/v1/push/subscribe', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify(subscription)
      })
      return response.ok
    } catch (error) {
      console.error('Error sending subscription:', error)
      return false
    }
  }

  // Remove subscription from server
  async removeSubscriptionFromServer(subscription) {
    try {
      const response = await fetch('/api/v1/push/unsubscribe', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({ endpoint: subscription.endpoint })
      })
      return response.ok
    } catch (error) {
      console.error('Error removing subscription:', error)
      return false
    }
  }

  // Show local notification
  async showNotification(title, options = {}) {
    if (!this.isSupported || Notification.permission !== 'granted') {
      return false
    }

    const defaultOptions = {
      icon: '/logo-bunda-paud.png',
      badge: '/icons/icon-72x72.png',
      vibrate: [100, 50, 100],
      requireInteraction: false,
      ...options
    }

    try {
      const registration = await navigator.serviceWorker.ready
      await registration.showNotification(title, defaultOptions)
      return true
    } catch (error) {
      // Fallback to regular notification
      new Notification(title, defaultOptions)
      return true
    }
  }

  // Show toast notification (in-app)
  showToast(message, type = 'info', duration = 5000) {
    const toast = document.createElement('div')
    toast.className = `notification-toast notification-${type}`
    toast.innerHTML = `
      <div class="toast-content">
        <span class="toast-icon">${this.getToastIcon(type)}</span>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
      </div>
    `
    
    document.body.appendChild(toast)
    
    // Auto remove
    setTimeout(() => {
      toast.classList.add('toast-hiding')
      setTimeout(() => toast.remove(), 300)
    }, duration)
  }

  getToastIcon(type) {
    const icons = {
      success: '✓',
      error: '✕',
      warning: '⚠',
      info: 'ℹ'
    }
    return icons[type] || icons.info
  }

  // Convert VAPID key
  urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4)
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/')

    const rawData = window.atob(base64)
    const outputArray = new Uint8Array(rawData.length)

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i)
    }
    return outputArray
  }
}

// Export singleton
export const notificationService = new NotificationService()

// Vue composable
export function useNotifications() {
  return {
    isSupported: notificationService.checkSupport(),
    permission: notificationService.getPermission(),
    requestPermission: () => notificationService.requestPermission(),
    subscribe: () => notificationService.subscribe(),
    unsubscribe: () => notificationService.unsubscribe(),
    showNotification: (title, options) => notificationService.showNotification(title, options),
    showToast: (message, type, duration) => notificationService.showToast(message, type, duration)
  }
}

export default notificationService
