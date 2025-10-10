// components/ReopenInventoryModal.tsx
import React, { useState } from 'react';

interface ReopenInventoryModalProps {
  onConfirm: (justification: string) => void;
  onCancel: () => void;
}

const ReopenInventoryModal: React.FC<ReopenInventoryModalProps> = ({ onConfirm, onCancel }) => {
  const [justification, setJustification] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!justification.trim()) {
      alert('A justificativa é obrigatória para reabrir o inventário.');
      return;
    }
    onConfirm(justification);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-lg">
        <h2 className="text-2xl font-bold mb-4 text-gray-800">Reabrir Inventário</h2>
        <p className="text-sm text-gray-600 mb-6">Por favor, forneça uma justificativa para a reabertura deste inventário. A razão será registrada no histórico.</p>
        <form onSubmit={handleSubmit}>
          <div className="mb-6">
            <label htmlFor="justification" className="block text-sm font-medium text-gray-700">Justificativa</label>
            <textarea
              id="justification"
              value={justification}
              onChange={(e) => setJustification(e.target.value)}
              required
              rows={4}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
              placeholder="Ex: Contagem inicial incompleta, necessidade de incluir novos itens, etc."
            />
          </div>
          <div className="flex justify-end space-x-4">
            <button type="button" onClick={onCancel} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</button>
            <button type="submit" className="px-6 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition">Confirmar Reabertura</button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ReopenInventoryModal;
