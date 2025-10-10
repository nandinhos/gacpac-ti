// types.ts

export enum AssetStatus {
  InUse = 'Em Uso',
  Available = 'Disponível no Almoxarifado',
  Maintenance = 'Manutenção',
  Decommissioned = 'Baixado',
}

export enum AssetCategory {
  Computing = 'Computação',
  Peripheral = 'Periférico',
  Power = 'Energia',
  Communications = 'Comunicações',
  Other = 'Outros Ativos de TI',
}

export interface MaintenanceRecord {
  id: number;
  date: string;
  reportedProblem: string;
  solution: string;
}

export interface Asset {
  id: number;
  qrCode: string;
  patrimonyId?: string;
  serialNumber: string;
  category: AssetCategory;
  type: string;
  photos: string[];
  currentSectorId: number;
  custodianUserId?: number;
  status: AssetStatus;
  acquisitionDate: string;
  warrantyEndDate?: string;
  maintenanceHistory?: MaintenanceRecord[];
}

export interface Sector {
  id: number;
  name: string;
  description?: string;
}

export interface MilitaryUser {
  id: number;
  name: string;
  rank: string;
  militaryId: string;
  sectorId: number;
  active: boolean;
}

export interface CustodyLog {
  id: number;
  cautelaNumber: string; // ex: 001/GAC-PAC/2024
  userId: number;
  assetIds: number[];
  checkoutDate: string;
  checkinDate?: string;
  termUrl?: string; // a simulated URL to the PDF term
  signedTermUrl?: string; // a simulated URL to the uploaded signed PDF
}

export interface InventorySummary {
  total: number;
  found: number;
  pending: number;
  uncatalogued: number;
}

export interface ReopenHistory {
    reopenDate: string;
    reopenedByUserId: number;
    justification: string;
}

// New interface for assets within an inventory context
export interface InventoryAsset extends Asset {
    inventoryObservation?: string;
}

export interface InventoryRecord {
  id: number;
  startDate: string;
  completionDate?: string;
  responsibleUserId: number;
  sectorId?: number; // Added to scope inventory by sector
  commissionNumber?: string;
  summary: InventorySummary;
  foundAssets: InventoryAsset[];
  pendingAssets: InventoryAsset[];
  uncataloguedItems: string[];
  status: 'Concluído' | 'Reaberto' | 'Em Andamento';
  reopenHistory?: ReopenHistory[];
  observations?: string;
}
