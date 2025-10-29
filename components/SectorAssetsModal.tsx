import React, { useState, useMemo } from 'react';
import { Sector, Asset, MilitaryUser, AssetStatus } from '../types';

// --- SUB-COMPONENTS for the new Dashboard Layout ---

interface StatCardProps {
  title: string;
  value: string | number;
  icon: React.ReactNode;
}

const StatCard: React.FC<StatCardProps> = ({ title, value, icon }) => (
  <div className="bg-white p-4 rounded-lg shadow-md flex items-center">
    <div className="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">{icon}</div>
    <div>
      <p className="text-sm font-medium text-gray-500">{title}</p>
      <p className="text-2xl font-bold text-gray-800">{value}</p>
    </div>
  </div>
);

const AssetCard: React.FC<{ asset: Asset }> = ({ asset }) => (
    <div className="bg-gray-50 border border-gray-200 rounded-lg p-3 flex justify-between items-center">
        <div>
            <p className="font-semibold text-gray-800">{asset.name}</p>
            <p className="text-xs text-gray-500 font-mono">{asset.qr_code} | {asset.patrimony_id || 'Sem Patrimônio'}</p>
        </div>
        <span className={`px-2 py-1 text-xs font-medium rounded-full ${asset.status === AssetStatus.InUse ? 'bg-teal-100 text-teal-800' : 'bg-sky-100 text-sky-800'}`}>
            {asset.status}
        </span>
    </div>
);

// --- MAIN COMPONENT ---

interface SectorAssetManagerProps {
  sector: Sector;
  assets: Asset[];
  users: MilitaryUser[];
  onBack: () => void;
  // Props below are not used in this new design but kept for interface compatibility
  setAssets: React.Dispatch<React.SetStateAction<Asset[]>>;
  allSectors: Sector[];
  custodyLogs: any[];
}

const SectorAssetManager: React.FC<SectorAssetManagerProps> = ({ sector, assets, users, onBack }) => {
  const [openUserIds, setOpenUserIds] = useState<Set<string>>(new Set());

  const sectorData = useMemo(() => {
    const usersInSector = users.filter(u => u.sector_id === sector.id);
    const userIdsInSector = new Set(usersInSector.map(u => u.id));

    const assetsInCustodyOfSectorUsers = assets.filter(a => a.custodian_user_id && userIdsInSector.has(a.custodian_user_id));
    const availableAssetsInSector = assets.filter(a => a.sector_id === sector.id && a.status === AssetStatus.Available);
    
    const totalAssetsInSector = new Set([...assetsInCustodyOfSectorUsers, ...availableAssetsInSector]).size;

    const assetsByUser = new Map<string, Asset[]>();
    assetsInCustodyOfSectorUsers.forEach(asset => {
        if (!assetsByUser.has(asset.custodian_user_id!)) {
            assetsByUser.set(asset.custodian_user_id!, []);
        }
        assetsByUser.get(asset.custodian_user_id!)!.push(asset);
    });

    return {
        usersInSector,
        assetsByUser,
        availableAssetsInSector,
        totalAssetsInSector,
        totalCustodians: assetsByUser.size,
    };
  }, [sector, assets, users]);

  const toggleUserSection = (userId: string) => {
    setOpenUserIds(prev => {
        const newSet = new Set(prev);
        if (newSet.has(userId)) newSet.delete(userId); else newSet.add(userId);
        return newSet;
    });
  };

  const ICONS = {
    assets: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>,
    users: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 006-6v-1a4 4 0 00-4-4h-4a4 4 0 00-4 4v1a6 6 0 006 6z"></path></svg>,
    inUse: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>,
    available: <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>,
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <div>
          <button onClick={onBack} className="text-sm text-blue-600 hover:underline mb-2 flex items-center">
            <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path></svg>
            Voltar para Setores
          </button>
          <h1 className="text-3xl font-bold text-gray-800">Dashboard do Setor: {sector.name}</h1>
          <p className="text-gray-500 mt-1">{sector.description}</p>
        </div>
      </div>

      {/* Stat Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Total de Ativos no Setor" value={sectorData.totalAssetsInSector} icon={ICONS.assets} />
        <StatCard title="Militares no Setor" value={sectorData.usersInSector.length} icon={ICONS.users} />
        <StatCard title="Responsáveis com Ativos" value={sectorData.totalCustodians} icon={ICONS.inUse} />
        <StatCard title="Ativos Disponíveis" value={sectorData.availableAssetsInSector.length} icon={ICONS.available} />
      </div>

      {/* Main Content */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Left Column: Users and their assets */}
        <div className="lg:col-span-2 space-y-4">
            <h2 className="text-xl font-bold text-gray-700 border-b pb-2">Ativos por Militar</h2>
            {sectorData.usersInSector.length > 0 ? sectorData.usersInSector.map(user => {
                const userAssets = sectorData.assetsByUser.get(user.id) || [];
                if (userAssets.length === 0) return null; // Only show users with assets
                const isOpen = openUserIds.has(user.id);
                
                return (
                    <div key={user.id} className="bg-white shadow-md rounded-lg overflow-hidden transition-all duration-300">
                        <button onClick={() => toggleUserSection(user.id)} className="w-full p-4 text-left flex justify-between items-center bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div className="flex items-center">
                                <span className={`w-2.5 h-2.5 rounded-full mr-3 ${user.is_active ? 'bg-green-500' : 'bg-gray-400'}`}></span>
                                <h3 className="text-lg font-bold text-gray-700">{user.rank} {user.name}</h3>
                            </div>
                            <div className="flex items-center">
                                <span className="text-sm text-gray-500 mr-2">{userAssets.length} ativo(s)</span>
                                <svg className={`w-6 h-6 text-gray-500 transition-transform duration-300 ${isOpen ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        {isOpen && (
                            <div className="p-4 space-y-3 border-t">
                                {userAssets.map(asset => <AssetCard key={asset.id} asset={asset} />)}
                            </div>
                        )}
                    </div>
                );
            }) : <p className="text-center py-4 text-gray-500">Nenhum militar neste setor.</p>}
        </div>

        {/* Right Column: Available assets */}
        <div className="bg-white shadow-md rounded-lg p-6">
            <h2 className="text-xl font-bold text-gray-700 border-b pb-2 mb-4">Ativos Disponíveis no Setor</h2>
            <div className="space-y-3 h-[60vh] overflow-y-auto pr-2">
                {sectorData.availableAssetsInSector.length > 0 ? 
                    sectorData.availableAssetsInSector.map(asset => <AssetCard key={asset.id} asset={asset} />) : 
                    <p className="text-center py-4 text-gray-500">Nenhum ativo disponível neste setor.</p>}
            </div>
        </div>
      </div>
    </div>
  );
};

export default SectorAssetManager;
