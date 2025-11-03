// types.ts

export enum AssetStatus {
  InUse = 'Em Uso',
  Available = 'Disponível',
  Maintenance = 'Manutenção',
  Decommissioned = 'Baixado',
}

export enum AssetCategory {
  Computing = 'Computação',
  Peripheral = 'Periféricos',
  Power = 'Energia',
  Communications = 'Comunicações',
  Other = 'Outros Ativos de TI',
}

export interface MaintenanceRecord {
  id: number;
  asset_id?: number;
  date: string;
  description: string;
  performed_by?: string;
  cost?: number;
  created_at?: string;
}

export interface AssetPhoto {
  id: number;
  asset_id: number;
  url: string;
  caption?: string;
  uploaded_at: string;
  mime_type: string;
}

export interface Asset {
  id: number;
  qr_code: string;
  name: string;
  category: string;
  subcategory?: string;
  description?: string;
  serial_number?: string;
  patrimony_id?: string;
  manufacturer?: string;
  model?: string;
  acquisition_date?: string;
  warranty_expiry?: string;
  purchase_price?: number;
  status: string;
  condition_rating?: number;
  sector_id?: number;
  location?: string;
  custodian_user_id?: number;
  notes?: string;
  created_at?: string;
  updated_at?: string;
  sector_name?: string;
  custodian_name?: string;
  custodian_rank?: string;
  photos: AssetPhoto[];
  maintenanceHistory: MaintenanceRecord[];

  // Novas colunas para inventário geral
  conta?: string;
  categoria_inventario?: string;
  bmp?: string;
  componente?: string;
  situacao?: string;
  qtd?: number;
  valor_atualizado?: number;
  deprec_acumulada?: number;
  valor_liquido?: number;
}

export interface Sector {
  id: number;
  name: string;
  description?: string;
  created_at?: string;
  updated_at?: string;
}

export interface MilitaryUser {
  id: number;
  name: string;
  rank: string;
  military_id: string;
  sector_id?: number;
  email?: string;
  phone?: string;
  is_active: boolean;
  user_role?: 'user' | 'commission' | 'admin';
  commission_inventories?: number[];
  created_at?: string;
  updated_at?: string;
  sector_name?: string;
}

export interface AuthUser extends MilitaryUser {
  user_role: 'user' | 'commission' | 'admin';
}

export interface LoginCredentials {
  military_id: string;
  password: string;
}

export interface AuthResponse {
  message: string;
  user: AuthUser;
  token: string;
  abilities: string[];
}

export interface AuthContextType {
  user: AuthUser | null;
  token: string | null;
  abilities: string[];
  login: (credentials: LoginCredentials) => Promise<void>;
  logout: () => void;
  isAuthenticated: boolean;
  hasAbility: (ability: string) => boolean;
  loading: boolean;
}

export interface CustodyLog {
  id: number;
  cautela_number: string;
  user_id: number;
  assetIds?: number[];
  assets?: Asset[];
  checkout_date: string;
  checkin_date?: string;
  term_url?: string;
  signed_term_url?: string;
  notes?: string;
  created_at?: string;
  updated_at?: string;
  user_name?: string;
  user_rank?: string;
  military_id?: string;
}

export interface InventorySummary {
  total: number;
  found: number;
  pending: number;
  uncatalogued: number;
}

export interface ReopenHistory {
  id: number;
  inventory_id: number;
  reopened_by_user_id: number;
  reopened_at: string;
  justification: string;
  created_at?: string;
  user_name?: string;
  user_rank?: string;
}

export interface UncataloguedItem {
  id: number;
  inventory_id: number;
  description: string;
  location?: string;
  found_date: string;
}

// New interface for assets within an inventory context
export interface InventoryAsset extends Asset {
  observation?: string;
}

export interface InventoryRecord {
  id: number;
  commission_number: string;
  start_date: string;
  end_date?: string;
  sector_id?: number;
  responsible_user_id?: number;
  status: 'Concluído' | 'Reaberto' | 'Em Andamento';
  notes?: string;
  created_at?: string;
  updated_at?: string;
  sector_name?: string;
  responsible_user_name?: string;
  responsible_user_rank?: string;
  foundItems: InventoryAsset[];
  pendingItems: Asset[];
  uncataloguedItems: UncataloguedItem[];
  reopenHistory?: ReopenHistory[];
}
