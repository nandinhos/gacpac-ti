import React, { useState, useEffect } from 'react';
import Sidebar from './components/Sidebar';
import Dashboard from './components/Dashboard';
import AssetManagement from './components/AssetManagement';
import SectorManagement from './components/SectorManagement';
import UserManagement from './components/UserManagement';
import CustodyManagement from './components/CustodyManagement';
import CreateCustody from './components/CreateCustody';
import InventoryManagement from './components/InventoryManagement';
import SectorAssetManager from './components/SectorAssetsModal';
import PrintLabels from './components/PrintLabels';
import { Asset, Sector, MilitaryUser, CustodyLog, InventoryRecord } from './types';
import { sectorsApi, usersApi, assetsApi, custodyApi, inventoryApi } from './services/api';

const App: React.FC = () => {
  const [activeView, setActiveView] = useState('dashboard');
  const [managingSector, setManagingSector] = useState<Sector | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const [assets, setAssets] = useState<Asset[]>([]);
  const [sectors, setSectors] = useState<Sector[]>([]);
  const [users, setUsers] = useState<MilitaryUser[]>([]);
  const [custodyLogs, setCustodyLogs] = useState<CustodyLog[]>([]);
  const [inventoryRecords, setInventoryRecords] = useState<InventoryRecord[]>([]);
  const [initialAssetStatusFilter, setInitialAssetStatusFilter] = useState<AssetStatus | 'all'>('all');

  const navigateToAssetsWithFilter = (status: AssetStatus | 'all') => {
    setInitialAssetStatusFilter(status);
    setActiveView('assets');
  };

  // Load initial data from API
  useEffect(() => {
    const loadData = async () => {
      try {
        setLoading(true);
        setError(null);

        // Load all data in parallel
        const [sectorsData, usersData, assetsData, custodyData, inventoryData] = await Promise.all([
          sectorsApi.getAll(),
          usersApi.getAll(),
          assetsApi.getAll(),
          custodyApi.getAll(),
          inventoryApi.getAll(),
        ]);

        setSectors(sectorsData);
        setUsers(usersData);
        setAssets(assetsData);
        setCustodyLogs(custodyData);
        setInventoryRecords(inventoryData);
      } catch (err: any) {
        console.error('Error loading data:', err);
        setError(err.message || 'Erro ao carregar dados. Verifique se o backend está rodando.');
      } finally {
        setLoading(false);
      }
    };

    loadData();
  }, []);

  // Reload functions for specific entities
  const reloadAssets = async () => {
    try {
      const data = await assetsApi.getAll();
      setAssets(data);
    } catch (err: any) {
      console.error('Error reloading assets:', err);
      alert('Erro ao recarregar ativos: ' + err.message);
    }
  };

  const reloadSectors = async () => {
    try {
      const data = await sectorsApi.getAll();
      setSectors(data);
    } catch (err: any) {
      console.error('Error reloading sectors:', err);
    }
  };

  const reloadUsers = async () => {
    try {
      const data = await usersApi.getAll();
      setUsers(data);
    } catch (err: any) {
      console.error('Error reloading users:', err);
    }
  };

  const reloadCustody = async () => {
    try {
      const data = await custodyApi.getAll();
      setCustodyLogs(data);
    } catch (err: any) {
      console.error('Error reloading custody:', err);
      alert('Erro ao recarregar cautelas: ' + err.message);
    }
  };

  const reloadInventory = async () => {
    try {
      const data = await inventoryApi.getAll();
      setInventoryRecords(data);
    } catch (err: any) {
      console.error('Error reloading inventory:', err);
    }
  };

  const renderView = () => {
    if (loading) {
      return (
        <div className="flex items-center justify-center h-full">
          <div className="text-center">
            <div className="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p className="text-gray-600">Carregando dados...</p>
          </div>
        </div>
      );
    }

    if (error) {
      return (
        <div className="flex items-center justify-center h-full">
          <div className="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md">
            <div className="flex items-center mb-4">
              <svg className="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 className="text-lg font-semibold text-red-900">Erro ao conectar</h3>
            </div>
            <p className="text-red-700 mb-4">{error}</p>
            <button
              onClick={() => window.location.reload()}
              className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors"
            >
              Tentar novamente
            </button>
          </div>
        </div>
      );
    }

    switch (activeView) {
      case 'dashboard':
        return <Dashboard assets={assets} users={users} sectors={sectors} logs={custodyLogs} setActiveView={setActiveView} navigateToAssetsWithFilter={navigateToAssetsWithFilter} />;
      case 'assets':
        return (
          <AssetManagement
            assets={assets}
            setAssets={setAssets}
            sectors={sectors}
            users={users}
            onDataChange={reloadAssets}
            initialStatusFilter={initialAssetStatusFilter}
          />
        );
      case 'sectors':
        return (
          <SectorManagement
            sectors={sectors}
            setSectors={setSectors}
            assets={assets}
            users={users}
            onManageSector={(sector) => {
              setManagingSector(sector);
              setActiveView('manageSectorAssets');
            }}
            onDataChange={reloadSectors}
          />
        );
      case 'manageSectorAssets':
        if (!managingSector) {
          setActiveView('sectors');
          return null;
        }
        return (
          <SectorAssetManager
            sector={managingSector}
            assets={assets}
            setAssets={setAssets}
            users={users}
            allSectors={sectors}
            custodyLogs={custodyLogs}
            onBack={() => setActiveView('sectors')}
            onDataChange={reloadAssets}
          />
        );
      case 'users':
        return (
          <UserManagement
            users={users}
            setUsers={setUsers}
            sectors={sectors}
            assets={assets}
            setAssets={setAssets}
            custodyLogs={custodyLogs}
            setCustodyLogs={setCustodyLogs}
            onDataChange={reloadUsers}
          />
        );
      case 'custody':
        return (
          <CustodyManagement
            custodyLogs={custodyLogs}
            setCustodyLogs={setCustodyLogs}
            assets={assets}
            setAssets={setAssets}
            users={users}
            onDataChange={() => {
              reloadCustody();
              reloadAssets();
            }}
            onCreateCustody={() => setActiveView('createCustody')}
          />
        );
      case 'createCustody':
        return (
          <CreateCustody
            users={users}
            assets={assets}
            onCustodyCreated={() => {
              reloadCustody();
              reloadAssets();
            }}
            onBack={() => setActiveView('custody')}
          />
        );
      case 'inventory':
        return (
          <InventoryManagement
            assets={assets}
            setAssets={setAssets}
            users={users}
            sectors={sectors}
            inventoryRecords={inventoryRecords}
            setInventoryRecords={setInventoryRecords}
            onDataChange={() => {
              reloadInventory();
              reloadAssets();
            }}
          />
        );
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
