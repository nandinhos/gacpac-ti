import React, { useState, useEffect, Suspense } from 'react';
import Sidebar from './components/Sidebar';
import LoginScreen from './components/LoginScreen';
import ProtectedRoute from './components/ProtectedRoute';

// Lazy load heavy components for better performance
const Dashboard = React.lazy(() => import('./components/Dashboard'));
const AssetManagement = React.lazy(() => import('./components/AssetManagement'));
const SectorManagement = React.lazy(() => import('./components/SectorManagement'));
const UserManagement = React.lazy(() => import('./components/UserManagement'));
const CustodyManagement = React.lazy(() => import('./components/CustodyManagement'));
const CreateCustody = React.lazy(() => import('./components/CreateCustody'));
const InventoryManagement = React.lazy(() => import('./components/InventoryManagement'));
const SectorAssetManager = React.lazy(() => import('./components/SectorAssetsModal'));
const PrintLabels = React.lazy(() => import('./components/PrintLabels'));
const UserProfile = React.lazy(() => import('./components/UserProfile'));
import { AuthProvider, useAuth } from './components/AuthContext';
import { NotificationProvider } from './components/NotificationContext';
import { Asset, Sector, MilitaryUser, CustodyLog, InventoryRecord, AssetStatus } from './types';
import { sectorsApi, usersApi, assetsApi, custodyApi, inventoryApi } from './services/api';

// Componente principal autenticado
const AuthenticatedApp: React.FC = () => {
  const { isAuthenticated, loading: authLoading } = useAuth();
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

  // Se ainda está carregando a autenticação, mostrar loading
  if (authLoading) {
    return (
      <div className="min-h-screen bg-gray-100 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-gray-600">Carregando...</p>
        </div>
      </div>
    );
  }

  // Se não está autenticado, mostrar tela de login
  if (!isAuthenticated) {
    return <LoginScreen />;
  }

  const navigateToAssetsWithFilter = (status: AssetStatus | 'all') => {
    setInitialAssetStatusFilter(status);
    setActiveView('assets');
  };

  // CARREGAMENTO MANUAL DE DADOS - SEM useEffect AUTOMÁTICO
  const manualLoadData = async () => {
    const token = localStorage.getItem('auth_token');
    if (!isAuthenticated || !token) {
      setLoading(false);
      return;
    }

    try {
      setLoading(true);
      setError(null);
      console.log('🔍 DEBUG: Carregamento manual iniciado...');

      // Load all data in parallel
      const [sectorsData, usersData, assetsData, custodyData, inventoryData] = await Promise.all([
        sectorsApi.getAll(),
        usersApi.getAll(),
        assetsApi.getAll(),
        custodyApi.getAll(),
        inventoryApi.getAll(),
      ]);

      console.log('🔍 DEBUG: Dados carregados com sucesso');
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

  // INICIALIZAÇÃO SIMPLES SEM useEffect PROBLEMÁTICO
  React.useEffect(() => {
    if (isAuthenticated) {
      console.log('🔍 DEBUG: Usuário autenticado, setLoading false');
      setLoading(false);
    }
  }, [isAuthenticated]);

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
        // TEMPORÁRIO: Dashboard vazio para debug
        return (
          <div className="p-8 bg-white rounded-lg shadow">
            <h1 className="text-2xl font-bold text-green-600 mb-4">🎉 Sistema SGTI-GAC Funcionando!</h1>
            <p className="text-gray-600 mb-4">Login realizado com sucesso. Dashboard temporariamente simplificado para debug.</p>
            
            <button 
              onClick={manualLoadData}
              disabled={loading}
              className="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
            >
              {loading ? 'Carregando...' : 'Carregar Dados Manualmente'}
            </button>
            
            <div className="grid grid-cols-2 gap-4">
              <div className="bg-blue-50 p-4 rounded">
                <h3 className="font-semibold">Dados Carregados:</h3>
                <p>Assets: {assets.length}</p>
                <p>Users: {users.length}</p>
                <p>Sectors: {sectors.length}</p>
                <p>Custody: {custodyLogs.length}</p>
              </div>
              <div className="bg-green-50 p-4 rounded">
                <h3 className="font-semibold">Status:</h3>
                <p>✅ Autenticação OK</p>
                <p>✅ Login funcionando</p>
                <p>✅ Interface carregada</p>
              </div>
            </div>
            
            {error && (
              <div className="mt-4 p-4 bg-red-50 border border-red-200 rounded">
                <p className="text-red-700">{error}</p>
              </div>
            )}
          </div>
        );
        // return <Dashboard assets={assets} users={users} sectors={sectors} logs={custodyLogs} setActiveView={setActiveView} navigateToAssetsWithFilter={navigateToAssetsWithFilter} />;
      case 'assets':
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
              <AssetManagement
                assets={assets}
                setAssets={setAssets}
                sectors={sectors}
                users={users}
                onDataChange={reloadAssets}
                initialStatusFilter={initialAssetStatusFilter}
              />
            </Suspense>
          </ProtectedRoute>
        );
      case 'sectors':
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
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
            </Suspense>
          </ProtectedRoute>
        );
      case 'manageSectorAssets':
        if (!managingSector) {
          setActiveView('sectors');
          return null;
        }
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
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
            </Suspense>
          </ProtectedRoute>
        );
      case 'users':
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
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
            </Suspense>
          </ProtectedRoute>
        );
      case 'custody':
        return (
          <ProtectedRoute requiredAbility="view:custody">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
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
            </Suspense>
          </ProtectedRoute>
        );
      case 'createCustody':
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
              <CreateCustody
                users={users}
                assets={assets}
                onCustodyCreated={() => {
                  reloadCustody();
                  reloadAssets();
                }}
                onBack={() => setActiveView('custody')}
              />
            </Suspense>
          </ProtectedRoute>
        );
      case 'inventory':
        return (
          <ProtectedRoute requiredAbility="view:inventory">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
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
            </Suspense>
          </ProtectedRoute>
        );
      case 'printLabels':
        return (
          <ProtectedRoute requiredAbility="edit:all">
            <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
              <PrintLabels assets={assets} />
            </Suspense>
          </ProtectedRoute>
        );
      case 'profile':
        return (
          <Suspense fallback={<div className="flex items-center justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div></div>}>
            <UserProfile />
          </Suspense>
        );
      default:
        return <Dashboard assets={assets} users={users} sectors={sectors} logs={custodyLogs} />;
    }
  };

  return (
    <div className="flex h-screen bg-gray-100 font-sans">
      {/* SIDEBAR SIMPLIFICADO PARA DEBUG */}
      <div className="w-64 bg-white shadow-md p-4">
        <h1 className="text-xl font-bold text-gray-800 mb-4">SGTI-GAC</h1>
        <button 
          onClick={() => setActiveView('dashboard')}
          className="w-full p-2 bg-blue-600 text-white rounded mb-2"
        >
          Dashboard
        </button>
        <button 
          onClick={() => {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            localStorage.removeItem('auth_abilities');
            window.location.reload();
          }}
          className="w-full p-2 bg-red-600 text-white rounded"
        >
          Logout (Debug)
        </button>
      </div>
      {/* <Sidebar setActiveView={setActiveView} activeView={activeView} /> */}
      <div className="flex-1 flex flex-col overflow-hidden">
        <main className="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
          {renderView()}
        </main>
      </div>
    </div>
  );
};

// Componente principal com AuthProvider
const App: React.FC = () => {
  return (
    <AuthProvider>
      <NotificationProvider>
        <AuthenticatedApp />
      </NotificationProvider>
    </AuthProvider>
  );
};

export default App;
