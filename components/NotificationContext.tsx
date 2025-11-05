import React, { createContext, useContext, useState, useEffect } from 'react';
import { notificationsApi } from '../services/api';

// Notification types
export interface NotificationData {
  id: string;
  title: string;
  message: string;
  urgency: 'low' | 'medium' | 'high' | 'critical' | 'info' | 'positive';
  type: string;
  action_url?: string;
  read: boolean;
  created_at: string;
  timeAgo: string;
  // Additional fields depending on notification type
  [key: string]: any;
}

interface NotificationContextType {
  notifications: NotificationData[];
  unreadCount: number;
  loading: boolean;
  loadNotifications: () => Promise<void>;
  markAsRead: (id: string) => Promise<void>;
  markAllAsRead: () => Promise<void>;
  refreshUnreadCount: () => Promise<void>;
}

const NotificationContext = createContext<NotificationContextType | undefined>(undefined);

export const NotificationProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [notifications, setNotifications] = useState<NotificationData[]>([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [loading, setLoading] = useState(false);

  const loadNotifications = async () => {
    try {
      setLoading(true);
      const response = await notificationsApi.getAll();
      setNotifications(response.notifications);
      setUnreadCount(response.notifications.filter((n: NotificationData) => !n.read).length);
    } catch (error) {
      console.error('Erro ao carregar notificações:', error);
    } finally {
      setLoading(false);
    }
  };

  const markAsRead = async (id: string) => {
    try {
      await notificationsApi.markAsRead(id);
      setNotifications(prev =>
        prev.map(n => n.id === id ? { ...n, read: true } : n)
      );
      setUnreadCount(prev => Math.max(0, prev - 1));
    } catch (error) {
      console.error('Erro ao marcar notificação como lida:', error);
    }
  };

  const markAllAsRead = async () => {
    try {
      await notificationsApi.markAllAsRead();
      setNotifications(prev =>
        prev.map(n => ({ ...n, read: true }))
      );
      setUnreadCount(0);
    } catch (error) {
      console.error('Erro ao marcar todas as notificações como lidas:', error);
    }
  };

  const refreshUnreadCount = async () => {
    try {
      const response = await notificationsApi.getUnreadCount();
      setUnreadCount(response.count);
    } catch (error) {
      console.error('Erro ao atualizar contador de notificações não lidas:', error);
    }
  };

  // Load notifications on mount
  useEffect(() => {
    loadNotifications();

    // Refresh unread count every 30 seconds
    const interval = setInterval(refreshUnreadCount, 30000);
    return () => clearInterval(interval);
  }, []);

  return (
    <NotificationContext.Provider value={{
      notifications,
      unreadCount,
      loading,
      loadNotifications,
      markAsRead,
      markAllAsRead,
      refreshUnreadCount
    }}>
      {children}
    </NotificationContext.Provider>
  );
};

export const useNotifications = () => {
  const context = useContext(NotificationContext);
  if (context === undefined) {
    throw new Error('useNotifications must be used within a NotificationProvider');
  }
  return context;
};
