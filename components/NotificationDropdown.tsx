import React, { useState } from 'react';
import { BellIcon } from 'lucide-react';
import { useNotifications, NotificationData } from './NotificationContext';

const NotificationDropdown: React.FC = () => {
  const { notifications, unreadCount, markAsRead, markAllAsRead, loading } = useNotifications();
  const [isOpen, setIsOpen] = useState(false);

  const getUrgencyStyles = (urgency: string) => {
    switch (urgency) {
      case 'critical':
        return 'border-l-red-500 bg-red-50';
      case 'high':
        return 'border-l-orange-500 bg-orange-50';
      case 'medium':
        return 'border-l-yellow-500 bg-yellow-50';
      case 'info':
        return 'border-l-blue-500 bg-blue-50';
      case 'positive':
        return 'border-l-green-500 bg-green-50';
      case 'low':
      default:
        return 'border-l-gray-500 bg-gray-50';
    }
  };

  const handleNotificationClick = async (notification: NotificationData) => {
    if (!notification.read) {
      await markAsRead(notification.id);
    }
    if (notification.action_url) {
      // Navigate to action URL (implementar navegação)
      console.log('Navigate to:', notification.action_url);
    }
    setIsOpen(false);
  };

  const recentNotifications = notifications.slice(0, 5);

  return (
    <div className="relative">
      {/* Notification Button */}
      <button
        type="button"
        className="relative bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        onClick={() => setIsOpen(!isOpen)}
      >
        <span className="sr-only">Ver notificações</span>
        <BellIcon className="h-6 w-6" />
        {unreadCount > 0 && (
          <span className="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[20px] h-5">
            {unreadCount > 99 ? '99+' : unreadCount}
          </span>
        )}
      </button>

      {/* Dropdown Panel */}
      {isOpen && (
        <>
          {/* Overlay */}
          <div
            className="fixed inset-0 z-40"
            onClick={() => setIsOpen(false)}
          />

          {/* Dropdown */}
          <div className="absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg z-50 max-h-[80vh] overflow-hidden">
            <div className="py-1">
              {/* Header */}
              <div className="px-4 py-2 border-b border-gray-200">
                <div className="flex items-center justify-between">
                  <h3 className="text-sm font-medium text-gray-900">Notificações</h3>
                  {unreadCount > 0 && (
                    <button
                      onClick={markAllAsRead}
                      className="text-xs text-blue-600 hover:text-blue-800"
                    >
                      Marcar todas como lidas
                    </button>
                  )}
                </div>
              </div>

              {/* Loading State */}
              {loading && (
                <div className="px-4 py-8 text-center">
                  <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                  <p className="mt-2 text-sm text-gray-500">Carregando...</p>
                </div>
              )}

              {/* Notifications List */}
              {!loading && (
                <div className="max-h-96 overflow-y-auto">
                  {recentNotifications.length === 0 ? (
                    <div className="px-4 py-8 text-center">
                      <BellIcon className="mx-auto h-8 w-8 text-gray-400" />
                      <p className="mt-2 text-sm text-gray-500">Nenhuma notificação</p>
                    </div>
                  ) : (
                    recentNotifications.map((notification) => (
                      <div
                        key={notification.id}
                        className={`px-4 py-3 border-l-4 hover:bg-gray-50 cursor-pointer ${getUrgencyStyles(notification.urgency)} ${!notification.read ? 'bg-blue-50' : ''}`}
                        onClick={() => handleNotificationClick(notification)}
                      >
                        <div className="flex items-start">
                          <div className="flex-shrink-0">
                            {/* Icon based on notification type */}
                            <div className="w-2 h-2 bg-current rounded-full"></div>
                          </div>
                          <div className="ml-3 flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-900 truncate">
                              {notification.title}
                            </p>
                            <p className="text-sm text-gray-600 mt-1 line-clamp-2">
                              {notification.message}
                            </p>
                            <p className="text-xs text-gray-500 mt-1">
                              {notification.timeAgo}
                            </p>
                          </div>
                          {!notification.read && (
                            <div className="flex-shrink-0">
                              <div className="w-2 h-2 bg-blue-600 rounded-full"></div>
                            </div>
                          )}
                        </div>
                      </div>
                    ))
                  )}
                </div>
              )}

              {/* Footer */}
              <div className="px-4 py-2 border-t border-gray-200">
                <button
                  onClick={() => {
                    // Navigate to full notifications page
                    console.log('Navigate to full notifications page');
                    setIsOpen(false);
                  }}
                  className="text-sm text-blue-600 hover:text-blue-800 w-full text-left"
                >
                  Ver todas as notificações
                </button>
              </div>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

export default NotificationDropdown;
