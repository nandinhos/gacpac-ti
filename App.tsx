

import React, { useState } from 'react';
import Sidebar from './components/Sidebar';
import Dashboard from './components/Dashboard';
import AssetManagement from './components/AssetManagement';
import SectorManagement from './components/SectorManagement';
import UserManagement from './components/UserManagement';
import CustodyManagement from './components/CustodyManagement';
import InventoryManagement from './components/InventoryManagement';
import SectorAssetManager from './components/SectorAssetsModal'; // Repurposed for the new page
import PrintLabels from './components/PrintLabels';
import { Asset, Sector, MilitaryUser, CustodyLog, InventoryRecord } from './types';
import { initialAssets, initialSectors, initialUsers, initialCustodyLogs, initialInventoryRecords } from './services/mockData';

const App: React.FC = () => {
  const [activeView, setActiveView] = useState('dashboard');
  const [managingSector, setManagingSector] = useState<Sector | null>(null);
  
  const [assets, setAssets] = useState<Asset[]>(initialAssets);
  const [sectors, setSectors] = useState<Sector[]>(initialSectors);
  const [users, setUsers] = useState<MilitaryUser[]>(initialUsers);
  const [custodyLogs, setCustodyLogs] = useState<CustodyLog[]>(initialCustodyLogs);
  const [inventoryRecords, setInventoryRecords] = useState<InventoryRecord[]>(initialInventoryRecords);

  const renderView = () => {
    switch (activeView) {
      case 'dashboard':
        return <Dashboard assets={assets} users={users} sectors={sectors} logs={custodyLogs} />;
      case 'assets':
        return <AssetManagement assets={assets} setAssets={setAssets} sectors={sectors} users={users} />;
      case 'sectors':
        return <SectorManagement 
                    sectors={sectors} 
                    setSectors={setSectors} 
                    assets={assets} 
                    users={users} 
                    onManageSector={(sector) => {
                        setManagingSector(sector);
                        setActiveView('manageSectorAssets');
                    }}
                />;
      case 'manageSectorAssets':
        if (!managingSector) {
            setActiveView('sectors');
            return null;
        }
        return <SectorAssetManager 
                    sector={managingSector}
                    assets={assets}
                    setAssets={setAssets}
                    users={users}
                    allSectors={sectors}
                    custodyLogs={custodyLogs}
                    onBack={() => setActiveView('sectors')}
                />;
      case 'users':
        return <UserManagement users={users} setUsers={setUsers} sectors={sectors} assets={assets} setAssets={setAssets} custodyLogs={custodyLogs} setCustodyLogs={setCustodyLogs} />;
      case 'custody':
        return <CustodyManagement custodyLogs={custodyLogs} setCustodyLogs={setCustodyLogs} assets={assets} setAssets={setAssets} users={users} />;
      case 'inventory':
        // FIX: Pass sectors prop to InventoryManagement
        return <InventoryManagement assets={assets} setAssets={setAssets} users={users} sectors={sectors} inventoryRecords={inventoryRecords} setInventoryRecords={setInventoryRecords} />;
      case 'printLabels':
        return <PrintLabels assets={assets} />;
      default:
        return <Dashboard assets={assets} users={users} sectors={sectors} logs={custodyLogs} />;
    }
  };

  return (
    <div className="flex h-screen bg-gray-100 font-sans">
      <Sidebar setActiveView={setActiveView} activeView={activeView} />
      <div className="flex-1 flex flex-col overflow-hidden">
        <main className="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
          {renderView()}
        </main>
      </div>
    </div>
  );
};

export default App;