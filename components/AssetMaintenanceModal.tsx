// components/AssetMaintenanceModal.tsx

import React, { useState } from 'react';
import { Asset, MaintenanceRecord } from '../types';

interface AssetMaintenanceModalProps {
  asset: Asset;
  onClose: () => void;
  onSaveHistory: (newHistory: MaintenanceRecord[]) => void;
}

const AssetMaintenanceModal: React.FC<AssetMaintenanceModalProps> = ({ asset, onClose, onSaveHistory }) => {
  const [newRecord, setNewRecord] = useState({
    date: new Date().toISOString().split('T')[0],
    reportedProblem: '',
    solution: ''
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setNewRecord(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newRecord.reportedProblem || !newRecord.solution) {
      alert('Por favor, preencha o problema relatado e a solução.');
      return;
    }

    const newEntry: MaintenanceRecord = {
      id: Date.now(), // simple unique id
      ...newRecord
    };

    const updatedHistory = [...(asset.maintenanceHistory || []), newEntry];
    onSaveHistory(updatedHistory);
    
    // Reset form
    setNewRecord({
      date: new Date().toISOString().split('T')[0],
      reportedProblem: '',
      solution: ''
    });
  };

  return (
    <div
      className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
      onClick={onClose}
    >
      <div
        className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="flex justify-between items-start mb-4">
            <div>
                <h2 className="text-2xl font-bold text-gray-800">Histórico de Manutenção</h2>
                <p className="text-sm text-gray-500">{asset.type} ({asset.qrCode})</p>
            </div>
            <button
            onClick={onClose}
            className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800"
            aria-label="Fechar"
            >
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div className="flex-grow overflow-y-auto mb-6 pr-4 -mr-4 border-b">
            {(!asset.maintenanceHistory || asset.maintenanceHistory.length === 0) ? (
                 <div className="text-center py-10">
                    <p className="text-gray-500">Nenhum registro de manutenção encontrado.</p>
                </div>
            ) : (
                <div className="space-y-4">
                    {asset.maintenanceHistory.slice().reverse().map(record => (
                        <div key={record.id} className="p-4 bg-gray-50 rounded-lg border">
                            <p className="font-bold text-gray-700">Data: <span className="font-normal">{new Date(record.date).toLocaleDateString('pt-BR')}</span></p>
                            <p className="font-bold text-gray-700 mt-1">Problema Relatado:</p>
                            <p className="text-sm text-gray-600 pl-2">{record.reportedProblem}</p>
                             <p className="font-bold text-gray-700 mt-2">Solução Aplicada:</p>
                            <p className="text-sm text-gray-600 pl-2">{record.solution}</p>
                        </div>
                    ))}
                </div>
            )}
        </div>

        <div>
            <h3 className="text-xl font-semibold mb-4 text-gray-700">Adicionar Novo Registro</h3>
            <form onSubmit={handleSubmit}>
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div className="md:col-span-1">
                        <label className="block text-sm font-medium text-gray-700">Data</label>
                        <input type="date" name="date" value={newRecord.date} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
                    </div>
                    <div className="md:col-span-3">
                         <label className="block text-sm font-medium text-gray-700">Problema Relatado</label>
                        <input type="text" name="reportedProblem" value={newRecord.reportedProblem} onChange={handleChange} required className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
                    </div>
                    <div className="md:col-span-4">
                         <label className="block text-sm font-medium text-gray-700">Solução Aplicada</label>
                         <textarea name="solution" value={newRecord.solution} onChange={handleChange} required rows={2} className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                </div>
                 <div className="mt-4 flex justify-end">
                    <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Adicionar Registro</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  );
};

export default AssetMaintenanceModal;