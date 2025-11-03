
// components/AssetManagement.tsx

import React, { useState, useMemo, useEffect } from 'react';
import { Asset, Sector, MilitaryUser, AssetStatus, AssetCategory, MaintenanceRecord, AssetPhoto } from '../types';
import { assetsApi } from '../services/api';
import { generateNewQrCode } from '../services/mockData';
import PhotoGalleryModal from './PhotoGalleryModal';
import QrScannerModal from './QrScannerModal';
import AssetMaintenanceModal from './AssetMaintenanceModal';
import AssetDetailsModal from './AssetDetailsModal';

const AssetRow: React.FC<{
  asset: Asset,
  sectors: Sector[],
  users: MilitaryUser[],
  onEdit: (asset: Asset) => void,
  onDelete: (id: number) => void,
  onViewPhotos: (photos: AssetPhoto[]) => void,
  onViewMaintenance: (asset: Asset) => void,
  onViewDetails: (asset: Asset) => void
}> = ({ asset, sectors, users, onEdit, onDelete, onViewPhotos, onViewMaintenance, onViewDetails }) => {
  const custodian = users.find(u => u.id === asset.custodian_user_id);
  const sector = asset.status === 'Em Uso' && custodian ? custodian.sector_name : asset.sector_name;
  const user = custodian?.name || 'N/A';

  const getStatusBadge = (status: AssetStatus) => {
    switch (status) {
      case AssetStatus.InUse:
        return 'bg-teal-100 text-teal-800';
      case AssetStatus.Available:
        return 'bg-sky-100 text-sky-800';
      case AssetStatus.Maintenance:
        return 'bg-amber-100 text-amber-800';
      case AssetStatus.Decommissioned:
        return 'bg-rose-100 text-rose-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  return (
    <tr className="bg-white border-b hover:bg-gray-50">
      <td className="px-6 py-4 font-mono text-sm text-gray-700">{asset.qr_code}</td>
      <td className="px-6 py-4 font-medium text-gray-900">{asset.name}</td>
      <td className="px-6 py-4">{asset.category}</td>
      <td className="px-6 py-4">{asset.bmp}</td>
      <td className="px-6 py-4">{asset.situacao}</td>
      <td className="px-6 py-4">{asset.qtd}</td>
      <td className="px-6 py-4">{asset.valor_atualizado}</td>
      <td className="px-6 py-4">{sector}</td>
      <td className="px-6 py-4">{user}</td>
      <td className="px-6 py-4">
        <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge(asset.status)}`}>
          {asset.status}
        </span>
      </td>
      <td className="px-6 py-4">
        <div className="flex items-center justify-end space-x-1">
            <button 
                onClick={() => onViewDetails(asset)}
                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-green-600 focus:outline-none"
                title="Ver Detalhes"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </button>
            {asset.photos && Array.isArray(asset.photos) && asset.photos.length > 0 && (
                <button 
                    onClick={() => onViewPhotos(asset.photos)} 
                    className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                    title="Ver Fotos"
                >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </button>
            )}
            <button 
                onClick={() => onViewMaintenance(asset)}
                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-orange-600 focus:outline-none"
                title="Histórico de Manutenção"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </button>
            <button 
                onClick={() => onEdit(asset)}
                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                title="Editar Ativo"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <button 
                onClick={() => onDelete(asset.id)}
                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-red-600 focus:outline-none"
                title="Excluir Ativo"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
      </td>
    </tr>
  );
};

const AssetForm: React.FC<{
  asset: Partial<Asset> | null,
  sectors: Sector[],
  users: MilitaryUser[],
  onSave: (asset: Asset) => void,
  onCancel: () => void,
  lastAssetId: number
}> = ({ asset, sectors, users, onSave, onCancel, lastAssetId }) => {
  const [formData, setFormData] = useState<Partial<Asset>>(
    asset || {
      status: AssetStatus.Available,
      category: AssetCategory.Computing,
      acquisitionDate: new Date().toISOString().split('T')[0]
    }
  );

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newAsset: Asset = {
      id: formData.id || lastAssetId + 1,
      qrCode: formData.qrCode || formData.qr_code || generateNewQrCode(lastAssetId),
      serialNumber: formData.serialNumber || '',
      name: formData.name || '',
      category: formData.category || AssetCategory.Computing,
      sector_id: formData.sector_id,
      custodian_user_id: formData.custodian_user_id,
      status: formData.status || AssetStatus.Available,
      acquisitionDate: formData.acquisitionDate || new Date().toISOString().split('T')[0],
      patrimonyId: formData.patrimonyId,
      warrantyEndDate: formData.warrantyEndDate,
      photos: formData.photos || [],
      maintenanceHistory: formData.maintenanceHistory || [],
      conta: formData.conta,
      categoria_inventario: formData.categoria_inventario,
      bmp: formData.bmp,
      componente: formData.componente,
      situacao: formData.situacao,
      qtd: formData.qtd,
      valor_atualizado: formData.valor_atualizado,
      deprec_acumulada: formData.deprec_acumulada,
      valor_liquido: formData.valor_liquido,
    };
    onSave(newAsset);
  };
  
  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-2xl max-h-screen overflow-y-auto">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">{formData.id ? 'Editar Ativo' : 'Adicionar Novo Ativo'}</h2>
        <form onSubmit={handleSubmit}>
          {/* Campo oculto para preservar QR code */}
          {formData.id && (
            <input type="hidden" name="qr_code" value={formData.qr_code || formData.qrCode || ''} />
          )}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="col-span-2">
              <label className="block text-sm font-medium text-gray-700">Nome/Modelo</label>
              <input type="text" name="name" value={formData.name || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Categoria</label>
              <select name="category" value={formData.category} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                {Object.values(AssetCategory).map(cat => <option key={cat} value={cat}>{cat}</option>)}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Situação</label>
              <select name="status" value={formData.status} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                {Object.values(AssetStatus).map(stat => <option key={stat} value={stat}>{stat}</option>)}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Nº de Série</label>
              <input type="text" name="serial_number" value={formData.serial_number || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Nº de Patrimônio</label>
              <input type="text" name="patrimony_id" value={formData.patrimony_id || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Setor Atual</label>
              <select name="sector_id" value={formData.sector_id} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Selecione um setor</option>
                {sectors.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Usuário Cautelado</label>
              <select name="custodian_user_id" value={formData.custodian_user_id || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                 <option value="">Nenhum</option>
                 {users.filter(u => u.is_active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Data de Aquisição</label>
              <input type="date" name="acquisition_date" value={formData.acquisition_date ? formData.acquisition_date.split('T')[0] : ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
             <div>
              <label className="block text-sm font-medium text-gray-700">Fim da Garantia</label>
              <input type="date" name="warranty_expiry" value={formData.warranty_expiry ? formData.warranty_expiry.split('T')[0] : ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Conta</label>
              <input type="text" name="conta" value={formData.conta || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Categoria do Inventário</label>
              <input type="text" name="categoria_inventario" value={formData.categoria_inventario || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">BMP</label>
              <input type="text" name="bmp" value={formData.bmp || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Componente</label>
              <input type="text" name="componente" value={formData.componente || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Situação do Inventário</label>
              <input type="text" name="situacao" value={formData.situacao || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Quantidade</label>
              <input type="number" name="qtd" value={formData.qtd || 1} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Valor Atualizado</label>
              <input type="number" name="valor_atualizado" value={formData.valor_atualizado || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Depreciação Acumulada</label>
              <input type="number" name="deprec_acumulada" value={formData.deprec_acumulada || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Valor Líquido</label>
              <input type="number" name="valor_liquido" value={formData.valor_liquido || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
            </div>
          </div>
          <div className="mt-8 flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  );
};

const AssetManagement: React.FC<{
  assets: Asset[],
  setAssets: React.Dispatch<React.SetStateAction<Asset[]>>,
  sectors: Sector[],
  users: MilitaryUser[],
  onDataChange: () => void,
  initialStatusFilter?: AssetStatus | 'all';
}> = ({ assets, setAssets, sectors, users, onDataChange, initialStatusFilter = 'all' }) => {
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingAsset, setEditingAsset] = useState<Partial<Asset> | null>(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState<AssetStatus | 'all'>('all');
  const [viewingPhotos, setViewingPhotos] = useState<AssetPhoto[] | null>(null);
  const [isScannerOpen, setIsScannerOpen] = useState(false);
  const [viewingMaintenanceAsset, setViewingMaintenanceAsset] = useState<Asset | null>(null);
  const [viewingAsset, setViewingAsset] = useState<Asset | null>(null);

  useEffect(() => {
    if (initialStatusFilter) {
      setStatusFilter(initialStatusFilter);
    }
  }, [initialStatusFilter]);

  const handleAdd = () => {
    setEditingAsset(null);
    setIsFormOpen(true);
  };
  
  const handleEdit = (asset: Asset) => {
    setEditingAsset(asset);
    setIsFormOpen(true);
  };

  const handleDelete = async (id: number) => {
    if (window.confirm('Tem certeza que deseja excluir este ativo?')) {
      try {
        await assetsApi.delete(String(id));
        setAssets(assets.filter(a => a.id !== id));
        onDataChange(); // Recarregar dados da API
      } catch (error) {
        console.error('Erro ao excluir ativo:', error);
        alert('Erro ao excluir ativo. Tente novamente.');
      }
    }
  };

  const handleSave = async (asset: Asset) => {
    try {
      if (editingAsset && editingAsset.id) {
        // Edição - chamar API
        const updatedAsset = await assetsApi.update(String(asset.id), asset);
        setAssets(assets.map(a => a.id === asset.id ? updatedAsset.data || updatedAsset : a));
      } else {
        // Criação - chamar API
        const newAsset = await assetsApi.create(asset);
        setAssets([...assets, newAsset.data || newAsset]);
      }
      setIsFormOpen(false);
      setEditingAsset(null);
      onDataChange(); // Recarregar dados da API para garantir sincronização
    } catch (error) {
      console.error('Erro ao salvar ativo:', error);
      alert('Erro ao salvar ativo. Tente novamente.');
    }
  };

  const handleViewPhotos = (photos: AssetPhoto[]) => {
    setViewingPhotos(photos);
  };

  const handleViewDetails = (asset: Asset) => {
    setViewingAsset(asset);
  };

  const handleUpdateAsset = (updatedAsset: Asset) => {
    setAssets(prevAssets => prevAssets.map(a => a.id === updatedAsset.id ? updatedAsset : a));
    setViewingAsset(updatedAsset); // Keep the modal open with updated data
    onDataChange(); // Reload data from API to ensure sync
  };

  const handleViewMaintenance = (asset: Asset) => {
    setViewingMaintenanceAsset(asset);
  };
  
  const handleSaveMaintenance = (assetId: number, newHistory: MaintenanceRecord[]) => {
    setAssets(prevAssets =>
      prevAssets.map(asset =>
        asset.id === assetId ? { ...asset, maintenanceHistory: newHistory } : asset
      )
    );
  };

  const handleScanSuccess = (data: string) => {
    setSearchTerm(data);
    setIsScannerOpen(false);
  };

  const lastAssetId = useMemo(() => assets.reduce((max, a) => a.id > max ? a.id : max, 0), [assets]);

  const filteredAssets = useMemo(() => {
    return assets
      .filter(asset => {
        if (statusFilter === 'all') return true;
        return asset.status === statusFilter;
      })
      .filter(asset => {
        const term = searchTerm.toLowerCase();
        if (!term) return true;
        return (
          asset.name.toLowerCase().includes(term) ||
          asset.qrCode.toLowerCase().includes(term) ||
          asset.serialNumber.toLowerCase().includes(term) ||
          (asset.patrimonyId && asset.patrimonyId.toLowerCase().includes(term)) ||
          asset.category.toLowerCase().includes(term) ||
          (asset.conta && asset.conta.toLowerCase().includes(term)) ||
          (asset.categoria_inventario && asset.categoria_inventario.toLowerCase().includes(term)) ||
          (asset.bmp && asset.bmp.toLowerCase().includes(term)) ||
          (asset.componente && asset.componente.toLowerCase().includes(term))
        );
      });
  }, [assets, searchTerm, statusFilter]);

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Gestão de Ativos</h1>
        <button onClick={handleAdd} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Adicionar Ativo
        </button>
      </div>
      
      <div className="mb-4 relative">
        <input 
          type="text"
          placeholder="Buscar por tipo, QR Code, patrimônio, categoria, Nº de Série..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
        />
        <button
          onClick={() => setIsScannerOpen(true)}
          className="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-600"
          title="Escanear QR Code"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h-1m-1-6v1M6 12H5m1-6V5m6 1v-1m-6 6H5m1-1v-1M9 4v1m0 11v1m0-6v1m6-1v1m0 6v1M9 18v1m6-1v1m-6-1v1m6-6v1m-1 1h1M9 12h1m6 0h-1m-1-1v-1m-1 6v-1m-1-1h-1m-1 6v-1m-1-1h-1m6-1h-1m-1-1v-1m-1 6v-1m-1-1h-1m6 0h-1"></path></svg>
        </button>
      </div>

      <div className="flex flex-wrap items-center gap-2 mb-4">
        <span className="text-sm font-medium text-gray-700 mr-2">Filtrar por Situação:</span>
        <button
          onClick={() => setStatusFilter('all')}
          className={`px-3 py-1 text-xs font-medium rounded-full transition ${
            statusFilter === 'all' ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'
          }`}
        >
          Todos
        </button>
        {Object.values(AssetStatus).map(status => (
          <button
            key={status}
            onClick={() => setStatusFilter(status)}
            className={`px-3 py-1 text-xs font-medium rounded-full transition ${
              statusFilter === status ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'
            }`}
          >
            {status}
          </button>
        ))}
      </div>

      <div className="bg-white shadow-md rounded-lg overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">QR Code</th>
                <th scope="col" className="px-6 py-3">Tipo/Modelo</th>
                <th scope="col" className="px-6 py-3">Categoria</th>
                <th scope="col" className="px-6 py-3">BMP</th>
                <th scope="col" className="px-6 py-3">Situação</th>
                <th scope="col" className="px-6 py-3">Qtd</th>
                <th scope="col" className="px-6 py-3">Valor Atualizado</th>
                <th scope="col" className="px-6 py-3">Setor</th>
                <th scope="col" className="px-6 py-3">Cautelado por</th>
                <th scope="col" className="px-6 py-3">Situação</th>
                <th scope="col" className="px-6 py-3 text-right">Ações</th>
              </tr>
            </thead>
            <tbody>
              {filteredAssets.map(asset => (
                <AssetRow 
                    key={asset.id} 
                    asset={asset} 
                    sectors={sectors} 
                    users={users} 
                    onEdit={handleEdit} 
                    onDelete={handleDelete} 
                    onViewPhotos={handleViewPhotos}
                    onViewMaintenance={handleViewMaintenance}
                    onViewDetails={handleViewDetails}
                />
              ))}
            </tbody>
          </table>
        </div>
      </div>
      
      {isFormOpen && (
        <AssetForm 
          asset={editingAsset} 
          sectors={sectors} 
          users={users} 
          onSave={handleSave} 
          onCancel={() => setIsFormOpen(false)}
          lastAssetId={lastAssetId}
        />
      )}
      
      {viewingPhotos && (
        <PhotoGalleryModal
          photos={viewingPhotos}
          onClose={() => setViewingPhotos(null)}
        />
      )}

      {isScannerOpen && (
        <QrScannerModal
          onScanSuccess={handleScanSuccess}
          onClose={() => setIsScannerOpen(false)}
        />
      )}

      {viewingMaintenanceAsset && (
          <AssetMaintenanceModal
            asset={viewingMaintenanceAsset}
            onClose={() => setViewingMaintenanceAsset(null)}
            onSaveHistory={(newHistory) => {
              handleSaveMaintenance(viewingMaintenanceAsset.id, newHistory);
              setViewingMaintenanceAsset(prev => prev ? { ...prev, maintenanceHistory: newHistory } : null);
            }}
          />
      )}

      {viewingAsset && (
            <AssetDetailsModal
                asset={viewingAsset}
                sectors={sectors}
                users={users}
                onClose={() => setViewingAsset(null)}
                onUpdateAsset={handleUpdateAsset}
            />
        )}
    </div>
  );
};

export default AssetManagement;
