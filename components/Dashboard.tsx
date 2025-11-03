import React from 'react';
import { Asset, Sector, MilitaryUser, CustodyLog, AssetStatus } from '../types';

interface StatCardProps {
  title: string;
  value: string | number;
  icon: React.ReactNode;
  color: string;
  onClick?: () => void;
}

const StatCard: React.FC<StatCardProps> = ({ title, value, icon, color, onClick }) => (
  <div 
    onClick={onClick}
    className={`bg-white p-6 rounded-lg shadow-md flex items-center ${onClick ? 'cursor-pointer hover:shadow-lg hover:scale-105 transition-transform duration-200' : ''}`}>
    <div className={`p-3 rounded-full mr-4 ${color}`}>
      {icon}
    </div>
    <div>
      <p className="text-sm font-medium text-gray-500">{title}</p>
      <p className="text-2xl font-bold text-gray-800">{value}</p>
    </div>
  </div>
);

interface DashboardProps {
    assets: Asset[];
    users: MilitaryUser[];
    sectors: Sector[];
    logs: CustodyLog[];
    setActiveView: (view: string) => void;
    navigateToAssetsWithFilter: (status: AssetStatus | 'all') => void;
}

const Dashboard: React.FC<DashboardProps> = ({ assets, users, sectors, logs, setActiveView, navigateToAssetsWithFilter }) => {
    // Garantir que todos os arrays sejam seguros
    const safeAssets = assets || [];
    const safeUsers = users || [];
    const safeSectors = sectors || [];
    const safeLogs = logs || [];
    
    const totalAssets = safeAssets.length;
    const assetsInUse = safeAssets.filter(a => a.status === AssetStatus.InUse).length;
    const assetsAvailable = safeAssets.filter(a => a.status === AssetStatus.Available).length;
    const assetsInMaintenance = safeAssets.filter(a => a.status === AssetStatus.Maintenance).length;

  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Total de Ativos" value={totalAssets} color="bg-blue-100 text-blue-600" icon={<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>} onClick={() => navigateToAssetsWithFilter('all')} />
        <StatCard title="Ativos Cautelados" value={assetsInUse} color="bg-green-100 text-green-600" icon={<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>} onClick={() => setActiveView('custody')} />
        <StatCard title="Disponíveis" value={assetsAvailable} color="bg-yellow-100 text-yellow-600" icon={<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>} onClick={() => navigateToAssetsWithFilter(AssetStatus.Available)} />
        <StatCard title="Em Manutenção" value={assetsInMaintenance} color="bg-red-100 text-red-600" icon={<svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>} onClick={() => navigateToAssetsWithFilter(AssetStatus.Maintenance)} />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="text-xl font-bold text-gray-700 mb-4">Cautelas Ativas Recentes</h2>
            <div className="overflow-x-auto">
                <table className="w-full text-sm text-left text-gray-500">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" className="px-6 py-3">Militar</th>
                            <th scope="col" className="px-6 py-3">Data</th>
                            <th scope="col" className="px-6 py-3">Nº Itens</th>
                        </tr>
                    </thead>
                    <tbody>
                        {safeLogs.filter(l => !l.checkin_date).slice(0, 5).map(log => {
                            const user = safeUsers.find(u => u.id === log.user_id);
                            return (
                                <tr key={log.id} className="bg-white border-b hover:bg-gray-50">
                                    <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{user ? `${user.rank} ${user.name}` : 'N/A'}</td>
                                    <td className="px-6 py-4">{new Date(log.checkout_date).toLocaleDateString('pt-BR')}</td>
                                    <td className="px-6 py-4">{log.assetIds?.length || 0}</td>
                                </tr>
                            )
                        })}
                    </tbody>
                </table>
            </div>
        </div>
        <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="text-xl font-bold text-gray-700 mb-4">Resumo por Setor</h2>
            <div className="overflow-x-auto">
                <table className="w-full text-sm text-left text-gray-500">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" className="px-6 py-3">Setor</th>
                            <th scope="col" className="px-6 py-3 text-center">Nº de Ativos</th>
                        </tr>
                    </thead>
                    <tbody>
                        {safeSectors.map(sector => {
                            const assetCount = safeAssets.filter(asset => {
                                let currentAssetSectorId;
                                if (asset.status === 'Em Uso' && asset.custodian_user_id) {
                                    const custodian = safeUsers.find(u => u.id === asset.custodian_user_id);
                                    currentAssetSectorId = custodian?.sector_id;
                                } else {
                                    currentAssetSectorId = asset.sector_id;
                                }
                                return currentAssetSectorId === sector.id;
                            }).length;
                            return (
                                <tr key={sector.id} className="bg-white border-b hover:bg-gray-50">
                                    <td className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{sector.name}</td>
                                    <td className="px-6 py-4 text-center">{assetCount}</td>
                                </tr>
                            )
                        })}
                    </tbody>
                </table>
            </div>
        </div>
      </div>

    </div>
  );
};

export default Dashboard;