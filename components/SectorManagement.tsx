
import React, { useState, useMemo } from 'react';
import { Sector, Asset, MilitaryUser } from '../types';
import ConfirmationModal from './ConfirmationModal';

const SectorForm: React.FC<{
  sector: Partial<Sector> | null;
  onSave: (sector: Sector) => void;
  onCancel: () => void;
  lastSectorId: number;
}> = ({ sector, onSave, onCancel, lastSectorId }) => {
  const [formData, setFormData] = useState<Partial<Sector>>(
    sector || { name: '', description: '' }
  );

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.name) return;
    const newSector: Sector = {
      id: formData.id || lastSectorId + 1,
      name: formData.name,
      description: formData.description,
    };
    onSave(newSector);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
        <h2 className="text-2xl font-bold mb-6 text-gray-800">
          {formData.id ? 'Editar Setor' : 'Adicionar Novo Setor'}
        </h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700">Nome do Setor</label>
            <input
              type="text"
              name="name"
              value={formData.name || ''}
              onChange={handleChange}
              required
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div className="mb-6">
            <label className="block text-sm font-medium text-gray-700">Descrição</label>
            <textarea
              name="description"
              value={formData.description || ''}
              onChange={handleChange}
              rows={3}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div className="flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  );
};

const SectorManagement: React.FC<{
  sectors: Sector[],
  setSectors: React.Dispatch<React.SetStateAction<Sector[]>>,
  assets: Asset[],
  users: MilitaryUser[],
  onManageSector: (sector: Sector) => void,
}> = ({ sectors, setSectors, assets, users, onManageSector }) => {
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [editingSector, setEditingSector] = useState<Partial<Sector> | null>(null);

  // Estados para modais de confirmação
  const [confirmModal, setConfirmModal] = useState<{
    isOpen: boolean;
    type: 'delete';
    data?: any;
  }>({
    isOpen: false,
    type: 'delete'
  });

  const handleAdd = () => {
    setEditingSector(null);
    setIsFormOpen(true);
  };

  const handleEdit = (sector: Sector) => {
    setEditingSector(sector);
    setIsFormOpen(true);
  };

  const handleDelete = (sector: Sector) => {
    setConfirmModal({
      isOpen: true,
      type: 'delete',
      data: sector
    });
  };

  const handleConfirmDelete = async (justification?: string) => {
    const sector = confirmModal.data;
    if (!sector) return;

    try {
      setSectors(sectors.filter(s => s.id !== sector.id));
      alert('Setor excluído com sucesso!');
    } catch (error) {
      console.error('Erro ao excluir setor:', error);
      throw error; // Para ser tratado pelo modal
    }
  };

  const handleSave = (sector: Sector) => {
    if (editingSector && editingSector.id) {
      setSectors(sectors.map(s => s.id === sector.id ? sector : s));
    } else {
      setSectors([...sectors, sector]);
    }
    setIsFormOpen(false);
    setEditingSector(null);
  };

  // Função principal para lidar com confirmações
  const handleConfirmAction = async (justification?: string) => {
    switch (confirmModal.type) {
      case 'delete':
        await handleConfirmDelete(justification);
        break;
    }
  };

  const closeConfirmModal = () => {
    setConfirmModal({
      isOpen: false,
      type: 'delete'
    });
  };
  
  const lastSectorId = useMemo(() => sectors.reduce((max, s) => s.id > max ? s.id : max, 0), [sectors]);

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold text-gray-800">Gestão de Setores</h1>
        <button onClick={handleAdd} className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
          <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
          Adicionar Setor
        </button>
      </div>

      <div className="bg-white shadow-md rounded-lg overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm text-left text-gray-500">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <th scope="col" className="px-6 py-3">Nome</th>
                <th scope="col" className="px-6 py-3">Descrição</th>
                <th scope="col" className="px-6 py-3">Nº de Ativos</th>
                <th scope="col" className="px-6 py-3 text-right">Ações</th>
              </tr>
            </thead>
            <tbody>
              {sectors.map(sector => {
                const assetCount = assets.filter(asset => {
                  const custodian = users.find(u => u.id === asset.custodian_user_id);
                  const isAssetInSector = asset.sector_id === sector.id && asset.status !== 'Em Uso';
                  const isCustodianInSector = custodian?.sector_id === sector.id && asset.status === 'Em Uso';
                  return isAssetInSector || isCustodianInSector;
                }).length;
                return (
                  <tr key={sector.id} className="bg-white border-b hover:bg-gray-50">
                    <td className="px-6 py-4 font-medium text-gray-900">{sector.name}</td>
                    <td className="px-6 py-4">{sector.description}</td>
                    <td className="px-6 py-4 text-center">{assetCount}</td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end space-x-2">
                          <button
                            onClick={() => onManageSector(sector)}
                            className="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            title="Gerenciar Ativos do Setor"
                          >
                            Gerenciar Ativos
                          </button>
                          <button 
                              onClick={() => handleEdit(sector)}
                              className="p-2 text-gray-500 rounded-lg hover:bg-amber-100 hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors"
                              title="Editar Setor"
                          >
                              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                          </button>
                          <button 
                              onClick={() => handleDelete(sector)}
                              className="p-2 text-gray-500 rounded-lg hover:bg-rose-100 hover:text-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors"
                              title="Excluir Setor"
                          >
                              <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                      </div>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>
      </div>

      {isFormOpen && (
        <SectorForm
          sector={editingSector}
          onSave={handleSave}
          onCancel={() => setIsFormOpen(false)}
          lastSectorId={lastSectorId}
        />
      )}

      {/* Modal de Confirmação */}
      <ConfirmationModal
        isOpen={confirmModal.isOpen}
        onClose={closeConfirmModal}
        onConfirm={handleConfirmAction}
        title="Excluir Setor"
        message={`Tem certeza que deseja excluir permanentemente o setor "${confirmModal.data?.name}"? Esta ação não pode ser desfeita.`}
        confirmText="Excluir"
        type="danger"
        requireJustification={true}
        justificationLabel="Justificativa para exclusão"
        justificationPlaceholder="Ex: Setor extinto, reorganização, correção de dados, etc."
      />
    </div>
  );
};

export default SectorManagement;
