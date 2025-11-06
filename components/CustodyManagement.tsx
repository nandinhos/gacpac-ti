import React, { useState, useMemo } from 'react';
import { CustodyLog, MilitaryUser, Asset, AssetStatus } from '../types';
import QrScannerModal from './QrScannerModal';
import CustodyDetailsModal from './CustodyDetailsModal';
import ConfirmationModal from './ConfirmationModal';
import { custodyApi } from '../services/api';

// A interface para os dados do formulário foi simplificada
interface CustodyFormData {
  userId: string;
  assetIds: string[];
  notes?: string;
}

const CustodyForm: React.FC<{
  onSave: (data: CustodyFormData) => void;
  onCancel: () => void;
  users: MilitaryUser[];
  assets: Asset[];
}> = ({ onSave, onCancel, users, assets }) => {
  const [userId, setUserId] = useState<string>('');
  const [notes, setNotes] = useState<string>('');
  const [assetQrCode, setAssetQrCode] = useState('');
  const [selectedAssetIds, setSelectedAssetIds] = useState<string[]>([]);
  const [error, setError] = useState('');
  const [isScannerOpen, setIsScannerOpen] = useState(false);

  // Filtra apenas por ativos disponíveis
  const availableAssets = useMemo(() => assets.filter(a => a.status === 'Disponível'), [assets]);

  const handleAddAsset = (code?: string) => {
    const qrCode = (code || assetQrCode).trim().toLowerCase();
    if (!qrCode) return;

    const asset = availableAssets.find(a => a.qr_code.toLowerCase() === qrCode);
    if (asset) {
      if (!selectedAssetIds.includes(asset.id)) {
        setSelectedAssetIds([...selectedAssetIds, asset.id]);
        setAssetQrCode('');
        setError('');
      }
    } else {
      setError('Ativo não encontrado ou não disponível.');
    }
  };

  const handleRemoveAsset = (id: string) => {
    setSelectedAssetIds(selectedAssetIds.filter(assetId => assetId !== id));
  };
  
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!userId || selectedAssetIds.length === 0) {
      alert("Selecione um militar e adicione pelo menos um ativo.");
      return;
    }
    onSave({
      userId: userId,
      assetIds: selectedAssetIds,
      notes: notes,
    });
  };

  const selectedAssets = assets.filter(a => selectedAssetIds.includes(a.id));

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-3xl">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">Abrir Nova Cautela</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700">Militar Responsável</label>
            <select value={userId} onChange={(e) => setUserId(e.target.value)} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <option value="">Selecione um militar</option>
              {users.filter(u => u.is_active).map(u => <option key={u.id} value={u.id}>{u.rank} {u.name}</option>)}
            </select>
          </div>

          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700">Adicionar Ativo por QR Code</label>
            <div className="flex items-center mt-1">
              <input type="text" value={assetQrCode} onChange={(e) => setAssetQrCode(e.target.value)} onKeyPress={(e) => e.key === 'Enter' && handleAddAsset()} placeholder="Digite ou escaneie o QR Code" className="flex-grow block w-full border-gray-300 rounded-l-md shadow-sm"/>
              <button type="button" onClick={() => setIsScannerOpen(true)} className="px-3 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300" title="Escanear QR Code">
                 <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h-1m-1-6v1M6 12H5m1-6V5m6 1v-1m-6 6H5m1-1v-1M9 4v1m0 11v1m0-6v1m6-1v1m0 6v1M9 18v1m6-1v1m-6-1v1m6-6v1m-1 1h1M9 12h1m6 0h-1m-1-1v-1m-1 6v-1m-1-1h-1m-1 6v-1m-1-1h-1m6-1h-1m-1-1v-1m-1 6v-1m-1-1h-1m6 0h-1"></path></svg>
              </button>
              <button type="button" onClick={() => handleAddAsset()} className="px-4 py-2 bg-gray-600 text-white rounded-r-md hover:bg-gray-700">Adicionar</button>
            </div>
            {error && <p className="text-red-500 text-sm mt-1">{error}</p>}
          </div>
          
          <div className="mb-4 h-48 overflow-y-auto border rounded-md p-2 bg-gray-50">
            <h3 className="font-semibold mb-2">Ativos na Cautela:</h3>
            {selectedAssets.length === 0 ? (
                <p className="text-gray-500 text-sm">Nenhum ativo adicionado.</p>
            ) : (
                <ul>
                    {selectedAssets.map(asset => (
                    <li key={asset.id} className="flex justify-between items-center p-2 bg-white rounded shadow-sm mb-1">
                        <span>{asset.qr_code} - {asset.name}</span>
                        <button type="button" onClick={() => handleRemoveAsset(asset.id)} className="text-red-500 hover:text-red-700 p-1 rounded-full" title="Remover">
                          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </li>
                    ))}
                </ul>
            )}
          </div>

          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700">Observações</label>
            <textarea value={notes} onChange={e => setNotes(e.target.value)} rows={2} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
          </div>

          <div className="mt-8 flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar Cautela</button>
          </div>
        </form>
        {isScannerOpen && <QrScannerModal onScanSuccess={handleScanSuccess} onClose={() => setIsScannerOpen(false)} />}
      </div>
    </div>
  );
};

const CustodyManagement: React.FC<{
  custodyLogs: CustodyLog[];
  assets: Asset[];
  users: MilitaryUser[];
  onDataChange: () => void;
}> = ({ custodyLogs, assets, users, onDataChange }) => {
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [viewingLog, setViewingLog] = useState<CustodyLog | null>(null);
  const [searchTerm, setSearchTerm] = useState('');

  // Estados para modais de confirmação
  const [confirmModal, setConfirmModal] = useState<{
    isOpen: boolean;
    type: 'discharge';
    data?: any;
  }>({
    isOpen: false,
    type: 'discharge'
  });
  
  const handleSave = async (formData: CustodyFormData) => {
    try {
      const { nextCautelaNumber } = await custodyApi.getNextNumber();
      
      const payload = {
        cautelaNumber: nextCautelaNumber,
        userId: formData.userId,
        checkoutDate: new Date().toISOString().split('T')[0], // format YYYY-MM-DD
        assetIds: formData.assetIds,
        notes: formData.notes
      };

      await custodyApi.store(payload);
      
      onDataChange(); // Recarrega todos os dados do backend
      setIsFormOpen(false);
    } catch (error) {
      console.error('Erro ao salvar cautela:', error);
      alert('Não foi possível salvar a cautela. Verifique os dados e tente novamente.');
    }
  };

  const handleDischarge = (log: CustodyLog) => {
    setConfirmModal({
      isOpen: true,
      type: 'discharge',
      data: log
    });
  };

  const handleConfirmDischarge = async (justification?: string) => {
    const log = confirmModal.data;
    if (!log) return;

    try {
      await custodyApi.checkin(log.id, { checkinDate: new Date().toISOString().split('T')[0] });
      onDataChange(); // Recarrega do backend
      alert('Baixa realizada com sucesso!');
    } catch (error) {
      console.error('Erro ao dar baixa na cautela:', error);
      throw error; // Para ser tratado pelo modal
    }
  };

  const handleConfirmAction = async (justification?: string) => {
    switch (confirmModal.type) {
      case 'discharge':
        await handleConfirmDischarge(justification);
        break;
    }
  };

  const closeConfirmModal = () => {
    setConfirmModal({
      isOpen: false,
      type: 'discharge'
    });
  };

  const handleUploadSignedTerm = async (logId: string, fileUrl: string) => {
    try {
        await custodyApi.update(logId, { signedTermUrl: fileUrl });
        onDataChange(); // Recarrega do backend
    } catch (error) {
        console.error('Erro ao enviar termo assinado:', error);
        alert('Erro ao enviar termo assinado. Tente novamente.');
    }
  };

  const filteredLogs = useMemo(() => {
    const sortedLogs = [...custodyLogs].sort((a,b) => new Date(b.checkout_date).getTime() - new Date(a.checkout_date).getTime());
    if (!searchTerm.trim()) {
        return sortedLogs;
    }
    return sortedLogs.filter(log => {
        // A API ja retorna o user, entao nao precisamos procurar
        if (!log.user) return false;
        const term = searchTerm.toLowerCase();
        return (
            log.user.name.toLowerCase().includes(term) || 
            log.user.rank.toLowerCase().includes(term) || 
            log.cautela_number.toLowerCase().includes(term)
        );
    });
  }, [custodyLogs, searchTerm]);

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Gestão de Cautelas</h1>
        <button onClick={() => setIsFormOpen(true)} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Criar Cautela
        </button>
      </div>

      <div className="mb-4">
        <input
          type="text"
          placeholder="Buscar por Nº da Cautela, nome ou posto/graduação do militar..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>
      
      <div className="bg-white shadow-md rounded-lg overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">Nº Cautela</th>
                <th scope="col" className="px-6 py-3">Responsável</th>
                <th scope="col" className="px-6 py-3">Data Abertura</th>
                <th scope="col" className="px-6 py-3">Status</th>
                <th scope="col" className="px-6 py-3">Nº Itens</th>
                <th scope="col" className="px-6 py-3 text-right">Ações</th>
              </tr>
            </thead>
            <tbody>
              {filteredLogs.map(log => {
                const status = log.checkin_date ? 'Concluída' : 'Ativa';
                return (
                  <tr key={log.id} className="bg-white border-b hover:bg-gray-50">
                    <td className="px-6 py-4 font-mono">{log.cautela_number}</td>
                    <td className="px-6 py-4 font-medium text-gray-900">{log.user ? `${log.user.rank} ${log.user.name}` : 'N/A'}</td>
                    <td className="px-6 py-4">{new Date(log.checkout_date).toLocaleDateString('pt-BR')}</td>
                    <td className="px-6 py-4">
                      <span className={`px-2 py-1 text-xs font-medium rounded-full ${status === 'Ativa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`}>
                        {status}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-center">{log.assets?.length || 0}</td>
                    <td className="px-6 py-4 text-right">
                       <div className="flex items-center justify-end space-x-1">
                            <button
                                onClick={() => setViewingLog(log)}
                                className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-blue-600 focus:outline-none"
                                title="Ver Detalhes"
                            >
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                            {!log.checkin_date && (
                                <button
                                    onClick={() => handleDischarge(log)}
                                    className="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-green-600 focus:outline-none"
                                    title="Dar Baixa na Cautela"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </button>
                            )}
                       </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>
      
      {isFormOpen && (
        <CustodyForm 
          onSave={handleSave} 
          onCancel={() => setIsFormOpen(false)} 
          users={users}
          assets={assets}
        />
      )}
      {viewingLog && (
        <CustodyDetailsModal
          log={viewingLog}
          user={viewingLog.user!}
          assetsInLog={viewingLog.assets || []}
          onClose={() => setViewingLog(null)}
          onDischarge={(logId: string) => {
            const log = custodyLogs.find(l => l.id === logId);
            if (log) handleDischarge(log);
          }}
          onUploadSignedTerm={handleUploadSignedTerm}
        />
      )}

      {/* Modal de Confirmação */}
      <ConfirmationModal
        isOpen={confirmModal.isOpen}
        onClose={closeConfirmModal}
        onConfirm={handleConfirmAction}
        title="Dar Baixa na Cautela"
        message={`Tem certeza que deseja dar baixa na cautela ${confirmModal.data?.cautela_number}? Os ativos serão retornados ao almoxarifado.`}
        confirmText="Dar Baixa"
        type="warning"
        requireJustification={true}
        justificationLabel="Motivo da baixa"
        justificationPlaceholder="Ex: Devolução programada, transferência, fim de uso, etc."
        icon={
          <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7" />
          </svg>
        }
      />
    </div>
  );
};

export default CustodyManagement;