# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

SGAITI-UM (Sistema de Gestão de Ativos de TI) is an IT asset management system designed for Brazilian Air Force military units. The system uses QR codes to streamline asset tracking, custody management, and inventory processes. This is a React + TypeScript single-page application built with Vite, using in-memory state management (no backend).

## Development Commands

### Setup
```bash
npm install
```

### Development Server
```bash
npm run dev
```
Server runs on `http://0.0.0.0:3000` (accessible from network)

### Build
```bash
npm run build
```

### Preview Production Build
```bash
npm run preview
```

### Environment Setup
The app expects a `GEMINI_API_KEY` environment variable (configured in vite.config.ts). While the README mentions `.env.local`, the file doesn't exist yet - create it if needed for Gemini API integration.

## Architecture

### State Management Pattern
The application uses **prop drilling** for state management - all state is lifted to the root `App.tsx` component and passed down through props. There are five main data entities managed in App state:

- `assets` - IT assets (computers, peripherals, etc.)
- `sectors` - Military sectors/departments
- `users` - Military personnel (MilitaryUser type)
- `custodyLogs` - Custody records (cautelas)
- `inventoryRecords` - Inventory sessions

### Core Data Flow
1. **App.tsx** holds all state using `useState` hooks
2. **View components** receive both data and setter functions as props
3. **Data initialization** comes from `services/mockData.ts`
4. **Type definitions** are centralized in `types.ts`

### View Management
App.tsx uses a string-based view switcher (`activeView` state) to render different management pages:
- `dashboard` - Overview statistics
- `assets` - Asset CRUD operations
- `sectors` - Sector management with ability to transition to sector asset view
- `manageSectorAssets` - Dedicated sector asset allocation view (uses `managingSector` state)
- `users` - User/personnel management
- `custody` - Custody (cautela) management
- `inventory` - Inventory operations
- `printLabels` - QR code label printing

### Component Structure

#### Management Pages (Main Views)
Each corresponds to a primary navigation item:
- `Dashboard.tsx` - Summary statistics and recent activity
- `AssetManagement.tsx` - Full asset CRUD with filtering and QR scanning
- `SectorManagement.tsx` - Sector CRUD, triggers navigation to sector asset manager
- `SectorAssetsModal.tsx` - Repurposed as full-page sector asset allocation manager
- `UserManagement.tsx` - Military personnel CRUD
- `CustodyManagement.tsx` - Custody (cautela) checkout/checkin workflow
- `InventoryManagement.tsx` - Inventory session management
- `PrintLabels.tsx` - QR label generation

#### Modal Components
Reusable modal dialogs for specific workflows:
- `AssetDetailsModal.tsx` - View/edit detailed asset information
- `AssetMaintenanceModal.tsx` - Maintenance history management
- `CustodyDetailsModal.tsx` - Custody record details
- `InventoryDetailsModal.tsx` - Inventory session details
- `ReopenInventoryModal.tsx` - Inventory reopening workflow
- `PhotoGalleryModal.tsx` - Image viewer
- `QrScannerModal.tsx` - Camera-based QR scanning (uses camera permission)
- `UserDetailsModal.tsx` - User/personnel details

#### Layout
- `Sidebar.tsx` - Navigation sidebar

### Key Type Definitions (types.ts)

**Asset Lifecycle:**
- `AssetStatus` enum: Em Uso (In Use), Disponível (Available), Manutenção (Maintenance), Baixado (Decommissioned)
- `AssetCategory` enum: Computing, Peripheral, Power, Communications, Other IT Assets

**Core Entities:**
- `Asset` - Includes QR code, serial number, patrimony ID, photos array, current sector, optional custodian, status, dates, maintenance history
- `InventoryAsset` - Extends Asset with optional inventory observation
- `Sector` - Military department/unit
- `MilitaryUser` - Personnel with rank, military ID, sector assignment, active status
- `CustodyLog` (Cautela) - Custody record with cautela number format (e.g., "001/GAC-PAC/2024"), checkout/checkin dates, asset IDs, term URLs
- `InventoryRecord` - Inventory session with found/pending/uncatalogued items, status (Concluído/Reaberto/Em Andamento), optional sector scoping, reopen history

### Mock Data (services/mockData.ts)

Initial data includes:
- 10 military sectors (CHF, ATI, AIT, SEC, ALOG, SFI, SAD, STEC, SCP-SIS, Almoxarifado TI)
- 14 military users with Brazilian Air Force ranks
- 42 IT assets across all categories
- 11 custody logs (some closed, some active)
- 2 inventory records (one completed, one in progress)

**Important Functions:**
- `generateNewQrCode(lastId)` - Generates QR codes in format "SGAITI-XXXX" with zero-padded 4-digit numbers

### Path Aliasing
The project uses `@/` as an alias for the root directory (configured in both tsconfig.json and vite.config.ts):
```typescript
import { Asset } from '@/types';
import { initialAssets } from '@/services/mockData';
```

### Brazilian Air Force Context
The system is tailored for Brazilian military terminology:
- Uses Portuguese language throughout
- Military ranks (Coronel, Major, Capitão, Tenente, Sargento, Cabo, Soldado) with specializations (Aviador, Especialista, Intendente, BCT, BCO, etc.)
- Cautela number format: XXX/GAC-PAC/YYYY (e.g., "001/GAC-PAC/2024")
- Commission numbers for inventories: CI-ATI-YYYY/XX

### Camera Permission
The app requests camera permission (see metadata.json) for QR code scanning functionality via QrScannerModal.

## Common Development Patterns

### Adding a New Asset Field
1. Update `Asset` interface in `types.ts`
2. Update mock data in `services/mockData.ts`
3. Update AssetForm in `AssetManagement.tsx`
4. Update AssetDetailsModal if needed for viewing/editing

### Adding a New View
1. Create component in `components/`
2. Add case to `renderView()` switch in `App.tsx`
3. Add state and setter props as needed
4. Update Sidebar navigation if public-facing

### Working with Inventory
- Inventory can be scoped to a specific sector (optional `sectorId` field)
- Assets in inventory context use `InventoryAsset` type with optional observations
- Inventory status: "Em Andamento" (In Progress), "Concluído" (Completed), "Reaberto" (Reopened)
- Reopen history tracks justification and user

### Working with Custody (Cautelas)
- Each custody log has a `cautelaNumber` following military format
- `termUrl` points to the blank custody term PDF
- `signedTermUrl` points to the signed/uploaded PDF (optional)
- Active custody = no `checkinDate`
- Assets in custody have `custodianUserId` set and status "Em Uso"
