import React, { useState, useMemo, useEffect, useRef } from 'react';
import { Asset, AssetStatus, MilitaryUser, InventoryRecord, InventoryAsset, Sector, AssetCategory } from '../types';
import { inventoryApi } from '../services/api';
import QrScannerModal from './QrScannerModal';
import InventoryDetailsModal from './InventoryDetailsModal';
import ReopenInventoryModal from './ReopenInventoryModal';

type ActiveInventorySession = {
  id: number;
  isNew: boolean;
  responsibleUserId: number;
  sectorId?: number;
  commissionNumber?: string;
  pending: InventoryAsset[];
  found: InventoryAsset[];
  uncatalogued: string[];
  observations: string;
};

const StartInventoryModal: React.FC<{
  users: MilitaryUser[];
  sectors: Sector[];
  onStart: (responsibleUserId: number, sectorId: number | 'all', commissionNumber?: string) => void;
  onCancel: () => void;
}> = ({ users, sectors, onStart, onCancel }) => {
  const [responsibleUserId, setResponsibleUserId] = useState<string>('');
  const [sectorId, setSectorId] = useState<string>('all');
  const [commissionNumber, setCommissionNumber] = useState<string>('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!responsibleUserId) {
      alert('Por favor, selecione um militar responsável.');
      return;
    }
    onStart(Number(responsibleUserId), sectorId === 'all' ? 'all' : Number(sectorId), commissionNumber.trim() || undefined);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">Iniciar Nova Contagem de Inventário</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label htmlFor="sector" className="block text-sm font-medium text-gray-700">Setor</label>
            <select
              id="sector"
              value={sectorId}
              onChange={(e) => setSectorId(e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">Todos os Setores (Global)</option>
              {sectors.map(s => (
                <option key={s.id} value={s.id}>{s.name}</option>
              ))}
            </select>
          </div>
          <div className="mb-4">
            <label htmlFor="responsibleUser" className="block text-sm font-medium text-gray-700">Responsável</label>
            <select
              id="responsibleUser"
              value={responsibleUserId}
              onChange={(e) => setResponsibleUserId(e.target.value)}
              required
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Selecione um militar</option>
              {users.filter(u => u.is_active).map(u => (
                <option key={u.id} value={u.id}>{u.rank} {u.name}</option>
              ))}
            </select>
          </div>
          <div className="mb-6">
            <label htmlFor="commissionNumber" className="block text-sm font-medium text-gray-700">Nº da Comissão (Opcional)</label>
            <input
              type="text"
              id="commissionNumber"
              value={commissionNumber}
              onChange={(e) => setCommissionNumber(e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div className="flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
            <button type="submit" className="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Iniciar</button>
          </div>
        </form>
      </div>
    </div>
  );
};

interface InventoryManagementProps {
  assets: Asset[];
  setAssets: React.Dispatch<React.SetStateAction<Asset[]>>;
  users: MilitaryUser[];
  sectors: Sector[];
  inventoryRecords: InventoryRecord[];
  setInventoryRecords: React.Dispatch<React.SetStateAction<InventoryRecord[]>>;
}

const InventoryManagement: React.FC<InventoryManagementProps> = ({ assets, setAssets, users, sectors, inventoryRecords, setInventoryRecords }) => {
  const [activeSession, setActiveSession] = useState<ActiveInventorySession | null>(null);
  const [isStartModalOpen, setIsStartModalOpen] = useState(false);
  const [viewingRecord, setViewingRecord] = useState<InventoryRecord | null>(null);
  const [reopeningRecord, setReopeningRecord] = useState<InventoryRecord | null>(null);
  const [scannedCode, setScannedCode] = useState('');
  const [isScannerOpen, setIsScannerOpen] = useState(false);
  const [manualUncatalogued, setManualUncatalogued] = useState<string>('');
  const [sectorFilter, setSectorFilter] = useState<string>('all');

  const [editingAsset, setEditingAsset] = useState<InventoryAsset | null>(null);
  const [observingAsset, setObservingAsset] = useState<InventoryAsset | null>(null);

  const [selection, setSelection] = useState<{
    pending: Set<number>;
    found: Set<number>;
    uncatalogued: Set<string>;
  }>({ pending: new Set(), found: new Set(), uncatalogued: new Set() });

  const lastRecordId = useMemo(() => inventoryRecords.reduce((max, r) => r.id > max ? r.id : max, 0), [inventoryRecords]);

  useEffect(() => {
    setSelection({ pending: new Set(), found: new Set(), uncatalogued: new Set() });
  }, [activeSession]);

  const handleStartInventory = async (responsibleUserId: number, sectorId: number | 'all', commissionNumber?: string) => {
    try {
      const createdRecord = await inventoryApi.create({
        commission_number: commissionNumber || '',
        start_date: new Date().toISOString().split('T')[0],
        sector_id: sectorId === 'all' ? undefined : sectorId,
        responsible_user_id: responsibleUserId,
      });

      // Reload records to get the new one
      const data = await inventoryApi.getAll();
      setInventoryRecords(data);

      // Find the created record
      const record = data.find(r => r.id === createdRecord.id);
      if (record) {
        handleContinueInventory(record);
      }
      setIsStartModalOpen(false);
    } catch (error: any) {
      console.error('Error creating inventory:', error);
      alert('Erro ao criar inventário: ' + error.message);
    }
  };

  const handleContinueInventory = async (record: InventoryRecord) => {
    try {
      console.log("🔄 Carregando dados salvos do inventário...");
      
      // Carregar dados completos do backend
      const fullRecord = await inventoryApi.getById(record.id.toString());
      
      let pendingAssets: InventoryAsset[] = [];
      let foundAssets: InventoryAsset[] = [];
      
      // O backend retorna found_items via atributo calculado
      if (fullRecord.found_items && fullRecord.found_items.length > 0) {
        // Inventário existente com dados salvos
        foundAssets = fullRecord.found_items.map(item => ({
          ...item,
          observation: item.observation || ''
        }));
        
        // Calcular itens faltantes: todos os assets do setor menos os encontrados
        const foundAssetIds = new Set(foundAssets.map(a => a.id));
        if (fullRecord.sector_id) {
          pendingAssets = assets
            .filter(a => a.sector_id === fullRecord.sector_id && !foundAssetIds.has(a.id))
            .map(a => ({...a, observation: ''}));
        } else {
          pendingAssets = assets
            .filter(a => !foundAssetIds.has(a.id))
            .map(a => ({...a, observation: ''}));
        }
      } else {
        // Inventário sem dados salvos - gerar lista inicial
        if (fullRecord.sector_id) {
          pendingAssets = assets
            .filter(a => a.sector_id === fullRecord.sector_id)
            .map(a => ({...a, observation: ''}));
        } else {
          pendingAssets = assets.map(a => ({...a, observation: ''}));
        }
        foundAssets = [];
      }
      
      // Extrair descrições dos itens não catalogados
      const uncataloguedDescriptions = fullRecord.uncatalogued_items || [];
      
      setActiveSession({
        id: fullRecord.id,
        isNew: false,
        responsibleUserId: fullRecord.responsible_user_id || 0,
        sectorId: fullRecord.sector_id,
        commissionNumber: fullRecord.commission_number,
        pending: pendingAssets,
        found: foundAssets,
        uncatalogued: uncataloguedDescriptions,
        observations: fullRecord.notes || '',
      });
      
      console.log("✅ Dados carregados:", {
        found: foundAssets.length,
        pending: pendingAssets.length,
        uncatalogued: uncataloguedDescriptions.length,
        foundIds: foundAssets.map(a => a.id),
        pendingIds: pendingAssets.map(a => a.id)
      });
      
    } catch (error) {
      console.error("❌ Erro ao carregar dados do inventário:", error);
      alert('Erro ao carregar dados salvos. Tentando continuar...');
      
      // Fallback: usar dados básicos do record
      let pendingAssets: InventoryAsset[] = [];
      if (record.sector_id) {
        pendingAssets = assets
          .filter(a => a.sector_id === record.sector_id)
          .map(a => ({...a, observation: ''}));
      } else {
        pendingAssets = assets.map(a => ({...a, observation: ''}));
      }
      
      setActiveSession({
        id: record.id,
        isNew: false,
        responsibleUserId: record.responsible_user_id || 0,
        sectorId: record.sector_id,
        commissionNumber: record.commission_number,
        pending: pendingAssets,
        found: [],
        uncatalogued: [],
        observations: record.notes || '',
      });
    }
  };

  const handleScan = (codeToScan?: string) => {
    if (!activeSession) return;
    const code = (codeToScan || scannedCode).trim().toLowerCase();
    if (!code) return;

    setActiveSession(prev => {
        if (!prev) return null;
        const pendingAssetIndex = prev.pending.findIndex(a => (a.qr_code || '').toLowerCase() === code);

        if (pendingAssetIndex > -1) {
            const foundAsset = prev.pending[pendingAssetIndex];
            const newPending = [...prev.pending];
            newPending.splice(pendingAssetIndex, 1);

            return {
                ...prev,
                pending: newPending,
                found: [foundAsset, ...prev.found].sort((a,b) => (a.qr_code || '').localeCompare(b.qr_code || '')),
            };
        } else {
            const alreadyFound = prev.found.some(a => (a.qr_code || '').toLowerCase() === code);
            const alreadyUncatalogued = prev.uncatalogued.includes(code.toUpperCase());

            if (!alreadyFound && !alreadyUncatalogued) {
                return {
                    ...prev,
                    uncatalogued: [code.toUpperCase(), ...prev.uncatalogued]
                };
            }
        }
        return prev;
    });
    setScannedCode('');
  };

  const handleManualAddUncatalogued = () => {
    if (!activeSession) return;
    const value = manualUncatalogued.trim();
    if (value && !activeSession.uncatalogued.some(item => item.toLowerCase() === value.toLowerCase())) {
      setActiveSession(prev => {
        if (!prev) return null;
        return {
          ...prev,
          uncatalogued: [value, ...prev.uncatalogued],
        };
      });
      setManualUncatalogued('');
    }
  };

  const handleScanSuccess = (data: string) => {
    setIsScannerOpen(false);
    handleScan(data);
  };

  const handleKeyPress = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter') handleScan();
  };

  const handleSaveProgress = async () => {
    if (!activeSession) return;

    try {
      // Preparar dados para envio
      const updateData = {
        status: 'Em Andamento',
        notes: activeSession.observations,
        found_items: activeSession.found.map(asset => ({
          asset_id: asset.id,
          observation: asset.inventoryObservation || null
        })),
        uncatalogued_items: activeSession.uncatalogued
      };

      // Salvar no backend
      await inventoryApi.update(activeSession.id.toString(), updateData);

      // Atualizar estado local
      setInventoryRecords(prevRecords => prevRecords.map(rec => {
        if (rec.id === activeSession.id) {
          return {
            ...rec,
            foundItems: activeSession.found,
            pendingItems: activeSession.pending,
            uncataloguedItems: activeSession.uncatalogued,
            notes: activeSession.observations,
            summary: {
              total: activeSession.found.length + activeSession.pending.length,
              found: activeSession.found.length,
              pending: activeSession.pending.length,
              uncatalogued: activeSession.uncatalogued.length,
            },
          };
        }
        return rec;
      }));

      setActiveSession(null);
      alert('Progresso salvo com sucesso!');
    } catch (error) {
      console.error('Erro ao salvar progresso:', error);
      alert('Erro ao salvar progresso. Tente novamente.');
    }
  };

  const handleFinishInventory = async () => {
    if (!activeSession) return;
    if (!window.confirm("Tem certeza que deseja concluir este inventário?")) return;

    try {
      // Preparar dados para conclusão
      const updateData = {
        status: 'Concluído',
        end_date: new Date().toISOString().split('T')[0],
        notes: activeSession.observations,
        found_items: activeSession.found.map(asset => ({
          asset_id: asset.id,
          observation: asset.inventoryObservation || null
        })),
        uncatalogued_items: activeSession.uncatalogued
      };

      // Salvar no backend
      await inventoryApi.update(activeSession.id.toString(), updateData);

      // Atualizar estado local
      setInventoryRecords(prevRecords => prevRecords.map(rec => {
        if (rec.id === activeSession.id) {
          return {
            ...rec,
            end_date: new Date().toISOString(),
            status: 'Concluído',
            foundItems: activeSession.found,
            pendingItems: activeSession.pending,
            uncataloguedItems: activeSession.uncatalogued,
            notes: activeSession.observations,
            summary: {
              total: activeSession.found.length + activeSession.pending.length,
              found: activeSession.found.length,
              pending: activeSession.pending.length,
              uncatalogued: activeSession.uncatalogued.length,
            }
          };
        }
        return rec;
      }));

      setActiveSession(null);
      alert('Inventário concluído com sucesso!');
    } catch (error) {
      console.error('Erro ao concluir inventário:', error);
      alert('Erro ao concluir inventário. Tente novamente.');
    }
  };

  const handleDiscardAndExit = () => {
    if (!activeSession) return;
    if (window.confirm("Tem certeza que deseja sair? O progresso não salvo nesta sessão será perdido.")) {
      if (activeSession.isNew) {
        setInventoryRecords(prev => prev.filter(rec => rec.id !== activeSession.id));
      }
      setActiveSession(null);
    }
  };

  const handleReopenRequest = (record: InventoryRecord) => {
    setViewingRecord(null);
    setReopeningRecord(record);
  };

  const handleConfirmReopen = (justification: string) => {
    if (!reopeningRecord) return;
    const reopenedByUserId = 1; // Assuming user ID 1 for now

    let reopenedRecordForSession: InventoryRecord | null = null;

    setInventoryRecords(prev => prev.map(rec => {
        if (rec.id === reopeningRecord.id) {
            reopenedRecordForSession = {
                ...rec,
                status: 'Reaberto',
                completionDate: undefined, // Clear completion date on reopen
                reopenHistory: [
                    ...(rec.reopenHistory || []),
                    {
                        reopenDate: new Date().toISOString(),
                        reopenedByUserId: reopenedByUserId,
                        justification: justification,
                    }
                ]
            };
            return reopenedRecordForSession;
        }
        return rec;
    }));

    if (reopenedRecordForSession) {
        handleContinueInventory(reopenedRecordForSession);
    }

    setReopeningRecord(null);
  };

    const handleSaveAsset = (updatedAsset: Asset) => {
        // Update the global assets list
        setAssets(prev => prev.map(a => a.id === updatedAsset.id ? updatedAsset : a));

        // Update the asset in the active inventory session
        setActiveSession(prev => {
            if (!prev) return null;
            const updateList = (list: InventoryAsset[]) => list.map(a => a.id === updatedAsset.id ? { ...a, ...updatedAsset } : a);
            return {
                ...prev,
                found: updateList(prev.found),
                pending: updateList(prev.pending)
            };
        });
        setEditingAsset(null);
    };

    const handleSaveObservation = (assetId: number, observation: string) => {
        setActiveSession(prev => {
            if (!prev) return null;
            const updateList = (list: InventoryAsset[]) => list.map(a => a.id === assetId ? { ...a, inventoryObservation: observation } : a);
            return {
                ...prev,
                found: updateList(prev.found),
                pending: updateList(prev.pending)
            };
        });
        setObservingAsset(null);
    };

    const handleDeleteRecord = async (recordId: number) => {
        if (window.confirm("Tem certeza que deseja apagar permanentemente este registro de inventário? Esta ação não pode ser desfeita.")) {
          try {
            await inventoryApi.delete(recordId.toString());
            setInventoryRecords(prev => prev.filter(rec => rec.id !== recordId));
            alert('Inventário excluído com sucesso!');
          } catch (error) {
            console.error('Erro ao excluir inventário:', error);
            alert('Erro ao excluir inventário. Tente novamente.');
          }
        }
    };

  // --- Bulk Actions Handlers ---
  const handleToggleSelection = (list: 'pending' | 'found', id: number) => {
    setSelection(prev => {
        const newSet = new Set(prev[list]);
        if (newSet.has(id)) newSet.delete(id); else newSet.add(id);
        return { ...prev, [list]: newSet };
    });
  };

  const handleToggleUncatalogued = (code: string) => {
    setSelection(prev => {
        const newSet = new Set(prev.uncatalogued);
        if (newSet.has(code)) newSet.delete(code); else newSet.add(code);
        return { ...prev, uncatalogued: newSet };
    });
  };

  const handleSelectAll = (list: 'pending' | 'found' | 'uncatalogued') => {
    if (!activeSession) return;
    setSelection(prev => {
      const currentSelectionSet = prev[list];
      let allItemIds: (string | number)[];
      switch (list) {
        case 'pending': allItemIds = activeSession.pending.map(a => a.id); break;
        case 'found': allItemIds = activeSession.found.map(a => a.id); break;
        case 'uncatalogued': allItemIds = activeSession.uncatalogued; break;
      }
      const allSelected = currentSelectionSet.size === allItemIds.length && allItemIds.length > 0;
      const newSet = allSelected ? new Set() : new Set(allItemIds);
      return { ...prev, [list]: newSet as any };
    });
  };

  const handleMarkSelectedAsFound = async () => {
    if (!activeSession) return;
    
    try {
      console.log("🔄 Movendo itens para 'Encontrados'...");
      
      // Atualizar estado local primeiro (para responsividade)
      const itemsToMove = activeSession.pending.filter(a => selection.pending.has(a.id));
      setActiveSession(prev => {
          if (!prev) return null;
          const newPending = prev.pending.filter(a => !selection.pending.has(a.id));
          const newFound = [...prev.found, ...itemsToMove].sort((a,b) => (a.qr_code || '').localeCompare(b.qr_code || ''));
          return { ...prev, pending: newPending, found: newFound };
      });
      setSelection(prev => ({ ...prev, pending: new Set() }));
      
      // Persistir no backend
      const foundItems = [...activeSession.found, ...itemsToMove].map(item => ({
        asset_id: item.id,
        observation: item.observation || null
      }));
      
      await inventoryApi.update(activeSession.id.toString(), {
        found_items: foundItems,
        status: 'Em Andamento'
      });
      
      console.log("✅ Movimentação persistida com sucesso");
    } catch (error) {
      console.error("❌ Erro ao persistir movimentação:", error);
      alert('Erro ao salvar movimentação. Tente novamente.');
    }
  };

  const handleReturnSelectedToPending = async () => {
    if (!activeSession) return;
    
    try {
      console.log("🔄 Movendo itens para 'Faltantes'...");
      
      // Atualizar estado local primeiro (para responsividade)
      const itemsToMove = activeSession.found.filter(a => selection.found.has(a.id));
      setActiveSession(prev => {
          if (!prev) return null;
          const newFound = prev.found.filter(a => !selection.found.has(a.id));
          const newPending = [...prev.pending, ...itemsToMove].sort((a,b) => (a.qr_code || '').localeCompare(b.qr_code || ''));
          return { ...prev, found: newFound, pending: newPending };
      });
      setSelection(prev => ({ ...prev, found: new Set() }));
      
      // Persistir no backend
      const foundItems = activeSession.found.filter(a => !selection.found.has(a.id)).map(item => ({
        asset_id: item.id,
        observation: item.observation || null
      }));
      
      await inventoryApi.update(activeSession.id.toString(), {
        found_items: foundItems,
        status: 'Em Andamento'
      });
      
      console.log("✅ Movimentação persistida com sucesso");
    } catch (error) {
      console.error("❌ Erro ao persistir movimentação:", error);
      alert('Erro ao salvar movimentação. Tente novamente.');
    }
  };

  const handleRemoveSelectedUncatalogued = () => {
    if (!activeSession) return;
    setActiveSession(prev => {
        if (!prev) return null;
        const newUncatalogued = prev.uncatalogued.filter(code => !selection.uncatalogued.has(code));
        return { ...prev, uncatalogued: newUncatalogued };
    });
    setSelection(prev => ({ ...prev, uncatalogued: new Set() }));
  };

  const filteredRecords = useMemo(() => {
    return inventoryRecords.filter(record => {
        if (sectorFilter === 'all') return true;
        if (sectorFilter === 'global') return !record.sector_id;
        return record.sector_id === Number(sectorFilter);
    }).sort((a, b) => new Date(b.start_date).getTime() - new Date(a.start_date).getTime());
  }, [inventoryRecords, sectorFilter]);

  if (activeSession) {
    return (
      <div>
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold text-gray-800">Inventário em Andamento</h1>
          <div className="flex space-x-2">
            <button onClick={handleSaveProgress} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar Progresso e Sair</button>
            <button onClick={handleFinishInventory} className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Concluir Inventário</button>
            <button onClick={handleDiscardAndExit} className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">Descartar e Sair</button>
          </div>
        </div>

        <div className="bg-white p-6 rounded-lg shadow-md mb-6">
            <label className="block text-sm font-medium text-gray-700">Escanear QR Code</label>
            <div className="flex items-center mt-1">
                <input type="text" value={scannedCode} onChange={(e) => setScannedCode(e.target.value)} onKeyPress={handleKeyPress} placeholder="Digite o código do ativo e pressione Enter" className="flex-grow block w-full border-gray-300 rounded-l-md shadow-sm"/>
                 <button type="button" onClick={() => setIsScannerOpen(true)} className="px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300" title="Escanear QR Code"><svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h-1m-1-6v1M6 12H5m1-6V5m6 1v-1m-6 6H5m1-1v-1M9 4v1m0 11v1m0-6v1m6-1v1m0 6v1M9 18v1m6-1v1m-6-1v1m6-6v1m-1 1h1M9 12h1m6 0h-1m-1-1v-1m-1 6v-1m-1-1h-1m-1 6v-1m-1-1h-1m6-1h-1m-1-1v-1m-1 6v-1m-1-1h-1m6 0h-1"></path></svg></button>
                <button onClick={() => handleScan()} className="px-4 py-2 bg-gray-600 text-white rounded-r-md hover:bg-gray-700">Registrar</button>
            </div>
        </div>

        <div className="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div className="space-y-6">
                <AssetList
                    title="Faltantes"
                    assets={activeSession.pending}
                    selection={selection.pending}
                    onToggleSelection={(id) => handleToggleSelection('pending', id)}
                    onSelectAll={() => handleSelectAll('pending')}
                    onEditAsset={setEditingAsset}
                    onAddObservation={setObservingAsset}
                    actionButton={<ActionButton title="Conferir Selecionados" onClick={handleMarkSelectedAsFound} selectionSet={selection.pending} />}
                    users={users}
                    sectors={sectors}
                />
                 <AssetList
                    title="Conferidos"
                    assets={activeSession.found}
                    selection={selection.found}
                    onToggleSelection={(id) => handleToggleSelection('found', id)}
                    onSelectAll={() => handleSelectAll('found')}
                    onEditAsset={setEditingAsset}
                    onAddObservation={setObservingAsset}
                    actionButton={<ActionButton title="Desfazer Conferência" onClick={handleReturnSelectedToPending} selectionSet={selection.found} />}
                    users={users}
                    sectors={sectors}
                />
            </div>
            <div className="space-y-6">
              <div className="bg-white p-4 rounded-lg shadow-md flex flex-col">
                  <h3 className="font-bold text-lg text-yellow-700 border-b pb-2 mb-2">Não Catalogados ({activeSession.uncatalogued.length})</h3>
                  <ul className="flex-grow h-48 overflow-y-auto divide-y divide-gray-200">
                    <SelectAllRow listKey="uncatalogued" allIds={activeSession.uncatalogued} selectionSet={selection.uncatalogued} onSelectAll={handleSelectAll} />
                    {activeSession.uncatalogued.map(code => <ListItem key={code} id={code} label={code} isSelected={selection.uncatalogued.has(code)} onToggle={() => handleToggleUncatalogued(code)} />)}
                  </ul>
                  <div className="mt-2 pt-2 border-t">
                    <label className="text-xs font-medium text-gray-600">Adicionar Manualmente</label>
                    <div className="flex items-center mt-1">
                        <input type="text" value={manualUncatalogued} onChange={(e) => setManualUncatalogued(e.target.value)} onKeyPress={(e) => e.key === 'Enter' && handleManualAddUncatalogued()} placeholder="Descrição do item" className="flex-grow block w-full border-gray-300 rounded-l-md shadow-sm text-sm p-1.5"/>
                        <button type="button" onClick={handleManualAddUncatalogued} className="px-3 py-1.5 bg-gray-600 text-white rounded-r-md hover:bg-gray-700 text-xs">Adicionar</button>
                    </div>
                  </div>
                  <div className="mt-auto pt-2 border-t mt-2">
                      <ActionButton title="Remover Selecionados" onClick={handleRemoveSelectedUncatalogued} selectionSet={selection.uncatalogued} />
                  </div>
              </div>

              <div className="bg-white p-6 rounded-lg shadow-md">
                  <h3 className="text-lg font-bold text-gray-800 mb-2">Observações Gerais</h3>
                  <textarea value={activeSession.observations} onChange={(e) => setActiveSession(prev => prev ? { ...prev, observations: e.target.value } : null)} rows={4} className="w-full p-2 border border-gray-300 rounded-md shadow-sm" placeholder="Adicione notas sobre o estado dos itens, localização, ou qualquer outra observação relevante..."/>
              </div>
            </div>
        </div>

        {isScannerOpen && <QrScannerModal onScanSuccess={handleScanSuccess} onClose={() => setIsScannerOpen(false)} />}
        {editingAsset && <AssetEditModalForInventory asset={editingAsset} onSave={handleSaveAsset} onCancel={() => setEditingAsset(null)} allSectors={sectors} allUsers={users} />}
        {observingAsset && <ObservationModal asset={observingAsset} onSave={handleSaveObservation} onCancel={() => setObservingAsset(null)} />}
      </div>
    );
  }

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Histórico de Inventários</h1>
        <button onClick={() => setIsStartModalOpen(true)} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Iniciar Novo Inventário
        </button>
      </div>
        <div className="mb-4">
            <label htmlFor="sectorFilter" className="block text-sm font-medium text-gray-700">Filtrar por Setor</label>
            <select
              id="sectorFilter"
              value={sectorFilter}
              onChange={(e) => setSectorFilter(e.target.value)}
              className="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">Todos os Setores</option>
              <option value="global">Inventário Global</option>
              {sectors.map(s => (
                <option key={s.id} value={s.id}>{s.name}</option>
              ))}
            </select>
        </div>
       <div className="bg-white shadow-md rounded-lg overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">Data Início</th>
                <th scope="col" className="px-6 py-3">Setor</th>
                <th scope="col" className="px-6 py-3">Responsável</th>
                <th scope="col" className="px-6 py-3">Resultado Parcial</th>
                <th scope="col" className="px-6 py-3">Status</th>
                <th scope="col" className="px-6 py-3 text-center">Ações</th>
              </tr>
            </thead>
            <tbody>
              {filteredRecords.map(record => {
                const user = users.find(u => u.id === record.responsible_user_id) || 
                             record.responsible_user || 
                             record.responsibleUser ||
                             {
                               id: record.responsible_user_id || 0,
                               name: record.responsible_user_name || 'Usuário não encontrado',
                               rank: record.responsible_user_rank || 'N/A',
                               military_id: '',
                               is_active: true
                             };
                const sector = sectors.find(s => s.id === record.sector_id);
                
                // Criar summary se não existir (compatibilidade com API)
                const summary = record.summary || {
                  total: (record.found_items?.length || 0) + (record.pending_items?.length || 0),
                  found: record.found_items?.length || 0,
                  pending: record.pending_items?.length || 0,
                  uncatalogued: record.uncatalogued_items?.length || 0
                };
                const getStatusBadge = () => {
                    switch(record.status) {
                        case 'Concluído': return 'bg-green-100 text-green-800';
                        case 'Reaberto': return 'bg-yellow-100 text-yellow-800';
                        case 'Em Andamento': return 'bg-blue-100 text-blue-800';
                        default: return 'bg-gray-100 text-gray-800';
                    }
                }
                return (
                  <tr key={record.id} className="bg-white border-b hover:bg-gray-50">
                    <td className="px-6 py-4">{new Date(record.start_date).toLocaleDateString('pt-BR')}</td>
                    <td className="px-6 py-4 font-medium">{sector ? sector.name : 'Global'}</td>
                    <td className="px-6 py-4 font-medium text-gray-900">{user && user.rank && user.name ? `${user.rank} ${user.name}` : 'N/A'}</td>
                    <td className="px-6 py-4">
                        <span title={`Encontrados: ${summary.found}, Faltantes: ${summary.pending}, Não catalogados: ${summary.uncatalogued}`}>
                            {summary.found} / {summary.total}
                        </span>
                    </td>
                    <td className="px-6 py-4">
                       <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge()}`}>
                         {record.status}
                       </span>
                    </td>
                    <td className="px-6 py-4 text-center">
                        <div className="flex items-center justify-center space-x-1">
                           {(record.status === 'Em Andamento' || record.status === 'Reaberto') ? (
                             <button onClick={() => handleContinueInventory(record)} className="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Continuar
                             </button>
                           ) : (
                            <>
                                <button onClick={() => setViewingRecord(record)} className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600" title="Ver Detalhes">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                {record.status === 'Concluído' && (
                                     <button onClick={() => handleReopenRequest(record)} className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-orange-600" title="Reabrir Inventário">
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                                    </button>
                                )}
                            </>
                           )}
                           <button
                                onClick={() => handleDeleteRecord(record.id)}
                                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-red-600"
                                title="Apagar Inventário"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {isStartModalOpen && <StartInventoryModal users={users} sectors={sectors} onStart={handleStartInventory} onCancel={() => setIsStartModalOpen(false)} />}

      {viewingRecord && (
        <InventoryDetailsModal
          record={viewingRecord}
          responsibleUser={users.find(u => u.id === viewingRecord.responsible_user_id) || 
                           viewingRecord.responsible_user || 
                           viewingRecord.responsibleUser ||
                           {
                             id: viewingRecord.responsible_user_id || 0,
                             name: viewingRecord.responsible_user_name || 'Usuário não encontrado',
                             rank: viewingRecord.responsible_user_rank || 'N/A',
                             military_id: '',
                             is_active: true
                           }}
          users={users}
          onClose={() => setViewingRecord(null)}
          onReopenRequest={() => handleReopenRequest(viewingRecord)}
        />
      )}

      {reopeningRecord && (
        <ReopenInventoryModal
            onConfirm={handleConfirmReopen}
            onCancel={() => setReopeningRecord(null)}
        />
      )}
    </div>
  );
};


// --- Helper Components for Inventory Session ---

const getStatusBadge = (status: AssetStatus) => {
    switch (status) {
      case AssetStatus.InUse: return 'bg-teal-100 text-teal-800';
      case AssetStatus.Available: return 'bg-sky-100 text-sky-800';
      case AssetStatus.Maintenance: return 'bg-amber-100 text-amber-800';
      case AssetStatus.Decommissioned: return 'bg-rose-100 text-rose-800';
      default: return 'bg-gray-100 text-gray-800';
    }
};

const InventoryItemRow: React.FC<{
    asset: InventoryAsset; isSelected: boolean; onToggleSelection: () => void;
    onEdit: (asset: InventoryAsset) => void; onAddObservation: (asset: InventoryAsset) => void;
    sectors: Sector[]; users: MilitaryUser[];
}> = ({ asset, isSelected, onToggleSelection, onEdit, onAddObservation, sectors, users }) => {
    const sector = sectors.find(s => s.id === asset.currentSectorId)?.name || 'N/A';
    const user = users.find(u => u.id === asset.custodianUserId)?.name || 'N/A';

    return (
        <tr className={`border-b ${isSelected ? 'bg-blue-50' : 'bg-white'}`}>
            <td className="p-2 text-center">
                <input type="checkbox" checked={isSelected} onChange={onToggleSelection} className="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
            </td>
            <td className="p-2 font-mono text-xs">{asset.qr_code}</td>
            <td className="p-2 text-xs font-semibold">{asset.type}</td>
            <td className="p-2 text-xs">{asset.category}</td>
            <td className="p-2 text-xs">{sector}</td>
            <td className="p-2 text-xs">{user}</td>
            <td className="p-2 text-xs">
                <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusBadge(asset.status)}`}>{asset.status}</span>
            </td>
            <td className="p-2 text-center">
                <div className="flex items-center justify-center space-x-1">
                    <button onClick={() => onAddObservation(asset)} title="Adicionar Observação" className={`p-1 rounded-full ${asset.inventoryObservation ? 'text-green-600 bg-green-100' : 'text-gray-400 hover:bg-gray-100'}`}>
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </button>
                    <button onClick={() => onEdit(asset)} title="Editar Ativo" className="p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
                    </button>
                </div>
            </td>
        </tr>
    );
};

const AssetList: React.FC<{
    title: string; assets: InventoryAsset[]; selection: Set<number>;
    onToggleSelection: (id: number) => void; onSelectAll: () => void;
    onEditAsset: (asset: InventoryAsset) => void; onAddObservation: (asset: InventoryAsset) => void;
    actionButton: React.ReactNode; users: MilitaryUser[]; sectors: Sector[];
}> = ({ title, assets, selection, onToggleSelection, onSelectAll, onEditAsset, onAddObservation, actionButton, users, sectors }) => {
    const titleColor = title === 'Faltantes' ? 'text-red-700' : 'text-green-700';
    const allIds = assets.map(a => a.id);

    return (
        <div className="bg-white p-4 rounded-lg shadow-md flex flex-col">
            <h3 className={`font-bold text-lg ${titleColor} border-b pb-2 mb-2`}>{title} ({assets.length})</h3>
            <div className="flex-grow h-96 overflow-y-auto">
                <table className="w-full text-sm">
                    <thead className="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0">
                        <tr>
                            <th className="p-2 w-10 text-center"><input type="checkbox" onChange={onSelectAll} checked={allIds.length > 0 && selection.size === allIds.length}/></th>
                            <th className="p-2 text-left">QR Code</th>
                            <th className="p-2 text-left">Tipo</th>
                            <th className="p-2 text-left">Categoria</th>
                            <th className="p-2 text-left">Setor</th>
                            <th className="p-2 text-left">Cautelado</th>
                            <th className="p-2 text-left">Situação</th>
                            <th className="p-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {assets.map(asset => <InventoryItemRow key={asset.id} asset={asset} isSelected={selection.has(asset.id)} onToggleSelection={() => onToggleSelection(asset.id)} onEdit={onEditAsset} onAddObservation={onAddObservation} users={users} sectors={sectors} />)}
                    </tbody>
                </table>
            </div>
            <div className="mt-auto pt-2 border-t">{actionButton}</div>
        </div>
    );
};

const ObservationModal: React.FC<{ asset: InventoryAsset; onSave: (assetId: number, observation: string) => void; onCancel: () => void; }> = ({ asset, onSave, onCancel }) => {
    const [observation, setObservation] = useState(asset.inventoryObservation || '');
    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div className="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 className="text-lg font-bold mb-1">Observação do Item</h3>
                <p className="text-sm text-gray-600 mb-4">{asset.qr_code} - {asset.type}</p>
                <textarea value={observation} onChange={e => setObservation(e.target.value)} rows={5} className="w-full p-2 border rounded" placeholder="Ex: Tela arranhada, falta cabo de força..."/>
                <div className="flex justify-end space-x-2 mt-4">
                    <button onClick={onCancel} className="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
                    <button onClick={() => onSave(asset.id, observation)} className="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
                </div>
            </div>
        </div>
    );
};

// FIX: Complete the definition for AssetEditModalForInventory
const AssetEditModalForInventory: React.FC<{
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

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { name, value } = e.target;

        let newFormData: Partial<Asset> = {
            ...formData,
            [name]: value
        };

        if (name === 'currentSectorId' || name === 'custodianUserId') {
            newFormData[name] = value ? Number(value) : undefined;
        }

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

        setFormData(newFormData as Asset);
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
                <h2 className="text-2xl font-bold mb-2 text-gray-800">Editar Ativo (Inventário)</h2>
                <p className="text-sm text-gray-500 mb-6">{asset.type} ({asset.qr_code})</p>
                <form onSubmit={handleSubmit}>
                    <div className="space-y-4 max-h-[60vh] overflow-y-auto pr-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Tipo/Modelo</label>
                            <input type="text" name="type" value={formData.type || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Situação</label>
                            <select name="status" value={formData.status} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                {Object.values(AssetStatus).map(s => <option key={s} value={s}>{s}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Setor de Lotação</label>
                            <select name="currentSectorId" value={formData.currentSectorId} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                {allSectors.map(s => <option key={s.id} value={s.id}>{s.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Militar Responsável (Cautela)</label>
                            <select name="custodianUserId" value={formData.custodianUserId || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled={formData.status !== AssetStatus.InUse}>
                                <option value="">Nenhum</option>
                                {allUsers.filter(u => u.active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Nº de Série</label>
                            <input type="text" name="serialNumber" value={formData.serialNumber || ''} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Nº de Patrimônio</label>
                            <input type="text" name="patrimonyId" value={formData.patrimonyId || ''} onChange={handleChange} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
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

// FIX: Define missing ActionButton component
const ActionButton: React.FC<{ title: string; onClick: () => void; selectionSet: Set<any>; }> = ({ title, onClick, selectionSet }) => {
    return (
        <button
            onClick={onClick}
            disabled={selectionSet.size === 0}
            className="w-full px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
        >
            {title} ({selectionSet.size})
        </button>
    );
};

// FIX: Define missing SelectAllRow component
const SelectAllRow: React.FC<{
    listKey: 'pending' | 'found' | 'uncatalogued';
    allIds: (string | number)[];
    selectionSet: Set<any>;
    onSelectAll: (list: 'pending' | 'found' | 'uncatalogued') => void;
}> = ({ listKey, allIds, selectionSet, onSelectAll }) => {
    const allSelected = allIds.length > 0 && selectionSet.size === allIds.length;
    return (
        <li className="flex items-center p-2 bg-gray-100 font-semibold text-xs text-gray-600 sticky top-0 z-10">
            <input
                type="checkbox"
                checked={allSelected}
                onChange={() => onSelectAll(listKey)}
                className="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
            />
            <span>Selecionar Todos</span>
        </li>
    );
};

// FIX: Define missing ListItem component
const ListItem: React.FC<{
    id: string;
    label: string;
    isSelected: boolean;
    onToggle: () => void;
}> = ({ id, label, isSelected, onToggle }) => {
    return (
        <li className={`flex items-center p-2 text-sm ${isSelected ? 'bg-blue-50' : ''}`}>
            <input
                type="checkbox"
                checked={isSelected}
                onChange={onToggle}
                className="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"
            />
            <label className="flex-grow cursor-pointer" onClick={onToggle}>{label}</label>
        </li>
    );
};

// FIX: Add default export
export default InventoryManagement;
