// API Client Service
import { Asset, Sector, MilitaryUser, CustodyLog, InventoryRecord, AssetPhoto } from '../types';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:5050/api';

class ApiError extends Error {
  constructor(public status: number, message: string) {
    super(message);
    this.name = 'ApiError';
  }
}

// Function to get auth token from localStorage
function getAuthToken(): string | null {
  return localStorage.getItem('auth_token');
}

// Function to handle auth errors (401/403)
function handleAuthError(status: number) {
  if (status === 401 || status === 403) {
    // Token expired or invalid, clear auth data and redirect to login
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    localStorage.removeItem('auth_abilities');
    
    // Trigger a page reload to force re-authentication
    window.location.reload();
  }
}

async function fetchApi<T>(endpoint: string, options?: RequestInit): Promise<T> {
  const token = getAuthToken();
  
  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    ...options?.headers,
  };

  // Add Authorization header if token exists
  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }

  const response = await fetch(`${API_URL}${endpoint}`, {
    headers,
    ...options,
  });

  if (!response.ok) {
    // Handle auth errors
    handleAuthError(response.status);
    
    const error = await response.json().catch(() => ({ error: 'Erro desconhecido' }));
    throw new ApiError(response.status, error.error || response.statusText);
  }

  return response.json();
}

// Special fetch for file uploads (photos)
async function fetchApiFormData<T>(endpoint: string, formData: FormData): Promise<T> {
  const token = getAuthToken();
  
  const headers: Record<string, string> = {};

  // Add Authorization header if token exists (don't set Content-Type for FormData)
  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }

  const response = await fetch(`${API_URL}${endpoint}`, {
    method: 'POST',
    headers,
    body: formData,
  });

  if (!response.ok) {
    handleAuthError(response.status);
    
    const error = await response.json().catch(() => ({ error: 'Erro ao enviar arquivo' }));
    throw new ApiError(response.status, error.error);
  }

  return response.json();
}

// Sectors API
// Notification API
export const notificationsApi = {
  async getAll(params?: { read?: boolean; type?: string; page?: number }) {
    const response = await api.get('/notifications', { params });
    return response.data;
  },

  async getUnreadCount() {
    const response = await api.get('/notifications/unread-count');
    return response.data;
  },

  async markAsRead(id: string) {
    const response = await api.put(`/notifications/${id}/read`);
    return response.data;
  },

  async markAllAsRead() {
    const response = await api.put('/notifications/mark-all-read');
    return response.data;
  }
};

export const sectorsApi = {
  getAll: () => fetchApi<Sector[]>('/sectors'),
  getById: (id: string) => fetchApi<Sector>(`/sectors/${id}`),
  create: (sector: Omit<Sector, 'id'>) => fetchApi<Sector>('/sectors', {
    method: 'POST',
    body: JSON.stringify(sector),
  }),
  update: (id: string, sector: Partial<Sector>) => fetchApi<Sector>(`/sectors/${id}`, {
    method: 'PUT',
    body: JSON.stringify(sector),
  }),
  delete: (id: string) => fetchApi<{ message: string }>(`/sectors/${id}`, {
    method: 'DELETE',
  }),
};

// Users API
export const usersApi = {
  getAll: (params?: { active?: boolean; sectorId?: string }) => {
    const query = new URLSearchParams();
    if (params?.active !== undefined) query.append('active', String(params.active));
    if (params?.sectorId) query.append('sectorId', params.sectorId);
    return fetchApi<MilitaryUser[]>(`/users${query.toString() ? `?${query}` : ''}`);
  },
  getById: (id: string) => fetchApi<MilitaryUser>(`/users/${id}`),
  create: (user: Omit<MilitaryUser, 'id'>) => fetchApi<MilitaryUser>('/users', {
    method: 'POST',
    body: JSON.stringify(user),
  }),
  update: (id: string, user: Partial<MilitaryUser>) => fetchApi<MilitaryUser>(`/users/${id}`, {
    method: 'PUT',
    body: JSON.stringify(user),
  }),
  delete: (id: string) => fetchApi<{ message: string }>(`/users/${id}`, {
    method: 'DELETE',
  }),
};

// Assets API
export const assetsApi = {
  getAll: (params?: { category?: string; status?: string; sectorId?: string; search?: string }) => {
    const query = new URLSearchParams();
    if (params?.category) query.append('category', params.category);
    if (params?.status) query.append('status', params.status);
    if (params?.sectorId) query.append('sectorId', params.sectorId);
    if (params?.search) query.append('search', params.search);
    return fetchApi<Asset[]>(`/assets${query.toString() ? `?${query}` : ''}`);
  },
  getById: (id: string) => fetchApi<Asset>(`/assets/${id}`),
  getByQrCode: (qrCode: string) => fetchApi<Asset>(`/assets/qr/${qrCode}`),
  getNextQrCode: () => fetchApi<{ qrCode: string }>('/assets/utils/next-qr-code'),
  create: (asset: Omit<Asset, 'id' | 'photos' | 'maintenanceHistory'>) => fetchApi<Asset>('/assets', {
    method: 'POST',
    body: JSON.stringify(asset),
  }),
  update: (id: string, asset: Partial<Asset>) => fetchApi<Asset>(`/assets/${id}`, {
    method: 'PUT',
    body: JSON.stringify(asset),
  }),
  delete: (id: string) => fetchApi<{ message: string }>(`/assets/${id}`, {
    method: 'DELETE',
  }),
  addPhoto: async (assetId: string, photo: File, caption?: string): Promise<AssetPhoto> => {
    const formData = new FormData();
    formData.append('photo', photo);
    if (caption) formData.append('caption', caption);

    return fetchApiFormData<AssetPhoto>(`/assets/${assetId}/photos`, formData);
  },
  deletePhoto: (assetId: string, photoId: string) =>
    fetchApi<{ message: string }>(`/assets/${assetId}/photos/${photoId}`, {
      method: 'DELETE',
    }),
  addMaintenance: (assetId: string, maintenance: { date: string; description: string; performedBy?: string; cost?: number }) =>
    fetchApi(`/assets/${assetId}/maintenance`, {
      method: 'POST',
      body: JSON.stringify(maintenance),
    }),
  deleteMaintenance: (assetId: string, maintenanceId: string) =>
    fetchApi<{ message: string }>(`/assets/${assetId}/maintenance/${maintenanceId}`, {
      method: 'DELETE',
    }),
};

// Custody API
export const custodyApi = {
  getAll: (params?: { active?: boolean; userId?: string }) => {
    const query = new URLSearchParams();
    if (params?.active !== undefined) query.append('active', String(params.active));
    if (params?.userId) query.append('userId', params.userId);
    return fetchApi<CustodyLog[]>(`/custody${query.toString() ? `?${query}` : ''}`);
  },
  getById: (id: string) => fetchApi<CustodyLog>(`/custody/${id}`),
  store: (custody: {
    cautelaNumber: string;
    userId: string;
    checkoutDate: string;
    assetIds: string[];
    termUrl?: string;
    notes?: string;
  }) => fetchApi<CustodyLog>('/custody', {
    method: 'POST',
    body: JSON.stringify(custody),
  }),
  checkin: (id: string, data: { checkinDate: string; signedTermUrl?: string }) =>
    fetchApi<CustodyLog>(`/custody/${id}/checkin`, {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
  update: (id: string, custody: { notes?: string; signedTermUrl?: string; termUrl?: string }) =>
    fetchApi<CustodyLog>(`/custody/${id}`, {
      method: 'PUT',
      body: JSON.stringify(custody),
    }),
  delete: (id: string) => fetchApi<{ message: string }>(`/custody/${id}`, {
    method: 'DELETE',
  }),
  getNextNumber: () => fetchApi<{ nextCautelaNumber: string }>('/custody/next-number'),
  getReports: (params?: { type?: string; user_id?: string; start_date?: string; end_date?: string }) => {
    const query = new URLSearchParams();
    if (params?.type) query.append('type', params.type);
    if (params?.user_id) query.append('user_id', params.user_id);
    if (params?.start_date) query.append('start_date', params.start_date);
    if (params?.end_date) query.append('end_date', params.end_date);
    return fetchApi<{ summary: any; custodies: CustodyLog[] }>(`/custody-reports${query.toString() ? `?${query}` : ''}`);
  },
};

// Inventory API
export const inventoryApi = {
  getAll: (params?: { status?: string; sectorId?: string }) => {
    const query = new URLSearchParams();
    if (params?.status) query.append('status', params.status);
    if (params?.sectorId) query.append('sectorId', params.sectorId);
    return fetchApi<InventoryRecord[]>(`/inventory${query.toString() ? `?${query}` : ''}`);
  },
  getById: (id: string) => fetchApi<InventoryRecord>(`/inventory/${id}`),
  create: (inventory: {
    commissionNumber: string;
    startDate: string;
    sectorId?: string;
    responsibleUserId?: number;
    notes?: string;
  }) => fetchApi<InventoryRecord>('/inventory', {
    method: 'POST',
    body: JSON.stringify(inventory),
  }),
  update: (id: string, data: any) => fetchApi<InventoryRecord>(`/inventory/${id}`, {
    method: 'PUT',
    body: JSON.stringify(data),
  }),
  addFoundItem: (id: string, data: { assetId: string; observation?: string }) =>
    fetchApi(`/inventory/${id}/found`, {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  addUncataloguedItem: (id: string, data: { description: string; location?: string }) =>
    fetchApi(`/inventory/${id}/uncatalogued`, {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  complete: (id: string, endDate: string) =>
    fetchApi<InventoryRecord>(`/inventory/${id}/complete`, {
      method: 'PUT',
      body: JSON.stringify({ endDate }),
    }),
  reopen: (id: string, data: { justification: string }) =>
    fetchApi<InventoryRecord>(`/inventory/${id}/reopen`, {
      method: 'POST',
      body: JSON.stringify({ justification: data.justification }),
    }),
  delete: (id: string) => fetchApi<{ message: string }>(`/inventory/${id}`, {
    method: 'DELETE',
  }),
  deleteUncataloguedItem: (id: string, uncataloguedId: string) =>
    fetchApi<{ message: string }>(`/inventory/${id}/uncatalogued/${uncataloguedId}`, {
      method: 'DELETE',
    }),
};

// Dashboard API
export const dashboardApi = {
  getStats: () => fetchApi<{
    assets: {
      total: number;
      byStatus: { emUso: number; disponivel: number; manutencao: number; baixado: number };
      byCategory: Record<string, number>;
      maintenanceNeeded: number;
    };
    custody: { active: number };
    inventory: { active: number };
    users: { total: number; active: number };
    sectors: { total: number };
    recent: {
      assets: Array<{ id: string; name: string; qr_code: string; category: string; created_at: string }>;
      custody: Array<{ id: string; cautela_number: string; checkout_date: string; checkin_date: string | null; user_name: string; user_rank: string }>;
    };
  }>('/dashboard/stats'),
};

export { ApiError };
