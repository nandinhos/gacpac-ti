import React, { useState, useMemo, useEffect } from 'react';
import { Sector, Asset, MilitaryUser, AssetStatus, CustodyLog } from '../types';

// Local component for the editing modal
const AssetSectorEditModal: React.FC<{
  asset: Asset;
  onSave: (updatedAsset: Asset) => void;
  onCancel: () => void;
  allSectors: Sector[];
  allUsers: MilitaryUser[];
}> = ({ asset, onSave, onCancel, allSectors, allUsers }) => {
    const [formData, setFormData] = useState<Asset>(asset);

    useEffect(() => {
        setFormData(asset);
    }, [asset]);

    const handleChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        const { name, value } = e.target;
        
        let newFormData: Asset = { 
            ...formData, 
            [name]: name === 'currentSectorId' || name === 'custodianUserId' ? (value ? Number(value) : undefined) : value 
        };

        if (name === 'status') {
            const statusValue = value as AssetStatus;
            if (statusValue !== AssetStatus.InUse) {
                newFormData.custodianUserId = undefined;
            }
            if (statusValue === AssetStatus.Available) {
                const warehouse = allSectors.find(s => s.name.toLowerCase().includes('almoxarifado'));
                if (warehouse) {
                    newFormData.currentSectorId = warehouse.id;
                }
            }
        }
        
        setFormData(newFormData);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (formData.status === AssetStatus.InUse && !formData.custodianUserId) {
            alert('Um militar deve ser selecionado para ativos com status "Em Uso".');
            return;
        }
        onSave(formData);
    };
    
    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
                <h2 className="text-2xl font-bold mb-2 text-gray-800">Editar Ativo</h2>
                <p className="text-sm text-gray-500 mb-6">{asset.type} ({asset.qrCode})</p>
                <form onSubmit={handleSubmit}>
                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Setor de Lotação</label>
                            <select name="currentSectorId" value={formData.currentSectorId} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                {allSectors.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Situação</label>
                            <select name="status" value={formData.status} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                {Object.values(AssetStatus).map(s => <option key={s} value={s}>{s}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Militar Responsável (Cautela)</label>
                            <select name="custodianUserId" value={formData.custodianUserId || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled={formData.status !== AssetStatus.InUse}>
                                <option value="">Nenhum</option>
                                {allUsers.filter(u => u.active && u.sectorId === formData.currentSectorId).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
                            </select>
                        </div>
                    </div>
                    <div className="mt-8 flex justify-end space-x-4">
                        <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
                        <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    );
};


interface SectorAssetManagerProps {
  sector: Sector;
  assets: Asset[];
  setAssets: React.Dispatch<React.SetStateAction<Asset[]>>;
  users: MilitaryUser[];
  allSectors: Sector[];
  onBack: () => void;
  custodyLogs: CustodyLog[];
}

const getStatusBadge = (status: AssetStatus) => {
    switch (status) {
        case AssetStatus.InUse: return 'bg-teal-100 text-teal-800';
        case AssetStatus.Available: return 'bg-sky-100 text-sky-800';
        case AssetStatus.Maintenance: return 'bg-amber-100 text-amber-800';
        case AssetStatus.Decommissioned: return 'bg-rose-100 text-rose-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const AssetRow: React.FC<{ asset: Asset; onEdit: (asset: Asset) => void; }> = ({ asset, onEdit }) => (
    <tr className="bg-white border-b hover:bg-gray-50">
        <td className="px-4 py-2 font-mono text-xs">{asset.qrCode}</td>
        <td className="px-4 py-2 font-medium text-gray-900 text-sm">{asset.type}</td>
        <td className="px-4 py-2">
            <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge(asset.status)}`}>
                {asset.status}
            </span>
        </td>
        <td className="px-4 py-2 text-right">
            <button
                onClick={() => onEdit(asset)}
                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                title="Editar Ativo"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
        </td>
    </tr>
);

const AssetTable: React.FC<{
    assets: Asset[];
    onEdit: (asset: Asset) => void;
}> = ({ assets, onEdit }) => {
    if (assets.length === 0) {
        return <p className="text-center py-4 text-gray-500 text-sm">Nenhum componente de estação de trabalho atribuído diretamente.</p>;
    }
    return (
        <div className="overflow-x-auto border rounded-lg">
            <table className="w-full text-sm text-left text-gray-500">
                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" className="px-4 py-2">QR Code</th>
                        <th scope="col" className="px-4 py-2">Tipo/Modelo</th>
                        <th scope="col" className="px-4 py-2">Situação</th>
                        <th scope="col" className="px-4 py-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {assets.map(asset => (
                        <AssetRow key={asset.id} asset={asset} onEdit={onEdit} />
                    ))}
                </tbody>
            </table>
        </div>
    );
};

const SectorAssetManager: React.FC<SectorAssetManagerProps> = ({ sector, assets, setAssets, users, allSectors, onBack, custodyLogs }) => {
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [assetToEdit, setAssetToEdit] = useState<Asset | null>(null);
  const [openUserIds, setOpenUserIds] = useState<Set<number>>(new Set());

  const { assetsByUser, unassignedAssets, usersWithAssetsOrLogs } = useMemo(() => {
    const assetsInSector = assets.filter(a => a.currentSectorId === sector.id);
    const assetsByUser = new Map<number, Asset[]>();
    const unassignedAssets: Asset[] = [];
    const userIdsWithActivity = new Set<number>();

    // Group assets by custodian and find unassigned ones
    assetsInSector.forEach(asset => {
      if (asset.custodianUserId) {
        if (!assetsByUser.has(asset.custodianUserId)) {
          assetsByUser.set(asset.custodianUserId, []);
        }
        assetsByUser.get(asset.custodianUserId)!.push(asset);
        userIdsWithActivity.add(asset.custodianUserId);
      } else {
        unassignedAssets.push(asset);
      }
    });

    // Find users in this sector with active custody logs
    custodyLogs.forEach(log => {
      if (!log.checkinDate) {
          const user = users.find(u => u.id === log.userId);
          if (user && user.sectorId === sector.id) {
            userIdsWithActivity.add(log.userId);
          }
      }
    });

    // Get the full user objects and sort them
    const usersWithAssetsOrLogs = Array.from(userIdsWithActivity)
        .map(id => users.find(u => u.id === id))
        .filter((u): u is MilitaryUser => !!u)
        .sort((a,b) => a.name.localeCompare(b.name));

    return { assetsByUser, unassignedAssets, usersWithAssetsOrLogs };
  }, [assets, sector.id, custodyLogs, users]);

  const toggleUserSection = (userId: number) => {
    setOpenUserIds(prev => {
        const newSet = new Set(prev);
        if (newSet.has(userId)) newSet.delete(userId); else newSet.add(userId);
        return newSet;
    });
  };

  const handleEdit = (asset: Asset) => {
    setAssetToEdit(asset);
    setIsEditModalOpen(true);
  };

  const handleSave = (updatedAsset: Asset) => {
    setAssets(prevAssets => prevAssets.map(a => a.id === updatedAsset.id ? updatedAsset : a));
    setIsEditModalOpen(false);
    setAssetToEdit(null);
  };

  return (
    <div>
        <div className="flex justify-between items-center mb-6">
            <div>
                <button onClick={onBack} className="text-sm text-blue-600 hover:underline mb-2 flex items-center">
                    <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path></svg>
                    Voltar para Setores
                </button>
                <h1 className="text-3xl font-bold text-gray-800">Gerenciamento de Ativos - {sector.name}</h1>
            </div>
        </div>
        
        <div className="space-y-4">
            {usersWithAssetsOrLogs.map(user => {
                const userWorkstationAssets = assetsByUser.get(user.id) || [];
                const userActiveLogs = custodyLogs.filter(log => log.userId === user.id && !log.checkinDate);
                const isOpen = openUserIds.has(user.id);
                
                return (
                    <div key={user.id} className="bg-white shadow-md rounded-lg overflow-hidden transition-all duration-300">
                        <button onClick={() => toggleUserSection(user.id)} className="w-full p-4 text-left flex justify-between items-center bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <h2 className="text-lg font-bold text-gray-700">{user.rank} {user.name}</h2>
                            <svg className={`w-6 h-6 text-gray-500 transition-transform duration-300 ${isOpen ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        {isOpen && (
                            <div className="p-6 space-y-6 bg-white border-t">
                                <div>
                                    <h3 className="text-md font-semibold text-gray-600 mb-3 flex items-center">
                                        <svg className="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Componentes da Estação de Trabalho
                                    </h3>
                                    <AssetTable assets={userWorkstationAssets} onEdit={handleEdit} />
                                </div>
                                {userActiveLogs.length > 0 && (
                                    <div>
                                        <h3 className="text-md font-semibold text-gray-600 mb-3 flex items-center">
                                            <svg className="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            Cautelas Ativas
                                        </h3>
                                        <div className="space-y-3">
                                            {userActiveLogs.map(log => (
                                                <div key={log.id} className="border rounded-lg p-3 bg-gray-50">
                                                    <p className="font-semibold text-gray-800">{log.cautelaNumber} <span className="font-normal text-sm text-gray-500">({new Date(log.checkoutDate).toLocaleDateString('pt-BR')})</span></p>
                                                    <ul className="list-disc list-inside ml-4 mt-2 text-sm text-gray-700 space-y-1">
                                                        {log.assetIds.map(id => {
                                                            const asset = assets.find(a => a.id === id);
                                                            return asset ? <li key={id}>{asset.qrCode} - {asset.type}</li> : <li key={id}>ID: {id} (Não encontrado)</li>;
                                                        })}
                                                    </ul>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                );
            })}
        </div>

        <div className="mt-8">
            <div className="bg-white shadow-md rounded-lg overflow-hidden">
                <div className="p-4 bg-gray-50 border-b">
                    <h2 className="text-lg font-bold text-gray-700">Ativos do Setor (Sem Responsável Direto)</h2>
                </div>
                <div className="p-4">
                    <AssetTable assets={unassignedAssets} onEdit={handleEdit} />
                </div>
            </div>
        </div>

        {isEditModalOpen && assetToEdit && (
            <AssetSectorEditModal
                asset={assetToEdit}
                onSave={handleSave}
                onCancel={() => setIsEditModalOpen(false)}
                allSectors={allSectors}
                allUsers={users}
            />
        )}
    </div>
  );
};

export default SectorAssetManager;
